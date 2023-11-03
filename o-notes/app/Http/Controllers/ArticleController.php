<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = trim($request->input('limit', null));
        $orderBy = trim($request->input('orderBy', 'created_at'));

        // Vérification qu'une limite est défini et si c'est bien un int
        if ($limit && !is_numeric($limit)){
            return $this->onError(404, 'Limit invalid');
        }

        $articles = Article::with('user:id,pseudo,avatar,firstname,lastname')->with('tag')->limit($limit)->orderBy($orderBy, 'DESC')->where('status', 'published')->paginate(10);
        foreach ($articles as $article) {
            // on vérifie si l'utilisateur a un pseudo
            if ($article->user->pseudo != null) {
                // L'utilisateur a un pseudo, on laisse le pseudo
                $article->user->pseudo;
                unset($article->user->firstname);
                unset($article->user->lastname);

            } else {
                $article->user->firstname;
                $article->user->lastname;
                unset($article->user->pseudo);
            }
        }

        if (!empty($articles)) {
            return $this->onSuccess($articles, 'All Articles');
        }
        return $this->onError(404, 'No Articles Found');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if($this->IsAdmin($user) || $this->isUser($user)) {
            $validator = Validator::make($request->all(), $this->postValidationRules());

            if ($request->banner){
                // récupération de l'image et enregistrement dans le dossier public/img
                $banner = $request->banner;
                $banner = str_replace('data:image/png;base64,', '', $banner);
                $banner = str_replace(' ', '+', $banner);
                $imageName = Str::random(10).'.'.'png';

                // création du dossier img s'il n'existe pas
                if (!file_exists(public_path().'/img')){
                    mkdir(public_path().'/img');
                }

                \File::put(public_path(). '/img/' . $imageName, base64_decode($banner));
            }

            if ($validator->passes()) {
                $article = Article::create([
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'slug' => Str::slug($request->title),
                    'text_content' => $request->text_content,
                    'file_content' => $request->file_content,
                    'resume' => $request->resume,
                    'banner' => '/img/' . $imageName ?? null,
                    'user_id' => $request->user()->id,
                    'subcategory_id' => $request->subcategory_id,
                    'status' => $request->status,
                ]);

                // Récupérer les tags sélectionnés par l'utilisateur
                $tags = $request->get('tags');

                // Synchroniser les tags avec l'article
                $article->tag()->sync($tags);

                // Vérifier si un nouveau tag a été créé
                $newTags = $request->get('newTags');
                if ($newTags) {
                    foreach ($newTags as $newTag) {
                            // Créer un nouveau tag
                            $tag = Tag::create([
                                'name' => $newTag['name'],
                                'slug' => Str::slug($newTag['name']),
                                'user_id' => $request->user()->id,
                                'logo' => $newTag['logo'],
                                'color' => $newTag['color'],
                                'bg_color' => $newTag['bg_color'],
                            ]);
                        // Synchroniser le nouveau tag avec l'article
                        $article->tag()->attach($tag->id);
                    }
                }

                return $this->onSuccess($article, 'Article Created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::with('user:id,pseudo,avatar')->with('tag')->find($id);
        $subcategory = SubCategory::with('category')->where('id', $article->subcategory_id)->get();

        if (!empty($article)) {
            return $this->onSuccess([$article, $subcategory], 'Article Found');
        }

        return $this->onError(404, 'Article Not Found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = $request->user();

        // Vérifier si l'utilisateur est autorisé à éditer l'article
        if (!($this->IsAdmin($user) || $this->isUser($user))) {
            return $this->onError(401, 'Unauthorized');
        }

        // Récupérer l'article à éditer
        //$article = Article::findOrFail($id);
        $article = Article::find($id);

        // Valider les données de la requête
        $validator = Validator::make($request->all(), $this->postValidationRules());

        if ($validator->passes()) {
            // Mettre à jour les données de l'article
            $article->update([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'slug' => Str::slug($request->title),
                'text_content' => $request->text_content,
                'file_content' => $request->file_content,
                'resume' => $request->resume,
                'banner' => $request->banner,
                'subcategory_id' => $request->subcategory_id,
                'tags' => $request->tags,
                'status' => $request->status,
            ]);

            // Récupérer les tags sélectionnés par l'utilisateur
            $tags = $request->get('tags');

            // Synchroniser les tags avec l'article
            $article->tag()->sync($tags);

            $newTags = $request->get('newTags');
            if ($newTags) {
                foreach ($newTags as $newTag) {
                    // Créer un nouveau tag
                    $tag = Tag::create([
                        'name' => $newTag['name'],
                        'slug' => Str::slug($newTag['name']),
                        'user_id' => $request->user()->id,
                        'logo' => $newTag['logo'],
                        'color' => $newTag['color'],
                        'bg_color' => $newTag['bg_color'],
                    ]);
                    // Synchroniser le nouveau tag avec l'article
                    $article->tag()->attach($tag->id);
                }
            }

            // Enregistrer les modifications
            $article->save();

            return $this->onSuccess([$article, $article->tag()->get()], 'Article Updated');
        }

        return $this->onError(400, $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if($this->IsAdmin($user) || $this->isUser($user)) {
            $article = Article::find($id);
            if (!empty($article)) {
                $article->delete();
                return $this->onSuccess(null, 'Article Deleted');
            }
            return $this->onError(404, 'Article Not Found');
        }
        return $this->onError(401, 'Unauthorized');
    }
}

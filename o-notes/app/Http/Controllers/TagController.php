<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();

        if (!empty($tags)) {
            return $this->onSuccess($tags, 'All Tags');
        }

        return $this->onError(404, 'No Tags Found');

    }

    /**
     * Récupère 25 tags pour la homepage.
     */
    public function homepage()
    {
        $tags = Tag::orderBy('created_at', 'desc')->take(25)->get();

        if (!empty($tags)) {
            return $this->onSuccess($tags, 'All Tags');
        }
        return $this->onError(404, 'No Tags Found');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($this->isUser($user)) {
            $validator = Validator::make($request->all(), $this->tagValidationRules());



            if ($request->banner){
                // récupération de l'image et enregistrement dans le dossier public/img
                $logo = $request->banner;
                $logo = str_replace('data:image/png;base64,', '', $logo);
                $logo = str_replace(' ', '+', $logo);
                $imageName = Str::random(10).'.'.'png';

                // création du dossier img s'il n'existe pas
                if (!file_exists(public_path().'/img')){
                    mkdir(public_path().'/img');
                }

                \File::put(public_path(). '/img' . $imageName, base64_decode($logo));
            }

            if ($validator->passes()) {
                $tag = Tag::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'user_id' => $user->id,
                    'logo' => 'img/' . $imageName ?? '',
                    'color' => $request->color,
                    'bg_color' => $request->bg_color
                ]);
                return $this->onSuccess($tag, 'Tag Created');
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
        $tags = Tag::find($id);
        $articles = $tags->articles()->where('status', 'published')->get();

        if (!empty($tags)) {
            return $this->onSuccess([$tags, $articles], 'Tag Found');
        }
        return $this->onError(404, 'Tag Not Found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();
        $tag = Tag::find($id);

        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->tagValidationRules());
            if ($validator->passes()) {
                $tag->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'user_id' => $user->id,
                    'logo' => $request->logo,
                    'color' => $request->color,
                    'bg_color' => $request->bg_color
                ]);
                return $this->onSuccess($tag, 'Tag Updated');
            }
            return $this->onError(404, 'Tag Not Found');
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if ($this->isAdmin($user)) {
            $tag = Tag::find($id);
            if (!empty($tag)){
                $tag->delete();
                return $this->onSuccess($tag, 'Tag Deleted');
            }
            return $this->onError(404, 'Tag Not Found');
        }
        return $this->onError(401, 'Unauthorized');
    }
}

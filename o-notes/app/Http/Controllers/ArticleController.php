<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $articles = Article::all();
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
        // dd($user->id);

        if($this->IsAdmin($user) || $this->isUser($user)) {
            $validator = Validator::make($request->all(), $this->postValidationRules());

            if ($validator->passes()) {
                $article = Article::create([
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'slug' => Str::slug($request->title),
                    'text_content' => $request->text_content,
                    'file_content' => $request->file_content,
                    'banner' => $request->banner,
                    'user_id' => $request->user()->id,
                    'subcategory_id' => $request->subcategory_id,
                ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

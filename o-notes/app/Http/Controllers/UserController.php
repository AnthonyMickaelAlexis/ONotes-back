<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiHelpers;

    /**
     * Dashboard avec 3 derniers articles et 20 derniers tags
     */
    public function dashboard()
    {
        $user = auth()->user();

        if ($user) {
            $articles = Article::orderBy('created_at', 'desc')->where('user_id', $user->id)->take(20)->get();
            $tags = Tag::orderBy('created_at', 'desc')->where('user_id', $user->id)->take(20)->get();
            return $this->onSuccess([$user, $articles, $tags], 'User Dashboard');
        }
        return $this->onError(400, 'User Not Found');
    }

    /**
     * Listing des articles de l'utilisateur
     */
    public function articles()
    {
        $user = auth()->user();
        if ($user) {
            $articles = Article::orderBy('created_at', 'desc')->where('user_id', $user->id)->with('tag')->get();
            return $this->onSuccess([$user, $articles], 'User Dashboard');
        }
        return $this->onError(400, 'User Not Found');
    }

    /**
     * Listing des tags de l'utilisateur
     */
    public function tags()
    {
        $user = auth()->user();

        if ($user) {
            //Réupère les tags de l'utilisateur
            $tags = Tag::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

            //Récupère les articles de chaque tag
            foreach ($tags as $tag) {
                $tag->article = Article::whereHas('tag', function ($query) use ($tag) {
                    $query->where('tag_id', $tag->id);
                })->get();
            }
            return $this->onSuccess([$user, $tags], 'User Dashboard');
        }

        return $this->onError(400, 'User Not Found');
    }

    public function show(string $id)
    {
        $user = User::find($id);
        $articles = Article::where('user_id', $id)->where('status', 'published')->get();
        $tags = Tag::where('user_id', $id)->get();

        if (!empty($user)) {
            return $this->onSuccess([$user, $articles, $tags], 'User Found');
        }
        return $this->onError(404, 'User Not Found');
    }

}

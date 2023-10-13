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
            $articles = Article::orderBy('created_at', 'desc')->where('user_id', $user->id)->take(3)->get();
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
            $articles = Article::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();
            return $this->onSuccess([$user, $articles], 'User Dashboard');
        }
        return $this->onError(400, 'User Not Found');
    }

    /**
     * Listing des tags de l'utilisateur
     */
    public function tags(){
        $user = auth()->user();

        if ($user) {
            $tags = Tag::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();
            return $this->onSuccess([$user, $tags], 'User Dashboard');
        }

        return $this->onError(400, 'User Not Found');
    }

}

<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        dd($request->user());
        $user = DB::table('users')->select('role')->where('id',1)->first();

        if ($user && (int)$user->role === 1){
            // fail and redirect silently if we already have a user with that role
            return redirect("/");
        }

        return $next($request);
    }
}



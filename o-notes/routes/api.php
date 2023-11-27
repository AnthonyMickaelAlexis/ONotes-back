<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('/article', [ArticleController::class, 'store']);
    Route::put('/article/{id}', [ArticleController::class, 'update']);
    Route::delete('/article/{id}', [ArticleController::class, 'destroy']);

    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    Route::post('/subcategory', [SubCategoryController::class, 'store']);
    Route::put('/subcategory/{id}', [SubCategoryController::class, 'update']);
    Route::delete('/subcategory/{id}', [SubCategoryController::class, 'destroy']);

    Route::post('/tag', [TagController::class, 'store']);
    Route::put('/tag/{id}', [TagController::class, 'update']);
    Route::delete('/tag/{id}', [TagController::class, 'destroy']);

    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/dashboard/mes-tags', [UserController::class, 'tags']);
    Route::get('/dashboard/mes-articles', [UserController::class, 'articles']);
});

Route::get('user/{id}', [UserController::class, 'show']);

Route::get('/articles', [
    ArticleController::class,
    'index',
    'middleware' => ['trim', 'strip_tags', 'query_params'],
    ]);
Route::get('/articles/homepage', [ArticleController::class, 'homepage']);
Route::get('/article/{id}', [ArticleController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);

Route::get('/subcategories', [SubCategoryController::class, 'index']);
Route::get('/subcategory/{id}', [SubCategoryController::class, 'show']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/homepage', [TagController::class, 'homepage']);
Route::get('/tag/{id}', [TagController::class, 'show']);

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');



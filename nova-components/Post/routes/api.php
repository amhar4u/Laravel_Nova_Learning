<?php

use Illuminate\Support\Facades\Route;
use Acme\Post\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Post Tool – API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /nova-vendor/post  (set in ToolServiceProvider)
| and protected by the Nova + Authorize middleware.
|
| CRUD Endpoints:
|   GET    /nova-vendor/post/posts          → index   (list all posts)
|   POST   /nova-vendor/post/posts          → store   (create new post)
|   GET    /nova-vendor/post/posts/{id}     → show    (get one post)
|   PUT    /nova-vendor/post/posts/{id}     → update  (update a post)
|   DELETE /nova-vendor/post/posts/{id}     → destroy (delete a post)
|
*/

Route::get('/posts',        [PostController::class, 'index']);
Route::post('/posts',       [PostController::class, 'store']);
Route::get('/posts/{id}',   [PostController::class, 'show']);
Route::put('/posts/{id}',   [PostController::class, 'update']);
Route::delete('/posts/{id}',[PostController::class, 'destroy']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    UserController,
    PostController
};

/**
 * API V1
 */
Route::prefix("v1")->group(function () {

    Route::resource('user', UserController::class)->only([
        'index', 'show'
    ])->names([
        'index' => 'v1.user.index',
        'show' => 'v1.user.show',
    ]);

    Route::resource('post', PostController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ])->names([
        'index' => 'v1.post.index',
        'store' => 'v1.post.store',
        'show' => 'v1.post.show',
        'update' => 'v1.post.update',
        'destroy' => 'v1.post.destroy',
    ]);
});

<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;

Route::middleware('web', 'auth:sanctum')->group(function () {

    Route::middleware('admin')->group(function () {

        #Admin Users
        Route::get('/admin/users', [UsersController::class, 'index']);
        Route::delete('/admin/users/{id}/delete', [UsersController::class, 'destroy']);

        #Admin Tasks
        Route::get('/admin/tasks', [TasksController::class, 'index']);

        Route::delete('/admin/tasks/{id}/delete', [TasksController::class, 'destroy']);

        // Route::patch('/admin/posts/{id}/unhide', [PostsController::class, 'unhide']);

        #Admin Categories
        Route::get('/admin/categories', [CategoriesController::class, 'index']);

        // Route::post('/admin/categories/store', [CategoriesController::class, 'store']);

        Route::delete('/admin/categories/{id}/delete', [CategoriesController::class, 'destroy']);

        // Route::patch('/admin/categories/{id}/update', [CategoriesController::class, 'update']);

    });

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/counts', [TaskController::class, 'counts']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/tasks/stats', [TaskController::class, 'stats']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'changePassword']);
});


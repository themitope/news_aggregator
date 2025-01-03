<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('articles', [ArticleController::class, 'index']);
});
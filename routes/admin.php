<?php

use App\Http\Controllers\Admin\AdminPostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('admin/posts', AdminPostController::class)
        ->names('admin.posts');
});

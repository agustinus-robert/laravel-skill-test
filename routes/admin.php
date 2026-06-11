<?php

use App\Http\Controllers\Admin\AdminPostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('admin/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::get('admin/posts/create', [AdminPostController::class, 'create'])->name('admin.posts.create');
    Route::post('admin/posts', [AdminPostController::class, 'store'])->name('admin.posts.store');

    Route::get('admin/posts/{post}', [AdminPostController::class, 'show'])->name('admin.posts.show');
    Route::get('admin/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('admin/posts/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
});

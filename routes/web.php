<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\FrontendController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth','isAdmin'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'dashboard']);
    Route::resource('/posts', DashboardController::class);
});

Route::get('/',[FrontendController::class, 'index'])->name('post.index');
Route::get('/post/{slug}',[FrontendController::class, 'show'])->name('post.show');
Route::post('/comments',[CommentController::class, 'store'])->name('comments.store');
Route::post('/delete-comment',[CommentController::class, 'destroy']);


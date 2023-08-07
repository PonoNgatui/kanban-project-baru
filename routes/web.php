<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home'); // Diperbarui
})->name('home');

Route::prefix('tasks')
    ->name('tasks.')
    ->controller(TaskController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create/{status?}','create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::get('{id}/delete', 'delete')->name('delete');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('progress', 'progress')->name('progress');
        Route::patch('{id}/move', 'move')->name('move');
        Route::patch('{id}/complete', 'complete')->name('complete');
        Route::patch('{id}/completeList', 'completeList')->name('completeList');
    });

    Route::name('auth.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('signup', 'signupForm')->name('signupForm');
        Route::post('signup', 'signup')->name('signup');
        Route::get('login', 'loginForm')->name('loginForm');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
    });
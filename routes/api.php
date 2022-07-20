<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PictureController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/user', fn() => Auth::user())->name('user');
Route::post('/pictures', [PictureController::class, 'create'])->name('picture.create');
Route::get('/pictures', [PictureController::class, 'index'])->name('picture.index');
Route::get('/pictures/{id}', [PictureController::class, 'show'])->name('picture.show');
Route::post('/pictures/{picture}/comments', [PictureController::class, 'addComment'])->name('picture.comment');
Route::put('/pictures/{id}/like', [PictureController::class, 'like'])->name('picture.like');
Route::delete('/pictures/{id}/like', [PictureController::class, 'unlike']);
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
  $request->session()->regenerateToken();
  return response()->json();
})->name('reflesh-token');

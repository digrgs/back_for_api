<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('vendor.adminlte.auth.login');
});
Route::get('/login', function () {
    return view('vendor.adminlte.auth.login');
})->name('site.login');

Route::post('/login', [AuthController::class, 'login'])->name('site.login');

Route::middleware('auth.back')->post('/logout', [AuthController::class, 'logout'])->name('site.logout');


Route::get('/register', function () {
    return view('vendor.adminlte.auth.register');
})->name('site.register');

Route::get('/password/reset', function () {
    return view('vendor.adminlte.auth.passwords.reset');
})->name('site.password.reset');

Route::middleware('auth.back')->get('/dashboard',  [AuthController::class, 'dashboard'])->name('site.dashboard');

Route::get('/admin/settings',  [AuthController::class, 'settings'])->name('site.settings');
Route::post('/admin/settings',  [AuthController::class, 'settings'])->name('site.settings');

Route::get('/password/reset',  [AuthController::class, 'passwordReset'])->name('site.password.reset');
Route::post('/password/reset',  [AuthController::class, 'passwordReset'])->name('site.password.reset');

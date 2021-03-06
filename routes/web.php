<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

Route::middleware(['auth'])->group(function () { 
    
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/user/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/user/edit/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/user/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');

});

Route::prefix('dt')->middleware(['auth'])->group(function () { 

    Route::post('/users', [UserController::class, 'getUsers'])->name('dt.users');

});
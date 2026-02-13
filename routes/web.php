<?php

use Illuminate\Support\Facades\Route;
#use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EntryController;


Route::get('/', function () {return redirect()->route('login');});
#Route::get('/', function () {   return view('welcome');});
Route::get('/login', [LoginController::class, 'show'])->name('login');
#Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/calendar-entry', [EntryController::class, 'store'])->name('entry.store');
Route::get('/calendar-entries', [EntryController::class, 'index'])->name('entry.index');

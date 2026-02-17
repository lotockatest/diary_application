<?php

use Illuminate\Support\Facades\Route;
#use App\Http\Controllers\AuthController;
#use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\GoalController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {return redirect()->route('login');});
#Route::get('/', function () {   return view('welcome');});
Route::get('/login', [LoginController::class, 'show'])->name('login');
#Route::get('/register', [AuthController::class, 'register'])->name('register');
#Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/calendar-entry', [EntryController::class, 'store'])->name('entry.store');
Route::get('/home', [EntryController::class, 'index'])->name('home');

Route::get('/customization', [CustomizationController::class, 'index'])->name('customization');

Route::post('/customization/mood', [CustomizationController::class, 'storeMood'])->name('customization.mood.store');
Route::post('/customization/routine', [CustomizationController::class, 'storeRoutine'])->name('customization.routine.store');
Route::post('/customization/activity', [CustomizationController::class, 'storeActivity'])->name('customization.activity.store');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/goal', [GoalController::class, 'index'])->name('goal');

Route::post('/logout', function () {Auth::logout();request()->session()->invalidate();request()->session()->regenerateToken();return redirect('/login');})->name('logout');


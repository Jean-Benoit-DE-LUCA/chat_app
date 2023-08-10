<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;

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


Route::get('/', [HomeController::class, 'homeAction']);

Route::get('/login', [ConnectionController::class, 'loginAction'])->name('login');
Route::post('/login', [ConnectionController::class, 'loginPostAction']);

Route::get('/logout', [ConnectionController::class, 'logoutAction']);

Route::get('/signup', [RegistrationController::class, 'signupAction'])->middleware(['auth', 'admin'])->name('signup');
Route::post('/signup', [RegistrationController::class, 'signupPostAction']);

Route::get('/chat', [ChatController::class, 'chatAction'])->middleware(['auth', 'member'])->name('chat');

Route::get('/chat/getusers', [ChatController::class, 'chatGetAction'])->middleware(['auth', 'member']);

Route::get('/chat/{id}', [ChatController::class, 'chatRoomAction'])->middleware(['auth', 'member'])->name('chat');
Route::post('/chat/{id}', [ChatController::class, 'chatRoomPostAction'])->middleware(['auth', 'member'])->name('chatPost');

Route::get('/chat/{id}/load/{loadNumber}', [ChatController::class, 'chatRoomGetLoadAction'])->middleware(['auth', 'member']);
Route::delete('/chat/{id}/delete/{deleteNumber}', [ChatController::class, 'chatRoomDeleteAction'])->middleware(['auth', 'member']);
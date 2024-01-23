<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatGptController;
use App\Http\Controllers\ChatBotController;

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
    return view('welcome');
});

Route::get('/chat', [ChatGptController::class, 'index'])->name('chat_gpt-index');
Route::post('/chat', [ChatGptController::class, 'chat'])->name('chat_gpt-chat');

Route::get('/chatbot', [ChatBotController::class, 'index'])->name('chat_bot-index');
Route::post('/chatbot', [ChatBotController::class, 'chat'])->name('chat_bot-chat');


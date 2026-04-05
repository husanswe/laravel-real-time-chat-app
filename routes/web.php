<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;


Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');     // shows chat page
Route::post('/chat/enter', [ChatController::class, 'enter'])->name('chat.enter'); // handles name form submit
Route::get('/', [ChatController::class, 'landing'])->name('chat.landing');     // shows name input page
Route::post('/chat/leave', [ChatController::class, 'leave'])->name('chat.leave');
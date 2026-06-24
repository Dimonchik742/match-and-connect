<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;

// Головна сторінка (Вітальна)
Route::get('/', function () {
    return view('welcome_page');
});

// Відкриті маршрути (для всіх)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login'); // Дали ім'я маршруту
Route::post('/login', [AuthController::class, 'loginSubmit']);
Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'registerSubmit']);

// ЗАХИЩЕНІ маршрути (тільки для тих, хто увійшов)
Route::middleware('auth')->group(function () {
    Route::get('/user/{id}', [ProfileController::class, 'showUser']);
    Route::get('/profile', [ProfileController::class, 'profile']);
    
    // Нові маршрути для лайків та рекомендацій
    Route::get('/recommendations', [MatchController::class, 'recommendations']);
    Route::get('/matches', [MatchController::class, 'matches']);
    Route::post('/like/{id}', [MatchController::class, 'likeUser']);
    
    // Показати сторінку переписки з конкретним юзером
    Route::get('/chat/{user_id}', [ChatController::class, 'chat']);
    
    // Відправити йому нове повідомлення (зверніть увагу, це POST запит)
    Route::post('/chat/{user_id}', [ChatController::class, 'sendMessage']);
    Route::get('/messages', [ChatController::class, 'inbox']);
    
    // Спеціальний маршрут для AJAX-запитів, який повертає JSON
    Route::get('/api/chat/{user_id}/messages', [ChatController::class, 'getMessagesJson']);
    
    // Сторінка форми редагування
    Route::get('/profile/edit', [ProfileController::class, 'editProfile']);
    
    // Обробка форми (збереження даних)
    Route::post('/profile/edit', [ProfileController::class, 'updateProfile']);

    // Адмін-панель
    Route::get('/admin', [AdminController::class, 'adminPanel']);
    
    // Видалення користувача
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser']);

    // Видалення повідомлення
    Route::delete('/message/{id}', [ChatController::class, 'deleteMessage']);
});

Route::get('/logout', [AuthController::class, 'logout']);
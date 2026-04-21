<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;

// Відкриті маршрути (для всіх)
Route::get('/login', [MatchController::class, 'loginForm'])->name('login'); // Дали ім'я маршруту
Route::post('/login', [MatchController::class, 'loginSubmit']);
Route::get('/register', [MatchController::class, 'registerForm']);
Route::post('/register', [MatchController::class, 'registerSubmit']);

// ЗАХИЩЕНІ маршрути (тільки для тих, хто увійшов)
Route::middleware('auth')->group(function () {
    Route::get('/user/{id}', [MatchController::class, 'showUser']);
    Route::get('/profile', [MatchController::class, 'profile']);
    Route::get('/search', [MatchController::class, 'search']);
    // Показати сторінку переписки з конкретним юзером
    Route::get('/chat/{user_id}', [MatchController::class, 'chat']);
    
    // Відправити йому нове повідомлення (зверніть увагу, це POST запит)
    Route::post('/chat/{user_id}', [MatchController::class, 'sendMessage']);
    Route::get('/messages', [MatchController::class, 'inbox']);
    // Спеціальний маршрут для AJAX-запитів, який повертає JSON
    Route::get('/api/chat/{user_id}/messages', [MatchController::class, 'getMessagesJson']);
    // Сторінка форми редагування
    Route::get('/profile/edit', [MatchController::class, 'editProfile']);
    
    // Обробка форми (збереження даних)
    Route::post('/profile/edit', [MatchController::class, 'updateProfile']);

    // Адмін-панель
    Route::get('/admin', [MatchController::class, 'adminPanel']);
    
    // Видалення користувача
    Route::delete('/admin/user/{id}', [MatchController::class, 'deleteUser']);

    // Видалення повідомлення
    Route::delete('/message/{id}', [MatchController::class, 'deleteMessage']);
});

Route::get('/logout', [MatchController::class, 'logout']);
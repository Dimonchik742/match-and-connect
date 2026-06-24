<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminPanel()
    {
        // ПЕРЕВІРКА БЕЗПЕКИ: Якщо не адмін - викидаємо помилку 403
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ заборонено. Тільки для адміністраторів.');
        }

        // Дістаємо всіх юзерів (бажано відсортувати від найновіших)
        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('admin', compact('users'));
    }

    public function deleteUser($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ заборонено.');
        }

        $user = User::findOrFail($id);

        // Захист від того, щоб адмін випадково не видалив сам себе
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Ви не можете видалити власний профіль!');
        }

        // Видаляємо користувача (його інтереси і повідомлення також зникнуть, якщо налаштовано каскадне видалення в БД)
        $user->delete();

        return back()->with('success', 'Користувача успішно видалено.');
    }
}

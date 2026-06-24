<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm() {
        // Дістаємо всі інтереси з бази даних
        $allInterests = Interest::all(); 

        // Передаємо їх у шаблон register
        return view('register', compact('allInterests'));
    }

    public function registerSubmit(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'name' => 'required|min:2|max:50',
            'age' => 'required|numeric|min:16|max:100',
            'bio' => 'nullable|max:500',
            'photo' => 'nullable|image',
            'interests' => 'required|array'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'age' => $validatedData['age'],
            'bio' => $validatedData['bio'],
            'photo' => $photoPath,
        ]);

        if (!empty($validatedData['interests'])) {
            $user->interests()->attach($validatedData['interests']);
        }

        Auth::login($user);

        return redirect('/profile');
    }

    public function loginForm()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/profile');
        }

        return back()->withErrors([
            'email' => 'Неправильний email або пароль. Спробуйте ще раз.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken();

        return redirect('/'); 
    }
}

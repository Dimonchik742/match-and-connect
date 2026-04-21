<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class MatchController extends Controller
{
    
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    
    public function search(Request $request)
    {
        $currentUser = Auth::user();
        
        // Дістаємо всі інтереси для списку
        $allInterests = Interest::all();
        
        // Перевіряємо, чи є фільтр в URL
        $selectedInterestId = $request->query('interest');

        $query = User::where('id', '!=', $currentUser->id);

        if ($selectedInterestId) {
            // Якщо вибрали один інтерес - шукаємо тільки по ньому
            $query->whereHas('interests', function ($q) use ($selectedInterestId) {
                $q->where('interests.id', $selectedInterestId);
            });
        } else {
            // Твій оригінальний алгоритм (спільні інтереси)
            $myInterestIds = $currentUser->interests->pluck('id');
            
            $query->whereHas('interests', function ($q) use ($myInterestIds) {
                $q->whereIn('interests.id', $myInterestIds);
            });
        }

        $users = $query->get();

        return view('search', compact('users', 'currentUser', 'allInterests', 'selectedInterestId'));
    }
    
    
    public function registerForm()
    {
        return view('register');
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

        $interestIds = [];
        foreach ($validatedData['interests'] as $interestName) {
            $interest = Interest::firstOrCreate(['name' => $interestName]);
            $interestIds[] = $interest->id;
        }
        $user->interests()->attach($interestIds);

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

    public function showUser($id)
    {

        $user = User::with('interests')->findOrFail($id);

        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return redirect('/profile');
        }

        return view('user-profile', compact('user', 'currentUser'));
    }

    public function chat($receiver_id)
    {
        $currentUser = Auth::user();
        
        $receiver = User::findOrFail($receiver_id);

        $messages = Message::where(function ($query) use ($currentUser, $receiver_id) {
            
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($currentUser, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                  ->where('receiver_id', $currentUser->id);
        })
        ->orderBy('created_at', 'asc') 
        ->get();

        Message::where('sender_id', $receiver_id)
               ->where('receiver_id', $currentUser->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return view('chat', compact('receiver', 'messages', 'currentUser'));
    }


    public function sendMessage(Request $request, $receiver_id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_id,
            'content' => $validated['content'] 
        ]);

        return back();
    }

    
    public function inbox()
    {
        $currentUserId = Auth::id();

        
        $sentToIds = Message::where('sender_id', $currentUserId)->pluck('receiver_id');
        
        
        $receivedFromIds = Message::where('receiver_id', $currentUserId)->pluck('sender_id');

        
        $contactIds = $sentToIds->merge($receivedFromIds)->unique();

       
        $contacts = User::whereIn('id', $contactIds)->get();

        return view('inbox', compact('contacts'));
    }

    public function getMessagesJson($receiver_id)
    {
        $currentUserId = Auth::id();

        $messages = Message::where(function ($query) use ($currentUserId, $receiver_id) {
            $query->where('sender_id', $currentUserId)->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($currentUserId, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $currentUserId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json([
            'messages' => $messages,
            'current_user_id' => $currentUserId
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        $allInterests = Interest::all();
        
        return view('edit-profile', compact('user', 'allInterests'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:16|max:100',
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'interests' => 'nullable|array'
        ]);

        $user->name = $validated['name'];
        $user->age = $validated['age'];
        $user->bio = $validated['bio'] ?? '';

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        if (isset($validated['interests'])) {
            $user->interests()->sync($validated['interests']); 
        } else {
            $user->interests()->detach(); 
        }

        return redirect('/profile')->with('success', 'Профіль успішно оновлено!');
    }

    // 1. Сторінка адмінки
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

    // 2. Видалення користувача
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

    public function deleteMessage($id)
    {
        $message = \App\Models\Message::findOrFail($id);
        $user = Auth::user();

        // Перевірка безпеки: чи це моє повідомлення, АБО чи я адмін?
        if ($message->sender_id === $user->id || $user->is_admin) {
            $message->delete();
            return back()->with('success', 'Повідомлення видалено.');
        }

        // Якщо хтось хитрий намагається видалити чуже повідомлення
        abort(403, 'Ви не можете видалити чуже повідомлення.');
    }
}
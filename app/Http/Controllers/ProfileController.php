<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
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
            // Delete old photo
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            
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
}

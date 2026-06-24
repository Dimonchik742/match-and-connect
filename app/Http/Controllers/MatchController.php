<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Interest;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function recommendations(Request $request)
    {
        $currentUser = Auth::user();
        $currentUserInterestIds = $currentUser->interests->pluck('id');
        $allInterests = Interest::all();

        // Отримуємо ID користувачів, яких поточний юзер вже лайкнув
        $likedUserIds = $currentUser->likes()->pluck('liked_user_id');

        $selectedInterests = $request->query('interests'); // Масив ID

        $query = User::where('id', '!=', $currentUser->id)
            ->whereNotIn('id', $likedUserIds);

        if (!empty($selectedInterests) && is_array($selectedInterests)) {
            // Фільтрація за вибраними інтересами
            $query->whereHas('interests', function ($q) use ($selectedInterests) {
                $q->whereIn('interests.id', $selectedInterests);
            })->withCount(['interests as common_interests_count' => function ($q) use ($selectedInterests) {
                $q->whereIn('interests.id', $selectedInterests);
            }]);
        } else {
            // Розумні рекомендації за спільними інтересами
            $query->whereHas('interests', function ($q) use ($currentUserInterestIds) {
                $q->whereIn('interests.id', $currentUserInterestIds);
            })->withCount(['interests as common_interests_count' => function ($q) use ($currentUserInterestIds) {
                $q->whereIn('interests.id', $currentUserInterestIds);
            }]);
        }

        $recommendedUsers = $query->orderBy('common_interests_count', 'desc')
            ->with('interests')
            ->paginate(12);

        return view('recommendations', compact('recommendedUsers', 'allInterests', 'selectedInterests'));
    }

    public function matches()
    {
        $currentUser = Auth::user();
        
        // Get users where is_match is true
        $matches = Like::where('user_id', $currentUser->id)
                       ->where('is_match', true)
                       ->with('likedUser.interests') // Load relations to prevent N+1
                       ->get()
                       ->pluck('likedUser');

        return view('matches', compact('matches', 'currentUser'));
    }

    public function likeUser(Request $request, $liked_id)
    {
        $currentUser = Auth::user();

        // Заборона лайкати себе
        if ($currentUser->id == $liked_id) {
            return back()->with('error', 'Ви не можете лайкнути себе.');
        }

        // Перевіряємо, чи користувач вже поставив лайк
        $existingLike = Like::where('user_id', $currentUser->id)
                            ->where('liked_user_id', $liked_id)
                            ->first();

        if ($existingLike) {
            return back()->with('info', 'Ви вже поставили лайк цьому користувачу.');
        }

        // Створюємо лайк
        Like::create([
            'user_id' => $currentUser->id,
            'liked_user_id' => $liked_id,
            'is_match' => false
        ]);

        // Перевіряємо зустрічний лайк
        $reciprocalLike = Like::where('user_id', $liked_id)
                              ->where('liked_user_id', $currentUser->id)
                              ->first();

        if ($reciprocalLike) {
            // Оновлюємо обидва записи, ставимо is_match = true
            Like::where('user_id', $currentUser->id)
                ->where('liked_user_id', $liked_id)
                ->update(['is_match' => true]);

            Like::where('user_id', $liked_id)
                ->where('liked_user_id', $currentUser->id)
                ->update(['is_match' => true]);

            return back()->with('success', 'У вас новий Match!');
        }

        return back()->with('success', 'Ви вподобали користувача!');
    }
}
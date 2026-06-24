<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Like;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private function checkMatch($user1_id, $user2_id)
    {
        $isMatch = Like::where('user_id', $user1_id)
                       ->where('liked_user_id', $user2_id)
                       ->where('is_match', true)
                       ->exists();
        
        if (!$isMatch) {
            abort(403, 'Ви можете спілкуватися лише з тими, з ким у вас є взаємний Match!');
        }
    }

    public function chat($receiver_id)
    {
        $currentUser = Auth::user();
        
        $this->checkMatch($currentUser->id, $receiver_id);

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
        $this->checkMatch(Auth::id(), $receiver_id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_id,
            'content' => $validated['content'] 
        ]);

        broadcast(new MessageSent($message))->toOthers();

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
        $this->checkMatch($currentUserId, $receiver_id);

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

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();

        // Перевірка безпеки: чи це моє повідомлення, АБО чи я адмін?
        if ($message->sender_id === $user->id || $user->is_admin) {
            $message->delete();
            return back()->with('success', 'Повідомлення видалено.');
        }

        abort(403, 'Ви не можете видалити чуже повідомлення.');
    }
}

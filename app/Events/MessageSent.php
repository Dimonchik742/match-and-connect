<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// ShouldBroadcastNow означає "відправити по WebSocket негайно"
class MessageSent implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Все, що вказано як public, автоматично полетить на фронтенд (в JavaScript)
    public $message;

    /**
     * Створюємо новий екземпляр події.
     */
    public function __construct(Message $message)
    {
        // Передаємо наше повідомлення з бази даних у подію
        $this->message = $message;
    }

    /**
     * Визначаємо, по якому каналу (тунелю) передавати дані.
     */
    public function broadcastOn(): array
    {
        // Створюємо ПРИВАТНИЙ канал. 
        // Назва каналу буде, наприклад, "chat.5", якщо ми пишемо юзеру з ID 5.
        // Ніхто інший цей тунель підслухати не зможе.
        return [
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
    }

    /**
     * Назва події, яку буде слухати наш JavaScript.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
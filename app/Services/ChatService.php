<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatService
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true
            ]
        );
    }

    public function createRoom($data)
    {
        $room = ChatRoom::create([
            'name' => $data['name'],
            'type' => $data['type'] ?? 'support'
        ]);

        return $room;
    }

    public function sendMessage($roomId, $message, $type = 'text')
    {
        $msg = ChatMessage::create([
            'room_id' => $roomId,
            'sender_id' => Auth::id(),
            'message' => $message,
            'message_type' => $type
        ]);

        // بث الرسالة مباشرة
        $this->pusher->trigger('chat.' . $roomId, 'new-message', [
            'message' => $msg->load('sender')
        ]);

        return $msg;
    }

    public function getRoomMessages($roomId)
    {
        return ChatMessage::where('room_id', $roomId)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }
} 
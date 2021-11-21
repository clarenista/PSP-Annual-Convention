<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index()
    {

        $guests = User::whereHas('activities', function ($q) {
            $min2ago = date("Y-m-d H:i:s", time() - 300);
            $q->where('sent_at', '>', $min2ago);
        })->get();

        return view("cms.sponsor.chat.index", compact('guests'));
    }

    public function guest(User $guest)
    {

        $guest->load(['chats' => function ($q) use ($guest) {
            $booth_id = auth()->user()->booth->id;
            $q->whereChatRoomId("{$booth_id}-{$guest->id}");
        }]);

        return view("cms.sponsor.chat.guest", compact('guest'));
    }


    public function guestMessage(User $guest)
    {

        $user = auth()->user();
        $rroom_id = "{$user->booth->id}-{$guest->id}";

        ChatMessage::create([
            'chat_room_id' => $rroom_id,
            'message' => \request()->message,
            'user_id' => $guest->id,
            'sender_id' => $user->booth->id
        ]);

        return redirect()->back();
    }
}

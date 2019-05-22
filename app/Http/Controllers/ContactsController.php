<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Laravel\Facades\Pusher;

class ContactsController extends Controller
{
    public function get()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $unreadIds = Message::select(DB::raw('`from` as sender_id, count(`from`) as unread_messages'))
            ->where('to', auth()->id())
            ->where('read', false)
            ->groupBy('from')
            ->get();

        $users = $users->map(function($user) use ($unreadIds){
            $unread = $unreadIds->where('sender_id', $user->id)->first();
            $user->unread_messages = $unread->unread_messages ?? 0;
            return $user;
        });

        return response()->json($users);
    }

    public function getMessagesFor($userId)
    {
        Message::where('from', $userId)->where('to', auth()->id())->update(['read' => true]);
        $messages = Message::where(function($q) use ($userId) {
            $q->where('to', $userId);
            $q->where('from', auth()->id());
        })->orWhere(function($q) use ($userId){
            $q->where('from', $userId);
            $q->where('to', auth()->id());
        })->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);
        $message = $message->load('fromContact');

        Pusher::trigger("messages.$message->to", 'NewMessage', ['message' => $message]);

        return response()->json($message);
    }
}

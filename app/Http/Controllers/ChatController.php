<?php

namespace App\Http\Controllers;

use App\Models\Dialog;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    // LOGIN API
    public function sendMessage(Request $request)
    {
        // validation
        $request->validate([
            "receiver_id" => "required",
            "text" => "required",
        ]);

        $user = auth()->user();

        $sender = $user->id;
        $receiver = $request->receiver_id;

        $dialog = Dialog::where(function($query) use ($sender, $receiver) {
            $query->where('sender_id', $sender)
                  ->where('receiver_id', $receiver);
        })->orWhere(function($query) use ($sender, $receiver) {
            $query->where('sender_id', $receiver)
                  ->where('receiver_id', $sender);
        })->first();

        if (!$dialog) {
            $dialog = Dialog::create([
                'sender_id' => $sender,
                'receiver_id' => $receiver
            ]);
        }

        $msg = new Message();

        $msg->dialog_id = $dialog->id;
        $msg->user_id = $sender;
        $msg->text = $request->text;

        $msg->save();

        MessageSent::dispatch($dialog, $msg);

        return response()->json([
            "status" => true,
            "dialog_id" => $dialog->id
        ], 200);
    }
}
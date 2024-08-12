<?php

namespace App\Events;

use App\Models\Dialog;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Dialog $dialog,
        public Message $msg
    ) {}

    public function broadcastWith(): array
    {
        return [
            'id' => $this->msg->id, 
            'text' => $this->msg->text,
            'sender_id' => $this->msg->user_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dialog.' . $this->dialog->sender_id),
            new PrivateChannel('dialog.' . $this->dialog->receiver_id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'messageSent';
    }
}

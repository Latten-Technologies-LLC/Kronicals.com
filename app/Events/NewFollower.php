<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class NewFollower implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $send_to;
    public $send_to_data;

    public $sent_from;
    public $sent_from_data;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // Plug vars
        $this->sent_from = auth()->user()->unique_salt_id;
        $this->sent_from_data = DB::table('users')->where('unique_salt_id', auth()->user()->unique_salt_id)->get();

        // Plug var
        $this->send_to = $data['user_to'];
        $this->sent_to_data = DB::table('users')->where('unique_salt_id', $data['user_to'])->get();
        
        // Create message
        $this->message = "Someone followed you!";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->send_to != $this->sent_from)
        {
            return ['notify.user.' . $this->send_to];
        }
    }
}

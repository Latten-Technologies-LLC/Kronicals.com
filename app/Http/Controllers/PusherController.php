<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Events\PostLiked;

class PusherController extends Controller
{

    public function sendNotification()
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        //Send a message to notify channel with an event name of notify-event
        //$pusher->trigger('notify.user.' . $to, 'notify-event', $message);
    }
}
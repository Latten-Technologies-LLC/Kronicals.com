<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\IncogMessages as IncogMessages;
use App\Libraries\Notifications as Notifications;



class TimelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->incog = new IncogMessages();
        $this->notifications = new Notifications();
    }
    
    public function index()
    {
        return view('timeline', ['no_footer' => false, 'notifications' => $this->notifications->get(auth()->user()->unique_salt_id), 'messages' => $this->incog->getMessages(auth()->user()->unique_salt_id), 'incog' => $this->incog]);
    }

    public function sent()
    {
        return view('timeline.sent', ['no_footer' => false, 'notifications' => $this->notifications->get(auth()->user()->unique_salt_id), 'sentmessages' => $this->incog->getSentMessages(auth()->user()->unique_salt_id), 'incog' => $this->incog]);
    }
}

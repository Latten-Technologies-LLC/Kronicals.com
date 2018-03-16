<?php

namespace App\Http\Controllers;

use App\Libraries\FollowSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\IncogMessages as IncogMessages;
use App\Libraries\Notifications as Notifications;
use App\Libraries\PostingSystem as PostingSystem;

use App\Events\NewUserSignup;

class TimelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(!is_null(Auth::check()))
        {
            $this->middleware('auth');

            $this->incog = new IncogMessages();
            $this->notifications = new Notifications();
            $this->postingSystem = new PostingSystem();
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }
    
    public function index()
    {
        return view('timeline', ['feed' => $this->postingSystem->feed(auth()->user()->unique_salt_id), 'no_footer' => false, 'notifications' => $this->notifications->get(auth()->user()->unique_salt_id), 'messages' => $this->incog->getMessages(auth()->user()->unique_salt_id), 'incog' => $this->incog, 'postingsystem' => $this->postingSystem]);
    }

    public function anons()
    {
        // Mark all read
        $this->incog->markAllRead(auth()->user()->unique_salt_id);

        // Load view
        return view('timeline.anons', ['no_footer' => false, 'notifications' => $this->notifications->get(auth()->user()->unique_salt_id), 'messages' => $this->incog->getMessages(auth()->user()->unique_salt_id), 'incog' => $this->incog]);
    }

    public function sent()
    {
        return view('timeline.sent', ['no_footer' => false, 'notifications' => $this->notifications->get(auth()->user()->unique_salt_id), 'sentmessages' => $this->incog->getSentMessages(auth()->user()->unique_salt_id), 'incog' => $this->incog]);
    }
}

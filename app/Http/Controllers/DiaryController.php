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

class DiaryController extends Controller
{
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

    //
    public function index()
    {
        if(!is_null(Auth::check()))
        {
            //$this->middleware('auth');

            return view('diary/index');
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    public function view($entry_id)
    {
        if(!is_null(Auth::check()))
        {
            //$this->middleware('auth');

            return view('diary/view', ['entry_id' => $entry_id]);
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }
}

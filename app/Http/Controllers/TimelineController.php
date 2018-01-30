<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\IncogMessages as IncogMessages;


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
    }
    
    public function index()
    {
        return view('timeline', ['no_footer' => false, 'messages' => $this->incog->getMessages(auth()->user()->unique_salt_id)]);
    }
}

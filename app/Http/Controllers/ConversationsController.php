<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\ConversationsSystem;

class ConversationsController extends Controller
{
    public $conversationSystem;

    //
    public function __construct()
    {
        if(!is_null(Auth::check()))
        {
            $this->conversationSystem = new ConversationsSystem;

            $this->middleware('auth');
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    public function index()
    {
        if(!is_null(Auth::check()))
        {
            return view('conversations/index');
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }
}

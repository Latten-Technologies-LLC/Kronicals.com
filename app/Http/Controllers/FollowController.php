<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Libraries\FollowSystem;

class FollowController extends Controller
{
    private $followSystem;
    
    public function __construct()
    {
        if(!is_null(Auth::check()))
        {
            $this->_followSystem = new FollowSystem();
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    //
    public function subscribe(Request $request)
    {
        // Validation
        $validation = $request->validate([
            'uid' => 'required'
        ]);
        
        // Call
        if(!is_null(Auth::check()))
        {
            echo json_encode($this->_followSystem->follow(['followee' => $request->uid]));
        }else{
            echo json_encode(['code' => 0, 'status' => 'You need to login!']);
        }
    }

    public function unsubscribe(Request $request)
    {
        // Validation
        $validation = $request->validate([
            'uid' => 'required'
        ]);

        // Call
        if(!is_null(Auth::check()))
        {
            echo json_encode($this->_followSystem->unfollow(['followee' => $request->uid]));
        }else{
            echo json_encode(['code' => 0, 'status' => 'You need to login!']);
        }
    }
}

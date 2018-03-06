<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\IncogMessages as IncogMessages;

class IncogController extends Controller
{
    /*
     * Response
     */
    protected $response = array();

    /*
     * Instance variable for incog messages
     */
    protected $incog;

    public function __construct()
    {
        $this->incog = new IncogMessages();
    }

    //
    public function index($username)
    {
        // Make sure this user exists
        $user = DB::table('users')->where('username', $username)->get();

        if(count($user) == 1)
        {
            // See if they're logged in
            if (Auth::check())
            {
                return view('incog/index-logged', ['user' => $user]);
            }else{
                return view('incog/index-not-logged', ['user' => $user]);
            }
        }else{
            return redirect('/');
        }
    }

    public function hide(Request $request)
    {
        if(isset($_POST))
        {
            // Now we're good, call the method
            $send = $this->incog->hideMessage($request->incogid);
            echo $send;
        }else{
            $this->response = array('code' => 0, 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function reply(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'id' => 'required',
            'message' => 'required|max:1000'
        ]);

        // Call method
        if(isset($_POST))
        {
            $reply = $this->incog->replyIncogMessage(['message' => $request->message, 'id' => $request->id]);
            echo $reply;
        }else{
            $this->response = array('code' => 0, 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function confess(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'id' => 'required',
        ]);

        // Call method
        if(isset($_POST))
        {
            $confess = $this->incog->confessAnon(['id' => $request->id]);
            echo $confess;
        }else{
            $this->response = array('code' => 0, 'Invalid Request');
            echo json_encode($this->response);
        }
    }
    
    public function send(Request $request)
    {
        if(isset($_POST))
        {
            // Validate
            $validate = $request->validate([
                'usi' => 'required',
                'message' => 'required|max:1000'
            ]);

            // See if its anonymous
            if(isset($request->anonymous))
            {
                $anonymous = '1';
            }else{
                $anonymous = '0';
            }
            
            // Now we're good, call the method
            //$send = $this->incog->sendIncogMessage(['usi' => $request->usi, 'message' => Crypt::encryptString($request->message), 'anonymous' => $anonymous]);
            $send = $this->incog->sendIncogMessage(['usi' => $request->usi, 'message' => $request->message, 'anonymous' => $anonymous]);
            echo $send;
        }else{
            $this->response = array('code' => 0, 'Invalid Request');
            echo json_encode($this->response);
        }
    }
}

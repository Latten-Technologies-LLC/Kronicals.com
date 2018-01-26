<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function send(Request $request)
    {
        if(isset($_POST))
        {
            // Validate
            $validate = $request->validate([
                'usi' => 'required',
                'message' => 'required|max:1000',
            ]);

            // Now we're good, call the method
            $send = $this->incog->sendIncogMessage(['usi' => $request->usi, 'message' => $request->message]);
            echo $send;
        }else{
            $this->response = array('code' => 0, 'Invalid Request');
            echo json_encode($this->response);
        }
    }
}

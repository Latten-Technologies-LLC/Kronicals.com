<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Libraries\User;
use App\Libraries\Settings as Settings;

class AccountSettingsController extends Controller
{
    /*
     * For responses
     */
    protected $response = array();

    /*
     * Instance variables for settings class
     */
    protected $settings;

    public function __construct()
    {
        $this->middleware("auth");

        // Instance
        $this->settings = new Settings();
    }

    //
    public function index()
    {
        return view('account_settings/index', array('user' => new User));
    }
    
    public function email_change()
    {
        return view('account_settings/email_change');   
    }

    public function password_change()
    {
        return view('account_settings/password_change');
    }

    // Ajax
    public function change_basic_info(Request $request)
    {
        if(isset($_POST))
        {
            // Validation
            $data = $request->validate([
                'name' => 'required|max:255',
                'bio' => 'required|max:1000',
                'interests' => 'required|max:255'
            ]);

            // Since it's validated, call the function
            $change = $this->settings->basic_information_change(auth()->user()->id, ['name' =>  $request->name, 'bio' => $request->bio, 'interests' => $request->interests]);
            echo $change;
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function change_email(Request $request)
    {
        if(isset($_POST))
        {
            // Validation
            $data = $request->validate([
                'email' => 'required|unique:users|email',
            ]);

            // Since it's validated, call the function
            $change = $this->settings->email_information_change(auth()->user()->id, ['email' =>  $request->email]);
            echo $change;
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function change_password(Request $request)
    {
        if(isset($_POST))
        {
            // Validation
            $data = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string',
                'new_password_confirmation' => 'required|string'
            ]);

            // Make sure the current matches the current
            if((Hash::check($request->current_password, Auth()->user()->password)))
            {
                // Make sure current pass isnt same as old one
                if(strcmp($request->get('current-password'), $request->get('new-password')) == 0)
                {
                    // Since it's validated, call the function
                    $change = $this->settings->password_information_change(auth()->user()->id, ['new_password' => $request->new_password, 'new_password_confirmation' => $request->new_password_confirmation]);
                    echo $change;
                }else{
                    $this->response = array('code' => 0, 'desc' => 'New Password cannot be same as your current password. Please choose a different password.');
                    echo json_encode($this->response);
                }
            }else{
                $this->response = array('code' => 0, 'desc' => 'Your current password does not matches with the password you provided. Please try again.');
                echo json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function change_profile_picture(Request $request)
    {
        if(isset($_POST))
        {
            // Validation
            $data = $request->validate([
                'profile_picture' => 'required'
            ]);

            if($request->hasFile('profile_picture'))
            {
                // Call function
                $upload = $this->settings->change_profile_picture(auth()->user()->id, [
                    'profile_picture' => $request
                ]);

                // Done
                echo $upload;
            }else{
                $this->response = array('code' => 0, 'desc' => 'Invalid Request');
                echo json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request');
            echo json_encode($this->response);
        }
    }

    public function change_profile_banner(Request $request)
    {
        if(isset($_POST))
        {
            // Validation
            $data = $request->validate([
                'profile_banner' => 'required'
            ]);

            if($request->hasFile('profile_banner'))
            {
                // Call function
                $upload = $this->settings->change_profile_banner(auth()->user()->id, [
                    'profile_banner' => $request
                ]);

                // Done
                echo $upload;
            }else{
                $this->response = array('code' => 0, 'desc' => 'Invalid Request');
                echo json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request');
            echo json_encode($this->response);
        }
    }
}

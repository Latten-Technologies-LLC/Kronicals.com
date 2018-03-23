<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserSignup;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Libraries\TutorialSystem;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/timeline';

    protected $newUserID = "";
    protected $newUserSalt = "";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Now create a unique id
        $unique_id = md5($data['username']);

        // Put user model in var
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'unique_salt_id' => $unique_id,
            'profile_picture' => 'default_pic.jpg',
            'banner_picture' => 'default_banner.jpg',
            'remove_ads' => '0',
            'tutorial' => json_encode(TutorialSystem::init())
        ]);

        // See if it works
        if($user->save())
        {
            // Now since thats all good we can get the users id
            $this->_newUserID = $user->id;
            $this->_newUserSalt = $unique_id;

            // Now create directories
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt, 0777, true);
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/profile_pictures', 0777, true); // Profile pictures
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/banners', 0777, true); // Banners
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/photos', 0777, true); // Photos
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/videos', 0777, true); // Videos
            mkdir(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/data', 0777, true); // Data

            copy(storage_path() . '/data/user_data/default_pic.jpg', storage_path() . '/data/user_data/' . $this->_newUserSalt . '/profile_pictures/default_pic.jpg'); // Default profile pic
            copy(storage_path() . '/data/user_data/default_banner.jpg', storage_path() . '/data/user_data/' . $this->_newUserSalt . '/banners/default_banner.jpg'); // Default banner pic

            // Permissions
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/profile_pictures/default_pic.jpg', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/banners/default_banner.jpg', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt, 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/profile_pictures', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/banners', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/photos', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/videos', 0777);
            chmod(storage_path() . '/data/user_data/' . $this->_newUserSalt . '/data', 0777);

            // User info
            $insertUserInfo = DB::table("user_info")->insert([
                'unique_salt_id' => $this->_newUserSalt,
                'user_bio' => 'Hi! Welcome to my profile'
            ]);

            // Alert everyone
            event(new NewUserSignup($user));

            return $user;
        }
        else{
            echo "Nope";
        }
    }
}

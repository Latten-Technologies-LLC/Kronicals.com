<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\FileHandler as FileHandler;

class FileHandlerController extends Controller
{
    //
    public function __construct()
    {
        // Instance variables
        $this->filehandle = new FileHandler();
    }
    
    public function find($salt, $file)
    {
        if(!empty($salt) && !empty($file))
        {
            // Call the function
            if($file == "profile_picture")
            {
                // Find current profile pic
                $user = DB::table('users')->where('unique_salt_id', $salt)->get()[0];

                // Create path
                $path = storage_path() . "/data/user_data/" . $salt . '/profile_pictures/' . $user->profile_picture;

                // Display file
                $this->filehandle->photo($path);
            }else{
                // Find current profile pic
                $user = DB::table('users')->where('unique_salt_id', $salt)->get()[0];

                // Create path
                $path = storage_path() . "/data/user_data/" . $salt . '/banners/' . $user->banner_picture;

                // Display file
                $this->filehandle->photo($path);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }
}

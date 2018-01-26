<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\User;

class Settings
{
    /*
     * Will be used to hold a persons
     * user ID globally
     */
    protected $user_id;

    /*
     * This will hold the response from the functions
     */
    protected $response = array();

    /*
     * User class object
     */
    protected $user;

    /*
     *
     */
    public function __construct()
    {
        $this->user = new User;
    }

    /*
     * basic_information_change()
     * ---
     * Desc: This will change the basic information of the user
     */
    public function basic_information_change($id, $data = array())
    {
        if(!empty($id) && !empty($data))
        {
            // Lets make sure this user exists
            if(count(User::exists($id)) == 1)
            {
                // Get user unique salt
                $salt = json_decode($this->user->get($id, 'unique_salt_id'), true);

                // Now lets update everything
                $update_users_table = DB::table('users')->where('id', $id)->update(['name' => $data['name']]);
                $update_user_info_table = DB::table('user_info')->where('unique_salt_id', $salt['unique_salt_id'])->update([
                    'user_bio' => $data['bio'],
                    'interests' => $data['interests']
                ]);

                // We should be good
                $this->response = array('code' => 1, 'desc' => 'Your information has been updated successfully!');
                return json_encode($this->response);
            }else{
                $this->response = array('code' => 0, 'desc' => 'This user doesn\'t exist!');
                return json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }

    public function password_information_change($id, $data = array())
    {
        if(!empty($id) && !empty($data))
        {
            if(count(User::exists($id)) == 1)
            {
                // Create object
                $user = Auth::user();

                // Change password
                $user->password = bcrypt($data['new_password']);

                // Save
                $user->save();

                // Done
                $this->response = array('code' => 1, 'desc' => 'Your information has been updated successfully!');
                return json_encode($this->response);
            }else{
                $this->response = array('code' => 0, 'desc' => 'This user doesn\'t exist!');
                return json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }

    /*
     * This will change the users email
     */
    public function email_information_change($id, $data = array())
    {
        if(!empty($id) && !empty($data))
        {
            // Lets make sure this user exists
            if(count(User::exists($id)) == 1)
            {
                // Now lets update everything
                $update_users_table = DB::table('users')->where('id', $id)->update(['email' => $data['email']]);

                // We should be good
                $this->response = array('code' => 1, 'desc' => 'Your information has been updated successfully!');
                return json_encode($this->response);
            }else{
                $this->response = array('code' => 0, 'desc' => 'This user doesn\'t exist!');
                return json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }

    public function change_profile_picture($id, $data = array())
    {
        if(!empty($id) && !empty($data))
        {
            if(count(User::exists($id)) == 1)
            {
                // Get salt
                $salt = json_decode($this->user->get($id, 'unique_salt_id'), true);

                // Create file name
                $name = md5($data['profile_picture']->profile_picture->path()) . '.jpg';

                // Now store the image
                $data['profile_picture']->profile_picture->move(storage_path() . '/data/user_data/' . $salt['unique_salt_id'] . '/profile_pictures', $name);

                // Update the records
                $update = DB::table('users')->where('id', $id)->update(['profile_picture' => $name]);

                // Return info
                $this->response = array('code' => 1, 'desc' => 'Profile picture has been updated successfully!');
                return json_encode($this->response);
            }else{
                $this->response = array('code' => 0, 'desc' => 'This user doesn\'t exist!');
                return json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }

    public function change_profile_banner($id, $data = array())
    {
        if(!empty($id) && !empty($data))
        {
            if(count(User::exists($id)) == 1)
            {
                // Get salt
                $salt = json_decode($this->user->get($id, 'unique_salt_id'), true);

                // Create file name
                $name = md5($data['profile_banner']->profile_banner->path()) . '.jpg';

                // Now store the image
                $data['profile_banner']->profile_banner->move(storage_path() . '/data/user_data/' . $salt['unique_salt_id'] . '/banners', $name);

                // Update the records
                $update = DB::table('users')->where('id', $id)->update(['banner_picture' => $name]);

                // Return info
                $this->response = array('code' => 1, 'desc' => 'Banner picture has been updated successfully!');
                return json_encode($this->response);
            }else{
                $this->response = array('code' => 0, 'desc' => 'This user doesn\'t exist!');
                return json_encode($this->response);
            }
        }else{
            $this->response = array('code' => 0, 'desc' => 'Invalid Request. Please try again');
            return json_encode($this->response);
        }
    }
}
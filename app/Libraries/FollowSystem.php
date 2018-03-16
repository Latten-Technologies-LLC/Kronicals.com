<?php
namespace App\Libraries;

use App\Mail\FollowNew;
use App\Mail\IncogConfess;
use App\Mail\IncogReceived;
use App\Mail\IncogReply;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;
use App\Libraries\Notifications;


class FollowSystem
{
    public function __construct()
    {
        $this->notifications = new Notifications();
    }

    public function follow($data)
    {
        if(Auth::check())
        {
            if (!empty($data))
            {
                // Vars
                $follower = auth()->user()->unique_salt_id;
                $followee = $data['followee'];

                // Make sure the followee exists
                $check = DB::table('users')->where('unique_salt_id', $followee)->get();

                if (count($check) == 1)
                {
                    // Now lets see if the follower follows that person already
                    $status = DB::table('followings')->where(['follower_id' => $follower, 'followee_id' => $followee])->get();

                    if(count($status) == 0)
                    {
                        // Now insert stuff
                        $insert = DB::table('followings')->insert([
                            'follower_id' => $follower,
                            'followee_id' => $followee,
                            'date' => date('y-m-d H:i:s')
                        ]);

                        // Notify
                        $notify = $this->notifications->make(['user_to' => $check[0]->unique_salt_id, 'from' => auth()->user()->unique_salt_id, 'type' => 'follow', 'message' => 'Has followed you!']);
                        Mail::to($check[0]->email)->send(new FollowNew(['fullname' => $check[0]->name, 'url' => url('/') . '/timeline']));

                        // Return
                        return ['code' => 1];
                    }else{
                        return ['code' => 0, 'status' => 'You already follow this user!'];
                    }
                } else {
                    // Oops
                    return ['code' => 0, 'status' => 'This user does not exist!'];
                }
            }else{
                return ['code' => 0, 'status' => 'Error occurred!'];
            }
        }else{
            return ['code' => 0, 'status' => 'You need to login!'];
        }
    }

    public function unfollow($data)
    {
        if(Auth::check())
        {
            if (!empty($data))
            {
                // Vars
                $follower = auth()->user()->unique_salt_id;
                $followee = $data['followee'];

                // Make sure the followee exists
                $check = DB::table('users')->where('unique_salt_id', $followee)->get();

                if (count($check) == 1)
                {
                    // Now lets see if the follower follows that person already
                    $status = DB::table('followings')->where(['follower_id' => $follower, 'followee_id' => $followee])->get();

                    if(count($status) == 1)
                    {
                        // Now delete stuff
                        $delete = DB::table('followings')->where(['follower_id' => $follower, 'followee_id' => $followee])->delete();

                        // Return
                        return ['code' => 1];
                    }else{
                        return ['code' => 0, 'status' => 'You never followed this user!'];
                    }
                } else {
                    // Oops
                    return ['code' => 0, 'status' => 'This user does not exist!'];
                }
            }else{
                return ['code' => 0, 'status' => 'Error occurred!'];
            }
        }else{
            return ['code' => 0, 'status' => 'You need to login!'];
        }
    }

    public function following($unique_id)
    {
        if(!empty($unique_id))
        {
            return DB::table("followings")->where('follower_id', $unique_id)->get();
        }
    }

    public function check($id)
    {
        if(!empty($id))
        {
            return $check = DB::table('followings')->where(['follower_id' => auth()->user()->unique_salt_id, 'followee_id' => $id])->get();
        }else{
            return false;
        }
    }
}
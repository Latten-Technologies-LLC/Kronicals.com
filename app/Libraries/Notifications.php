<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\User;
use App\Events\PostLiked;

class Notifications
{
    protected $id;

    /*
     * Instance variable for users class
     */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function make($data)
    {
        // Just insert
        $insert = DB::table('notifications')->insert([
            'user_to' => $data['user_to'],
            'user_from' => $data['from'],
            'type' => $data['type'],
            'message' => $data['message'],
            'date' => date('Y-m-d H:i:s'),
            'read' => '0'
        ]);
        
        return $insert;
    }

    public function get($unique_id)
    {
        return DB::table('notifications')->where('user_to', $unique_id)->orderBy('id', 'desc')->get();
    }
    
    public function unreadNotifications($unique_id)
    {
        return DB::table('notifications')->where(['user_to' => $unique_id, 'read' => '0'])->get();

    }

    public function markAllRead($user_salt)
    {
        if(!empty($user_salt))
        {
            return DB::table('notifications')->where(['user_to' => $user_salt, 'read' => '0'])->update(['read' => '1']);
        }
    }
}
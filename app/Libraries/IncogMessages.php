<?php
namespace App\Libraries;

use App\Mail\incogReceived;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;
use App\Libraries\Notifications;


class IncogMessages
{
    protected $id;

    /*
     * Instance variable for users class
     */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
        $this->notifications = new Notifications();
    }
    
    public function hideMessage($id)
    {
        // Just update
        DB::table('incog_messages')->where('id', $id)->update(['hide' => '1']);

        // Response
        return json_encode(['code' => 1, 'status' => 'Message has been hidden']);
    }

    public function getMessages($user_salt)
    {
        if(!empty($user_salt))
        {
            $messages = DB::table('incog_messages')->where('user_id', $user_salt)->get();

            if(count($messages) > 0)
            {
                $response = ['code' => 1, 'messages' => $messages];
                return json_encode($response);
            }else{
                return json_encode(['code' => 0, 'message' => 'You don\'t have any messages yet']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'You don\'t have any messages yet']);
        }
    }

    public function getSentMessages($user_salt)
    {
        if(!empty($user_salt))
        {
            $messages = DB::table('incog_messages')->where('from_id', $user_salt)->get();

            if(count($messages) > 0)
            {
                $response = ['code' => 1, 'messages' => $messages];
                return json_encode($response);
            }else{
                return json_encode(['code' => 0, 'message' => 'You don\'t have any sent messages yet']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'You don\'t have any sent messages yet']);
        }
    }

    public function sendIncogMessage($data)
    {
        if(!empty($data['usi']) && !empty($data['message']))
        {
            // Make sure the user exists
            $check = DB::table('users')->where("unique_salt_id", $data['usi'])->get();

            // Make sure they're real
            if(count($check))
            {
                // See if someone is logged
                if(Auth::check())
                {
                    // Now insert the message (Logged)
                    $insert = DB::table('incog_messages')->insert([
                        'user_id' => $data['usi'],
                        'from_id' => auth()->user()->unique_salt_id,
                        'message' => Crypt::encrypt($data['message']),
                        'date' => date('y-m-d H:i:s'),
                        'anonymous' => $data['anonymous']
                    ]);

                    $notify = $this->notifications->make(['user_to' => $data['usi'], 'from' => auth()->user()->unique_salt_id, 'type' => 'incog', 'message' => 'New anonymous message!']);

                }else{
                    // Now insert the message (Not logged)
                    $insert = DB::table('incog_messages')->insert([
                        'user_id' => $data['usi'],
                        'from_id' => '',
                        'message' => Crypt::encrypt($data['message']),
                        'date' => date('y-m-d H:i:s'),
                    ]);

                    // Notify
                    $notify = $this->notifications->make(['user_to' => $data['usi'], 'from' => 'null', 'type' => 'incog', 'message' => 'New anonymous message!']);

                }

                // Email
                //Mail::to($check[0]->email)->send(new incogReceived(['fullname' => $check[0]->name]));

                // Return
                return json_encode(['code' => 1, 'message' => 'Your message has been sent!']);
            }else{
                return json_encode(['code' => 0, 'message' => 'This user does not exist!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Please enter a message!']);
        }
    }
    
    public function replyIncogMessage($data)
    {
        if(!empty($data['id']) && !empty($data['message']))
        {
            // Make sure they're logged in
            if(Auth::check())
            {
                // Make sure the message exists
                $check = DB::table('incog_messages')->where('id', $data['id'])->get();

                if(count($check) == 1)
                {
                    // Now insert the reply
                    $insert = DB::table('incog_reply')->insert([
                        'incog_id' => $data['id'],
                        'user_id' => auth()->user()->unique_salt_id,
                        'message' => Crypt::encrypt($data['message']),
                        'date' => date('y-m-d H:i:s'),
                        'hide' => '0'
                    ]);

                    // Notify
                    $notify = $this->notifications->make(['user_to' => $check[0]->from_id, 'from' => auth()->user()->unique_salt_id, 'type' => 'incog-reply', 'message' => 'New reply to your <a href="'.url('/').'/timeline/sent/?m='.$data['id'].'">message!</a>']);

                    // Email
                    

                    // Reply
                    return json_encode(['code' => 1, 'message' => 'Your reply has been sent!', 'data' => ['name' => ucwords(auth()->user()->name), 'username' => auth()->user()->username, 'usi' => auth()->user()->unique_salt_id, 'message' => $data['message'], 'url' => url('/')]]);
                }else{
                    return json_encode(['code' => 0, 'message' => 'This message does not exist!']);
                }
            }else{
                return json_encode(['code' => 0, 'message' => 'You must be logged in!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Please enter a message!']);
        }
    }

    public function displayIncogMessageReplies($data)
    {
        return DB::table('incog_reply')->where('incog_id', $data['id'])->get();
    }
}
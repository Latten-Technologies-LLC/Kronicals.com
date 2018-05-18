<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\User;
use App\Libraries\Notifications as Notifications;
use App\Libraries\FollowSystem as FollowSystem;
use App\Libraries\DiarySystem as DiarySystem;

class PostingSystem
{
    // make() function vars
    private $type;
    private $text;
    
    private $followSystem;
    private $notifications;
    
    public function __construct()
    {
        $this->followSystem = new FollowSystem();
        $this->notifications = new Notifications();
    }

    /*
     * Creates a timeline feed
     */
    public function feed()
    {
        if(!is_null(Auth::check()))
        {
            // Get users followings
            $followings = $this->followSystem->following(auth()->user()->unique_salt_id);

            // Following Id's
            $ids = [];

            // Iterate through ids
            foreach($followings as $follow){
                $ids[] = $follow->followee_id;
            }

            // Add logged user
            array_push($ids, auth()->user()->unique_salt_id);

            if(count($ids) > 0)
            {
                // return
                return json_encode(DB::table("timeline_posts")->whereIn('user_id', $ids)->orderBy('id', 'desc')->get());
            }else{
                return json_encode(['code' => 0, 'message' => 'No posts to show']);
            }
        }
    }
    
    /*
     * Creates a profile feed
     */
    public function profile()
    {
        
    }

    /*
     * Creates a post
     */
    public function make($data)
    {
        if(!empty($data))
        {
            // Now lets do stuff
            $this->_type = $data['type'];
            $this->_text = $data['text'];

            // Insert
            $types = ['1', '2', '3'];

            if(in_array($this->_type, $types))
            {
                // Now lets insert the post
                if($this->type == "3")
                {
                    // First make the main post
                    $insert = DB::table('timeline_posts')->insertGetId(['user_id' => auth()->user()->unique_salt_id, 'text' => Crypt::encrypt($this->_text), 'type' => $this->_type, 'date' => date('y-m-d H:i:s'), 'removed' => '0']);

                    // This is a diary post, make the entry second
                    $data['parent_id'] = $insert;

                    // Process
                    return DiarySystem::makeEntry($data);
                }else {
                    $insert = DB::table('timeline_posts')->insertGetId(['user_id' => auth()->user()->unique_salt_id, 'text' => Crypt::encrypt($this->_text), 'type' => $this->_type, 'date' => date('y-m-d H:i:s'), 'removed' => '0']);
                }

                // Beta 1.3 send event to followers

                // Return info
                return json_encode(['code' => 1, 'message' => 'Posted successfully!', 'post' => [
                    'user_data' => ['name' => auth()->user()->name, 'username' => auth()->user()->username, 'user_id' => auth()->user()->unique_salt_id],
                    'post_data' => ['id' => $insert, 'user_id' => auth()->user()->unique_salt_id, 'text' => $this->_text, 'type' => $this->_type, 'date' => 'Just now']
                ]]);
            }else{
                return json_encode(['code' => 0, 'message' => 'Invalid post type!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Error occurred, try again!']);
        }
    }

    /*
     * Liking posts
     */
    public function like($data)
    {
        if(!empty($data))
        {
            // make sure post exist
            if(count($check = $this->exists($data['pid'])) == 1)
            {
                // Now insert the like
                DB::table('likes')->insert(['user_id' => auth()->user()->unique_salt_id, 'post_id' => $data['pid'], 'date' => date('y-m-d H:i:s')]);

                // Notify
                if(auth()->user()->unique_salt_id != $check[0]->user_id)
                {
                    $notify = $this->notifications->make(['user_to' => $check[0]->user_id, 'from' => auth()->user()->unique_salt_id, 'type' => 'post-like', 'message' => 'Has liked your post!']);
                }

                // Return
                return json_encode(['code' => 1, 'count'  => $this->count($data['pid']), 'Liked successfully']);
            }else{
                return json_encode(['code' => 0, 'message' => 'This post does not exist!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Invalid request, try again!']);
        }
    }

    /*
     * Unliking posts
     */
    public function unlike($data)
    {
        if(!empty($data))
        {
            // make sure post exist
            if(count($check = $this->exists($data['pid'])) == 1)
            {
                // Now insert the like
                DB::table('likes')->where(['user_id' => auth()->user()->unique_salt_id, 'post_id' => $data['pid']])->delete();

                // Return
                return json_encode(['code' => 1, 'count'  => $this->count($data['pid']), 'Unliked successfully']);
            }else{
                return json_encode(['code' => 0, 'message' => 'This post does not exist!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Invalid request, try again!']);
        }
    }

    /*
     * Delete posts
     */
    public function delete($data)
    {
        if(!empty($data))
        {
            // make sure post exist
            if(count($check = $this->exists($data['pid'])) == 1)
            {
                // Make sure logged user owns this post
                if($check[0]->user_id == auth()->user()->unique_salt_id)
                {
                    // Now delete the post
                    DB::table('timeline_posts')->where(['id' => $data['pid']])->delete();

                    // Return
                    return json_encode(['code' => 1, 'message' => 'Deleted successfully']);
                }else{
                    return json_encode(['code' => 0, 'message' => 'You can not delete a post that isnt yours']);
                }
            }else{
                return json_encode(['code' => 0, 'message' => 'This post does not exist!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Invalid request, try again!']);
        }
    }

    /*
     * Check if post exist
     */
    public function exists($pid)
    {
        return DB::table('timeline_posts')->where('id', $pid)->get();
    }

    /*
     * Count likes
     */
    public function count($pid)
    {
        return count(DB::table('likes')->where('post_id', $pid)->get());
    }

    /*
     * Check like status
     */
    public function check($pid)
    {
        if(!empty($pid))
        {
            return $check = DB::table('likes')->where(['user_id' => auth()->user()->unique_salt_id, 'post_id' => $pid])->get();
        }else{
            return false;
        }
    }

    /*
     * Make comment
     */
    public function comment($data)
    {
        if(!empty($data['pid']) && !empty($data['message']))
        {
            // Make sure they're logged in
            if(Auth::check())
            {
                // Make sure the post exists
                $check = DB::table('timeline_posts')->where('id', $data['pid'])->get();

                if(count($check) == 1)
                {
                    // Now insert the reply
                    $insert = DB::table('timeline_posts_reply')->insert([
                        'post_id' => $data['pid'],
                        'user_id' => auth()->user()->unique_salt_id,
                        'message' => Crypt::encrypt($data['message']),
                        'date' => date('y-m-d H:i:s'),
                        'hide' => '0'
                    ]);

                    // Notify
                    if (auth()->user()->unique_salt_id != $check[0]->user_id)
                    {
                        $notify = $this->notifications->make(['user_to' => $check[0]->user_id, 'from' => auth()->user()->unique_salt_id, 'type' => 'post-reply', 'message' => 'New reply to your post']);

                        // Email
                        $email = DB::table('users')->where('unique_salt_id', $check[0]->user_id)->get();
                        Mail::to($email[0]->email)->send(new postReply(['fullname' => $email[0]->name, 'url' => url('/') . '/timeline']));
                    }

                    // Reply
                    return json_encode(['code' => 1, 'message' => 'Your reply has been sent!', 'data' => ['name' => ucwords(auth()->user()->name), 'username' => auth()->user()->username, 'usi' => auth()->user()->unique_salt_id, 'message' => $data['message'], 'url' => url('/')]]);
                }else{
                    return json_encode(['code' => 0, 'message' => 'This post does not exist!']);
                }
            }else{
                return json_encode(['code' => 0, 'message' => 'You must be logged in!']);
            }
        }else{
            return json_encode(['code' => 0, 'message' => 'Please enter a reply!']);
        }
    }

    /*
     * Display replies
     */
    public function replies($pid)
    {
        return DB::table('timeline_posts_reply')->where('post_id', $pid)->get();
    }
}
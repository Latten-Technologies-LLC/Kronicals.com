<?php

namespace App\Http\Controllers;

use App\Libraries\FollowSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\IncogMessages as IncogMessages;
use App\Libraries\Notifications as Notifications;
use App\Libraries\PostingSystem as PostingSystem;

class DiaryController extends Controller
{
    public function __construct()
    {
        if(!is_null(Auth::check()))
        {
            $this->middleware('auth');

            $this->incog = new IncogMessages();
            $this->notifications = new Notifications();
            $this->postingSystem = new PostingSystem();
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    //
    public function index()
    {
        if(!is_null(Auth::check()))
        {
            //$this->middleware('auth');

            return view('diary/index');
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    public function view($entry_id)
    {
        if(!is_null(Auth::check()))
        {
            //$this->middleware('auth');

            return view('diary/view', ['entry_id' => $entry_id]);
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }

    public function convert(Request $request)
    {
        $pid = $request->pid;

        if(!is_null(Auth::check()))
        {
            $entry = DB::table('diary_entry')->where(['id' => $pid])->get();

            if(count($entry) == 1)
            {
                if (Auth()->user()->unique_salt_id == $entry[0]->entry_author)
                {
                    // Insert
                    $insert = DB::table('timeline_posts')->insertGetId(['user_id' => auth()->user()->unique_salt_id, 'text' => $entry[0]->entry_text, 'type' => '2', 'data' => '', 'date' => date('y-m-d H:i:s'), 'removed' => '0']);

                    // Update
                    DB::table('diary_entry')->where('id', $entry[0]->id)->update(['parent_id' => $insert]);

                    echo json_encode(['code' => 1, 'message' => 'Entry has been converted!']);
                } else {
                    echo json_encode(['code' => 0, 'message' => 'Access denied']);
                }
            }else{
                echo json_encode(['code' => 0, 'message' => 'Entry does not exist']);
            }
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
        }
    }
}

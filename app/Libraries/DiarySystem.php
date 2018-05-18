<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\User;

class DiarySystem
{

    static public function fetchDiary($user_id)
    {
        return DB::table('diary_entry')->where(['entry_author' => auth()->user()->unique_salt_id])->get();
    }

    static public function fetchEntry($entry_id)
    {
        return DB::table('timeline_posts')->where(['id' => $entry_id])->get();
    }

    static public function makeEntry($data)
    {
        if(!empty($data))
        {
            
        }
        else{
            return json_encode(['code' => 0, 'message' => 'Error occurred, try again!']);
        }
    }
}
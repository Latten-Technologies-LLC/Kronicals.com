<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
            $insert = DB::table('diary_entry')->insertGetId(['parent_id' => $data['parent_id'], 'entry_author' => auth()->user()->unique_salt_id, 'entry_title' => $data['title'], 'entry_text' => Crypt::encrypt($data['text']), 'entry_date' => date('y-m-d H:i:s')]);

            // Return
            return json_encode(['code' => 1, 'id' => $insert, 'message' => 'Success!']);
        }
        else{
            return json_encode(['code' => 0, 'message' => 'Error occurred, try again!']);
        }
    }
}
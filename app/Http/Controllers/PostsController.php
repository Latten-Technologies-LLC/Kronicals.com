<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Libraries\PostingSystem as PostingSystem;

class PostsController extends Controller
{
    public $PostingSystem;

    //
    public function __construct()
    {
        if(!is_null(Auth::check()))
        {
            $this->PostingSystem = new PostingSystem();
        }else{
            echo json_encode(['code' => 0, 'message' => 'Access denied']);
            return false;
        }
    }

    // Making a new post
    public function make(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'text' => 'required',
            'type' => 'required'
        ]);

        // Lets go
        echo $this->PostingSystem->make(['text' => $request->text, 'type' => $request->type]);
    }

    // Liking posts
    public function like(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'pid' => 'required',
        ]);

        // Lets go
        echo $this->PostingSystem->like(['pid' => $request->pid]);
    }

    // Unliking posts
    public function unlike(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'pid' => 'required',
        ]);

        // Lets go
        echo $this->PostingSystem->unlike(['pid' => $request->pid]);
    }

    // Delete posts
    public function delete(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'pid' => 'required',
        ]);

        // Lets go
        echo $this->PostingSystem->delete(['pid' => $request->pid]);
    }

    // Delete posts
    public function comment(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'pid' => 'required',
            'message' => 'required|max:1000'
        ]);

        // Lets go
        echo $this->PostingSystem->comment(['pid' => $request->pid, 'message' => $request->message]);
    }
}

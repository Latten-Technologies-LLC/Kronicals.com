<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function index($username)
    {
        // Make sure this user exists
        $user = DB::table('users')->where('username', $username)->get();

        if(count($user) == 1)
        {
            // See if they're logged in
            if (Auth::check())
            {
                return view('profile/index-logged', ['user' => $user]);
            }else{
                return view('profile/index-not-logged', ['user' => $user]);
            }
        }else{
            return redirect('/');
        }
    }

    public function feed($username)
    {
        // Make sure this user exists
        $user = DB::table('users')->where('username', $username)->get();

        if(count($user) == 1)
        {
            // See if they're logged in
            if (Auth::check())
            {
                return view('profile/pages/feed/logged-feed', ['user' => $user]);
            }else{
                return view('profile/pages/feed/feed', ['user' => $user]);
            }
        }else{
            return redirect('/');
        }
    }

    public function followings($username)
    {
        // Make sure this user exists
        $user = DB::table('users')->where('username', $username)->get();

        if(count($user) == 1)
        {
            // See if they're logged in
            if (Auth::check())
            {
                return view('profile/pages/followings/logged-followings', ['user' => $user]);
            }else{
                return view('profile/pages/followings/followings', ['user' => $user]);
            }
        }else{
            return redirect('/');
        }
    }

    public function followers($username)
    {
        // Make sure this user exists
        $user = DB::table('users')->where('username', $username)->get();

        if(count($user) == 1)
        {
            // See if they're logged in
            if (Auth::check())
            {
                return view('profile/pages/followers/logged-followers', ['user' => $user]);
            }else{
                return view('profile/pages/followers/followers', ['user' => $user]);
            }
        }else{
            return redirect('/');
        }
    }
}

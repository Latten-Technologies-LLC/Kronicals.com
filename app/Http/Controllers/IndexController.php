<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    //
    public function index()
    {
        // Redirect if logged
        if(Auth::check())
        {
            return redirect('/timeline');
        }
        
        return view('index')->with('no_footer', true);
    }
}

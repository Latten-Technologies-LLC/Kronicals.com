<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SearchController extends Controller
{
    //
    public function live(Request $request)
    {
        // Validation
        $validation = $request->validate([
           'searchMainInput' => 'required|max:255' 
        ]);
        
        // Logic
        $search = DB::table('users')->where('name', 'like', '%'. $request->searchMainInput .'%')->orWhere('username', 'like', '%'. $request->searchMainInput .'%')->get();

        // Response
        $response['code'] = 1;
        $response['url'] = url('/');
        $response['data'] = $search;

        echo json_encode($response);
    }
}

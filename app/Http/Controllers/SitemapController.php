<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    //
    public function index(){
        return response()->view('sitemap.index', [
            'post' => array(),
            'podcast' => array(),
        ])->header('Content-Type', 'text/xml');
    }
}

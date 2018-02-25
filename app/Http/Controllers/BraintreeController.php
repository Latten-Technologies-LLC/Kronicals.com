<?php

namespace App\Http\Controllers;

use Braintree_ClientToken;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
    //
    public function token ()
    {
        return json_encode(
            array(
                'token'=> Braintree_ClientToken::generate()
            )
        );
    }
}

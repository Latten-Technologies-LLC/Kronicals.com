<?php

namespace App\Http\Controllers;

use Braintree_ClientToken;
use Illuminate\Http\Request;
use Braintree_Transaction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


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
    
    public function disableAds(Request $request)
    {
        // Validate
        $validation = $request->validate([
            'payment_method_nonce' => 'required',
        ]);

        // Now make sure the user exist
        if(Auth::check())
        {
            // Now check to see if they've paid already
            if(auth()->user()->remove_ads == 0)
            {
                // Split name
                $name = auth()->user()->name;

                if(count(explode(" ", $name)) == 2)
                {
                    // First & Lastname
                    $firstname = explode(" ", $name)[0];
                    $lastname = explode(" ", $name)[1];
                }else{
                    $firstname = explode(" ", $name)[0];
                    $lastname = explode(" ", $name)[1] . ' ' . explode(" ", $name)[2];
                }

                // Make the purchase
                $result = Braintree_Transaction::sale([
                    'amount' => 2,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => [
                        'submitForSettlement' => True
                    ],
                    'customer' => [
                        'firstName' => $firstname,
                        'lastName' => $lastname,
                        'email' => auth()->user()->email
                    ],
                ]);

                if($result->success)
                {
                    // Now remove ads
                    DB::table('users')->where('unique_salt_id', auth()->user()->unique_salt_id)->update(['remove_ads' => '1']);

                    return redirect("timeline")->with('success', "Ads removed!");
                }else{
                    $errors = '';

                    foreach($result->errors->deepAll() AS $error) {
                        $errors .= "<li>" . $error->message . "</li>";
                    }

                    return redirect("timeline")->with('error', "Error occurred! Please try again <br /><ul>".$errors."</ul>");
                }
            }else{
                return redirect("timeline")->with('error', "You've already paid to have your ads removed!");
            }
        }else{
            return redirect("login")->with('error', 'You must be logged in');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    //achieve the request from firebase auth and check exist -> get user info , if not -> create
    public function getLogin(Request $request)
    {

        $uid = $request->user['uid'];
        $email = $request->user['email'];

        // check exist -> get user info , if not -> create
        if (Customer::where('uid', $uid)->exists()) {
            $cus = Customer::where('uid', $uid)->first();
        } else {
            $cus = Customer::create([
                'uid' => $uid,
                'email' => $email
            ]);
        }
        //check matching
        return response()->json($cus);
    }
}

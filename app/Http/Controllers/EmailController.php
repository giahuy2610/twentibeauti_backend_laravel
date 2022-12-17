<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;
use App\Exceptions\InvalidOrderException;
use App\Exceptions;
use App\Models\Customer;
use App\Models\PromotionRegister;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $customer = $request->Customers;
        $title_email = 'CHƯƠNG TRÌNH KHUYẾN MÃI CỦA TWENTI';
        $data = [];
        foreach ($customer as $cus) {
            $data['email'][] = $cus['Email'];
        }
        $content = $request->Content;
        Mail::send('email.test', compact('data', 'content'), function ($message) use ($title_email, $data) {
            $message->to($data['email'])->subject($title_email);
            $message->from($data['email'], $title_email);
        });
        return response()->json('Done', 200);
    }
}

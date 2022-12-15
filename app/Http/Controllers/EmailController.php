<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;
use App\Exceptions\InvalidOrderException;
use App\Exceptions;

class EmailController extends Controller
{
    public function sendEmail() {
        $name='Dịu Ái';
        Mail::send('email.test',compact('name'), function($email) use($name){
            $email->subject('Đặt hàng thành công');
            $email->to('20520368@gm.uit.edu.vn', $name);
        });

    } 
}
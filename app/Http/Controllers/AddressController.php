<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;
use App\Exceptions\InvalidOrderException;
use App\Exceptions;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function read(int $id)
    {
        if($id==null) {
            return Address::orderBy('City','asc')->get();
        } else {
            return Address::find($id);
        }
    }

 
}
<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PromotionRegister;
use Illuminate\Http\Request;

class PromotionRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(PromotionRegister::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $temp = PromotionRegister::where('Email', $request->Email)->first();
        if ($temp == null) {
            $new = new PromotionRegister();
            $new->Email = $request->Email;
            $new->IDCus = Customer::where('Email', $request->Email)->first()->IDCus;
            $new->save();
            return response()->json('Completed', 200);
        }
        return response()->json('Is exist', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromotionRegister  $promotionRegister
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionRegister $promotionRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromotionRegister  $promotionRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(PromotionRegister $promotionRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromotionRegister  $promotionRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromotionRegister $promotionRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromotionRegister  $promotionRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionRegister $promotionRegister)
    {
        //
    }

    public function sendEmail(Request $request)
    {
        foreach ($request as $register) {
        }
    }
}

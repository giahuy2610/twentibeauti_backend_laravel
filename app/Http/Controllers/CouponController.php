<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Coupon::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAvailable()
    {
        return response()->json(Coupon::where('IsDeleted', '=', 0)->where('EndOn', '>=', Carbon::now())->where('StartOn', '<=', Carbon::now())->where('Stock', '>', 0)->get());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $coupon = new Coupon();
        $coupon->ValueDiscount = $request->ValueDiscount;
        $coupon->StartOn = $request->StartOn;
        $coupon->EndOn = $request->EndOn;
        $coupon->Description = $request->Description;
        $coupon->MinInvoiceValue = $request->MinInvoiceValue;
        $coupon->CodeCoupon = $request->CodeCoupon;
        $coupon->Quantity = $request->Quantity;
        $coupon->Stock = $request->Quantity;
        $coupon->IsMutualEvent = $request->IsMutualEvent;
        $coupon->save();
        return response()->json($coupon, 200);
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
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show($IDCoupon)
    {
        $data =  Coupon::find($IDCoupon);
        if ($data == null) return response()->json('Not found', 404);
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        //
    }

    public function checkAvailableOfCode($CodeCoupon)
    {
        $coupon = Coupon::where('CodeCoupon', $CodeCoupon)->first();
        if ($coupon == null) return response()->json('Mã giảm giá không tồn tại', 404);
        else if ($coupon->Stock == 0 || $coupon->EndOn < now() || $coupon->StartOn > now()) return response()->json('Not available now (out of stock, expired..etc...', 400);
        else return response()->json($coupon, 200);
    }
}

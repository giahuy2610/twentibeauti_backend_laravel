<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;
use App\Exceptions\InvalidOrderException;
use App\Exceptions;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = Product::find($request->IDProduct);
        if ($product == null) return response()->json('Product not found', 404);
        else if ($product->IsDeleted == true || $product->Stock == 0) return response()->json('Product not available to buy', 400);
        Cart::create(['idcus' => $request->IDCus, 'idproduct' => $request->IDProduct]);
        return $this->show($request);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = [];
        $cartitems = DB::table('Cart')->where('idcus', $request->IDCus)->get();
        foreach ($cartitems as $product) {
            $productDetail = Product::getProductDetailByID($product->IDProduct);
            $productDetail->Quantity = $product->Quantity;
            array_push($data, $productDetail);
        }
        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //check if the product exists -> update
        //else create new
        $cart = Cart::where('IDCus', $request->IDCus)->where('IDProduct', $request->IDProduct)->first();
        if ($cart != null) {
            if ($request->IsAdd == 1) {
                $cart->where('IDCus', $request->IDCus)->where('IDProduct', $request->IDProduct)->update(['Quantity' => $cart->Quantity + 1]);
                return $this->show($request);
            } else {
                if ($cart->Quantity - 1 > 0) {
                    $cart->where('IDCus', $request->IDCus)->where('IDProduct', $request->IDProduct)->update(['Quantity' => $cart->Quantity - 1]);
                    return $this->show($request);
                } else {
                    $cart->where('IDCus', $request->IDCus)->where('IDProduct', $request->IDProduct)->delete();
                    return $this->show($request);
                }
            }
        } else {
            if ($request->IsAdd)
                return $this->create($request);
            else return response()->json('Product not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Cart::where('IDCus', $request->IDCus)->where('IDProduct', $request->IDProduct)->delete();
        return $this->show($request);
    }
}

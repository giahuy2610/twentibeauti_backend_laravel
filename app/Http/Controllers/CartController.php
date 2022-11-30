<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Handler;
use App\Exceptions\InvalidOrderException;
use App\Exceptions;
use App\Models\ProductImage;
use Illuminate\Support\Collection;

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
        return response()->json(Cart::create(['idcus' => $request->idcus, 'idproduct' => $request->idproduct]));

        // $idcus = $request->idcus;
        // $idproduct = $request->idproduct;
        // $quantity = $request->quantity;
        // $cart = Cart::create([
        //     'idcus' => $idcus,
        //     'idproduct' => $idproduct,
        //     'quantity' => $quantity,
        // ]);
        // return $cart;

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //get all products in cart
        $cartitems = DB::table('Cart')->where('idcus', $request->IDCus)->get();

        //loop each -> add first image of it as thumbnail and the oldest retailPrice as customer's price
        foreach ($cartitems as $item) {
            $item->{'image'} = ProductImage::where('idproduct', $item->id)->order('createon', 'desc')->first();
        }

        return response()->json($cartitems);
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
        $cart = Cart::where(['idcus' => $request->idcus, 'idproduct' => $request->idproduct]);
        if ($cart->exists()) {
            if ($request->newquantity > 0) {
                return response()->json($cart->update(['quantity' => $request->newquantity]));
            } else {
                return $this->destroy($cart);
            }
        } else {
            return $this->create($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        return response()->json($cart->delete());
    }
}

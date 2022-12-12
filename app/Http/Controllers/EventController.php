<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RetailPrice;
use App\Models\Product;
use Illuminate\Http\Request;
use Throwable;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Event::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            //base event
            $event = new Event();
            $event->NameEvent = $request->NameEvent;
            $event->ValueDiscount = $request->ValueDiscount;
            $event->UnitsDiscount = $request->UnitsDiscount; //1: Bảng ánh xạ: 1: ‘giảm trực tiếp giá theo đơn vị ngàn đồng’ (ví dụ: sản phẩm đang 50.000 khi được giảm 10.000 sẽ còn 40.000) 2: ‘giảm theo %’ 3: ‘giảm đồng giá, giá trị của chương trình khuyến mãi là giá thực tế của sản phẩm’
            $event->StartOn = $request->StartOn;
            $event->EndOn = $request->EndOn;
            $event->save();

            //create product retail price belonged to this event
            foreach ($request->Products as $product) {
                $retailPrice = new RetailPrice();
                $retailPrice->IDProduct = $product['IDProduct'];
                $retailPrice->IDEvent = $event->IDEvent;
                $retailPrice->Price = Product::find($product['IDProduct'])->ListPrice;
                if ($request->UnitsDiscount == 1) {
                    $retailPrice->Price -= $request->ValueDiscount;
                } else if ($request->UnitsDiscount == 2) {
                    $retailPrice->Price -= $retailPrice->Price * $request->ValueDiscount;
                } else if ($request->UnitsDiscount == 3) {
                    $retailPrice->Price = $request->ValueDiscount;
                }
                $retailPrice->StartOn = $request->StartOn;
                $retailPrice->EndOn = $request->EndOn;
                $retailPrice->save();
            }


            response($event, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 404);
        }
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
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($IDEvent)
    {
        $data = Event::find($IDEvent);
        if ($data === null) return response()->json('Event not found', 404);
        else {
            //list of product, whose retail price is belonged to this event
            $data->Products = RetailPrice::where('IDEvent', $IDEvent)->get();
            return response()->json($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
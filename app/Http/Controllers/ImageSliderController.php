<?php

namespace App\Http\Controllers;

use App\Models\ImageSlider;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImageSliderController extends Controller
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
    public function create()
    {
        //
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
     * @param  \App\Models\ImageSlider  $imageSlider
     * @return \Illuminate\Http\Response
     */
    public function show(ImageSlider $imageSlider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImageSlider  $imageSlider
     * @return \Illuminate\Http\Response
     */
    public function edit(ImageSlider $imageSlider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImageSlider  $imageSlider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImageSlider $imageSlider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageSlider  $imageSlider
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageSlider $imageSlider)
    {
        //
    } 

    public function getAvailable()
    {
        $data = ImageSlider::where('IsDeleted', '=', 0)->where('EndOn', '>=', Carbon::now())->where('StartOn', '<=', Carbon::now())->get();
        return response()->json($data, 200);
    }
}

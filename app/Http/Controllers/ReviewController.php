<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function show(int $id)
    {
        $review = [];
        // Lấy id
        $review = Review::find($id);
        // Lấy pathImage trong ProductImage
        

        return response()->json($review);

    }
    public function showProduct(int $id)
    {
        $review = [];
        // Lấy id
        $review = Review::where('idproduct', $id)->get();
        // Lấy pathImage trong ProductImage
        

        return response()->json($review);

    }

}

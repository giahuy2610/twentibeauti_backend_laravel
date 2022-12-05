<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the image of specified product
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(int $IDProduct)
    {
        return (ProductImage::where('IDProduct', $IDProduct)->get());
    }
}

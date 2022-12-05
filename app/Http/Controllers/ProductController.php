<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Review;
use App\Models\RetailPrice;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $products = Product::get();
        foreach ($products as $product) {
            $productDetail = Product::getProductDetailByID($product->IDProduct);
            array_push($data, $productDetail);
        }
        return response()->json($data, 200);
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $image = [];
        $review = [];
        // Lấy id 
        $product = Product::find($id);
        // Lấy pathImage trong ProductImage
        $image_items = ProductImage::where('idproduct', $id)->get();
        foreach ($image_items as $item) {
            array_push($image, $item['Path']);
        }
        $product->Images =   $image;

        // Lấy ra brand 
        $brand = Brand::where('idbrand', $product->IDBrand)->first();
        if ($brand->IsDeleted == 0) {
            $brand = $brand->NameBrand;
        }
        $product->Brand =   $brand;

        return response()->json($product);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function getProduct(int $IDProduct)
    {
        //get everything belong to this product
        $product = Product::getProductDetailByID($IDProduct);
        if ($product == null) return response()->json('Product is not found', 404);
        else if ($product->IsDeleted == true) return response()->json('Product is deleted', 400);
        return response()->json($product, 200);
    }
}

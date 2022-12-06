<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Review;
use App\Models\RetailPrice;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Throwable;

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
    public function create(Request $request)
    {
        try {
            $product = new Product();
            $product->NameProduct = $request->NameProduct;
            $product->IDBrand = $request->IDBrand;
            $product->Description = $request->Description;
            $product->Stock = $request->Stock;
            $product->Mass = $request->Mass;
            $product->UnitsOfMass = $request->UnitsOfMass;
            $product->Units = $request->Units;
            $product->ApplyTaxes = $request->ApplyTaxes;
            $product->StatusSale = $request->StatusSale;
            $product->ListPrice = $request->ListPrice;
            $product->IDType = $request->IDType;
            $product->IDTag = $request->IDTag;
            $product->save();
            return response()->json($product, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 400);
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
    public function update(Request $request)
    {
        try {
            $product = Product::find($request->IDProduct);
            $product->NameProduct = $request->NameProduct;
            $product->IDBrand = $request->IDBrand;
            $product->Description = $request->Description;
            $product->Stock = $request->Stock;
            $product->Mass = $request->Mass;
            $product->UnitsOfMass = $request->UnitsOfMass;
            $product->Units = $request->Units;
            $product->ApplyTaxes = $request->ApplyTaxes;
            $product->StatusSale = $request->StatusSale;
            $product->ListPrice = $request->ListPrice;
            $product->IDType = $request->IDType;
            $product->IDTag = $request->IDTag;
            $product->save();
            return response()->json($product, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
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

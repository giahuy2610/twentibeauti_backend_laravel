<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use App\Models\CollectionProduct;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use function Pest\Laravel\get;

class ProductCollectionController extends Controller
{

    // public function show(int $id)
    // {
    //     $product=[];
    //     $product_collection = CollectionProduct::where('IDCollection',$id)->get();
    //     $collection = Collection::where('IDCollection',$id)->get();
    //         foreach ($product_collection as $items) { 
    //                 $x = Product::select('NameProduct','IDProduct','ListPrice')->find($items['IDProduct']);
    //                 $x->ImagePath = ProductImage::where('IDProduct',$items['IDProduct'])->first()['Path'];
    //                 array_push($product, $x,$collection); 
    //         }
    //     return response()->json($product);
    // }
    public function show(int $id)
    {
        $collections = Collection::where('IDCollection', $id)->get();
        $productlist = array();
        foreach ($collections as $col) {
            $product = DB::table('Product')
                ->join('CollectionProduct', 'Product.IDProduct', '=', 'CollectionProduct.IDProduct')
                ->where('CollectionProduct.IDCollection', '=', $id)
                ->get();

            $object =
                [
                    'IDCollection' => $col->IDCollection,
                    'NameCollection' => $col->NameCollection,
                    'Description' => $col->Description,
                    'LogoImagePath' => $col->LogoImagePath,
                    'WallPaperPath' => $col->WallPaperPath,
                    'Product' => $product
                ];
            array_push($productlist, $object);
        }
        return response()->json($productlist);
    }

    public function create(Request $req)
    {
        try {
            $collection = new Collection();
            $collection->NameCollection = $req->input('NameCollection');
            $collection->RoutePath = $req->input('RoutePath');
            $collection->Description = $req->input('Description');
            $collection->LogoImagePath = $req->input('LogoImagePath');
            $collection->WallPaperPath = $req->input('WallPaperPath');
            $collection->StartOn = $req->input('StartOn');
            $collection->EndOn = $req->input('EndOn');
            $collection->CoverImagePath = $req->input('CoverImagePath');
            $collection->save();
            return $collection;
        } catch (Throwable $e) {
            return $e->getMessage();
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
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        try {
            $collection = Collection::find($id);
            if ($collection) {
                $collection->NameCollection = $req->input('NameCollection');
                $collection->RoutePath = $req->input('RoutePath');
                $collection->Description = $req->input('Description');
                $collection->LogoImagePath = $req->input('LogoImagePath');
                $collection->WallPaperPath = $req->input('WallPaperPath');
                $collection->StartOn = $req->input('StartOn');
                $collection->EndOn = $req->input('EndOn');
                $collection->CoverImagePath = $req->input('CoverImagePath');
                $collection->save();
                return $collection;
            } else {
                return 'Data not found';
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $collection = Collection::find($id);
            if ($collection) {
                $collection->delete();
                return $collection;
            } else {
                return 'Collection not found';
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }



}

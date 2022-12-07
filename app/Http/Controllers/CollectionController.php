<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CollectionController extends Controller
{
    public function show($id = null)
    {
        if ($id == null) {
            return Collection::orderBy('IDCollection', 'asc')->get();
        } else {
            $collection =  Collection::getCollectionDetailByID($id);
            if ($collection == null) return response()->json('Collection is not found', 404);
            else if ($collection->IsDeleted == true || $collection->StartOn > now() || $collection->EndOn < now()) return response()->json('Collection is not available', 400);
            return response()->json($collection, 200);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        try {
            $collection = new Collection();
            $collection->NameCollection = $req->input('NameCollection');
            $collection->Description = $req->input('Description') ?? '';
            $collection->LogoImagePath = $req->input('LogoImagePath') ?? '';
            $collection->WallPaperPath = $req->input('WallPaperPath') ?? '';
            $collection->StartOn = $req->input('StartOn') ?? now();
            $collection->EndOn = $req->input('EndOn') ?? now();
            $collection->CoverImagePath = $req->input('CoverImagePath') ?? "";
            $collection->save();
            // return $collection;

            //insert products into collection
            foreach ($req->Products as $product) {
                $collectionProduct = DB::table('CollectionProduct')->insert(
                    ['IDCollection' =>  $collection->IDCollection, 'IDProduct' => $product]
                );
            }
            $collection->Products = DB::table('CollectionProduct')->where('IDCollection', $collection->IDCollection)->get();
            return  $collection;
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

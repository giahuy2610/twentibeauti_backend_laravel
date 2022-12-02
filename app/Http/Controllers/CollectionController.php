<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function show($id = null)
    // {
    //     if ($id==null) {
    //         return Collection::orderBy('NameCollection','asc')->get();
    //     } else {
    //         return Collection::find($id);
    //     }
    //     return response()->json();
    // }
    public function show($id=null)
    {
        if($id==null) {
            return Collection::orderBy('NameCollection','asc')->get();
        } else {
            return Collection::find($id);
        }
    }
    // public function read(int $id) {
    //     $collection=DB::table('Collection')->get();
    //     foreach( $collection as )
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        try {
            $collection = new Collection();
            $collection->NameCollection= $req->input('NameCollection');
            $collection->RoutePath= $req->input('RoutePath');
            $collection->Description= $req->input('Description');
            $collection->LogoImagePath= $req->input('LogoImagePath');
            $collection->WallPaperPath= $req->input('WallPaperPath');
            $collection->StartOn= $req->input('StartOn');
            $collection->EndOn= $req->input('EndOn');
            $collection->CoverImagePath= $req->input('CoverImagePath');
            $collection->save();
            return $collection;
        } catch(Throwable $e) {
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
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}

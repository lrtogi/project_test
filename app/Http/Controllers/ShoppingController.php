<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shopping;

class ShoppingController extends Controller
{

    public function getAll(){
        $shopping = Shopping::all();
        return response()->json([
            'data' => $shopping
        ]);
    }

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
        $shopping = new Shopping();
        $shopping->CreatedDate = $request->shopping['createddate'];
        $shopping->Name = $request->shopping['name'];
        $shopping->save();

        return response()->json([
            'data' => $shopping
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shopping = Shopping::find($id);
        return response()->json([
            'data' => $shopping
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shopping = Shopping::find($id);
        $shopping->CreatedDate = $request->shopping['createddate'];
        $shopping->Name = $request->shopping['name'];
        $shopping->save();

        return response()->json([$shopping]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shopping = Shopping::where('id',$id)->delete();

        return response()->json([
            'message' => 'Success delete data'
        ]);
    }
}

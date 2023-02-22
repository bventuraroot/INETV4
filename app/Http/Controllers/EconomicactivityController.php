<?php

namespace App\Http\Controllers;

use App\Models\Economicactivity;
use Illuminate\Http\Request;

class EconomicactivityController extends Controller
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
    public function geteconomicactivity($pais)
    {
        $Economicactivity= Economicactivity::where('country_id', base64_decode($pais))->get();
        return response()->json($Economicactivity);
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
     * @param  \App\Models\Economicactivity  $Economicactivity
     * @return \Illuminate\Http\Response
     */
    public function show(Economicactivity $Economicactivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Economicactivity  $Economicactivity
     * @return \Illuminate\Http\Response
     */
    public function edit(Economicactivity $Economicactivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Economicactivity  $Economicactivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Economicactivity $Economicactivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Economicactivity  $Economicactivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Economicactivity $Economicactivity)
    {
        //
    }
}

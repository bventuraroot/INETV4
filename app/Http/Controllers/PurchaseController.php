<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase = Purchase::join("typedocuments", "typedocuments.id", "=", "purchases.document_id")
        ->join("providers", "providers.id", "=", "purchases.provider_id")
        ->join("companies", "companies.id", "=", "purchases.company_id")
        ->select("typedocuments.description AS namedoc",
            "purchases.number",
            "purchases.datedoc",
            "purchases.exenta",
            "purchases.gravada",
            "purchases.iva",
            "purchases.otros",
            "purchases.total",
            "providers.razonsocial AS name_provider")
        ->get();
        return view('purchases.index', array(
            "purchases" => $purchase
        ));
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
        $purchase = new Purchase();
        $purchase->document_id = $request->document;
        $purchase->provider_id = $request->provider;
        $purchase->company_id = $request->company;
        $purchase->number = $request->number;
        $daterequest = strtotime($request->datedoc);
        $new_date = date('Y-m-d', $daterequest);
        $purchase->datedoc = $new_date;
        $purchase->exenta = $request->exenta;
        $purchase->gravada = $request->gravada;
        $purchase->iva = $request->iva;
        $purchase->contrns = $request->contrans;
        $purchase->fovial = $request->fovial;
        $purchase->iretenido = $request->iretenido;
        $purchase->otros = $request->others;
        $purchase->total = $request->total;
        $purchase->fingreso = date('Y-m-d');
        $purchase->periodo = $request->period;
        $purchase->user_id = $request->iduser;
        $purchase->save();
        return redirect()->route('purchase.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Salesdetail;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::all();
        return view('sales.index', $sales);
    }

    public function savefactemp($idsale, $clientid, $productid, $cantidad, $price, $pricenosujeta, $priceexenta, $pricegravada, $ivarete13, $ivarete, $acuenta, $fpago){
        $sale = Sale::find($idsale);
        $sale->client_id = $clientid;
        $sale->acuenta = $acuenta;
        $sale->waytopay = $fpago;
        $sale->save();

        $saledetails = new Salesdetail();
        $saledetails->sale_id = $idsale;
        $saledetails->product_id = $productid;
        $saledetails->amountp = $cantidad;
        $saledetails->priceunit = $price;
        $saledetails->pricesale = $pricegravada;
        $saledetails->nosujeta = $pricenosujeta;
        $saledetails->exempt = $priceexenta;
        $saledetails->detained13 = $ivarete13;
        $saledetails->detained = $ivarete;
        $saledetails->save();
        return response()->json(array(
            "res" => "1",
            "idsaledetail" => $saledetails['id']
        ));

    }

    public function newcorrsale($idempresa, $iduser, $iddoc){
        $corr = new Sale();
        $corr->company_id = $idempresa;
        $corr->typedocument_id = $iddoc;
        $corr->user_id = $iduser;
        $corr->date = date('Y-m-d');
        $corr->state = 1;
        $corr->typesale = 2;
        $corr->save();
        return response()->json($corr['id']);
    }

    public function destroysaledetail($idsaledetail){
        $saledetails = Salesdetail::find(base64_decode($idsaledetail));
        $saledetails->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.create');
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
     * @param  \App\Models\Sale  $Sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $Sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $Sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $Sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $Sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $Sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $Sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $Sale)
    {
        //
    }
}

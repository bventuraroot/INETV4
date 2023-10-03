<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credit = Sale::join('companies', 'companies.id', '=', 'sales.company_id')
    ->join('clients', 'clients.id', '=', 'sales.client_id')
    ->leftJoin('credits', 'credits.sale_id', '=', 'sales.id')
    ->where('waytopay', 2)
    ->select(
        'sales.id as idsale',
        'sales.date',
        'clients.id AS client_id',
        'clients.firstname AS client_firstname',
        'clients.secondname AS client_secondname',
        'clients.tipoContribuyente AS client_contribuyente',
        'sales.id AS corr',
        'clients.tpersona',
        'clients.name_contribuyente',
        'companies.name as NameCompany',
        'sales.totalamount',
        DB::raw('SUM(credits.amountpay) as current'),
        DB::raw('(CASE WHEN sales.state_credit = 0 THEN "MORA" ELSE "PAGADO" END) AS state_credit')
    )
    ->groupBy([
        'sales.date',
        'clients.id',
        'clients.firstname',
        'clients.secondname',
        'clients.tipoContribuyente',
        'corr',
        'clients.tpersona',
        'clients.name_contribuyente',
        'NameCompany',
        'sales.totalamount',
        'state_credit'
    ])
    ->get();
        //dd($credit);
        return view('credits.index', array(
            "credits" => $credit
        ));
    }

    public function getinfocredit($idcredit) {
        $findcredit = Credit::where('credits.sale_id', '=', base64_decode($idcredit))->latest()->first();
        $findsale = Sale::find(base64_decode($idcredit));

        if ($findcredit) {
            // La consulta de crédito trajo datos
            $saldo = $findcredit->current; // Obtén el primer resultado
        } else {
            // La consulta de crédito no trajo datos
            $saldo = $findsale->totalamount;
        }

        return response()->json($saldo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addpay(Request $request){

        $initialamount = Sale::find($request->idsale);
        $currentamount = Credit::where('credits.sale_id', '=', $request->idsale)->latest()->first();

        if($currentamount){
            $newamount = ($currentamount->current)-($request->amountpay);
        }else{
            $newamount = ($initialamount->totalamount)-$request->amountpay;
        }

        $addcredit = new Credit();
        $addcredit->sale_id = $request->idsale;
        $addcredit->date_pay = date('Y-m-d H:i:s');
        $addcredit->current = $newamount;
        $addcredit->initial = $initialamount->totalamount;
        $addcredit->amountpay = $request->amountpay;
        $addcredit->save();

        if($newamount==0){
        $updatesalecredit = Sale::find($request->idsale);
        $updatesalecredit->state_credit = 1;
        $updatesalecredit->save();
        }



        return redirect()->route('credit.index');
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
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Credit $credit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credit $credit)
    {
        //
    }
}

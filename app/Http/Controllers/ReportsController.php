<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Purchase;
use App\Models\Report;
use App\Models\Sale;
use App\Models\Salesdetail;
use Illuminate\Http\Request;

class ReportsController extends Controller
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

    public function sales(){
        return view('reports.sales');
    }

    public function purchases(){
        return view('reports.purchases');
    }

    public function reportsales($company, $year, $period){
        $sales_r = Sale::join('typedocuments', 'typedocuments.id', '=', 'sales.typedocument_id')
        ->join('clients', 'clients.id', '=', 'sales.client_id')
        ->join('companies', 'companies.id', '=', 'sales.company_id')
        ->select('sales.*',
        'typedocuments.description AS document_name',
        'clients.firstname',
        'clients.firstlastname',
        'clients.comercial_name',
        'clients.tpersona',
        'companies.name AS company_name')
        ->where("companies.id", "=", $company)
        ->whereRaw('YEAR(sales.date) = ?', [$year])
        ->whereRaw('MONTH(sales.date) = ?', [$period])
        ->get();
        $sales_r1['data'] = $sales_r;
        return response()->json($sales_r1);
    }
    public function reportpurchases($company, $year, $period){
        $purchases_r = Purchase::join('typedocuments', 'typedocuments.id', '=', 'purchases.document_id')
        ->join('providers', 'providers.id', '=', 'purchases.provider_id')
        ->join('companies', 'companies.id', '=', 'purchases.company_id')
        ->select('purchases.*',
        'typedocuments.description AS document_name',
        'providers.razonsocial AS nameprovider',
        'companies.name AS company_name')
        ->where("companies.id", "=", $company)
        ->whereRaw('YEAR(purchases.datedoc) = ?', [$year])
        ->whereRaw('MONTH(purchases.datedoc) = ?', [$period])
        ->get();
        $purchases_r1['data'] = $purchases_r;
        return response()->json($purchases_r1);
    }

    public function contribuyentes(){
            return view('reports.contribuyentes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function contribusearch(Request $request){
        $Company = Company::find($request['company']);
        $sales = Sale::leftjoin('clients', 'sales.client_id', '=', 'clients.id')
        ->select('*','sales.id AS correlativo',
        'clients.ncr AS ncrC',)
        ->selectRaw("DATE_FORMAT(sales.date, '%d/%m/%Y') AS dateF ")
        ->selectRaw("(SELECT SUM(sde.exempt) FROM salesdetails AS sde WHERE sde.sale_id=sales.id) AS exenta")
        ->selectRaw("(SELECT SUM(sdg.pricesale) FROM salesdetails AS sdg WHERE sdg.sale_id=sales.id) AS gravada")
        ->selectRaw("(SELECT SUM(sdn.nosujeta) FROM salesdetails AS sdn WHERE sdn.sale_id=sales.id) AS nosujeta")
        ->selectRaw("(SELECT SUM(sdi.detained13) FROM salesdetails AS sdi WHERE sdi.sale_id=sales.id) AS iva")
        ->where('sales.typedocument_id', "=", "3")
        ->whereRaw('YEAR(sales.date)=?', $request['year'])
        ->whereRaw('MONTH(sales.date)=?', $request['period'])
        ->WhereRaw('DAY(sales.date) BETWEEN "01" AND "31"')
        ->where('sales.company_id', '=', $request['company'])
        ->orderBy('sales.id')
        ->get();
        return view('reports.contribuyentes', array(
            "heading" => $Company,
            "yearB" => $request['year'],
            "period" => $request['period'],
            "sales" => $sales
        ));
    }

    public function directas(){

        return view('reports.directas');

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
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}

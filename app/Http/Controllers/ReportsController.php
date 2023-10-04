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
    public function reportyear(){
            return view('reports.reportyear');
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function yearsearch(Request $request){
        $Company = Company::find($request['company']);
        $sales = Sale::join('salesdetails','salesdetails.sale_id', '=','sales.id')
        ->join('iva','iva.company_id', '=', 'sales.company_id')
        ->selectRaw("SUM((CASE sales.typedocument_id WHEN 3 THEN
        CASE WHEN salesdetails.exempt<>'0' THEN ROUND((salesdetails.exempt),2) WHEN salesdetails.exempt='0' THEN ROUND(((salesdetails.pricesale)/1.13),2) END
        WHEN 6 THEN
        CASE WHEN salesdetails.exempt<>'0' THEN ROUND((salesdetails.exempt),2) WHEN salesdetails.exempt='0' THEN  ROUND((salesdetails.pricesale)/1.13,2) END END)) as GRAVADAS")
        ->selectRaw("SUM((CASE sales.typedocument_id WHEN 3 THEN
        CASE WHEN salesdetails.exempt<>'0' THEN 0 WHEN salesdetails.exempt='0' THEN ROUND(salesdetails.pricesale-salesdetails.pricesale/1.13,2) END
        WHEN 6 THEN CASE WHEN salesdetails.exempt<>'0' THEN 0 WHEN salesdetails.exempt='0' THEN ROUND((salesdetails.pricesale-(salesdetails.pricesale)/1.13),2) END END)) DEBITO")
        ->selectRaw("SUM((CASE sales.typedocument_id WHEN 3 THEN
        CASE WHEN salesdetails.exempt<>'0' THEN ROUND(salesdetails.exempt,2) WHEN salesdetails.exempt='0' THEN ROUND(salesdetails.pricesale/1.13+(salesdetails.pricesale-(salesdetails.pricesale)/1.13),2) END
         WHEN 6 THEN
         CASE WHEN salesdetails.exempt<>'0' THEN ROUND(salesdetails.exempt,2) WHEN salesdetails.exempt='0' THEN ROUND(salesdetails.pricesale/1.13+(salesdetails.pricesale-(salesdetails.pricesale)/1.13),2) END END)) TOTALV")
        ->selectRaw("YEAR(sales.date) as yearsale")
        ->selectRaw("MONTH(sales.date) as monthsale")
        ->where('sales.company_id', '=', $request['company'])
        ->where('sales.state', '<>', 0)
        ->groupBy([
            'yearsale',
            'monthsale'
        ])
        ->orderBy('monthsale', 'asc')
         ->get();
         $purchases = Purchase::selectRaw("YEAR(purchases.date) as yearpuchase")
         ->selectRaw("SUM(purchases.gravada+purchases.exenta) INTERNASPU")
         ->selectRaw("SUM(purchases.iva) CREDITOPU")
         ->selectRaw("SUM(purchases.gravada+purchases.iva+purchases.exenta) TOTALC")
         ->selectRaw("MONTH(purchases.date) as monthpurchase")
         ->groupBy([
            'yearpuchase',
            'monthpurchase'
        ])
        ->orderBy('monthpurchase', 'asc')
         ->get();
        return view('reports.reportyear', array(
            "heading" => $Company,
            "yearB" => $request['year'],
            "purchases" => $purchases,
            "sales" => $sales
        ));
    }

    public function consumidor(){
        return view('reports.consumidor');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function consumidorsearch(Request $request){
        $Company = Company::find($request['company']);
        $sales = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
        ->select('*','sales.id AS correlativo',
        'clients.ncr AS ncrC',)
        ->selectRaw("DATE_FORMAT(sales.date, '%d/%m/%Y') AS dateF ")
        ->selectRaw("(SELECT SUM(sde.exempt) FROM salesdetails AS sde WHERE sde.sale_id=sales.id) AS exenta")
        ->selectRaw("(SELECT SUM(sdg.pricesale) FROM salesdetails AS sdg WHERE sdg.sale_id=sales.id) AS gravada")
        ->selectRaw("(SELECT SUM(sdn.nosujeta) FROM salesdetails AS sdn WHERE sdn.sale_id=sales.id) AS nosujeta")
        ->selectRaw("(SELECT SUM(sdi.detained13) FROM salesdetails AS sdi WHERE sdi.sale_id=sales.id) AS iva")
        ->where('sales.typedocument_id', "=", "6")
        ->whereRaw('(clients.tpersona = "N" OR clients.tpersona = "J")' )
        ->whereRaw('YEAR(sales.date)=?', $request['year'])
        ->whereRaw('MONTH(sales.date)=?', $request['period'])
        ->WhereRaw('DAY(sales.date) BETWEEN "01" AND "31"')
        ->where('sales.company_id', '=', $request['company'])
        ->orderBy('sales.id')
        ->get();
        return view('reports.consumidor', array(
            "heading" => $Company,
            "yearB" => $request['year'],
            "period" => $request['period'],
            "sales" => $sales
        ));
    }

    public function bookpurchases(){
        return view('reports.bookpurchases');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function comprassearch(Request $request){
        $Company = Company::find($request['company']);

        $purchases = Purchase::join('providers AS pro', 'pro.id', '=', 'purchases.provider_id')
        ->select('*')
        ->selectRaw("DATE_FORMAT(purchases.date, '%d/%m/%Y') AS dateF")
        ->whereRaw('YEAR(purchases.date)=?', $request['year'])
        ->whereRaw('MONTH(purchases.date)=?', $request['period'])
        ->WhereRaw('DAY(purchases.date) BETWEEN "01" AND "31"')
        ->where('purchases.company_id', '=', $request['company'])
        ->orderByRaw('MONTH(purchases.date)')
        ->orderBy('purchases.date')
        ->get();
        return view('reports.bookpurchases', array(
            "heading" => $Company,
            "yearB" => $request['year'],
            "period" => $request['period'],
            "purchases" => $purchases
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

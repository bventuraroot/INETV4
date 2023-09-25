<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Company;
use App\Models\Dte;
use App\Models\Sale;
use App\Models\Config;
use App\Models\Salesdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::join('typedocuments', 'typedocuments.id', '=', 'sales.typedocument_id')
            ->join('clients', 'clients.id', '=', 'sales.client_id')
            ->join('companies', 'companies.id', '=', 'sales.company_id')
            ->select(
                'sales.*',
                'typedocuments.description AS document_name',
                'clients.firstname',
                'clients.firstlastname',
                'clients.name_contribuyente as nameClient',
                'clients.tpersona',
                'companies.name AS company_name'
            )
            ->get();
        return view('sales.index', array(
            "sales" => $sales
        ));
    }

    public function impdoc($corr)
    {
        return view('sales.impdoc', array("corr" => $corr));
    }

    public function savefactemp($idsale, $clientid, $productid, $cantidad, $price, $pricenosujeta, $priceexenta, $pricegravada, $ivarete13, $ivarete, $acuenta, $fpago)
    {
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

    public function newcorrsale($idempresa, $iduser, $iddoc)
    {
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

    public function destroysaledetail($idsaledetail)
    {
        $saledetails = Salesdetail::find(base64_decode($idsaledetail));
        $saledetails->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }

    public function getdatadocbycorr($corr)
    {
        $saledetails = Sale::join('companies', 'companies.id', '=', 'sales.company_id')
            ->join('iva', 'iva.company_id', '=', 'companies.id')
            ->leftJoin('clients', 'clients.id', '=', 'sales.client_id')
            ->select(
                'sales.*',
                'companies.*',
                'clients.id AS client_id',
                'clients.firstname AS client_firstname',
                'clients.secondname AS client_secondname',
                'clients.tipoContribuyente AS client_contribuyente',
                'iva.valor AS iva',
                'iva.valor_entre AS iva_entre'
            )
            ->where('sales.id', '=', base64_decode($corr))
            ->get();
        return response()->json($saledetails);
    }

    public function getdatadocbycorr2($corr)
    {
        $saledetails = Sale::join('companies', 'companies.id', '=', 'sales.company_id')
            ->join('addresses', 'addresses.id', '=', 'companies.address_id')
            ->join('countries', 'countries.id', '=', 'addresses.country_id')
            ->join('departments', 'departments.id', '=', 'addresses.department_id')
            ->join('municipalities', 'municipalities.id', '=', 'addresses.municipality_id')
            ->join('phones', 'phones.id', '=', 'companies.phone_id')
            ->join('clients', 'clients.id', '=', 'sales.client_id')
            ->join('typedocuments', 'typedocuments.id', '=', 'sales.typedocument_id')
            ->select(
                'sales.*',
                'companies.*',
                'companies.ncr AS NCR',
                'companies.nit AS NIT',
                'countries.name AS country_name',
                'departments.name AS department_name',
                'municipalities.name AS municipality_name',
                'addresses.reference AS address',
                'phones.*',
                'typedocuments.description AS document_name',
                'clients.id AS client_id',
                'clients.firstname AS client_firstname',
                'clients.secondname AS client_secondname',
                'clients.tipoContribuyente AS client_contribuyente',
                'sales.id AS corr',
                'clients.tpersona',
                'clients.name_contribuyente'
            )
            ->where('sales.id', '=', base64_decode($corr))
            ->get();
        return response()->json($saledetails);
    }

    public function createdocument($corr, $amount)
    {
        $amount = substr($amount, 1);
        $salesave = Sale::find(base64_decode($corr));
        $salesave->totalamount = $amount;
        $salesave->typesale = 1;
        $salesave->save();

        $idempresa = $salesave->company_id;
        $company = Company::find($idempresa);

        $config = Config::where('company_id', $idempresa)->first();

        $idambiente = Ambiente::where('cod', $config->ambiente)->first();
        $tipoDte = 0;
        $tipoModelo = 0;
        $tipoTransmision = 0;
        $tipoContingencia = 0;
        $dtecreate = new Dte();
        $dtecreate->versionJson = $config->versionJson;
        $dtecreate->ambiente_id = $idambiente;
        $dtecreate->tipoDte = $tipoDte;
        $dtecreate->tipoModelo = $tipoModelo;
        $dtecreate->tipoTransmision = $tipoTransmision;
        $dtecreate->tipoContingencia = $tipoContingencia;
        $dtecreate->idContingencia = null;
        $dtecreate->nameTable = 'Sales';
        $dtecreate->company_id = $idempresa;
        $dtecreate->company_name = $company->name;
        $dtecreate->id_doc = $corr;
        $dtecreate->codTransaction = $corr;
        $dtecreate->desTransaction = $corr;
        $dtecreate->type_document = $corr;
        $dtecreate->id_doc_Ref1 = null;
        $dtecreate->id_doc_Ref2 = null;
        $dtecreate->type_invalidacion = null;
        $dtecreate->codEstado = '01';
        $dtecreate->Estado = $corr;
        $dtecreate->codigoGeneracion = null;
        $dtecreate->selloRecibido = null;
        $dtecreate->fhRecibido = null;
        $dtecreate->estadoHacienda = null;
        $dtecreate->nSends = null;
        $dtecreate->codeMessage = null;
        $dtecreate->claMessage = null;
        $dtecreate->descriptionMessage = null;
        $dtecreate->detailsMessage = null;
        $dtecreate->save();

        return response()->json(array(
            "res" => "1"
        ));
    }

    public function getdetailsdoc($corr)
    {
        $saledetails = Salesdetail::leftJoin('products', 'products.id', '=', 'salesdetails.product_id')
            ->select(
                'salesdetails.*',
                DB::raw('CONCAT(products.name, " - ", products.description) as product_name')
            )
            ->where('sale_id', '=', base64_decode($corr))
            ->get();
        return response()->json($saledetails);
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
    public function destroy($id)
    {
        //dd($id);
        $anular = Sale::find(base64_decode($id));
        $anular->state = 0;
        $anular->typesale = 0;
        $anular->save();
        return response()->json(array(
            "res" => "1"
        ));
    }
}

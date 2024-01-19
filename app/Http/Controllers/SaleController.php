<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Client;
use App\Models\Company;
use App\Models\Dte;
use App\Models\Sale;
use App\Models\Config;
use App\Models\Salesdetail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Session;

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
    ->leftjoin('dte', 'dte.sale_id', '=', 'sales.id')
    ->select(
        'sales.*',
        'typedocuments.description AS document_name',
        'clients.firstname',
        'clients.firstlastname',
        'clients.name_contribuyente as nameClient',
        'clients.tpersona',
        'companies.name AS company_name',
        'dte.tipoDte',
        'dte.estadoHacienda',
        'dte.id_doc',
        \DB::raw('(SELECT dee.descriptionMessage FROM dte dee WHERE dee.id_doc_Ref2=sales.id) AS relatedSale')
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

    public function savefactemp($idsale, $clientid, $productid, $cantidad, $price, $pricenosujeta, $priceexenta, $pricegravada, $ivarete13, $renta, $ivarete, $acuenta, $fpago)
    {
        $sale = Sale::find($idsale);
        $sale->client_id = $clientid;
        $sale->acuenta = $acuenta;
        $sale->waytopay = $fpago;
        $sale->save();
        //$iva_calculado = round($price/1.13,2);
        //$preciogravado = round($iva_calculado*$cantidad,2);
        //$ivafac = round($pricegravada-($pricegravada/1.13),2);
        $ivafac = round($pricegravada-($pricegravada/1.13),2);
        $pricegravadafac = round($pricegravada/1.13,3);
        if($pricegravada!= "0.00"){
            $priceunitariofac = round($pricegravadafac/$cantidad,3);
        }else{
            $priceunitariofac = round($price,3);
        }
        if($sale->typedocument_id=='8'){
        $priceunitariofac = $price;
        $pricegravadafac = $pricegravada;
        }
        $ivarete13 = round($pricegravada*0.13,2);
        $ivafac = round($pricegravada-($pricegravada/1.13),2);
        if($sale->typedocument_id=='8'){
            $ivafac = 0.00;
            }
        $saledetails = new Salesdetail();
        $saledetails->sale_id = $idsale;
        $saledetails->product_id = $productid;
        $saledetails->amountp = $cantidad;
        //$saledetails->priceunit = ($sale->typedocument_id==6) ? round($iva_calculado,2) : $price;
        $saledetails->priceunit = ($sale->typedocument_id=='6' || $sale->typedocument_id=='8') ? round($priceunitariofac,2) : $price;
        //$saledetails->pricesale = ($sale->typedocument_id==6) ? round($preciogravado,2) : $pricegravada;
        $saledetails->pricesale = ($sale->typedocument_id=='6' || $sale->typedocument_id=='8') ? round($pricegravadafac,2) : $pricegravada;
        $saledetails->nosujeta = $pricenosujeta;
        $saledetails->exempt = $priceexenta;
        $saledetails->detained13 = ($sale->typedocument_id=='6' || $sale->typedocument_id=='8') ? round($ivafac,2) : $ivarete13;
        $saledetails->detained = $ivarete;
        $saledetails->renta = ($sale->typedocument_id != '8' ) ? round(0.00,2) : round($renta*$cantidad,2);
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
        //dd($amount);
        $salesave->save();

        $idempresa = $salesave->company_id;
        $createdby = $salesave->user_id;
        //$company = Company::find($idempresa);
        //$config = Config::where('company_id', $idempresa)->first();
        //detalle factura
        $detailsbd = Salesdetail::where('sale_id','=', base64_decode($corr))
        ->select(
            DB::raw('SUM(nosujeta) nosujeta,
            SUM(exempt) exentas,
            SUM(pricesale) gravadas,
            SUM(nosujeta+exempt+pricesale) subtotalventas,
            0 descnosujeta,
            0 descexenta,
            0 desgravada,
            0 porcedesc,
            0 totaldesc,
            NULL tributos,
            SUM(nosujeta+exempt+pricesale) subtotal,
            SUM(detained) ivarete,
            0 ivarete,
            SUM(renta) rentarete,
            NULL pagos,
            SUM(detained13) iva')
        )
        ->get();
        //detalle de montos de la factura
        $totalPagar = ($detailsbd[0]->nosujeta+$detailsbd[0]->exentas+$detailsbd[0]->gravadas+$detailsbd[0]->iva-($detailsbd[0]->rentarete+$detailsbd[0]->ivarete));
        $totales = [
            "totalNoSuj" => (float)$detailsbd[0]->nosujeta,
            "totalExenta" => (float)$detailsbd[0]->exentas,
            "totalGravada" => (float)$detailsbd[0]->gravadas,
            "subTotalVentas" => round((float)($detailsbd[0]->subtotalventas),2),
            "descuNoSuj" => $detailsbd[0]->descnosujeta,
            "descuExenta" => $detailsbd[0]->descexenta,
            "descuGravada" => $detailsbd[0]->desgravada,
            "porcentajeDescuento" => 0.00,
            "totalDescu" => $detailsbd[0]->totaldesc,
            "tributos" =>  null,
            "subTotal" => round((float)($detailsbd[0]->subtotal),2),
            "ivaPerci1" => 0.00,
            "ivaRete1" => 0.00,
            "reteRenta" => round((float)$detailsbd[0]->rentarete,2),
            "montoTotalOperacion" => round((float)($detailsbd[0]->subtotal),2),
            //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado" => (float)0,
            "totalPagar" => (float)$totalPagar,
            "totalLetras" => numtoletras($totalPagar),
            "saldoFavor" => 0.00,
            "condicionOperacion" => $salesave->waytopay,
            "pagos" => null,
            "totalIva" => (float)$detailsbd[0]->iva
        ];
        //detalle del comprobante como url de firmador y mh etc
        $querydocumento = "SELECT
        a.id id_doc,
        b.`type` id_tipo_doc,
        0 serie,
        1 inicial,
        1 final,
        a.id actual,
        1 estado,
        a.company_id id_empresa,
        a.user_id hechopor,
        a.created_at fechacreacion,
        b.description NombreDocumento,
        c.name NombreUsuario,
        c.nit docUser,
        b.codemh tipodocumento,
        b.versionjson versionJson,
        e.url_credencial,
        e.url_envio,
        e.url_invalidacion,
        e.url_contingencia,
        e.url_firmador,
        d.typeTransmission tipogeneracion,
        e.cod ambiente,
        a.updated_at,
        1 aparece_ventas
        FROM sales a
        INNER JOIN typedocuments b ON a.typedocument_id=b.id
        INNER JOIN users c ON a.user_id=c.id
        INNER JOIN config d ON a.company_id=d.company_id
        INNER JOIN ambientes e ON d.ambiente=e.id
        WHERE a.id = ".base64_decode($corr)."";
        $documento = DB::select(DB::raw($querydocumento));

        $queryproducto ="SELECT
        c.id id_producto,
        c.description descripcion,
        b.amountp cantidad,
        b.priceunit precio_unitario,
        0 descuento,
        0 no_imponible,
        (b.pricesale+b.nosujeta+b.exempt) subtotal,
        b.pricesale gravadas,
        b.nosujeta no_sujetas,
        b.exempt exentas,
        b.detained13 iva,
        0 porcentaje_descuento,
        b.detained13 iva_calculado,
        b.renta renta_retenida,
        1 tipo_item,
        59 uniMedida
        FROM sales a
        INNER JOIN salesdetails b ON b.sale_id=a.id
        INNER JOIN products c ON b.product_id=c.id
        WHERE a.id=".base64_decode($corr)."";
        $producto = DB::select(DB::raw($queryproducto));
        $detalle = $producto;
        //data del emisor
        $queryemisor ="SELECT
        a.nit,
        a.ncr,
        a.name nombre,
        c.code codActividad,
        c.name descActividad,
        a.name nombreComercial,
        a.tipoEstablecimiento,
        f.code departamento,
        g.code municipio,
        d.reference direccion,
        e.phone telefono,
        NULL codEstableMH,
        NULL codEstable,
        NULL codPuntoVentaMH,
        NULL codPuntoVenta,
        a.email correo,
        b.passkeyPublic clavePublicaMH,
        b.passPrivateKey clavePrivadaMH,
        b.passMH claveApiMH
        FROM companies a
        INNER JOIN config b ON a.id=b.company_id
        INNER JOIN economicactivities c ON a.economicactivity_id=c.id
        INNER JOIN addresses d ON a.address_id=d.id
        INNER JOIN phones e ON a.phone_id=e.id
        INNER JOIN departments f ON d.department_id=f.id
        INNER JOIN municipalities g ON d.municipality_id=g.id
        WHERE a.id=$idempresa";
        $emisor = DB::select(DB::raw($queryemisor));

        $querycliente ="SELECT
        a.id idcliente,
        a.nit,
        a.ncr,
        CASE
            WHEN a.tpersona = 'N' THEN CONCAT(a.firstname, ' ', a.secondname, ' ' , a.firstlastname, ' ', a.secondlastname)
            WHEN a.tpersona = 'J' THEN CONCAT(a.name_contribuyente)
        END AS nombre,
        b.code codActividad,
        b.name descActividad,
        CASE
            WHEN a.tpersona = 'N' THEN CONCAT(a.firstname, ' ', a.secondname, ' ' , a.firstlastname, ' ', a.secondlastname)
            WHEN a.tpersona = 'J' THEN CONCAT(a.comercial_name)
        END AS nombreComercial,
        a.email correo,
        f.code departamento,
        g.code municipio,
        c.reference direccion,
        p.phone telefono,
        1 id_tipo_contribuyente,
        a.tipoContribuyente id_clasificacion_tributaria,
        0 siempre_retiene,
        36 tipoDocumento,
        a.nit numDocumento,
        36 tipoDocumentoCliente,
        d.code codPais,
        d.name nombrePais,
        0 siempre_retiene_renta
    FROM clients a
    INNER JOIN economicactivities b ON a.economicactivity_id=b.id
    INNER JOIN addresses c ON a.address_id=c.id
    INNER JOIN phones p ON a.phone_id=p.id
    INNER JOIN countries d ON c.country_id=d.id
    INNER JOIN departments f ON c.department_id=f.id
    INNER JOIN municipalities g ON c.municipality_id=g.id
    WHERE a.id = $salesave->client_id";
        $cliente = DB::select(DB::raw($querycliente));

        $comprobante = [
            "emisor"    =>$emisor,
            "documento" => $documento,
            "detalle"   => $detalle,
            "totales"   => $totales,
            "cliente"   => $cliente
        ];

        //dd(json_encode($comprobante));
        $contingencia = [];
        $respuesta_hacienda = [];
        //dd($documento[0]->tipogeneracion);
        if ($documento[0]->tipogeneracion == 1) {
            //$contingencia = DB::table('Contingencias')->where('idEmpresa', $idempresa)->where('codEstado', '01')->get();
            $contingencia = 1;
            if($contingencia){
                $respuesta_hacienda = $this->Enviar_Hacienda($comprobante, "01");
                //dd($respuesta_hacienda);
                if ($respuesta_hacienda["codEstado"] == "03") {
                    return json_encode($respuesta_hacienda);
                }
                $comprobante["json"] = $respuesta_hacienda;
            }
        }
        //dd($respuesta_hacienda);
        //create respuesta de MH
        $dtecreate = new Dte();
        $dtecreate->versionJson = $documento[0]->versionJson;
        $dtecreate->ambiente_id = $documento[0]->ambiente;
        $dtecreate->tipoDte = $documento[0]->tipodocumento;
        $dtecreate->tipoModelo = $documento[0]->tipogeneracion;
        $dtecreate->tipoTransmision = 1;
        $dtecreate->tipoContingencia = "null";
        $dtecreate->idContingencia = "null";
        $dtecreate->nameTable = 'Sales';
        $dtecreate->company_id = $idempresa;
        $dtecreate->company_name = $emisor[0]->nombreComercial;
        $dtecreate->id_doc = $respuesta_hacienda["identificacion"]["numeroControl"];
        $dtecreate->codTransaction = "01";
        $dtecreate->desTransaction = "Emision";
        $dtecreate->type_document = $documento[0]->tipodocumento;
        $dtecreate->id_doc_Ref1 = "null";
        $dtecreate->id_doc_Ref2 = "null";
        $dtecreate->type_invalidacion = "null";
        $dtecreate->codEstado = $respuesta_hacienda["codEstado"];
        $dtecreate->Estado = $respuesta_hacienda["estado"];
        $dtecreate->codigoGeneracion = $respuesta_hacienda["codigoGeneracion"];
        $dtecreate->selloRecibido = $respuesta_hacienda["selloRecibido"];
        $dtecreate->fhRecibido = $respuesta_hacienda["fhRecibido"];
        $dtecreate->estadoHacienda = $respuesta_hacienda["estadoHacienda"];
        $dtecreate->json = json_encode($comprobante);;
        $dtecreate->nSends = $respuesta_hacienda["nuEnvios"];
        $dtecreate->codeMessage = $respuesta_hacienda["codigoMsg"];
        $dtecreate->claMessage = $respuesta_hacienda["clasificaMsg"];
        $dtecreate->descriptionMessage = $respuesta_hacienda["descripcionMsg"];
        $dtecreate->detailsMessage = $respuesta_hacienda["observacionesMsg"];
        $dtecreate->sale_id = base64_decode($corr);
        $dtecreate->created_by = $documento[0]->NombreUsuario;
        $dtecreate->save();

        if($dtecreate) $exit = 1;

        return response()->json(array(
            "res" => $exit
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

    public function ncr($id_sale){

        $id_sale = base64_decode($id_sale);

        $qfactura = "SELECT
                        *,
                        s.id id_factura,
                        s.id numero_factura,
                        s.created_at fecha_factura,
                        s.client_id id_cliente,
                        s.user_id id_vendedor,
                        '1' condiciones,
                        s.totalamount total_venta,
                        s.typesale estado_factura,
                        s.company_id id_empresa,
                        s.user_id hechopor,
                        s.created_at fechacreacion,
                        CASE
                                WHEN clie.tpersona = 'N' THEN CONCAT(clie.firstname, ' ', clie.secondname, ' ' , clie.firstlastname, ' ', clie.secondlastname)
                                WHEN clie.tpersona = 'J' THEN CONCAT(clie.name_contribuyente)
                            END AS nombre_cliente,
                        doct.type tipo_doc,
                        s.acuenta anombrede,
                        (SELECT SUM(sdet.exempt) FROM salesdetails sdet WHERE sdet.sale_id=s.id) exentas,
                        (SELECT SUM(sdet.nosujeta) FROM salesdetails sdet WHERE sdet.sale_id=s.id) nosujetas,
                        0.0 impint,
                        (SELECT SUM(sdet.detained13) FROM salesdetails sdet WHERE sdet.sale_id=s.id) iva,
                        (SELECT SUM(sdet.pricesale) FROM salesdetails sdet WHERE sdet.sale_id=s.id) gravadas,
                        '13' tipodoc,
                        clie.nit,
                        clie.email correo,
                        0.00 exentas_terceros,
                        0.00 nosujetas_terceros,
                        0.00 iva_terceros,
                        0.00 gravadas_terceros,
                        (SELECT SUM(sdet.detained) FROM salesdetails sdet WHERE sdet.sale_id=s.id) iva_retenido,
                        ad.reference direccion_cliente,
                        clie.ncr,
                        clie.nit,
                        clie.email email_cliente,
                        clie.tpersona tipo_personeria,
                        muni.code municipio,
                        dep.code departamento,
                        (SELECT SUM(sdet.pricesale) FROM salesdetails sdet WHERE sdet.sale_id=s.id) total_gravadas,
                        (SELECT SUM(sdet.detained13) FROM salesdetails sdet WHERE sdet.sale_id=s.id) total_iva,
                        (SELECT SUM(sdet.nosujeta) FROM salesdetails sdet WHERE sdet.sale_id=s.id) total_nosujetas,
                        (SELECT SUM(sdet.exempt) FROM salesdetails sdet WHERE sdet.sale_id=s.id) total_exentas,
                        econo.code giro,
                        dte.json,
                        dte.tipoModelo,
                        dte.fhRecibido,
                        am.url_contingencia,
                        am.url_envio,
                        am.url_credencial,
                        am.url_invalidacion,
                        am.url_firmador,
                        dte.nSends nuEnvios,
                        dte.updated_at,
                        am.cod,
                        dte.versionJson,
                        dte.selloRecibido,
                        dte.codigoGeneracion,
                        dte.tipoDte,
                        s.doc_related doc_relacionado,
                        '0000000000' serie,
                        users.name NombreUsuario,
                        users.nit docUser
                        FROM
                        sales s
                        INNER JOIN users ON s.user_id=users.id
                        LEFT JOIN dte ON dte.sale_id=s.id
                        INNER JOIN ambientes am ON CONCAT('0',dte.ambiente_id)=am.cod
                        INNER JOIN clients clie ON s.client_id=clie.id
                        INNER JOIN typedocuments doct ON s.typedocument_id=doct.id
                        INNER JOIN addresses ad ON clie.address_id=ad.id
                        INNER JOIN countries cou ON ad.department_id=cou.id
                        INNER JOIN departments dep ON ad.department_id=dep.id
                        INNER JOIN municipalities muni ON ad.municipality_id=muni.id
                        INNER JOIN economicactivities econo ON clie.economicactivity_id=econo.id
                        WHERE s.id = $id_sale";
            $factura = DB::select(DB::raw($qfactura));
        $qdoc = "SELECT
                        a.id id_doc,
                        a.`type` id_tipo_doc,
                        0 serie,
                        1 inicial,
                        1 final,
                        (SELECT MAX(sales.id) + 1 FROM sales) actual,
                        1 estado,
                        a.company_id id_empresa,
                        NULL hechopor,
                        a.created_at fechacreacion,
                        a.description NombreDocumento,
                        NULL NombreUsuario,
                        NULL docUser,
                        a.codemh tipodocumento,
                        a.versionjson versionJson,
                        e.url_credencial,
                        e.url_envio,
                        e.url_invalidacion,
                        e.url_contingencia,
                        e.url_firmador,
                        d.typeTransmission tipogeneracion,
                        e.cod ambiente,
                        a.updated_at,
                        1 aparece_ventas
                        FROM typedocuments a
                        INNER JOIN config d ON a.company_id=d.company_id
                        INNER JOIN ambientes e ON d.ambiente=e.id
                        WHERE a.`type`= 'NCR'";
                $doc = DB::select(DB::raw($qdoc));
        $qfacturadet = "SELECT
                        *,
                        det.id id_factura_det,
                        det.sale_id id_factura,
                        det.product_id id_producto,
                        pro.description descripcion,
                        det.amountp cantidad,
                        det.priceunit precio_unitario,
                        det.nosujeta no_sujetas,
                        det.exempt exentas,
                        det.pricesale gravadas,
                        det.detained13 iva,
                        0.00 no_imponible,
                        sa.company_id id_empresa,
                        CASE
                                WHEN pro.`type` = 'tercero' THEN 'T'
                                WHEN pro.`type` = 'directo' THEN 'D'
                            END AS tipo_producto,
                        0.00 porcentaje_descuento,
                        0.00 descuento,
                        det.created_at,
                        det.updated_at
                        FROM salesdetails det
                        INNER JOIN sales sa ON det.sale_id=sa.id
                        INNER JOIN products pro ON det.product_id=pro.id
                        WHERE det.sale_id = $id_sale";
                $detalle = DB::select(DB::raw($qfacturadet));
                $versionJson = $doc[0]->versionJson;
                $ambiente = $doc[0]->ambiente;
                $tipoDte = $doc[0]->tipodocumento;
                $tipoTransmision = $doc[0]->tipogeneracion;
                $tipoContingencia = null;
                $idContingencia = null;
                $codTransacion = '01';
                $codEstado = null;
                $codigoGeneracion = null;
                $selloRecibido = null;
                $fhRecibido = null;
                $codEstadoHacienda = null;
                $estadoHacienda = null;
                $nuEnvios = null;
                $codigoMsg = null;
                $clasificaMSg = null;
                $descripcionMsg = null;
                $observacionesMsg = null;
                $json = $factura[0]->json;

                $numero = $doc[0]->actual;

                $documento[0] = [
                    "tipodocumento"         => $doc[0]->tipodocumento,
                    "nu_doc"                => $numero,
                    "tipo_establecimiento"  => "1",  //Cambiar,
                    "version"               => $doc[0]->versionJson,
                    "ambiente"              => $doc[0]->ambiente,
                    "tipoDteOriginal"           => $factura[0]->tipoDte,
                    "tipoGeneracionOriginal"    => $factura[0]->tipoModelo,
                    "codigoGeneracionOriginal" => $factura[0]->codigoGeneracion,
                    "selloRecibidoOriginal"     => $factura[0]->selloRecibido,
                    "numeroOriginal"            => $factura[0]->codigoGeneracion,
                    "fecEmiOriginal"            => date('Y-m-d', strtotime($factura[0]->fhRecibido)),
                    "total_iva"                 => $factura[0]->iva,
                    "tipoDocumento"             => "",
                    "numDocumento"              => $factura[0]->nit,
                    "nombre"                    => $factura[0]->anombrede,
                    "versionjson"               => $doc[0]->versionJson,
                    "id_empresa"                => $factura[0]->id_empresa,
                    "url_credencial"            => $factura[0]->url_credencial,
                    "url_envio"                 => $factura[0]->url_envio,
                    "url_firmador"              => $factura[0]->url_firmador,
                    "nuEnvio"                   => 1,
                    "condiciones"               => $factura[0]->condiciones,
                    "total_venta"                =>$factura[0]->total_venta,
                    "tot_gravado"               => $factura[0]->total_gravadas,
                    "tot_nosujeto"              => $factura[0]->total_nosujetas,
                    "tot_exento"                => $factura[0]->total_exentas,
                    "subTotalVentas"            => $factura[0]->total_gravadas + $factura[0]->total_nosujetas + $factura[0]->total_exentas,
                    "descuNoSuj"                => 0.00,
                    "descuExenta"               => 0.00,
                    "descuGravada"              => 0.00,
                    "totalDescu"                => 0.00,
                    "subTotal"                  => $factura[0]->total_gravadas + $factura[0]->total_nosujetas + $factura[0]->total_exentas,
                    "ivaPerci1"                 => 0.00,
                    "ivaRete1"                  => $factura[0]->iva_retenido,
                    "reteRenta"                 => 0.00,
                    "total_letras"              => numeroletras($factura[0]->total_venta),
                    "totalPagar"                => $factura[0]->total_venta,
                    "NombreUsuario"             => $factura[0]->NombreUsuario,
                    "docUser"                   => $factura[0]->docUser

                ];

                $qcliente ="SELECT
                                a.id id_cliente,
                            CASE
                                WHEN a.tpersona = 'N' THEN CONCAT(a.firstname, ' ', a.secondname, ' ' , a.firstlastname, ' ', a.secondlastname)
                                WHEN a.tpersona = 'J' THEN CONCAT(a.name_contribuyente)
                            END AS nombre_cliente,
                                p.phone telefono_cliente,
                                a.email email_cliente,
                                c.reference direccion_cliente,
                                1 status_cliente,
                                a.created_at date_added,
                                a.ncr,
                            a.nit,
                            a.tpersona tipo_personeria,
                            g.code municipio,
                            f.code departamento,
                            a.company_id id_empresa,
                            NULL hechopor,
                            a.tipoContribuyente id_clasificacion_tributaria,
                            CASE
                                WHEN a.tipoContribuyente = 'GRA' THEN 'GRANDES CONTRIBUYENTES'
                                WHEN a.tipoContribuyente = 'MED' THEN 'MEDIANOS CONTRIBUYENTES'
                                WHEN a.tipoContribuyente = 'PEQU'  THEN 'PEQUEÑOS CONTRIBUYENTES'
                                WHEN a.tipoContribuyente = 'OTR'  THEN 'OTROS CONTRIBUYENTES'
                            END AS descripcion,
                            0 siempre_retiene,
                            1 id_tipo_contribuyente,
                            b.id giro,
                            b.code codActividad,
                            b.name descActividad,
                            a.comercial_name nombre_comercial
                        FROM clients a
                        INNER JOIN economicactivities b ON a.economicactivity_id=b.id
                        INNER JOIN addresses c ON a.address_id=c.id
                        INNER JOIN phones p ON a.phone_id=p.id
                        INNER JOIN countries d ON c.country_id=d.id
                        INNER JOIN departments f ON c.department_id=f.id
                        INNER JOIN municipalities g ON c.municipality_id=g.id
                        WHERE a.id = ".$factura[0]->id_cliente."";
                $cliente = DB::select(DB::raw($qcliente));

                //data del emisor
        $queryemisor ="SELECT
                        a.nit,
                        a.ncr,
                        a.name nombre,
                        c.code codActividad,
                        c.name descActividad,
                        a.name nombreComercial,
                        a.tipoEstablecimiento,
                        f.code departamento,
                        g.code municipio,
                        d.reference direccion,
                        e.phone telefono,
                        NULL codEstableMH,
                        NULL codEstable,
                        NULL codPuntoVentaMH,
                        NULL codPuntoVenta,
                        a.email correo,
                        b.passkeyPublic clavePublicaMH,
                        b.passPrivateKey clavePrivadaMH,
                        b.passMH claveApiMH
                        FROM companies a
                        INNER JOIN config b ON a.id=b.company_id
                        INNER JOIN economicactivities c ON a.economicactivity_id=c.id
                        INNER JOIN addresses d ON a.address_id=d.id
                        INNER JOIN phones e ON a.phone_id=e.id
                        INNER JOIN departments f ON d.department_id=f.id
                        INNER JOIN municipalities g ON d.municipality_id=g.id
                        WHERE a.id=".$factura[0]->id_empresa."";
        $emisor = DB::select(DB::raw($queryemisor));
                $comprobante = [
                    "emisor" => $emisor,
                    "documento" => $documento,
                    "detalle" => $detalle,
                    "totales" => $detalle,
                    "cliente" => $cliente
                ];
                //dd();
                //dd($factura);
                $respuesta = $this->Enviar_Hacienda($comprobante, "05");
                if ($respuesta["codEstado"] == "03") {
                    return json_encode($respuesta);
                }
                $comprobante["json"] = $respuesta;

                $codEstado = $respuesta["codEstado"];
                $codigoGeneracion = $respuesta["codigoGeneracion"];
                $selloRecibido = $respuesta["selloRecibido"];
                $fhRecibido = $respuesta["fhRecibido"];
                $codEstadoHacienda =null;
                $estadoHacienda = $respuesta["estadoHacienda"];
                $nuEnvios = 1;
                $codigoMsg = $respuesta["codigoMsg"];
                $clasificaMSg = $respuesta["clasificaMsg"];
                $descripcionMsg = $respuesta["descripcionMsg"];
                $observacionesMsg = $respuesta["observacionesMsg"];

                //dd($factura);

                $nfactura = new Sale();
                $nfactura->acuenta = $factura[0]->anombrede;
                $nfactura->state =  1;
                $nfactura->state_credit = $factura[0]->state_credit;
                $nfactura->totalamount = $factura[0]->totalamount;
                $nfactura->waytopay = $factura[0]->waytopay;
                $nfactura->typesale = 1;
                $nfactura->date = $factura[0]->date;
                $nfactura->user_id = $factura[0]->user_id;
                $nfactura->typedocument_id = 9;
                $nfactura->client_id = $factura[0]->client_id;
                $nfactura->company_id = $factura[0]->company_id;
                $nfactura->doc_related = $factura[0]->id_factura;
                $nfactura->save();

                foreach($detalle as $d){
                    $n = new Salesdetail();
                    $n->sale_id=$nfactura["id"];
                    $n->product_id=$d->product_id;
                    $n->amountp=$d->amountp;
                    $n->pricesale=$d->pricesale;
                    $n->priceunit=$d->priceunit;
                    $n->nosujeta=$d->nosujeta;
                    $n->exempt=$d->exempt;
                    $n->detained=$d->detained;
                    $n->detained13=$d->detained13;
                    $n->save();
                }
                //dd($respuesta);
                $dtecreate = new Dte();
                $dtecreate->versionJson = $documento[0]["versionjson"];
                $dtecreate->ambiente_id = $documento[0]["ambiente"];
                $dtecreate->tipoDte = $documento[0]["tipodocumento"];
                $dtecreate->tipoModelo = 2;
                $dtecreate->tipoTransmision = 1;
                $dtecreate->tipoContingencia = "null";
                $dtecreate->idContingencia = "null";
                $dtecreate->nameTable = 'Sales';
                $dtecreate->company_id = $factura[0]->id_empresa;
                $dtecreate->company_name = $emisor[0]->nombreComercial;
                $dtecreate->id_doc = $respuesta["identificacion"]["numeroControl"];
                $dtecreate->codTransaction = "01";
                $dtecreate->desTransaction = "Emision";
                $dtecreate->type_document = $documento[0]["tipodocumento"];
                $dtecreate->id_doc_Ref1 = "null";
                $dtecreate->id_doc_Ref2 = $factura[0]->id_factura;
                $dtecreate->type_invalidacion = "null";
                $dtecreate->codEstado = $respuesta["codEstado"];
                $dtecreate->Estado = $respuesta["estado"];
                $dtecreate->codigoGeneracion = $respuesta["codigoGeneracion"];
                $dtecreate->selloRecibido = $respuesta["selloRecibido"];
                $dtecreate->fhRecibido = $respuesta["fhRecibido"];
                $dtecreate->estadoHacienda = $respuesta["estadoHacienda"];
                $dtecreate->json = json_encode($comprobante);;
                $dtecreate->nSends = $respuesta["nuEnvios"];
                $dtecreate->codeMessage = $respuesta["codigoMsg"];
                $dtecreate->claMessage = $respuesta["clasificaMsg"];
                $dtecreate->descriptionMessage = $respuesta["descripcionMsg"];
                $dtecreate->detailsMessage = $respuesta["observacionesMsg"];
                $dtecreate->sale_id = $nfactura["id"];
                $dtecreate->created_by = $documento[0]["NombreUsuario"];
                $dtecreate->save();

        if($dtecreate) $exit = 1; else $exit = 0;

        return response()->json(array(
            "res" => $exit
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $Sale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $idFactura = base64_decode($id);
        $anular = Sale::find($idFactura);
        $anular->state = 0;
        $anular->typesale = 0;

        $queryinvalidacion ="SELECT
        b.tipoModelo,
        b.type_document,
        b.sale_id numero_factura,
        b.id_doc,
        b.tipoDte,
        am.cod ambiente,
        comp.tipoEstablecimiento,
        b.codigoGeneracion,
        b.selloRecibido,
        b.fhRecibido,
        (SELECT SUM(det.detained13) FROM salesdetails det WHERE det.sale_id=a.id) iva,
        clie.nit,
        CASE
                WHEN clie.tpersona = 'N' THEN CONCAT(clie.firstname, ' ', clie.secondname, ' ' , clie.firstlastname, ' ', clie.secondlastname)
                WHEN clie.tpersona = 'J' THEN CONCAT(clie.name_contribuyente)
            END AS anombrede,
        a.company_id id_empresa,
        a.client_id id_cliente,
        am.url_credencial,
        am.url_invalidacion,
        am.url_firmador
        FROM sales a
        INNER JOIN clients clie ON a.client_id=clie.id
        INNER JOIN companies comp ON a.company_id=comp.id
        INNER JOIN dte b ON b.sale_id=a.id
        LEFT JOIN ambientes am ON CONCAT('0',b.ambiente_id)=am.cod
        WHERE a.id = $idFactura";
        $invalidacion = DB::select(DB::raw($queryinvalidacion));
        //data del emisor
        $queryemisor ="SELECT
        a.nit,
        a.ncr,
        a.name nombre,
        c.code codActividad,
        c.name descActividad,
        a.name nombreComercial,
        a.tipoEstablecimiento,
        f.code departamento,
        g.code municipio,
        d.reference direccion,
        e.phone telefono,
        NULL codEstableMH,
        NULL codEstable,
        NULL codPuntoVentaMH,
        NULL codPuntoVenta,
        a.email correo,
        b.passkeyPublic clavePublicaMH,
        b.passPrivateKey clavePrivadaMH,
        b.passMH claveApiMH
        FROM companies a
        INNER JOIN config b ON a.id=b.company_id
        INNER JOIN economicactivities c ON a.economicactivity_id=c.id
        INNER JOIN addresses d ON a.address_id=d.id
        INNER JOIN phones e ON a.phone_id=e.id
        INNER JOIN departments f ON d.department_id=f.id
        INNER JOIN municipalities g ON d.municipality_id=g.id
        WHERE a.id=$anular->company_id";
        $emisor = DB::select(DB::raw($queryemisor));


        $queryproducto ="SELECT
        c.id id_producto,
        c.description descripcion,
        b.amountp cantidad,
        b.priceunit precio_unitario,
        0 descuento,
        0 no_imponible,
        (b.pricesale+b.nosujeta+b.exempt) subtotal,
        b.pricesale gravadas,
        b.nosujeta no_sujetas,
        b.exempt exentas,
        b.detained13 iva,
        0 porcentaje_descuento,
        b.detained13 iva_calculado,
        0 renta_retenida,
        1 tipo_item,
        59 uniMedida
        FROM sales a
        INNER JOIN salesdetails b ON b.sale_id=a.id
        INNER JOIN products c ON b.product_id=c.id
        WHERE a.id=$idFactura";
        $producto = DB::select(DB::raw($queryproducto));
        $detalle = $producto;

        $detailsbd = Salesdetail::where('sale_id','=', $idFactura)
        ->select(
            DB::raw('SUM(nosujeta) nosujeta,
            SUM(exempt) exentas,
            SUM(pricesale) gravadas,
            SUM(nosujeta+exempt+pricesale) subtotalventas,
            0 descnosujeta,
            0 descexenta,
            0 desgravada,
            0 porcedesc,
            0 totaldesc,
            NULL tributos,
            SUM(nosujeta+exempt+pricesale) subtotal,
            SUM(detained) ivarete,
            0 ivarete,
            0 rentarete,
            NULL pagos,
            SUM(detained13) iva')
        )
        ->get();
        //detalle de montos de la factura
        $totalPagar = ($detailsbd[0]->nosujeta+$detailsbd[0]->exentas+$detailsbd[0]->gravadas+$detailsbd[0]->iva-$detailsbd[0]->ivarete);
        $totales = [
            "totalNoSuj" => (float)$detailsbd[0]->nosujeta,
            "totalExenta" => (float)$detailsbd[0]->exentas,
            "totalGravada" => (float)$detailsbd[0]->gravadas,
            "subTotalVentas" => round((float)($detailsbd[0]->subtotalventas),2),
            "descuNoSuj" => $detailsbd[0]->descnosujeta,
            "descuExenta" => $detailsbd[0]->descexenta,
            "descuGravada" => $detailsbd[0]->desgravada,
            "porcentajeDescuento" => 0.00,
            "totalDescu" => $detailsbd[0]->totaldesc,
            "tributos" =>  null,
            "subTotal" => round((float)($detailsbd[0]->subtotal),2),
            "ivaPerci1" => 0.00,
            "ivaRete1" => 0.00,
            "reteRenta" => round((float)$detailsbd[0]->rentarete,2),
            "montoTotalOperacion" => round((float)($detailsbd[0]->subtotal),2),
            //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado" => (float)0,
            "totalPagar" => (float)$totalPagar,
            "totalLetras" => numtoletras($totalPagar),
            "saldoFavor" => 0.00,
            "condicionOperacion" => $anular->waytopay,
            "pagos" => null,
            "totalIva" => (float)$detailsbd[0]->iva
        ];
        $querycliente ="SELECT
        a.id idcliente,
        a.nit,
        a.ncr,
        CASE
            WHEN a.tpersona = 'N' THEN CONCAT(a.firstname, ' ', a.secondname, ' ' , a.firstlastname, ' ', a.secondlastname)
            WHEN a.tpersona = 'J' THEN CONCAT(a.name_contribuyente)
        END AS nombre,
        b.code codActividad,
        b.name descActividad,
        CASE
            WHEN a.tpersona = 'N' THEN CONCAT(a.firstname, ' ', a.secondname, ' ' , a.firstlastname, ' ', a.secondlastname)
            WHEN a.tpersona = 'J' THEN CONCAT(a.comercial_name)
        END AS nombreComercial,
        a.email correo,
        f.code departamento,
        g.code municipio,
        c.reference direccion,
        p.phone telefono,
        1 id_tipo_contribuyente,
        a.tipoContribuyente id_clasificacion_tributaria,
        0 siempre_retiene,
        36 tipoDocumento,
        a.nit numDocumento,
        36 tipoDocumentoCliente,
        d.code codPais,
        d.name nombrePais,
        0 siempre_retiene_renta
    FROM clients a
    INNER JOIN economicactivities b ON a.economicactivity_id=b.id
    INNER JOIN addresses c ON a.address_id=c.id
    INNER JOIN phones p ON a.phone_id=p.id
    INNER JOIN countries d ON c.country_id=d.id
    INNER JOIN departments f ON c.department_id=f.id
    INNER JOIN municipalities g ON c.municipality_id=g.id
    WHERE a.id = $anular->client_id";
        $cliente = DB::select(DB::raw($querycliente));

            $documento[0] = [
                "tipodocumento"         => 99,
                "nu_doc"                => $invalidacion[0]->numero_factura,
                "tipoDteOriginal"       => $invalidacion[0]->tipoDte,
                "tipo_establecimiento"  => $invalidacion[0]->tipoEstablecimiento,  //Cambiar,
                "version"               => 2,
                "ambiente"              => $invalidacion[0]->ambiente,
                "id_doc"                => $invalidacion[0]->id_doc,
                "fecAnulado"            => date('Y-m-d'), //"2022-07-20", // $encabezado["fecEmi"],    //Cambiar
                "horAnulado"            => date("H:i:s"),
                "codigoGeneracionOriginal" => $invalidacion[0]->codigoGeneracion,
                "selloRecibidoOriginal"     => $invalidacion[0]->selloRecibido,
                "fecEmiOriginal"            =>date('Y-m-d', strtotime($invalidacion[0]->fhRecibido)),
                "total_iva"                 => $invalidacion[0]->iva,
                "tipoDocumento"             => $invalidacion[0]->type_document,
                "numDocumento"              => $invalidacion[0]->nit,
                "nombre"                    => $invalidacion[0]->anombrede,
                "versionjson"               => 2,
                "id_empresa"                => $invalidacion[0]->id_empresa,
                "url_credencial"            => $invalidacion[0]->url_credencial,
                "url_envio"                 => $invalidacion[0]->url_invalidacion,
                "url_firmador"              => $invalidacion[0]->url_firmador,
                "nuEnvio"                   => 1
            ];
            $comprobante = [
                "emisor"    =>$emisor,
                "documento" => $documento,
                "detalle"   => $detalle,
                "totales"   => $totales,
                "cliente"   => $cliente
            ];
        //$cliente = Client::where('id', $invalidacion[0]->id_cliente)->get();
            //dd($documento);
           $respuesta = $this->Enviar_Hacienda($comprobante,"02");
           if ($respuesta["codEstado"] == "03") {
            return json_encode($respuesta);
            }
            $comprobante["json"] = $respuesta;


        //dd($respuesta);
        $dtecreate = new Dte();
        $dtecreate->versionJson = $documento[0]["versionjson"];
        $dtecreate->ambiente_id = $documento[0]["ambiente"];
        $dtecreate->tipoDte = $documento[0]["tipoDocumento"];
        $dtecreate->tipoModelo = 2;
        $dtecreate->tipoTransmision = $documento[0]["tipoDocumento"];
        $dtecreate->tipoContingencia = "null";
        $dtecreate->idContingencia = "null";
        $dtecreate->nameTable = 'Sales';
        $dtecreate->company_id = $anular->company_id;
        $dtecreate->company_name = $emisor[0]->nombreComercial;
        $dtecreate->id_doc = $documento[0]["id_doc"];
        $dtecreate->codTransaction = "02";
        $dtecreate->desTransaction = "Invalidacion";
        $dtecreate->type_document = $documento[0]["tipoDocumento"];
        $dtecreate->id_doc_Ref1 = $documento[0]["id_doc"];
        $dtecreate->id_doc_Ref2 = "null";
        $dtecreate->type_invalidacion = "1";
        $dtecreate->codEstado = $respuesta["codEstado"];
        $dtecreate->Estado = $respuesta["estado"];
        $dtecreate->codigoGeneracion = $respuesta["codigoGeneracion"];
        $dtecreate->selloRecibido = $respuesta["selloRecibido"];
        $dtecreate->fhRecibido = $respuesta["fhRecibido"];
        $dtecreate->estadoHacienda = $respuesta["estadoHacienda"];
        $dtecreate->json = json_encode($comprobante);;
        $dtecreate->nSends = $respuesta["nuEnvios"];
        $dtecreate->codeMessage = $respuesta["codigoMsg"];
        $dtecreate->claMessage = $respuesta["clasificaMsg"];
        $dtecreate->descriptionMessage = $respuesta["descripcionMsg"];
        $dtecreate->detailsMessage = $respuesta["observacionesMsg"];
        $dtecreate->sale_id = $idFactura;
        $dtecreate->created_by = $documento[0]["nombre"];
        $dtecreate->save();
        $anular->save();

        if($dtecreate && $anular) $exit = 1;
        else $exit = 0;
        return response()->json(array(
            "res" => $exit
        ));
    }

    public function Enviar_Hacienda($comprobante, $codTransaccion = "01"){
        //$codTransaccion ='01';
        date_default_timezone_set('America/El_Salvador');
        ini_set('max_execution_time', '300');
        $respuesta = [];
        $comprobante_electronico = [];
        //return $comprobante_electronico;
        $comprobante_electronico = convertir_json($comprobante, $codTransaccion);
        //return $comprobante_electronico;
        if ($codTransaccion=="02" || $codTransaccion=="05") {
            $tipo_documento = $comprobante["documento"][0]["tipodocumento"];
        } else {
            $tipo_documento = $comprobante["documento"][0]->tipodocumento;
        }
        //$tipo_documento = $comprobante["documento"][0]->tipodocumento;
        //dd($comprobante);
        if ($codTransaccion=="02" || $codTransaccion=="05") {
            $version = $comprobante["documento"][0]["version"];
        } else {
            $version = $comprobante["documento"][0]->versionJson;
        }
        //$version = $comprobante["documento"][0]->versionJson;
        if ($codTransaccion == '01' || $codTransaccion=="05") {
            $numero_control = $comprobante_electronico["identificacion"]["numeroControl"];
        } else {
            $numero_control = 'Anulacion o Contingencia';
        }
        $empresa = $comprobante["documento"][0];
        $id_empresa = ($codTransaccion=="02" || $codTransaccion=="05" ? $comprobante["documento"][0]["id_empresa"] : $empresa->id_empresa );
        $ambiente = ($codTransaccion=="02" || $codTransaccion=="05" ? $comprobante["documento"][0]["ambiente"] : $empresa->ambiente );
        $emisor = $comprobante["emisor"];
        $url_credencial = ($codTransaccion=="02" || $codTransaccion=="05" ? $comprobante["documento"][0]["url_credencial"] : $empresa->url_credencial);
        $url_envio = ($codTransaccion=="02" || $codTransaccion=="05" ? $comprobante["documento"][0]["url_envio"] : $empresa->url_envio);
        $url_firmador = ($codTransaccion=="02" || $codTransaccion=="05" ? $comprobante["documento"][0]["url_firmador"] : $empresa->url_firmador);
        //dd(str_replace('-','',$emisor[0]->nit));
        $firma_electronica = [
            "nit" => str_replace('-','',$emisor[0]->nit),
            "activo" => true,
            "passwordPri" => $emisor[0]->clavePrivadaMH,
            "dteJson" => $comprobante_electronico
        ];
        //dd($firma_electronica);
        //return json_encode($firma_electronica);
        //dd(json_encode($firma_electronica));
        //dd($url_firmador);
        try {
            $response = Http::accept('application/json')->post($url_firmador, $firma_electronica);
        } catch (\Throwable $th) {
            $error = [
                "mensaje" => "Error en Firma de Documento",
                "error" => $th
            ];
            return  json_encode($error);
        }
        //return "aqui llego";
        //return $response;
        $objResponse = json_decode($response, true);
        //dd($objResponse);
        //return json_last_error_msg();
        $objResponse = (array)$objResponse;
        $comprobante_encriptado = $objResponse["body"];
        $validacion_usuario = [
            "user"  => str_replace('-','',$emisor[0]->nit),
            "pwd"   => $emisor[0]->claveApiMH
        ];

        //dd($validacion_usuario);
        //dd($this->getTokenMH($id_empresa, $validacion_usuario, $url_credencial, $url_credencial));
        if ($this->getTokenMH($id_empresa, $validacion_usuario, $url_credencial, $url_credencial) == "OK") {
            // return 'paso validacion';
            $token = Session::get($id_empresa);
            //$ambiente = $comprobante["documento"][0]->ambiente;
            //dd($documento[0]);
            //return ["token" => $token];
            //dd($codTransaccion);
            if ($codTransaccion == "01" || $codTransaccion=="05") {
                $comprobante_enviar = [
                    "ambiente"      => $ambiente,
                    "idEnvio"       => 1, //intval($comprobante["nuEnvio"]),
                    "version"       => intval($version),
                    "tipoDte"       => $tipo_documento,
                    "documento"     => $comprobante_encriptado
                ];
            } else {
                $comprobante_enviar = [
                    "ambiente"      => $ambiente,
                    "idEnvio"       => intval($empresa["nuEnvio"]),
                    "version"       => intval($version),
                    "documento"     => $comprobante_encriptado
                ];
            }

            //dd($comprobante_enviar);
            //dd($url_envio);
            try {
                $response_enviado = Http::withToken($token)->post($url_envio, $comprobante_enviar);
            } catch (\Throwable $th) {
                //return 'entro aqui';
                $error  = [
                    "mensaje" => "Error con Servicios de Hacienda",
                    "erro" => $th
                ];
                return json_encode($error);
            }
        } else {
            $response_enviado = $this->getTokenMH($id_empresa, $url_credencial, $url_credencial);
        }

        //dd($comprobante);

            //return json_encode($comprobante);
            //dd($response_enviado);
            $objEnviado = json_decode($response_enviado); 
           //dd($objEnviado);
            if (isset($objEnviado->estado)) {
                $estado_envio = $objEnviado->estado;
                $dateString = $objEnviado->fhProcesamiento;
                $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
                $newDateString = $myDateTime->format('Y-m-d H:i:s');
                //$prueba = gettype($objEnviado->observaciones);
                //dd($objEnviado->observaciones);
                $observaciones = implode("<br>", $objEnviado->observaciones);
                if ($estado_envio == "PROCESADO") {
                    $respuesta = [
                    "codEstado"         => "02",
                    "estado"            => "Enviado",
                    "codigoGeneracion"  => $objEnviado->codigoGeneracion,
                    "fhRecibido"        => $newDateString,
                    "selloRecibido"     => $objEnviado->selloRecibido,
                    "estadoHacienda"    => $objEnviado->estado,
                    "nuEnvios"          => 1,
                    "clasificaMsg"      => $objEnviado->clasificaMsg,
                    "codigoMsg"         =>  $objEnviado->codigoMsg,
                    "descripcionMsg"    => $objEnviado->descripcionMsg,
                    "observacionesMsg"  => $observaciones,

                    ];
                    $comprobante_electronico["selloRecibido"] = $objEnviado->selloRecibido;
                    if($codTransaccion == '01' || $codTransaccion == '05'){
                        if($tipo_documento=='14'){
                            $respuesta["receptor"] = $comprobante_electronico["sujetoExcluido"];
                        }else{
                            $respuesta["receptor"] = $comprobante_electronico["receptor"];
                        }

                        $respuesta["identificacion"]    = $comprobante_electronico["identificacion"];
                        $respuesta["json_enviado"]      = $comprobante_electronico;
                    }

                   // $this->envia_correo($comprobante);

                } else {
                    $respuesta = [
                    "codEstado" =>  "03",
                    "estado" =>  "Rechazado",
                    "descripcionMsg" =>  $objEnviado->descripcionMsg,
                    "observacionesMsg" =>  $observaciones,
                    "nuEnvios" =>  1
                    ];
                }
            } else {
                return var_dump($objEnviado);

            }

        return $respuesta;
    }

    public function getTokenMH($id_empresa, $credenciales, $url_seguridad)
    {
        //dd('entra a gettoken');
        if (!Session::has($id_empresa)) {

            //dd('No encuentra la variable');
            //return ["mensaje" => "llama  getnewtokemh"];
            $respuesta =  $this->getNewTokenMH($id_empresa, $credenciales, $url_seguridad);
        } else {
            $now = new Datetime('now');
            $expira = DateTime::createFromFormat('Y-m-d H:i:s', Session::get($id_empresa . '_fecha'));
            $respuesta = 'OK';
            if ($now > $expira) {
                // dd($expira);
                $respuesta = $this->getNewTokenMH($id_empresa, $credenciales, $url_seguridad);
            }
        }
        //dd(Session::get($id_empresa));
        // return ["mensaje" => "pasa la autorizacion OK estoy en get"];
        if ($respuesta == 'OK') {
            return 'OK';
        } else {
            return $respuesta;
        }
    }

    public function getNewTokenMH($id_empresa, $credenciales, $url_seguridad)
    {


        $response_usuario = Http::asForm()->post($url_seguridad, $credenciales);


        //return ["mensaje" => $response_usuario, 'credenciales' => $credenciales];
        $objValidacion = json_decode($response_usuario, true);

        //dd($objValidacion);
        //return ["mensaje" => "pasa la autorizacion"];
        if ($objValidacion["status"] != 'OK') {
            // return ["mensaje" => "no pasa la autorizacion OK"];
            return $objValidacion["status"];
        } else {
            //dd($objValidacion);
            //return ["mensaje" => "pasa la autorizacion OK"];
            Session::put($id_empresa, str_replace('Bearer ', '', $objValidacion["body"]["token"]));
            $fecha_expira = date("Y-m-d H:i:S", strtotime('+24 hours'));
            Session::put($id_empresa . '_fecha', $fecha_expira);
            return 'OK';
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
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
        $iva_calculado = round($price/1.13,2);
        $pgravado = round($pricegravada/1.13,2);
        $saledetails = new Salesdetail();
        $saledetails->sale_id = $idsale;
        $saledetails->product_id = $productid;
        $saledetails->amountp = $cantidad;
        $saledetails->priceunit = ($sale->typedocument_id==6) ? round($iva_calculado,2) : $price;
        $saledetails->pricesale = ($sale->typedocument_id==6) ? round($iva_calculado*$cantidad,2) : $pricegravada;
        $saledetails->nosujeta = $pricenosujeta;
        $saledetails->exempt = $priceexenta;
        $saledetails->detained13 = ($sale->typedocument_id==6) ? round($pricegravada-$pgravado,2) : $ivarete13;
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
        //dd($amount);

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
        d.versionJson,
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
        0 renta_retenida,
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

    public function Enviar_Hacienda($comprobante, $codTransaccion = "01"){
        //$codTransaccion ='01';
        date_default_timezone_set('America/El_Salvador');
        ini_set('max_execution_time', '300');
        $respuesta = [];
        $comprobante_electronico = [];
        //return $comprobante_electronico;
        $comprobante_electronico = convertir_json($comprobante);
        //dd($comprobante_electronico);
        //return $comprobante_electronico;
        $tipo_documento = $comprobante["documento"][0]->tipodocumento;
        $version = $comprobante["documento"][0]->versionJson;
        if ($codTransaccion == '01') {
            $numero_control = $comprobante_electronico["identificacion"]["numeroControl"];
        } else {
            $numero_control = 'Anulacion o Contingencia';
        }
        $empresa = $comprobante["documento"][0];
        $id_empresa = $empresa->id_empresa;
        $ambiente = $empresa->ambiente;
        $emisor = $comprobante["emisor"];

        $url_credencial = $empresa->url_credencial;
        $url_envio = $empresa->url_envio;
        $url_firmador = $empresa->url_firmador;
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
            $ambiente = $comprobante["documento"][0]->ambiente;
            //dd($documento[0]);
            //return ["token" => $token];
            if ($codTransaccion == "01") {
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
                    if($codTransaccion == '01'){
                        $respuesta["receptor"] = $comprobante_electronico["receptor"];
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

<?php

namespace App\Http\Controllers;

use App\Models\Contingencia;
use App\Models\Dte;
use App\Models\Sale;
use Carbon\Carbon;
use DateTime;
use Session;
use Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ContingenciasController extends Controller
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


    public function contingencias(){
        $contingencias = Contingencia::all();
            return view('dtemh.contingencias', array(
                "contingencias" => $contingencias
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
        $contingencia = new Contingencia();
        $contingencia->idEmpresa = $request->company;
        $contingencia->versionJson = $request->versionJson ;
        $contingencia->ambiente = $request->ambiente ;
        $contingencia->codEstado = "01" ;
        $contingencia->estado = "En Cola" ;
        $contingencia->tipoContingencia = $request->tipoContingencia ;
        $contingencia->motivoContingencia = $request->motivoContingencia;
        $contingencia->nombreResponsable = $request->nombreResponsable;
        $contingencia->tipoDocResponsable = $request->tipoDocResponsable;
        $contingencia->nuDocResponsable = $request->nuDocResponsable;
        $contingencia->fechaCreacion = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaCreacion)->toDateTimeString();
        $contingencia->fInicio = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaInicioFin)->toDateString();
        $contingencia->fFin = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaInicioFin)->toDateString();
        $contingencia->horaCreacion = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaCreacion)->format('H:i:s');
        $contingencia->hInicio = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaInicioFin)->format('H:i:s');
        $contingencia->hFin = Carbon::createFromFormat('Y-m-d\TH:i:s', $request->fechaInicioFin)->format('H:i:s');
        $contingencia->codigoGeneracion = strtoupper(\Str::uuid()->toString());
        $contingencia->save();
        //validar que facturas estan con fallo de entregar
        $countfacturas = Sale::leftJoin('dte', 'dte.sale_id', '=', 'sales.id')
        ->whereNull('dte.sale_id')
        ->whereNull('sales.codigoGeneracion')
        //->where('dte.descriptionMessage', '=', 'RECIBIDO CON OBSERVACIONES')
        ->where(function ($query) {
            $query->where('typedocument_id', '=', 6)
                  ->orWhere('typedocument_id', '=', 3);
        })
        ->select('sales.id', 'dte.id as DTEID')
        ->take(3)
        ->get();
         //dd($countfacturas);

         foreach ($countfacturas as $fac) {
            $updatefac = Sale::find($fac->id);

            if ($updatefac) {
                $updatefac->id_contingencia = $contingencia->id;
                $uuid_generado = strtoupper(Str::uuid()->toString());
                $updatefac->codigoGeneracion = $uuid_generado;
                $updatefac->save();
            }

            //$quitcola = Dte::find( $fac->DTEID);
            //if($quitcola){
            //$quitcola->type_invalidacion = 1;
            //$quitcola->save();
            //}

        }

        return redirect()->route('factmh.contingencias')
        ->with('success', 'Contingencia creada con Exito');
    }

    public function autoriza_contingencia($empresa, $id){
        date_default_timezone_set('America/El_Salvador');
        ini_set('max_execution_time', '300');
        $tipo_resultado = "";
        $mensaje_resultado = '';
        $cola_aux = Contingencia::where('id',$id)->where('codEstado', '01')->get();
        if ($cola_aux->isEmpty()) {
            $tipo_resultado = "danger";
            $mensaje_resultado = 'Contingencia ya fue Procesada';

            return redirect()->route('factmh.contingencias')
            ->with($tipo_resultado, $mensaje_resultado);
        }
        $cola = $cola_aux[0];

        $i = 1;
        $total = 1;
        $id_empresa = $empresa;

        $queryEmpresa = "SELECT
        'Empresa' AS NmTabla,
        a.company_id AS id_empresa,
        REPLACE(c.nit, '-', '') AS nit,
        a.passPrivateKey AS passwordPri,
        a.passMH AS pwd,
        c.email,
        a.ambiente,
        b.url_credencial,
        b.url_envio,
        b.url_invalidacion,
        b.url_contingencia
        FROM config a
        LEFT OUTER JOIN ambientes b ON a.ambiente=b.id
        LEFT OUTER JOIN companies c ON a.company_id = c.id
        WHERE a.company_id = $id_empresa";
        $empresaconti = DB::select(DB::raw($queryEmpresa));

        $queryEncabezado ="SELECT
        'Encabezado' AS NmTabla,
        a.versionJson AS versionJson,
        a.ambiente,
        a.codigoGeneracion,
        a.fechaCreacion,
        TIME(NOW()) AS horaCreacion,
        REPLACE(b.nit, '-', '') AS nit_emisor,
        b.name AS nombre_empresa,
        a.nombreResponsable,
        a.tipoDocResponsable,
        a.nuDocResponsable,
        b.tipoEstablecimiento,
        NULL AS codigo_establecimiento,
        NULL AS codigo_punto_venta,
        REPLACE(c.phone, '-', '') AS telefono_emisor,
        b.email AS correo,
        a.fInicio,
        a.fFin,
        a.hInicio,
        a.hFin,
        a.tipoContingencia,
        a.motivoContingencia,
        a.selloRecibido
        FROM contingencias a
        LEFT JOIN companies b ON a.idEmpresa = b.id
        INNER JOIN phones c ON b.phone_id = c.id
        WHERE a.idEmpresa = $id_empresa AND a.id = $id";
        $encabezado = DB::select(DB::raw($queryEncabezado));

        echo $queryDetalle = "SELECT
        'Detalle' AS NmTabla,
        1 NuItems,
        c.codemh tipoDte,
        a.codigoGeneracion codigoGeneracion
        FROM sales a
        LEFT JOIN dte ON dte.sale_id = a.id
        INNER JOIN contingencias b ON a.id_contingencia = b.id
        INNER JOIN typedocuments c ON a.typedocument_id = c.id
        WHERE b.id = $id AND a.company_id = $id_empresa";
        $detalle = DB::select(DB::raw($queryDetalle));
        if(empty($detalle)){
            $cola->codEstado = "10";
            $cola->estado = "Revision";
            $cola->observacionesMsg =  "Rechazado por Detalle Vacio";
            $cola->save();
            $tipo_resultado = "danger";
            $mensaje_resultado = 'Rechazado  por Detalle Vacio';
            return redirect()->route('factmh.contingencias')
            ->with($tipo_resultado, $mensaje_resultado);
        }

        /* Si la consulta no trae la tabla encabezada continue hace que siga con la proxima interaccion */
        if (empty($encabezado)) {
        }
        /* Si la consulta no trae la tabla empresa continue hace que siga con la proxima interaccion */
        if (empty($empresaconti)) {
        }

        $comprobante = [];
        $comprobante[] = $encabezado;
        $comprobante[] = $detalle;
        $comprobante[] = $empresaconti;
        $comprobante["codigoGeneracion"] = $encabezado[0]->codigoGeneracion;

        $comprobante_electronico = [];

        $identificacion = [
            "version"   => intval($encabezado[0]->versionJson),
            "ambiente"  => $encabezado[0]->ambiente,
            "codigoGeneracion"  => $encabezado[0]->codigoGeneracion,
            "fTransmision"      => $encabezado[0]->fechaCreacion,
            "hTransmision"       => $encabezado[0]->horaCreacion
        ];
        $emisor = [
            "nit"                   => $encabezado[0]->nit_emisor,
            "nombre"                => trim($encabezado[0]->nombre_empresa),
            "nombreResponsable"     => $encabezado[0]->nombreResponsable,
            "tipoDocResponsable"    => trim($encabezado[0]->tipoDocResponsable),
            "numeroDocResponsable"  => str_replace("-", "", $encabezado[0]->nuDocResponsable),
            "tipoEstablecimiento"   => $encabezado[0]->tipoEstablecimiento,
            "codEstableMH"          => $encabezado[0]->codigo_establecimiento,
            "codPuntoVenta"         => "1",
            "telefono"              => $encabezado[0]->telefono_emisor,
            "correo"                => $encabezado[0]->correo,
        ];

        $detalleDte = [];
        $ban = 1;
        foreach ($detalle as $d) {
            $detalleDTE[] = [
                "noItem"    => intval($ban),
                "codigoGeneracion" => $d->codigoGeneracion,
                "tipoDoc"   => $d->tipoDte

            ];
            $ban++;
        }

        $motivo = [
            "fInicio"               => $cola->fInicio,
            "fFin"                  => $cola->fFin,
            "hInicio"               => $cola->hInicio,
            "hFin"                  => $cola->hFin,
            "tipoContingencia"      => intval($cola->tipoContingencia),
            "motivoContingencia"    => $cola->motivoContingencia
        ];

        $comprobante_electronico["identificacion"] = $identificacion;
        $comprobante_electronico["emisor"]      = $emisor;
        $comprobante_electronico["detalleDTE"] = $detalleDTE;
        $comprobante_electronico["motivo"]   = $motivo;


        if (empty($comprobante_electronico)) {
            $cola->codEstado = "10";
            $cola->estado = "Revision";
            $cola->observacionesMsg =  "Rechazado por Documento No Definido";
            $cola->save();
            $tipo_resultado = "danger";
            $mensaje_resultado = 'Rechazado  por Documento No Definido';

        } else {

            //dd($comprobante_electronico);

            //dd($url_seguridad);
            //$emisor = \Session::get('emisor');
            $firma_electronica = [
                "nit" => str_replace('-', '', $emisor["nit"]),
                "activo" => true,
                "passwordPri" => $empresaconti[0]->passwordPri,
                "dteJson" => $comprobante_electronico
            ];
            //return json_encode($firma_electronica);
            try {
                $response = Http::accept('application/json')->post('http://143.198.63.171:8113/firmardocumento/', $firma_electronica);
            } catch (\Throwable $th) {
                $error = [
                    "mensaje" => "Error en Firma de Documento",
                    "error" => $th
                ];
                return  json_encode($error);
            }

            //return $response;
            $objResponse = json_decode($response, true);
            //return json_last_error_msg();
            $objResponse = (array)$objResponse;

            $comprobante_encriptado = $objResponse["body"];

            //dd($comprobante_encriptado);

            $validacion_usuario = [
                "user"  => str_replace('-', '', $emisor["nit"]),
                "pwd"   =>  $empresaconti[0]->pwd
            ];

            //dd($validacion_usuario);
            //dd($empresa["url_contingencia"]);
            if ($this->getTokenMH($id_empresa, $validacion_usuario, $empresaconti[0]->url_credencial, $empresaconti[0]->url_credencial) == "OK") {
                // return 'paso validacion';
                $token = Session::get($id_empresa);
                //return ["token" => $token];

                $comprobante_enviar = [
                    "ambiente"      => $encabezado[0]->ambiente,
                    "version"       => intval($cola->version),
                    "documento"     => $comprobante_encriptado
                ];

                //dd($comprobante_enviar);
                //return $comprobante_enviar;
                try {

                    $response_enviado = Http::withToken($token)->post($empresaconti[0]->url_contingencia, $comprobante_enviar);
                } catch (\Throwable $th) {
                    $error  = [
                        "mensaje" => "Error con Servicios de Hacienda",
                        "erro" => $th
                    ];
                    return json_encode($error);
                }
            } else {
                $response_enviado = $this->getTokenMH($id_empresa, $validacion_usuario, $empresaconti[0]->url_credencial);
            }

            //dd($comprobante);
            if (count($comprobante[0]) > 0) {
                //return json_encode($comprobante);

                //dd(json_encode($credenciales));
                $id_empresa = $emisor["nit"];


                //dd($emisor);
                $objEnviado = json_decode($response_enviado);
                //dd($objEnviado);
                if (isset($objEnviado->estado)) {
                    $estado_envio = $objEnviado->estado;
                    $dateString = $objEnviado->fechaHora;
                    $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
                    $newDateString = $myDateTime->format('Y-m-d H:i:s');
                    //$prueba = gettype($objEnviado->observaciones);
                    //dd($objEnviado->observaciones);
                    $observaciones = implode("<br>", $objEnviado->observaciones);
                    if ($estado_envio == "RECIBIDO") {
                        $cola->codEstado = "02";
                        $cola->estado = "Enviado";
                        // $e->codigoGeneracion = $objEnviado->codigoGeneracion;
                        $cola->fhRecibido = $newDateString;
                        $cola->selloRecibido = $objEnviado->selloRecibido;
                        $cola->observacionesMsg = $observaciones;
                        $cola->estadoHacienda = $estado_envio;
                        $cola->save();
                        $tipo_resultado = "success";
                        $mensaje_resultado = "Procesado con Exito";
                    } else {
                        dd($objEnviado);
                        var_dump($objEnviado);
                        $cola->codEstado = "03";
                        $cola->estado = "Rechazado";
                        $cola->observacionesMsg = $observaciones;
                        $cola->save();
                        $tipo_resultado = "danger";
                        $mensaje_resultado = 'Rechazado';
                        //dd($objEnviado);
                    }
                } else {
                    return var_dump($objEnviado);
                    //break;
                }
                //dd($objEnviado);

                //echo $comprobante_electronico;

                echo '<br>';
            } else {
                $cola->codEstado = "03";
                $cola->estado = "Rechazado";
                $cola->observacionesMsg =  "Rechazado por Eliminacion de Comprobante";
                $cola->save();
                $tipo_resultado = "danger";
                $mensaje_resultado = 'Rechazado  por Eliminacion de Comprobante';
            }
            $i += 1;
        }

        return redirect()->route('factmh.contingencias')
            ->with($tipo_resultado, $mensaje_resultado);
    }

    public function getTokenMH($id_empresa, $credenciales, $url_seguridad)
    {
        //dd('entra a gettoken');
        if (!Session::has($id_empresa)) {

            //dd('No encuentra la variable');
            //return ["mensaje" => "llama  getnewtokemh"];
            $respuesta =  $this->getNewTokenMH($id_empresa, $credenciales, $url_seguridad);
        } else {
            $now = new DateTime('now');
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

    public function muestra_lote($id)
    {
        date_default_timezone_set('America/El_Salvador');
        $dtes = Sale::leftjoin('dte', 'dte.sale_id', '=', 'sales.id')
        ->join('typedocuments', 'typedocuments.id', '=', 'sales.typedocument_id')
        ->where('sales.id_contingencia', $id)
        ->select('sales.created_at',
                'typedocuments.type as tipo_doc',
                'sales.id as numero_factura')
        ->get();
        // dd($dtes);
        return view('dtemh.muestra_lote')
        ->with('dtes', $dtes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contingencia  $contingencia
     * @return \Illuminate\Http\Response
     */
    public function show(Contingencia $contingencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contingencia  $contingencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Contingencia $contingencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contingencia  $contingencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contingencia $contingencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contingencia  $contingencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contingencia $contingencia)
    {
        //
    }
}

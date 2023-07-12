<?php

namespace App\Http\Controllers;

use App\Models\Dte;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturacionElectronicaController extends Controller
{
    public function procesa_cola(){
        date_default_timezone_set('America/El_Salvador');
        ini_set('max_execution_time', '300'); 
        $cola = Dte::where('codEstado', '01')->where('idContingencia', null)->limit(5)->get();
        //$cola = DTE::where('id', 7832)->limit(5)->get();
        //dd($cola);
        //$cola = DTE::where('codEstado', '01')->where('idEmpresa',1)->limit(5)->get();
        $i = 1;
        $total = count($cola);
        $id_empresa = -1;
        foreach ($cola as $e) {
            //dd($e);
            echo '<br>procesando .....'. $i . ' de ' . $total. ' DocEntry '. $e->id_doc .' Codigo de Generacion '. $e->codigoGeneracion. '<br>';
            $consulta = [];
            $comprobante = $this->obtener_comprobante($e->id, $e->docEntry, $e->idEmpresa, 'P', $e->codTransaccion, $e->nmTablaDoc, $e->tipoDoc);
            $comprobante["codigoGeneracion"] = $e->codigoGeneracion;
            $comprobante["nuEnvio"] = intval($e->nuEnvios);
            //var_dump($consulta);
            //dd($comprobante);

            /* Verifica que trae la consulta */
            //dd($consulta);


            //dd($DocumentosRelacionados);
            /* Si la consulta no trae la tabla encabezada continue hace que siga con la proxima interaccion */
            //dd($comprobante["encabezado"]);
            if (empty($comprobante["encabezado"])) {
                $e->codEstado = "10";
                $e->estado = "Revision";
                $e->observacionesMsg =  "Rechazado por Encabezado Vacio";
                $e->save();
                echo 'Rechazado  por Encabezado Vacio <br><br>';
                continue;
            }
             /* Si la consulta no trae la tabla empresa continue hace que siga con la proxima interaccion */
            if (empty($comprobante["empresa"])) {
                $e->codEstado = "10";
                $e->estado = "Revision";
                $e->observacionesMsg =  "Rechazado por Credenciales de Empresa Vacio";
                $e->save();
                echo 'Rechazado  por Credenciales de Empresa Vacio <br><br>';
                continue;
            }

            if (empty($comprobante["detalle"]) and empty($comprobante["cuerpoDocumento"]) and ($e->codTransaccion == '01') and $e->nmTablaDoc != "compras" ) {
                /*$e->codEstado = "03";
                $e->estado = "Rechazado";
                $e->observacionesMsg =  "Rechazo Interno - No hay detalle";
                $e->save();
                echo 'Rechazo Interno - No hay detalle <br><br>';*/
                continue;
            }

            //var_dump($comprobante);
            //dd($e);
            $comprobante_electronico = [];
            //return $comprobante_electronico;
            $cGeneracion = $e->tipoDte;
            if($e->codTransaccion == '02'){
                $cGeneracion = '99';
            }
            //dd($cGeneracion);
            $comprobante_electronico = convertir_json($comprobante, $cGeneracion);
            //return json_encode($comprobante_electronico);
            //dd($comprobante_electronico);
          // var_dump( $comprobante_electronico);
            if(empty($comprobante_electronico)){
                $e->codEstado = "10";
                $e->estado = "Revision";
                $e->observacionesMsg =  "Rechazado por Documento No Definido";
                $e->save();
                echo 'Rechazado  por Documento No Definido <br><br>';

            }else{

            //dd($comprobante_electronico);
            $tipo_documento = $e->tipoDte; // $comprobante[0][0]["cod_tipo_documento"];
            $version = $e->versionJson; // $comprobante[0][0]["version"];
            if($e->codTransaccion == '01'){
                $numero_control = $comprobante_electronico["identificacion"]["numeroControl"];
            }else{
                    $numero_control = 'Anulacion o Contingencia';
            }
            $empresa = $comprobante["empresa"][0];
            $id_empresa = $empresa["id_empresa"];
           //dd($version);
            $ambiente = $empresa["ambiente"];

            //dd($ambiente);

            $url_credencial = $empresa["url_credencial"];
            $url_envio = $empresa["url_envio"];
            if ($e->codTrasaccion == '02'){
                $url_envio = $empresa["url_invalidacion"];
            }

            $url_firmador = $empresa["url_firmador"];
            //dd($empresa);
            //dd($url_credencial);
            //dd($url_envio);
            //dd($url_firmador);
            $firma_electronica = [
                "nit" => $empresa["nit"],
                "activo" => true,
                "passwordPri" => $empresa["passwordPri"],
                "dteJson" => $comprobante_electronico
            ];
            //return  json_encode($firma_electronica);
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
             //dd($objResponse);

             if ($objResponse["status"] == "ERROR") {
                echo $objResponse["status"]. ' '.$objResponse["body"]["codigo"]. ' '. $objResponse["body"]["mensaje"];
                return  ;
             }
            $comprobante_encriptado = $objResponse["body"];
                $validacion_usuario = [
                    "user"  => $empresa["nit"],
                    "pwd"   => $empresa["pwd"]
                ];
                //AlfaAccesoProd2023$
              // dd($validacion_usuario);
               // dd($url_seguridad);
                if ($this->getTokenMH($id_empresa, $validacion_usuario, $url_credencial, $url_credencial) == "OK") {
                   // return 'paso validacion';
                    $token = Session::get($id_empresa);
                    $ambiente = $empresa["ambiente"];
                    //dd($ambiente);
                    //return ["token" => $token];
                    if($e->codTransaccion == "01"){
                        $comprobante_enviar = [
                            "ambiente"      => $ambiente,
                            "idEnvio"       => intval($comprobante["nuEnvio"]),
                            "version"       => intval($version),
                            "tipoDte"       => $tipo_documento,
                            "documento"     => $comprobante_encriptado
                        ];
                    }else{
                        $comprobante_enviar = [
                            "ambiente"      => $ambiente,
                            "idEnvio"       => intval($comprobante["nuEnvio"]),
                            "version"       => intval($version),
                            "documento"     => $comprobante_encriptado
                        ];
                    }

                    //return $comprobante_enviar;
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
                    $response_enviado = $this->getTokenMH($id_empresa,$validacion_usuario, $url_credencial, $url_credencial);
                    if($response_enviado != "OK"){
                        //dd("entro aqui");
                        return response()->json($response_enviado, 400); //json_encode($response_enviado, JSON_PRETTY_PRINT);
                    }
                }

            //dd($response_enviado);
            if(count($comprobante["encabezado"])> 0){
           //return json_encode($comprobante);
                $empresa = $comprobante["empresa"][0];
                $credenciales = [
                   "email"          => $empresa["e_mail"],
                   "password"       => $empresa["pwd"],
                   "remember_me"    => true
                ];
            //dd(json_encode($credenciales));
                $id_empresa = $empresa["nit"];


                    //dd($response_enviado);
                    $objEnviado = json_decode($response_enviado);
                    //return  $response_enviado;
                    //dd($objEnviado);
                    if(isset($objEnviado->estado)){
                    $estado_envio = $objEnviado->estado;
                    $dateString = $objEnviado->fhProcesamiento;
                    $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
                    $newDateString = $myDateTime->format('Y-m-d H:i:s');
                    //$prueba = gettype($objEnviado->observaciones);
                    //dd($objEnviado->observaciones);
                    $observaciones = implode("<br>", $objEnviado->observaciones);
                    if(trim($estado_envio) == "PROCESADO"){
                        $comprobante["respuestaHacienda"] = $objEnviado;
                        $comprobante_electronico["selloRecibido"] = $objEnviado->selloRecibido;
                        $e->codEstado = "02";
                        $e->estado = "Enviado";
                       // $e->codigoGeneracion = $objEnviado->codigoGeneracion;
                        $e->fhRecibido = $newDateString;
                        $e->selloRecibido = $objEnviado->selloRecibido;
                        $e->estadoHacienda = $objEnviado->estado;
                       // $e->nuEnvios = 1;
                        $e->clasificaMsg = $objEnviado->clasificaMsg;
                        $e->codigoMsg =  $objEnviado->codigoMsg;
                        $e->descripcionMsg = $objEnviado->descripcionMsg;
                        $e->observacionesMsg = $observaciones;
                        $e->jsonDte = json_encode($comprobante_electronico);
                        $e->jsonDatos = json_encode($comprobante);
                        $e->save();
                        //dd('llega aqui');
                        if($ambiente == '01'){
                             $this->envia_correo($e->id, $e->docEntry, $e->idEmpresa, $e->codTransaccion,$e->nmTablaDoc, $e->tipoDte);
                        }

                        echo 'Procesado con Exito Numero de Control ' . $numero_control . '<br><br>';

                    }else{
                       // dd($observaciones);
                        $e->codEstado = "03";
                        $e->estado = "Rechazado";
                        $e->descripcionMsg = $objEnviado->descripcionMsg;
                        $e->observacionesMsg = $observaciones;
                        $e->nuEnvios = $e->nuEnvios + 1;
                        $e->jsonDte = json_encode($comprobante_electronico);
                        $e->jsonDatos = json_encode($comprobante);
                        $e->save();
                        echo '<span style="color:red;">Rechazado Numero de Control '. $numero_control . '<br>';
                        echo $observaciones . '<br>';
                        echo  $objEnviado->descripcionMsg .'</span><br><br>';
                    }
                }else{
                    return var_dump($objEnviado);
                    break;
                }
                    //dd($objEnviado);

            //echo $comprobante_electronico;

            echo '<br>';
            }else{
                $e->codEstado = "03";
                $e->estado = "Rechazado";
                $e->observacionesMsg =  "Rechazado por Eliminacion de Comprobante";
                $e->save();
                echo 'Rechazado  por Eliminacion de Comprobante <br><br>';
            }
           $i += 1;
        }
        }
        echo 'Proceso Terminado <br><br>';
        echo 'Ultima Actualización '. date('d/m/Y h:i A'). ' <br>';
    }



    public function obtener_comprobante($id, $docEntry, $idEmpresa,$opcion, $codTrasaccion = "01", $nombreTable, $tipoDoc){
        //dd($codTrasaccion);
       // dd($tipoDoc);
        $resultado = [];
        $encabezado = [];
        $detalle = array();
        $empresa = array();
        $terceros = array();
        $receptor = array();
        $cuerpoDocumento = array();
        $resumen = array();
        $resumenTributos = array();
        $extension = array();
        $DocumentosRelacionados = array();
        $Notificaciones = array();
        $resumenTributo = array();
        switch ($opcion) {
            case 'P':
                switch ($nombreTable){
                    case 'facturas':
                       // dd($idEmpresa);
                       // if ($idEmpresa == 1) {
                            $resultado = get_multi_result_set("sqlsrv", "exec dbo.sp_sele_comprobante_electronico $id, $docEntry, $idEmpresa, '$codTrasaccion','$tipoDoc'");
                        //} else {
                           // $resultado = get_multi_result_set("sqlsrv", "exec dbo.sp_sele_comprobante_electronico_test $id, $docEntry, $idEmpresa, '$codTrasaccion', '$tipoDoc'");
                        //}
                        break;
                }
                break;
            case 'I':

                switch ($nombreTable){
                    case 'facturas':
                       // dd($idEmpresa);
                       // if ($idEmpresa == 1) {
                            $resultado = get_multi_result_set("sqlsrv", "exec dbo.spRpt_imp_comprobante_electronico $docEntry, $idEmpresa");
                        //} else {
                           // $resultado = get_multi_result_set("sqlsrv", "exec dbo.sp_sele_comprobante_electronico_test $id, $docEntry, $idEmpresa, '$codTrasaccion', '$tipoDoc'");
                        //}
                        break;
                    case 'ComLiq':
                        //dd($idEmpresa);
                        $resultado = get_multi_result_set("sqlsrv", "exec dbo.sp_sele_Dte_CLQ  $id,$docEntry, $idEmpresa");
                        //dd($resultado);
                        break;
                    case 'compras':
                        //dd($idEmpresa);
                        $resultado = get_multi_result_set("sqlsrv", "exec dbo.sp_sele_Dte_Compras $id, $docEntry, $idEmpresa");
                        //dd($resultado);
                        break;
                }

                break;
            default:
                # code...
                break;
        }
        //dd($resultado);
        foreach ($resultado as $c) {
                if (isset($c[0]["NmTabla"])) {
                    # code...


                    switch ($c[0]["NmTabla"]) {
                        case 'Encabezado':
                            //dd($c[0]["NmTabla"]);
                            $encabezado = $c;
                            break;
                        case 'Detalle':
                            $detalle = $c;
                            break;
                        case 'Empresa':
                            $empresa = $c;
                            break;
                        case 'Terceros':
                            $terceros = $c;
                            break;
                        case 'Receptor':
                            $receptor = $c;
                            break;
                        case 'CuerpoDocumento':
                            $cuerpoDocumento = $c;
                            break;
                        case 'resumen':
                            $resumen = $c;
                            break;
                        case 'resumenTributos':
                            $resumenTributos = $c;
                            break;
                        case 'extension':
                            $extension = $c;
                            break;
                        case 'DocumentosRelacionados':
                            $DocumentosRelacionados = $c;
                            break;
                        case 'Notificaciones':
                            $Notificaciones = $c;
                            break;
                        case 'ResumenTributos':
                            $resumenTributo = $c;
                            break;
                        default:
                            # code...
                            break;
                    }
                }
                # code...
            }
            $comprobante= [];
            $comprobante["encabezado"] = $encabezado;
            $comprobante["detalle"] = $detalle;
            $comprobante["empresa"] = $empresa;
            $comprobante["terceros"] = $terceros;
            $comprobante["receptor"] = $receptor;
            $comprobante["cuerpoDocumento"] = $cuerpoDocumento;
            $comprobante["resumen"] = $resumen;
            $comprobante["resumenTributos"] = $resumenTributos;
            $comprobante["extension"] = $extension;
            $comprobante["Documentosrelacionados"] = $DocumentosRelacionados;
            $comprobante["Notificaciones"] = $Notificaciones;
            $comprobante["resumenTributo"] = $resumenTributo;
          // dd($comprobante);
        return $comprobante;
    }
}

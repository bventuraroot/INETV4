<?php

use PhpParser\Node\Stmt\Foreach_;

if (!function_exists('get_multi_result_set')) {
    function get_multi_result_set($conn, $statement)
    {
        $results = [];
        $pdo = DB::connection($conn)->getPdo();
        $result = $pdo->prepare($statement);
        $result->execute();
        do {
            $resultSet = [];
            foreach ($result->fetchall(PDO::FETCH_ASSOC) as $res) {
                array_push($resultSet, $res);
            }
            array_push($results, $resultSet);
        } while ($result->nextRowset());

        return $results;
    }
}

if (!function_exists('CerosIzquierda')) {
    function CerosIzquierda($cadena, $numero)
    {
        $aux = str_pad($cadena, $numero, "0", STR_PAD_LEFT);
        return $aux;
    }
}

if (!function_exists('codigoQR')) {
    function codigoQR($ambiente, $codigo, $fecha)
    {
        $url = 'https://webapp.dtes.mh.gob.sv/consultaPublica?ambiente=' . $ambiente . '&codGen=' . $codigo . '&fechaEmi=' . date('Y-m-d', strtotime($fecha));
        return (string)QrCode::size(100)->generate($url);

    }
}

if (!function_exists('urlCodigoQR')) {
    function urlCodigoQR($ambiente, $codigo, $fecha)
    {
        return 'https://webapp.dtes.mh.gob.sv/consultaPublica?ambiente=' . $ambiente . '&codGen=' . $codigo . '&fechaEmi=' . date('Y-m-d', strtotime($fecha));
    }
}

if (!function_exists('FEstatus')) {
    function FEstatus($estatus)
    {
        return ($estatus ==1)? 'Activo' : 'Inactivo';
    }
}

if (!function_exists('Frol')) {
    function Frol($rol)
    {
        switch ($rol) {
            case '1':
               $rol_name = "Caja";
                break;
            case '2':
                $rol_name = "Supervisor";
                break;
            case '3':
                $rol_name = "Administrador";
                break;
            default:
                # code...
                break;
        }
        return $rol_name;
    }
}

if (!function_exists('FNumero')) {
    function FNumero($numero)
    {
        return number_format($numero, 2, '.', ',');
    }
}
if (!function_exists('numeroDTE')) {
    function numeroDTE($numero, $tipo, $establecimiento, $cod_establecimiento)
    {
        $numero_documento = CerosIzquierda($numero,15);
        $tipo_documento = $tipo;
        $caja = $cod_establecimiento;
        $tipo_establecimiento = CerosIzquierda($establecimiento, 4);
        return "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento;
    }
}

if (!function_exists('Tipo_Establecimiento')) {
    function Tipo_Establecimiento($numero)
    {

        switch ($numero) {
            case '01':
                $tipo = 'Sucursal';
                break;
            case '02':
                $tipo = 'Casa matriz';
                break;
            case '04':
                $tipo = 'Bodega';
                break;
            case '07':
                $tipo = 'Predio y/o patio';
                break;
            case '20':
                $tipo = 'Otro';
                break;
            default:
                $tipo = 'Sucursal / Agencia';
                break;
        }
        return $tipo;
    }
}

if (!function_exists('Send_Mail')) {
    function Send_Mail($to_name, $to_email,$to_cc, $nombre, $data, $subject, $pdf)
    {
        try {
        //$data = array('name' => $nombre, "p" => $body);


        $envio = Mail::send(['html' => 'emails.comprobante_electronico'], $data, function ($message) use ($to_name, $to_email, $to_cc, $subject,$pdf) {
            $message->to($to_email, $to_name)
                ->cc(env('MAIL_CC', ''))
                ->subject($subject)
                ->attachData($pdf->output(), "comprobante.pdf");
            $message->from(env('MAIL_FROM_ADDRESS',''),env('MAIL_FROM_NAME', ''));
        });
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        //dd($envio);
        return $envio;
    }
}

if (!function_exists('enviar_correo_prueba')) {
    function enviar_correo_prueba($to_name, $to_email,$to_cc, $data, $subject)
    {
        try {
        //$data = array('name' => $nombre, "p" => $body);
        //dd($data);

        $envio = Mail::send(['html' => 'emails.prueba_correo'], $data, function ($message) use ($to_name, $to_email, $to_cc, $subject) {
            $message->to($to_email, $to_name)
                ->cc($to_cc)
                ->subject($subject);
            $message->from(env('MAIL_FROM_ADDRESS',''),env('MAIL_FROM_NAME', ''));
        });
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            return ;
        }
        //dd($envio);
        return $envio;
    }
}


if (!function_exists('convertir_json')) {
    function convertir_json($compro_procesar, $codTransaccion="01")
    {
        //var_dump($compro_procesar);
        $compro = $compro_procesar;
        //dd(is_array($compro));
        //dd($compro["documento"][0]["tipodocumento"]);
        if ($codTransaccion=="02") {
            $tipo_comprobante = $compro["documento"][0]["tipodocumento"];
        } else if($codTransaccion=="05") {
            $tipo_comprobante = $compro["documento"][0]["tipodocumento"];
        } else {
            $tipo_comprobante = $compro["documento"][0]->tipodocumento;
        }
        //dd($tipo_comprobante);
        $retorno = [];
        $uuid_generado = strtoupper(Str::uuid()->toString());
        //$retorno=[$compro, $uuid_generado];
        switch ($tipo_comprobante) {
            case '03': //CRF
                $retorno = crf($compro, $uuid_generado);
                break;
            case '01': //FAC
                $retorno = fac($compro, $uuid_generado);
                break;
            case '05':  //NCR
                $retorno = ncr($compro, $uuid_generado);; // ncr($compro, $uuid_generado);
                break;
            case '08':  //CLQ
                $retorno = []; //clq($compro, $uuid_generado);
                break;
            case '11':  //FEX
                $retorno = fex($compro, $uuid_generado);
                break;
            case '14':  //FSE
                $retorno = []; //$retorno = fse($compro, $uuid_generado);
                break;
            case '99':
                $retorno = fan($compro, $uuid_generado);
                break;

            default:
                $retorno = [];
                break;
        }
        return $retorno;
    }
}


if (!function_exists('crf')) {
    function crf($comprobante_procesar, $uuid_generado)
    {
        $comprobante = [];
        $encabezado = $comprobante_procesar["documento"][0];
        $emisor_data = $comprobante_procesar["emisor"];
        $cliente = $comprobante_procesar["cliente"];
        $totales = $comprobante_procesar["totales"];
        $cuerpo =  $comprobante_procesar["detalle"];
        //dd($emisor_data);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado->actual, 15);
        $tipo_documento = $encabezado->tipodocumento;
        $caja = "0001";
        $tipo_establecimiento = CerosIzquierda($emisor_data[0]->tipoEstablecimiento, 4);
        $empresa = $comprobante_procesar["emisor"];
        $identificacion = [
            "version"           => intval($encabezado->versionJson),
            "ambiente"          => $encabezado->ambiente,
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContin"      => null,
            "fecEmi"            => date('Y-m-d'), // "2022-07-23", //$encabezado["fecEmi"],    //Cambiar
            "horEmi"            => date("H:i:s"),      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];

        $documentoRelacionado = null;

        $direccion_emisor = [
            "departamento"  => $emisor_data[0]->departamento,
            "municipio"     => $emisor_data[0]->municipio,
            "complemento"   => $emisor_data[0]->direccion
        ];
        //dd($emisor_data);
        $emisor = [
            "nit"                   => str_replace('-', '', $emisor_data[0]->nit),
            "nrc"                   => str_replace('-', '', $emisor_data[0]->ncr),
            "nombre"                => trim($emisor_data[0]->nombre),
            "codActividad"          => $emisor_data[0]->codActividad,
            "descActividad"         => $emisor_data[0]->descActividad,
            "nombreComercial"       => $emisor_data[0]->nombreComercial,
            "tipoEstablecimiento"   => $emisor_data[0]->tipoEstablecimiento,
            "direccion"             => $direccion_emisor,
            "telefono"              => $emisor_data[0]->telefono,
            "correo"                 => $emisor_data[0]->correo,
            "codEstableMH"          => $emisor_data[0]->codEstableMH,
            "codEstable"            => $emisor_data[0]->codEstable,
            "codPuntoVentaMH"       => $emisor_data[0]->codPuntoVentaMH,
            "codPuntoVenta"         => $emisor_data[0]->codPuntoVenta
        ];

        //dd($emisor);

        $direccion_receptor = [];
        $direccion_receptor = [
            "departamento"  => $cliente[0]->departamento,
            "municipio"     => $cliente[0]->municipio,
            "complemento"   => $cliente[0]->direccion
        ];
        if (!empty($cliente[0]->codActividad)) {
            if (strlen($cliente[0]->codActividad) <= 4)
            {$codeActivity = '0' . $cliente[0]->codActividad;}
            else{ $codeActivity = $cliente[0]->codActividad; }
        }
        $receptor = [
            "nit"                   => str_replace("-", "", $cliente[0]->nit),
            "nrc"                   => str_replace("-", "", $cliente[0]->ncr),
            "nombre"                => $cliente[0]->nombre,
            "codActividad"          => $codeActivity,
            "descActividad"         => $cliente[0]->descActividad,
            "nombreComercial"       => $cliente[0]->nombreComercial,
            "direccion"             => $direccion_receptor

        ];
        //dd($receptor);
        if ($cliente[0]->telefono != '') {
            $receptor["telefono"] = $cliente[0]->telefono;
        }
        if ($cliente[0]->correo != '') {
            $receptor["correo"] = $cliente[0]->correo;
        }

        $ventaTercero = null;

        if (isset($comprobante_procesar[3][0])) {
            // dd($comprobante_procesar[2]);
            $ventaTercero = [
                "nit"       => $comprobante_procesar[3][0]["nit"],
                "nombre"    => $comprobante_procesar[3][0]["nombre"],

            ];
        }

        $otrosDocumentos = null;

        $codigos_tributos = [];
        $i = 0;

        foreach ($cuerpo as $item) {
            # code...
            //dd($item);
            $i += 1;
            $tributos_properties_items_cuerpoDocumento = array();

            if ($item->iva != 0 and count($codigos_tributos) == 0) {
                $codigos_tributos = [
                    "codigo"        =>  "20",
                    "descripcion"   =>  "Impuesto al Valor Agregado 13%",
                    "valor"         => round((float)$item->iva, 2)
                ];
            } else {
                if ($item->iva != 0 and count($codigos_tributos) > 0) {
                    $iva = round((float)($codigos_tributos["valor"] + $item->iva), 2);
                    $codigos_tributos["valor"] = (float)$iva;
                }
            }

            $tributos_properties_items_cuerpoDocumento = ($item->iva != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i,
                "tipoItem"          => intval($item->tipo_item),  //Bienes y Servicios
                "numeroDocumento"   => null,
                "cantidad"          => intval($item->cantidad),
                "codigo"            => "P0".$item->id_producto,
                "codTributo"        => null,
                "uniMedida"         => intval($item->uniMedida),
                "descripcion"       => $item->descripcion,


                "precioUni"         => (float)($item->precio_unitario),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)($item->no_sujetas),
                "ventaExenta"       => (float)($item->exentas),
                "ventaGravada"      => round((float)($item->gravadas),2),
                "tributos"          => ($item->gravadas != 0) ? ["20"] : null,
                "psv"               => (float)"0.00",
                "noGravado"         => (float)$item->no_imponible
            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }
        //dd($items_cuerpoDocumento);
        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];

        $properties_items_pagos = [];
        //contado
        if ($totales["condicionOperacion"] == "01") {
            $properties_items_pagos[] = [
                "codigo"        => "01",
                "montoPago"     => round((float)($totales["totalPagar"]- $totales["reteRenta"]),2),
                "referencia"    => null,
                "plazo"         => null,
                "periodo"       => null
            ];
        }

        //credito
        if ($totales["condicionOperacion"] == "02") {
            $properties_items_pagos[] = [
                "codigo"        => "13",
                "montoPago"     => (float)"0.00",
                "referencia"    => "Credito",
                "plazo"         => "01",
                "periodo"       => 15
            ];
        }

        //tarjeta
        if ($totales["condicionOperacion"] == "03") {
            $properties_items_pagos[] = [
                "codigo"        => "03",
                "montoPago"     => round((float)$totales["totalPagar"],2),
                "referencia"    => "Otro",
                "plazo"         => null,
                "periodo"       => null
            ];
        }
        /* if($encabezado["total_iva"] != 0){
        $codigos_tributos= [
            "codigo"        => "20",
            "descripcion"   => "Impuesto al Valor Agregado 13",
            "valor"         => (float)$encabezado["total_iva"]
        ];
        }*/

        $pagos = $properties_items_pagos;
        if (count($codigos_tributos) > 0) {
            if ($totales["totalGravada"] * 0.13 <> $codigos_tributos["valor"]) {
                $codigos_tributos["valor"] = round((float)($totales["totalGravada"] * 0.13),  2);
                // $codigos_tributos["valor"] = bcdiv($encabezado["tot_gravado"] * 0.13,1,2);

            }
        }

        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);
        //dd($encabezado);
        $resumen = [
            "totalNoSuj"            => (float)$totales["totalNoSuj"],
            "totalExenta"           => (float)$totales["totalExenta"],
            "totalGravada"          => round((float)$totales["totalGravada"],2),
            "subTotalVentas"        => (float)round((float)$totales["subTotalVentas"],2) ,
            "descuNoSuj"            => (float)$totales["descuNoSuj"],
            "descuExenta"           => (float)$totales["descuExenta"],
            "descuGravada"          => (float)$totales["descuGravada"],
            "porcentajeDescuento"   => (float)$totales["porcentajeDescuento"],
            "totalDescu"            => (float)$totales["totalDescu"],
            "tributos"              => (!empty($codigos_tributos)) ? [$codigos_tributos] : null,
            "subTotal"              => (float)($totales["subTotal"]- $totales["totalDescu"]),
            "ivaPerci1"             => (float)$totales["ivaRete1"],
            "ivaRete1"              => (float)$totales["ivaRete1"],
            "reteRenta"             => (float)$totales["reteRenta"],
            "montoTotalOperacion"   => round((float)($totales["montoTotalOperacion"] + $totales["totalIva"]), 2), //(float)$totales["montoTotalOperacion"], //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado"        => (float)$totales["totalNoGravado"],
            "totalPagar"            => round((float)($totales["totalPagar"]- $totales["reteRenta"]),2),
            "totalLetras"           => $totales["totalLetras"],
            "saldoFavor"            => (float)$totales["saldoFavor"],
            "condicionOperacion"    => (float)$totales["condicionOperacion"],
            "pagos"                 => $pagos,
            "numPagoElectronico"    => ""
        ];

        //dd($resumen);

        $es_mayor = ($totales["totalPagar"] >= 11428.57);

        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado->NombreUsuario : null,
            "docuEntrega"   => ($es_mayor) ? str_replace("-", "", $encabezado->docUser) : null,
            "nombRecibe"    => ($es_mayor) ? $cliente[0]->nombre : null,
            "docuRecibe"    => ($es_mayor) ? str_replace("-", "", $cliente[0]->nit) : null,
            "observaciones" => ($es_mayor) ? null : null,
            "placaVehiculo" => ($es_mayor) ? null : null
        ];

        $apendice = null;



        $comprobante["documentoRelacionado"]     = $documentoRelacionado;
        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;
        $comprobante["otrosDocumentos"]          = $otrosDocumentos;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;
        //echo '<br>'. var_dump($comprobante) . '<br>';
        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('fac')) {
    function fac($comprobante_procesar, $uuid_generado){

        $comprobante = [];
        $encabezado = $comprobante_procesar["documento"][0];
        $emisor_data = $comprobante_procesar["emisor"];
        $cliente = $comprobante_procesar["cliente"];
        $totales = $comprobante_procesar["totales"];
        //dd($emisor_data);
        //dd($encabezado);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado->actual, 15);
        $tipo_documento = $encabezado->tipodocumento;
        //dd($encabezado);
        $caja = "0001";
        $testablecimiento = "1";
        $tipo_establecimiento = CerosIzquierda($testablecimiento,4);//$encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["emisor"];
        $identificacion = [
            "version"           => intval($encabezado->versionJson),
            "ambiente"          => $encabezado->ambiente,
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContin"      => null,
            "fecEmi"            => date('Y-m-d'), //"2022-07-20", // $encabezado["fecEmi"],    //Cambiar
            "horEmi"            => date("H:i:s"), //$encabezado["horEmi"],      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];

        $documentoRelacionado = null;
        //dd($emisor_data);
        $direccion_emisor = [
            "departamento"  => $emisor_data[0]->departamento,
            "municipio"     => $emisor_data[0]->municipio,
            "complemento"   => $emisor_data[0]->direccion
        ];

        $emisor = [
            "nit"                   => str_replace('-','', $emisor_data[0]->nit),
            "nrc"                   => str_replace('-','', $emisor_data[0]->ncr),
            "nombre"                => trim($emisor_data[0]->nombre),
            "codActividad"          => $emisor_data[0]->codActividad,
            "descActividad"         => $emisor_data[0]->descActividad,
            "nombreComercial"       => $emisor_data[0]->nombreComercial,
            "tipoEstablecimiento"   => $emisor_data[0]->tipoEstablecimiento,
            "direccion"             => $direccion_emisor,
            "telefono"              => $emisor_data[0]->telefono,
            "codEstableMH"          => $emisor_data[0]->codEstableMH,
            "codEstable"            => $emisor_data[0]->codEstable,
            "codPuntoVentaMH"       => $emisor_data[0]->codPuntoVentaMH,
            "codPuntoVenta"         => $emisor_data[0]->codPuntoVenta,
            "correo"                => $emisor_data[0]->correo,
        ];

        $direccion_receptor = [];
        //dd($cliente);
        $direccion_receptor = [
            "departamento"  => $cliente[0]->departamento,
            "municipio"     => $cliente[0]->municipio,
            "complemento"   => $cliente[0]->direccion
        ];

        if (!empty($cliente[0]->codActividad)) {
            if (strlen($cliente[0]->codActividad) <= 4)
            {$codeActivity = '0' . $cliente[0]->codActividad;}
            else{ $codeActivity = $cliente[0]->codActividad; }
        }

        $receptor = [
            "tipoDocumento"         => ($cliente[0]->ncr == '' or is_null($cliente[0]->ncr) ) ? $cliente[0]->tipoDocumento : "36",
            "numDocumento"          => ($cliente[0]->ncr == '' or is_null($cliente[0]->ncr)) ? str_replace("-","",$cliente[0]->numDocumento) : str_replace("-", "", $cliente[0]->nit),
            "nrc"                   => ($cliente[0]->ncr == 'N/A') ? null : str_replace("-","",$cliente[0]->ncr),
            "nombre"                => $cliente[0]->nombre,
            "codActividad"          => ($cliente[0]->codActividad == '') ? null : $codeActivity,
            "descActividad"         => ($cliente[0]->descActividad == '') ? null : $cliente[0]->descActividad,

        ];

        if ($cliente[0]->codPais == '9300') {

            $receptor["direccion"] = $direccion_receptor;
        }
        //dd($cliente);
        if ($cliente[0]->telefono != '') {
            $receptor["telefono"] = $cliente[0]->telefono;
        }
        if ($cliente[0]->correo != '') {
            $receptor["correo"] = $cliente[0]->correo;
        }
        $otrosDocumentos = null;

        $ventaTercero = null;
        if (isset($comprobante_procesar[3][0])) {
            // dd($comprobante_procesar[2]);
            $ventaTercero = [
                "nit"       => $comprobante_procesar[3][0]["nit"],
                "nombre"    => $comprobante_procesar[3][0]["nombre"],

            ];
        }
        $codigos_tributos = [];
        $i = 0;
        //return var_dump($cuerpo);
        //echo '<br>';
        foreach ($cuerpo as $item) {
            //var_dump($item);
            // echo '<br>';
            $i += 1;
            // echo $i.'<br>';
            # code...
            //dd($item);
            $tributos_properties_items_cuerpoDocumento = array();

            $tributos_properties_items_cuerpoDocumento = ($item->iva != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $iva_calculadofac = round(($item->iva/$item->cantidad),2);

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i, //intval($item["corr"]),
                "tipoItem"          => intval($item->tipo_item),
                "numeroDocumento"   => null,
                "cantidad"          => intval($item->cantidad),
                "codigo"            => "P0".$item->id_producto,
                "codTributo"        => null,
                "uniMedida"         => intval($item->uniMedida),
                "descripcion"       => $item->descripcion,


                "precioUni"         => round((float)($item->precio_unitario + $iva_calculadofac), 2),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)$item->no_sujetas,
                "ventaExenta"       => (float)$item->exentas,
                "ventaGravada"      => round((float)($item->gravadas + $item->iva), 2),
                "tributos"          =>  null,
                "psv"               => (float)"0.00",
                "noGravado"         => (float)$item->no_imponible,
                "ivaItem"           => round((float)$item->iva,2)
            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }
        //dd($cuerpo);
        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];



        $properties_items_pagos = [];
        //contado
        //dd($totales);
        if ($totales["condicionOperacion"] == "01") {
            $properties_items_pagos[] = [
                "codigo"        => "01",
                "montoPago"     => round((float)($totales["totalPagar"]- $totales["reteRenta"]),2),
                "referencia"    => null,
                "plazo"         => null,
                "periodo"       => null
            ];
        }
        //credito
        if ($totales["condicionOperacion"] == "02") {
            $properties_items_pagos[] = [
                "codigo"        => "13",
                "montoPago"     => (float)"0.00",
                "referencia"    => "Credito",
                "plazo"         => "01",
                "periodo"       =>  15 //intval($encabezado["periodo"])
            ];
        }
        //tarjeta
        if ($totales["condicionOperacion"] == "03") {
            $properties_items_pagos[] = [
                "codigo"        => "03",
                "montoPago"     => round((float)($totales["totalPagar"]- $totales["reteRenta"]),2),
                "referencia"    => "Otro",
                "plazo"         => null,
                "periodo"       => null
            ];
        }

        $pagos = $properties_items_pagos;



        $resumen = [
            "totalNoSuj"            => (float)$totales["totalNoSuj"],
            "totalExenta"           => (float)$totales["totalExenta"],
            "totalGravada"          => round((float)($totales["totalGravada"] + $totales["totalIva"]), 2),
            "subTotalVentas"        => round((float)($totales["subTotal"] + $totales["totalIva"]), 2),
            "descuNoSuj"            => (float)$totales["descuNoSuj"],
            "descuExenta"           => (float)$totales["descuExenta"],
            "descuGravada"          => (float)$totales["descuGravada"],
            "porcentajeDescuento"   => (float)$totales["porcentajeDescuento"],
            "totalDescu"            => (float)$totales["totalDescu"],
            "tributos"              => $codigos_tributos,
            "subTotal"              => round((float)($totales["subTotal"] + $totales["totalIva"]), 2),

            "ivaRete1"              => (float)$totales["ivaRete1"],
            "reteRenta"             => (float)$totales["reteRenta"],
            "montoTotalOperacion"   => round((float)($totales["subTotal"] + $totales["totalIva"]), 2), //(float)$totales["montoTotalOperacion"],
            "totalNoGravado"        => (float)$totales["totalNoGravado"],
            "totalPagar"            => round((float)($totales["totalPagar"] - $totales["reteRenta"]),2),
            "totalLetras"           => $totales["totalLetras"],
            "totalIva"              => round((float)$totales["totalIva"],2),
            "saldoFavor"            => (float)$totales["saldoFavor"],
            "condicionOperacion"    => (float)$totales["condicionOperacion"],
            "pagos"                 => $pagos,
            "numPagoElectronico"    => ""
        ];

        $es_mayor = ($totales["totalPagar"] >= 200);

        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado->NombreUsuario : null,
            "docuEntrega"   => ($es_mayor) ? str_replace("-", "", $encabezado->docUser) : null,
            "nombRecibe"    => ($es_mayor) ? $cliente[0]->nombre : null,
            "docuRecibe"    => ($es_mayor) ? str_replace("-", "", $cliente[0]->nit) : null,
            "observaciones" => ($es_mayor) ? null : null,
            "placaVehiculo" => ($es_mayor) ? null : null
        ];

        $apendice[] = [
            "campo"         => "vendedor",
            "etiqueta"      => "Vendedor",
            "valor"         => $encabezado->hechopor
        ];

        $apendice[] = [
            "campo"         => "cliente",
            "etiqueta"      => "Cliente",
            "valor"         => $cliente[0]->idcliente
        ];


        $comprobante["documentoRelacionado"]     = $documentoRelacionado;
        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;
        $comprobante["otrosDocumentos"]          = $otrosDocumentos;
       // if (isset($comprobante_procesar[3][0])) {
        $comprobante["ventaTercero"]             =  $ventaTercero;
        //}
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = null;
        //$comprobante2 = [];
        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('fan')) {
    function fan($comprobante_procesar, $uuid_generado){
        date_default_timezone_set('America/El_Salvador');
        $comprobante = [];
        $encabezado = $comprobante_procesar["documento"][0];
        $emisor_data = $comprobante_procesar["emisor"];
        $cliente = $comprobante_procesar["cliente"];
        //dd($encabezado);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["tipoDteOriginal"];
        $caja = "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $encabezado;
        $identificacion = [
            "version"           => intval($encabezado["version"]),
            "ambiente"          => $empresa["ambiente"],
            "codigoGeneracion"  => $uuid,
            "fecAnula" => date("Y-m-d"),  // Fecha actual en formato "año-mes-día"
            "horAnula" => date("H:i:s"),
            //"fecAnula"          => $encabezado["fecAnulado"],
            //"horAnula"          => $encabezado["horAnulado"]
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];
        $documentoRelacionado = null;
        //dd($emisor_data);
        $emisor = [
            "nit"                   => str_replace('-', '', $emisor_data[0]->nit),
            "nombre"                => trim($emisor_data[0]->nombre),
            "tipoEstablecimiento"   => $emisor_data[0]->tipoEstablecimiento,
            "nomEstablecimiento"    => "casa matriz", //cambiar
            "codEstableMH"          => $emisor_data[0]->codEstableMH,
            "codEstable"            => $emisor_data[0]->codEstable,
            "codPuntoVentaMH"       => $emisor_data[0]->codPuntoVentaMH,
            "codPuntoVenta"         => $emisor_data[0]->codPuntoVenta,
            "telefono"              => $emisor_data[0]->telefono,
            "correo"                => $emisor_data[0]->correo,


        ];

        //dd($encabezado);
        $documento = [
            "tipoDte"               => $encabezado["tipoDteOriginal"],
            "codigoGeneracion"      => $encabezado["codigoGeneracionOriginal"],
            "selloRecibido"         => $encabezado["selloRecibidoOriginal"],
            "numeroControl"         => $encabezado["id_doc"],
            "fecEmi"                => $encabezado["fecEmiOriginal"],
            "montoIva"              => (float)($encabezado["total_iva"]),
            "codigoGeneracionR"     => null,
            "tipoDocumento"         => "36",
            "numDocumento"          => $encabezado["numDocumento"],
            "nombre"                => $encabezado["nombre"],

        ];
            $documento["telefono"] = $cliente[0]->telefono;


            $documento["correo"] = $cliente[0]->correo;

       $motivo = [
            "tipoAnulacion"         => intval("2"),
            "motivoAnulacion"       => "Rescindir de la operacion realizada.",
            "nombreResponsable"     => $encabezado["nombre"],
            "tipDocResponsable"    => "13",
            "numDocResponsable"     => "05095294-8", ///$encabezado["docuEntrega"],
            "nombreSolicita"        => $cliente[0]->nombre,
            "tipDocSolicita"        => "36",
            "numDocSolicita"        => str_replace('-','', $cliente[0]->numDocumento)

       ];


        $comprobante["emisor"]      = $emisor;
        $comprobante["documento"]   = $documento;
        $comprobante["motivo"]      = $motivo;

        //$comprobante2 = [];
        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('clq')) {
    function clq($comprobante_procesar, $uuid_generado)
    {
        date_default_timezone_set('America/El_Salvador');
        $comprobante = [];

        //dd($comprobante_procesar);
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($encabezado);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["identificacionNumeroControl"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"];// "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $identificacion = [
            "version"           => intval($encabezado["identificacionVersion"]),
            "ambiente"          => $encabezado["nu_doc"],
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento,
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "fecEmi"            => date('Y-m-d'), //"2022-07-20", // $encabezado["fecEmi"],    //Cambiar
            "horEmi"            => $encabezado["horEmi"],      //Cambiar
            "tipoMoneda"        => $encabezado["identificaciontipoMoneda"]            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];

        $direccion_emisor = [
            "departamento"  => $encabezado["departamento_emisor"],
            "municipio"     => $encabezado["municipio_emisor"],
            "complemento"   => $encabezado["complemento_emisor"]
        ];

        $emisor = [
            "nit"                   => str_replace("-","",$encabezado["nit_emisor"]),
            "nrc"                   => str_replace("-", "",trim($encabezado["nrc_emisor"])),
            "nombre"                => $encabezado["nombre_empresa"],
            "codActividad"          => $encabezado["emisorCodActividad"],
            "descActividad"         => $encabezado["descActividad"],
            "nombreComercial"       => $encabezado["nombreComercial"],
            "tipoEstablecimiento"   => $encabezado["emisorEstablecimiento"],
            "direccion"             => $direccion_emisor,
            "telefono"              => $encabezado["telefono"],

            "codEstableMH"          => null,
            "codEstable"            => null,
            "codPuntoVentaMH"       => null,
            "codPuntoVenta"         => null,
            "correo"                => $encabezado["correo"],
        ];

        $tblReceptor = $comprobante_procesar["receptor"][0];
        //dd($tblReceptor);
        $direccion_receptor = [
            "departamento"  => $tblReceptor["direccionDepartamento"],
            "municipio"     => $tblReceptor["direccionMunicipio"],
            "complemento"   => $tblReceptor["direccionComplemento"]
        ];

        $receptor = [
            "nit"                   => str_replace("-", "",$tblReceptor["nit"]),
            "nrc"                   => str_replace("-","",trim($tblReceptor["nrc"])),
            "nombre"                => $tblReceptor["nombre"],
            "codActividad"          => $tblReceptor["codActividad"],
            "descActividad"         => $tblReceptor["descActividad"],
            "nombreComercial"       => $tblReceptor["nombreComercial"],
            "direccion"             => $direccion_receptor

        ];

        if ($encabezado["receptorTelefono"] != '') {
            $receptor["telefono"] = $encabezado["receptorTelefono"];
        }
        if ($encabezado["receptorCorreo"] != '') {
            $receptor["correo"] = $encabezado["receptorCorreo"];
        }

        $otrosDocumentos = null;

        $codigos_tributos = [];
        $i = 0;

        $cuerpo = $comprobante_procesar["cuerpoDocumento"];
        //dd($cuerpo[5]);
        $items_cuerpoDocumento = [];

        $tblCodigosTributos = $comprobante_procesar["resumenTributos"];

        //dd($tblCodigosTributos);
        foreach($tblCodigosTributos as $tt){
            $codigos_tributos = [
                "codigo"        =>  $tt["codigo"],
                "descripcion"   =>  $tt["descripcion"],
                "valor"         => (float)$tt["valor"]
            ];
        }
        //dd($codigos_tributos);

        foreach ($cuerpo as $item) {
            //dd($item);
            $codigo_tributo = $item["tributos"];
                        $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => intval($item["numItem"]),
                "tipoDte"           =>$item["tipoDte"],
                "tipoGeneracion"    =>intval($item["tipoGeneracion"]),
                "numeroDocumento"   =>$item["numeroDocumento"],
                "fechaGeneracion"   =>$item["fechaGeneracion"],
                "ventaNoSuj"        => (float)($item["ventaNosuj"]),
                "ventaExenta"       => (float)($item["ventaExenta"]),
                "ventaGravada"      => (float)($item["ventaGravada"]),
                "exportaciones"     => (float)($item["exportaciones"]),
                "tributos"          => ($item["ventaGravada"] != 0) ? ["20"] : null,
                "ivaItem"           => (float)($item["ivaItem"]),
                "obsItem"          => $item["obsItem"],
                //"obsItem"           => null //$item["obsItem"]

            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }

        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];



        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);
        $tblResumen = $comprobante_procesar["resumen"][0];
        //dd($tblResumen);
        //dd(empty($codigos_tributos));
        //dd($codigo_tributos)>0);
        $resumen = [
            "totalNoSuj"            => (float)$tblResumen["totalNosuj"],
            "totalExenta"           => (float)$tblResumen["totalExenta"],
            "totalGravada"          => (float)$tblResumen["totalGravada"],
            "totalExportacion"      => (float)$tblResumen["totalExportacion"],
            "subTotalVentas"        => (float)$tblResumen["subTotalVentas"],
            "tributos"              => (empty($codigos_tributos))? null: [$codigos_tributos],
            "montoTotalOperacion"   => round((float)($tblResumen["montoTotalOperacion"]), 2), //(float)$tblResumen["montoTotalOperacion"],
            "ivaPerci"              => (float)$tblResumen["ivaPerci"],
            "total"                 => (float)$tblResumen["total"],
            "totalLetras"           => $tblResumen["totalLetras"],
            "condicionOperacion"    => (float)$tblResumen["condicionOperacion"],

        ];



        $es_mayor = ($tblResumen["total"] >= 11428.57);
        $tblExtension = $comprobante_procesar["extension"][0];
        //dd($tblExtension);
        $extension = [
            "nombEntrega"   => ($es_mayor) ? $tblExtension["nombreEntrega"] : null,
            "docuEntrega"   => ($es_mayor) ? $tblExtension["docuEntrega"] : null,
            "nombRecibe"    => ($es_mayor) ? $tblExtension["nombreRecibe"] : null,
            "docuRecibe"    => ($es_mayor) ? $tblExtension["docuRecibe"] : null,
            "observaciones" => ($es_mayor) ? $tblExtension["observaciones"] : null
        ];

        $apendice = null;


        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;

        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;

        //$comprobante2 = [];
        return ($comprobante);
    }
}

if (!function_exists('fex')) {
    function fex($comprobante_procesar, $uuid_generado)
    {
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($comprobante_procesar);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"];// "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];
        $identificacion = [
            "version"           => intval($encabezado["version"]),
            "ambiente"          => $empresa["ambiente"],
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContigencia" => null,
            "fecEmi"            => date('Y-m-d'), // "2022-07-23", //$encabezado["fecEmi"],    //Cambiar
            "horEmi"            => $encabezado["horEmi"],      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];



        $direccion_emisor = [
            "departamento"  => $encabezado["departamento_emisor"],
            "municipio"     => $encabezado["municipio_emisor"],
            "complemento"   => $encabezado["complemento_emisor"]
        ];

        $emisor = [
            "nit"                   => $encabezado["nit_emisor"],
            "nrc"                   => $encabezado["nrc_emisor"],
            "nombre"                => $encabezado["nombre_empresa"],
            "codActividad"          => $encabezado["codActividad"],
            "descActividad"         => $encabezado["descActividad"],
            "nombreComercial"       => $encabezado["nombreComercial"],
            "tipoEstablecimiento"   => $encabezado["tipo_establecimiento"],
            "direccion"             => $direccion_emisor,
            "telefono"              => $encabezado["telefono"],
            "correo"                => $encabezado["correo"],
            "codEstableMH"          => null,
            "codEstable"            => null,
            "codPuntoVentaMH"       => null,
            "codPuntoVenta"         => null,
            "tipoItemExpor"         => 2, //Cambiar
            "recintoFiscal"         => null, //Cambiar
            "regimen"               => null //Cambiar

        ];


        $receptor = [
            "nombre"                => $encabezado["nombre"],
            "tipoDocumento"         => $encabezado["tipoDocumento"] ,
            "numDocumento"          => $encabezado["numDocumento"],
            "descActividad"         => $encabezado["descActividad_receptor"],
            "nombreComercial"       => $encabezado["nombreComercial_receptor"],
            "codPais"               => $encabezado["codPais_receptor"], //cambiar
            "nombrePais"            => $encabezado["nomPais_receptor"], //cambiar
            "complemento"           => $encabezado["complemento_receptor"], //cambiar
            "tipoPersona"           => 2, //cambiar

        ];

        if ($encabezado["telefono_receptor"] != '') {
            $receptor["telefono"] = $encabezado["telefono_receptor"];
        }
        if ($encabezado["correo_receptor"] != '') {
            $receptor["correo"] = $encabezado["correo_receptor"];
        }

        $ventaTercero = null;
        if (isset($comprobante_procesar["terceros"][0])) {
            // dd($comprobante_procesar[2]);
            $ventaTercero = [
                "nit"       => $comprobante_procesar["terceros"][0]["nit"],
                "nombre"    => $comprobante_procesar["terceros"][0]["nombre"],

            ];
        }

        $otrosDocumentos = null;

        $codigos_tributos = [];
        $i = 0;

        foreach ($cuerpo as $item) {
            # code...
            //dd($item);
            $i += 1;
            $tributos_properties_items_cuerpoDocumento = array();

            if ($item["iva"] != 0 and count($codigos_tributos) == 0) {
                $codigos_tributos = [
                    "codigo"        =>  "20",
                    "descripcion"   =>  "Impuesto al Valor Agregado 13%",
                    "valor"         => (float)$item["iva"]
                ];
            } else {
                if ($item["iva"] != 0 and count($codigos_tributos) > 0) {
                    $iva =  $codigos_tributos["valor"] + $item["iva"];
                    $codigos_tributos["valor"] = $iva;
                }
            }

            $tributos_properties_items_cuerpoDocumento = ($item["iva"] != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i,
                "cantidad"          => intval($item["cantidad"]),
                "codigo"            => $item["id_producto"],
                "uniMedida"         => intval($item["uniMedida"]),
                "descripcion"       => $item["descripcion"],
                "precioUni"         => round((float)($item["pre_unitario"]),2),
                "montoDescu"        => (float)0.00,
                "tributos"          => (is_null($item["tributos"]))? ["C3"] : explode(",", $item["tributos"]), //($item["gravado"] != 0) ? ["20"] : null,
                "ventaGravada"      => (float)($item["gravado"]),
                "noGravado"         => (float)$item["imp_int_det"]


            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }

        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];

        $properties_items_pagos = [];
        //contado
        if ($encabezado["contado"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "01",
                "montoPago"     => (float)$encabezado["contado"],
                "referencia"    => null,
                "plazo"         => null,
                "periodo"       => null
            ];
        }

        //credito
        if ($encabezado["credito"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "13",
                "montoPago"     => (float)$encabezado["credito"],
                "referencia"    => "",
                "plazo"         => "01",
                "periodo"       => intval($encabezado["periodo"])
            ];
        }

        //tarjeta
        if ($encabezado["tarjeta"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "03",
                "montoPago"     => (float)$encabezado["tarjeta"],
                "referencia"    => $encabezado["referencia_tarjeta"],
                "plazo"         => null,
                "periodo"       => null
            ];
        }
        /* if($encabezado["total_iva"] != 0){
        $codigos_tributos= [
            "codigo"        => "20",
            "descripcion"   => "Impuesto al Valor Agregado 13",
            "valor"         => (float)$encabezado["total_iva"]
        ];
        }*/

        $pagos = $properties_items_pagos;
        if (count($codigos_tributos) > 0) {
            if ($encabezado["tot_gravado"] * 0.13 <> $codigos_tributos["valor"]) {
                $codigos_tributos["valor"] = round($encabezado["tot_gravado"] * 0.13,  2);
                // $codigos_tributos["valor"] = bcdiv($encabezado["tot_gravado"] * 0.13,1,2);

            }
        }

        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);

        $resumen = [

            "totalGravada"          => (float)$encabezado["tot_gravado"],
            "descuento"             => (float)$encabezado["totalDescu"],
            "porcentajeDescuento"   => (float)$encabezado["porcentajeDescuento"],
            "totalDescu"            => (float)$encabezado["totalDescu"],
            "seguro"                => (float)("0.00"),
            "flete"                 => (float)("0.00"),
            "montoTotalOperacion"   => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"] +$encabezado["otrosTributos"]), 2), //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado"        => (float)$encabezado["totalNoGravado"],
            "totalPagar"            => (float)$encabezado["totalPagar"],
            "totalLetras"           => $encabezado["total_letras"],
            "condicionOperacion"    => (float)$encabezado["condicionOperacion"],
            "pagos"                 => $pagos,
            "codIncoterms"          => null,
            "descIncoterms"         => null,
            "numPagoElectronico"    => "",
            "observaciones"         => ""

        ];




        $apendice[] = [
            "campo"         => "vendedor",
            "etiqueta"      => "Vendedor",
            "valor"         => $encabezado["id_vendedor"]
        ];
        $apendice[] = [
            "campo"         => "cliente",
            "etiqueta"      => "Cliente",
            "valor"         => $encabezado["id_cliente"]
        ];




        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;
        $comprobante["otrosDocumentos"]          = $otrosDocumentos;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["apendice"]                 = $apendice;
        //echo '<br>'. var_dump($comprobante) . '<br>';
        return ($comprobante);
    }
}

if (!function_exists('ncr')) {
    function ncr($comprobante_procesar, $uuid_generado)
    {
        $encabezado = $comprobante_procesar["documento"][0];
        $emisor_data = $comprobante_procesar["emisor"];
        $cliente = $comprobante_procesar["cliente"];
        $totales = $comprobante_procesar["totales"];
        $cuerpo =  $comprobante_procesar["detalle"];
        //dd($encabezado);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["tipodocumento"];
        $caja = "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["emisor"];
        $identificacion = [
            "version"           => intval($encabezado["version"]),
            "ambiente"          => $encabezado["ambiente"],
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContin"      => null,
            "fecEmi"            => date('Y-m-d'), // "2022-07-23", //$encabezado["fecEmi"],    //Cambiar
            "horEmi"            => date("H:i:s"),      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];
        $documentoRelacionado[] = [
            "tipoDocumento"     => $encabezado["tipoDteOriginal"],
            "tipoGeneracion"    => 2,
            "numeroDocumento"   => $encabezado["numeroOriginal"],
            "fechaEmision"      => $encabezado["fecEmiOriginal"],
        ];
        //dd($documentoRelacionado);
        $direccion_emisor = [
            "departamento"  => $emisor_data[0]->departamento,
            "municipio"     => $emisor_data[0]->municipio,
            "complemento"   => $emisor_data[0]->direccion
        ];

        $emisor = [
            "nit"                   => str_replace('-', '', $emisor_data[0]->nit),
            "nrc"                   => str_replace('-', '', $emisor_data[0]->ncr),
            "nombre"                => trim($emisor_data[0]->nombre),
            "codActividad"          => $emisor_data[0]->codActividad,
            "descActividad"         => $emisor_data[0]->descActividad,
            "nombreComercial"       => $emisor_data[0]->nombreComercial,
            "tipoEstablecimiento"   => $emisor_data[0]->tipoEstablecimiento,
            "direccion"             => $direccion_emisor,
            "telefono"              => str_replace("-", "",$emisor_data[0]->telefono),

           // "codEstableMH"          => null,
            //"codEstable"            => null,
            //"codPuntoVentaMH"       => null,
            //"codPuntoVenta"         => null,
            "correo"                => $emisor_data[0]->correo,
        ];

        //dd($cliente);
        $direccion_receptor = [];
        $direccion_receptor = [
            "departamento"  => $cliente[0]->departamento,
            "municipio"     => $cliente[0]->municipio,
            "complemento"   => $cliente[0]->direccion_cliente
        ];
        if (!empty($cliente[0]->codActividad)) {
            if (strlen($cliente[0]->codActividad) <= 4)
            {$codeActivity = '0' . $cliente[0]->codActividad;}
            else{ $codeActivity = $cliente[0]->codActividad; }
        }
        $receptor = [
            "nit"                   => str_replace("-", "", $cliente[0]->nit),
            "nrc"                   => str_replace("-", "", $cliente[0]->ncr),
            "nombre"                => $cliente[0]->nombre_cliente,
            "codActividad"          => $codeActivity,
            "descActividad"         => $cliente[0]->descActividad,
            "nombreComercial"       => $cliente[0]->nombre_comercial,
            "direccion"             => $direccion_receptor

        ];

        if ($cliente[0]->telefono_cliente != '') {
            $receptor["telefono"] = $cliente[0]->telefono_cliente;
        }
        if ($cliente[0]->email_cliente != '') {
            $receptor["correo"] = $cliente[0]->email_cliente;
        }
        //dd($receptor);
        $ventaTercero = null;
        if (isset($comprobante_procesar[3][0])) {
            // dd($comprobante_procesar[2]);
            $ventaTercero = [
                "nit"       => $comprobante_procesar[3][0]["nit"],
                "nombre"    => $comprobante_procesar[3][0]["nombre"],

            ];
        }

        $otrosDocumentos = null;

        $codigos_tributos = [];
        $i = 0;

        foreach ($cuerpo as $item) {
            # code...
            //dd($item);
            $i += 1;
            $tributos_properties_items_cuerpoDocumento = array();

            if ($item->iva != 0 and count($codigos_tributos) == 0) {
                $codigos_tributos = [
                    "codigo"        =>  "20",
                    "descripcion"   =>  "Impuesto al Valor Agregado 13%",
                    "valor"         => round((float)$item->iva, 2)
                ];
            } else {
                if ($item->iva != 0 and count($codigos_tributos) > 0) {
                    $iva = round((float)($codigos_tributos["valor"] + $item->iva), 2);
                    $codigos_tributos["valor"] = (float)$iva;
                }
            }

            $tributos_properties_items_cuerpoDocumento = ($item->iva != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i,
                "tipoItem"          => intval("2"),  //Bienes y Servicios
                "numeroDocumento"   => $encabezado["numeroOriginal"],
                "cantidad"          => intval($item->cantidad),
                "codigo"            => "P0".$item->id_producto,
                "codTributo"        => null,
                "uniMedida"         => 99,
                "descripcion"       => $item->descripcion,


                "precioUni"         => (float)($item->precio_unitario),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)($item->descuento),
                "ventaExenta"       => (float)($item->exentas),
                "ventaGravada"      => round((float)($item->gravadas),2),
                "tributos"          => ($item->gravadas != 0) ? ["20"] : null,
                //"psv"               => (float)"0.00",
                //"noGravado"         => (float)$item["no_imponible"]
            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }
        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];
       // dd($cuerpoDocumento);
        $properties_items_pagos = [];
        //contado
        //dd($encabezado);
        if ($encabezado["condiciones"] == 1) {
            $properties_items_pagos[] = [
                "codigo"        => "01",
                "montoPago"     => round((float)($encabezado["totalPagar"]- $encabezado["reteRenta"]),2),
                "referencia"    => null,
                "plazo"         => null,
                "periodo"       => null
            ];
        }

        //credito
        if ($encabezado["condiciones"] == 2) {
            $properties_items_pagos[] = [
                "codigo"        => "13",
                "montoPago"     => (float)"0.00",
                "referencia"    => "Credito",
                "plazo"         => "01",
                "periodo"       => 15
            ];
        }

        //tarjeta
        if ($encabezado["condiciones"] == 3) {
            $properties_items_pagos[] = [
                "codigo"        => "03",
                "montoPago"     => round((float)$totales["totalPagar"],2),
                "referencia"    => "Otro",
                "plazo"         => null,
                "periodo"       => null
            ];
        }
        /* if($encabezado["total_iva"] != 0){
        $codigos_tributos= [
            "codigo"        => "20",
            "descripcion"   => "Impuesto al Valor Agregado 13",
            "valor"         => (float)$encabezado["total_iva"]
        ];
        }*/
        //dd($properties_items_pagos);
        $pagos = $properties_items_pagos;
        if (count($codigos_tributos) > 0) {
            if ($encabezado["tot_gravado"] * 0.13 <> $codigos_tributos["valor"]) {
                $codigos_tributos["valor"] = round($encabezado["tot_gravado"] * 0.13,  2);
                // $codigos_tributos["valor"] = bcdiv($encabezado["tot_gravado"] * 0.13,1,2);

            }
        }
        //dd($codigos_tributos);
        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);

        $resumen = [
            "totalNoSuj"            => (float)$encabezado["tot_nosujeto"],
            "totalExenta"           => (float)$encabezado["tot_exento"],
            "totalGravada"          => (float)$encabezado["tot_gravado"],
            "subTotalVentas"        => (float)$encabezado["subTotalVentas"],
            "descuNoSuj"            => (float)$encabezado["descuNoSuj"],
            "descuExenta"           => (float)$encabezado["descuExenta"],
            "descuGravada"          => (float)$encabezado["descuGravada"],
            // "porcentajeDescuento"   => (float)$encabezado["porcentajeDescuento"],
            "totalDescu"            => (float)$encabezado["totalDescu"],
            "tributos"              => [$codigos_tributos],
            "subTotal"              => (float)$encabezado["subTotal"],
            "ivaPerci1"             => (float)$encabezado["ivaPerci1"],
            "ivaRete1"              => (float)$encabezado["ivaRete1"],
            "reteRenta"             => (float)$encabezado["reteRenta"],
            "montoTotalOperacion"   => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"]), 2), //(float)$encabezado["montoTotalOperacion"],
            //"totalNoGravado"        => (float)$encabezado["totalNoGravado"],
            //"totalPagar"            => (float)$encabezado["totalPagar"],
            "totalLetras"           => $encabezado["total_letras"],
            //"saldoFavor"            => (float)$encabezado["saldoFavor"],
            "condicionOperacion"    => (float)$encabezado["condiciones"],
            //"pagos"                 => $pagos,
            //"numPagoElectronico"    => ""
        ];

        //dd($resumen);
        //11428.57
        $es_mayor = ($encabezado["totalPagar"] >= 11428.57);
        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado["NombreUsuario"] : null,
            "docuEntrega"   => ($es_mayor) ? str_replace("-", "", $encabezado["docUser"]) : null,
            "nombRecibe"    => ($es_mayor) ? $cliente[0]->nombre_cliente : null,
            "docuRecibe"    => ($es_mayor) ? str_replace("-", "", $cliente[0]->nit) : null,
            "observaciones" => ($es_mayor) ? null : null,
            //"placaVehiculo" => ($es_mayor) ? null : null
            // "placaVehiculo" => ($es_mayor) ? $encabezado["placaVehiculo"] : null
        ];


        $apendice[] = [
            "campo"         => "cliente",
            "etiqueta"      => "Cliente",
            "valor"         => "".$cliente[0]->id_cliente
        ];


        $comprobante["documentoRelacionado"]     = $documentoRelacionado;
        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;
        //$comprobante["otrosDocumentos"]          = $otrosDocumentos;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;
        //echo '<br>'. var_dump($comprobante) . '<br>';

        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('fse')) {
    function fse($comprobante_procesar, $uuid_generado)
    {
        //dd($comprobante_procesar);
        $comprobante = [];
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($encabezado);

        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["identificacionNumeroControl"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"]; //"0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["identificacionTipoEstablecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];

        $identificacion = [
            "version"           => intval($encabezado["identificacionVersion"]),
            "ambiente"          => $encabezado["identificacionAmbiente"],
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContin"      => null,
            "fecEmi"            => date('Y-m-d'), //"2022-07-20", // $encabezado["fecEmi"],    //Cambiar
            "horEmi"            => $encabezado["identificacionHorEmi"],      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];
        //dd($comprobante);
        $documentoRelacionado = null;

        $direccion_emisor = [
            "departamento"  => $encabezado["emisorDepartamento"],
            "municipio"     => $encabezado["emisorMunicipio"],
            "complemento"   => $encabezado["emisorDireccionComplemento"]
        ];

        $emisor = [
            "nit"                   => str_replace("-", "",$encabezado["emisorNit"]),
            "nrc"                   => str_replace("-", "",trim($encabezado["emisorNrc"])),
            "nombre"                => trim($encabezado["emisorNombre"]),
            "codActividad"          => $encabezado["emisorCodActividad"],
            "descActividad"         => $encabezado["emisorDescActividad"],
            "direccion"             => $direccion_emisor,
            "telefono"              => $encabezado["emisorTelefono"],
            "codEstableMH"          => $encabezado["emisorCodEstableMH"],
            "codEstable"            => $encabezado["emisorCodEstable"],
            "codPuntoVentaMH"       => $encabezado["emisorCodPUntoVentaMH"],
            "codPuntoVenta"         => $encabezado["emisorCodPuntoVenta"],
            "correo"                => $encabezado["emisorCorreo"],
        ];

        //dd($emisor);
        $direccion_sujetoExcluido = [
            "departamento"  => $encabezado["receptorDepartamento"],
            "municipio"     => $encabezado["receptorMunicipio"],
            "complemento"   => $encabezado["receptorDireccionComplemento"]
        ];

        $sujetoExcluido = [
            "tipoDocumento"         => $encabezado["receptorTipoDocumento"],
            "numDocumento"          => str_replace("-","",$encabezado["receptorNumDocumento"]),
            "nombre"                => $encabezado["receptorNombre"],
            "codActividad"          => $encabezado["receptorCodActividad"],
            "descActividad"         => ($encabezado["receptorDescActividad"] == '') ? null : $encabezado["receptorDescActividad"],
            "direccion"             => $direccion_sujetoExcluido,
            "telefono"              => $encabezado["receptorTelefono"],
            "correo"                => $encabezado["receptorCorreo"]
        ];


        //dd($sujetoExcluido);

        $codigos_tributos = [];

            # code...
            //dd($item);


            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => intval($encabezado["cuerpoNumItem"]), //intval($item["corr"]),
                "tipoItem"          => intval($encabezado["cuerpoTipoItem"]),
                "cantidad"          => intval($encabezado["cuerpoCantidad"]),
                "codigo"            => $encabezado["cuerpoCodigo"],
                "uniMedida"         => intval($encabezado["cuerpoUniMedida"]),
                "descripcion"       => $encabezado["cuerpoDescripcion"],
                "precioUni"         => round((float)($encabezado["cuerpoPreciUni"]), 2),
                "montoDescu"        => (float)$encabezado["cuerpoMontoDescuento"],
                "compra"            => round((float)($encabezado["cuerpoCompra"] ), 2),
            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;


        $cuerpoDocumento = $items_cuerpoDocumento;

        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];


        //dd($cuerpoDocumento);
        $properties_items_pagos = [];
        //contado
        if ($encabezado["resumenCondicionOperacion"] == 1) {
            $properties_items_pagos[] = [
                "codigo"        => $encabezado["resumenPagosCodigo"],
                "montoPago"     => (float)$encabezado["resumenPagosMontoPago"],
                "referencia"    => $encabezado["resumenPagosReferencia"],
                "plazo"         => null,
                "periodo"       => null
            ];
        }




        $pagos = $properties_items_pagos;


        $resumen = [

            "totalCompra"           => round((float)($encabezado["resumenTotalCompra"]), 2),
            "descu"                 => (float)$encabezado["resumenDescuento"],
            "totalDescu"            => (float)$encabezado["resumenTotalDescuento"],
            "subTotal"              => round((float)($encabezado["resumenSubTotal"] ), 2),
            "ivaRete1"              => (float)$encabezado["resumenIvaRete1"],
            "reteRenta"             => (float)$encabezado["resumenReteRenta"],
            "totalPagar"            => (float)$encabezado["resumenTotalPagar"],
            "totalLetras"           => $encabezado["resumenTotalLetras"],
            "condicionOperacion"    => (float)$encabezado["resumenCondicionOperacion"],
            "pagos"                 => $pagos,
            "observaciones"    => ""
        ];




        $comprobante["emisor"]                   = $emisor;
        $comprobante["sujetoExcluido"]           = $sujetoExcluido;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["apendice"]                 = null;

        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('ndb')) {
    function ndb($comprobante_procesar, $uuid_generado)
    {
        //dd($comprobante_procesar);
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($encabezado);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"];; // "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];
        $identificacion = [
            "version"           => intval($encabezado["version"]),
            "ambiente"          => $empresa["ambiente"],
            "tipoDte"           => $tipo_documento,
            "numeroControl"     => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento, //Cambiar
            "codigoGeneracion"  => $uuid,
            "tipoModelo"        => 1,
            "tipoOperacion"     => 1,
            "tipoContingencia"  => null,
            "motivoContin"      => null,
            "fecEmi"            => date('Y-m-d'), // "2022-07-23", //$encabezado["fecEmi"],    //Cambiar
            "horEmi"            => $encabezado["horEmi"],      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];
        //dd($comprobante);
        $documentoRelacionado = null;
        $docRelacionado = (isset($comprobante_procesar["Documentosrelacionados"]))? $comprobante_procesar["Documentosrelacionados"]: [];
        //dd($docRelacionado);
        foreach ($docRelacionado as $dr) {
            $documentoRelacionado[] = [
                "tipoDocumento"     =>  $dr["tipoDodocumento"],
                "tipoGeneracion"    => intval($dr["tipoGeneracion"]),
                "numeroDocumento"   => $dr["nuDocRelacionado"],
                "fechaEmision"       => $dr["fechaEmision"],
            ];
        }
        //dd($documentoRelacionado);

        $direccion_emisor = [
            "departamento"  => $encabezado["departamento_emisor"],
            "municipio"     => $encabezado["municipio_emisor"],
            "complemento"   => $encabezado["complemento_emisor"]
        ];

        $emisor = [
            "nit"                   => $encabezado["nit_emisor"],
            "nrc"                   => $encabezado["nrc_emisor"],
            "nombre"                => $encabezado["nombre_empresa"],
            "codActividad"          => $encabezado["codActividad"],
            "descActividad"         => $encabezado["descActividad"],
            "nombreComercial"       => $encabezado["nombreComercial"],
            "tipoEstablecimiento"   => $encabezado["tipo_establecimiento"],
            "direccion"             => $direccion_emisor,
            "telefono"              => $encabezado["telefono"],
            "correo"                => $encabezado["correo"]

        ];
        //dd($emisor);
        $direccion_receptor = [
            "departamento"  => $encabezado["departamento_receptor"],
            "municipio"     => $encabezado["municipio_receptor"],
            "complemento"   => $encabezado["complemento_receptor"]
        ];

        $receptor = [
            "nit"                   => $encabezado["nit_receptor"],
            "nrc"                   => $encabezado["nrc_receptor"],
            "nombre"                => $encabezado["nombre"],
            "codActividad"          => $encabezado["codActividad_receptor"],
            "descActividad"         => $encabezado["descActividad_receptor"],
            "nombreComercial"       => $encabezado["nombreComercial_receptor"],
            "direccion"             => $direccion_receptor

        ];

        if ($encabezado["telefono_receptor"] != '') {
            $receptor["telefono"] = $encabezado["telefono_receptor"];
        }
        if ($encabezado["correo_receptor"] != '') {
            $receptor["correo"] = $encabezado["correo_receptor"];
        }
        //dd($receptor);
        $ventaTercero = null;

        if (isset($comprobante_procesar["terceros"][0])) {
            // dd($comprobante_procesar[2]);
            $ventaTercero = [
                "nit"       => $comprobante_procesar["terceros"][0]["nit"],
                "nombre"    => $comprobante_procesar["terceros"][0]["nombre"],

            ];
        }



        $codigos_tributos = [];
        $i = 0;

        foreach ($cuerpo as $item) {
            # code...
            //dd($item);
            $i += 1;
            $tributos_properties_items_cuerpoDocumento = array();

            if ($item["iva"] != 0 and count($codigos_tributos) == 0) {
                $codigos_tributos = [
                    "codigo"        =>  "20",
                    "descripcion"   =>  "Impuesto al Valor Agregado 13%",
                    "valor"         => (float)$item["iva"]
                ];
            } else {
                if ($item["iva"] != 0 and count($codigos_tributos) > 0) {
                    $iva =  $codigos_tributos["valor"] + $item["iva"];
                    $codigos_tributos["valor"] = $iva;
                }
            }

            $tributos_properties_items_cuerpoDocumento = ($item["iva"] != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i,
                "tipoItem"          => intval($item["tipoItem"]),  //Bienes y Servicios
                "numeroDocumento"   => $item["nuDocRelacionado"],
                "cantidad"          => intval($item["cantidad"]),
                "codigo"            => $item["id_producto"],
                "codTributo"        => null,
                "uniMedida"         => intval($item["uniMedida"]),
                "descripcion"       => $item["descripcion"],


                "precioUni"         => (float)($item["pre_unitario"]),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)($item["no_sujetas"]),
                "ventaExenta"       => (float)($item["excento"]),
                "ventaGravada"      => (float)($item["gravado"]),
                "tributos"          => ($item["gravado"] != 0) ? ["20"] : null,
               // "noGravado"         => (float)$item["imp_int_det"]
            ];

            $items_cuerpoDocumento[] = $properties_items_cuerpoDocumento;
        }

        $cuerpoDocumento = $items_cuerpoDocumento;
        //dd($cuerpoDocumento);
        $properties_items_tributo_resumen = [
            "codigo"        => "",
            "descripcion"   => "",
            "valor"         => ""
        ];

        $tributos_resumen = [
            "properties"   => $properties_items_tributo_resumen
        ];

        $properties_items_pagos = [];
        //contado
        if ($encabezado["contado"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "01",
                "montoPago"     => (float)$encabezado["contado"],
                "referencia"    => null,
                "plazo"         => null,
                "periodo"       => null
            ];
        }

        //credito
        if ($encabezado["credito"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "13",
                "montoPago"     => (float)$encabezado["credito"],
                "referencia"    => "",
                "plazo"         => "01",
                "periodo"       => intval($encabezado["periodo"])
            ];
        }

        //tarjeta
        if ($encabezado["tarjeta"] != 0) {
            $properties_items_pagos[] = [
                "codigo"        => "03",
                "montoPago"     => (float)$encabezado["tarjeta"],
                "referencia"    => $encabezado["referencia_tarjeta"],
                "plazo"         => null,
                "periodo"       => null
            ];
        }
        /* if($encabezado["total_iva"] != 0){
        $codigos_tributos= [
            "codigo"        => "20",
            "descripcion"   => "Impuesto al Valor Agregado 13",
            "valor"         => (float)$encabezado["total_iva"]
        ];
        }*/

        $pagos = $properties_items_pagos;
        if (count($codigos_tributos) > 0) {
            if ($encabezado["tot_gravado"] * 0.13 <> $codigos_tributos["valor"]) {
                $codigos_tributos["valor"] = round($encabezado["tot_gravado"] * 0.13,  2);
                // $codigos_tributos["valor"] = bcdiv($encabezado["tot_gravado"] * 0.13,1,2);

            }
        }

        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);

        $resumen = [
            "totalNoSuj"            => (float)$encabezado["tot_nosujeto"],
            "totalExenta"           => (float)$encabezado["tot_exento"],
            "totalGravada"          => (float)$encabezado["tot_gravado"],
            "subTotalVentas"        => (float)$encabezado["subTotalVentas"],
            "descuNoSuj"            => (float)$encabezado["descuNoSuj"],
            "descuExenta"           => (float)$encabezado["descuExenta"],
            "descuGravada"          => (float)$encabezado["descuGravada"],
           // "porcentajeDescuento"   => (float)$encabezado["porcentajeDescuento"],
            "totalDescu"            => (float)$encabezado["totalDescu"],
            "tributos"              => [$codigos_tributos],
            "subTotal"              => (float)$encabezado["subTotal"],
            "ivaPerci1"             => (float)$encabezado["ivaPerci1"],
            "ivaRete1"              => (float)$encabezado["ivaRete1"],
            "reteRenta"             => (float)$encabezado["reteRenta"],
            "montoTotalOperacion"   => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"]), 2), //(float)$encabezado["montoTotalOperacion"],
            //"totalNoGravado"        => (float)$encabezado["totalNoGravado"],
            //"totalPagar"            => (float)$encabezado["totalPagar"],
            "totalLetras"           => $encabezado["total_letras"],
            //"saldoFavor"            => (float)$encabezado["saldoFavor"],
            "condicionOperacion"    => intval($encabezado["condicionOperacion"]),
            //"pagos"                 => $pagos,
            "numPagoElectronico"    => ""
        ];



        $es_mayor = ($encabezado["totalPagar"] >= 11428.57);

        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado["nombEntrega"] : null,
            "docuEntrega"   => ($es_mayor) ? $encabezado["docuEntrega"] : null,
            "nombRecibe"    => ($es_mayor) ? $encabezado["nombRecibe"] : null,
            "docuRecibe"    => ($es_mayor) ? $encabezado["docuRecibe"] : null,
            "observaciones" => ($es_mayor) ? $encabezado["observaciones"] : null,
           // "placaVehiculo" => ($es_mayor) ? $encabezado["placaVehiculo"] : null
        ];

        $apendice[] = [
            "campo"         => "vendedor",
            "etiqueta"      => "Vendedor",
            "valor"         => $encabezado["id_vendedor"]
        ];
        $apendice[] = [
            "campo"         => "cliente",
            "etiqueta"      => "Cliente",
            "valor"         => $encabezado["id_cliente"]
        ];



        $comprobante["documentoRelacionado"]     = $documentoRelacionado;
        $comprobante["emisor"]                   = $emisor;
        $comprobante["receptor"]                 = $receptor;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;
        //echo '<br>'. var_dump($comprobante) . '<br>';

        //dd($comprobante);
        return ($comprobante);
    }
}
if (! function_exists('subfijo')) {
    function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }
}
if (!function_exists('numeroletras')) {
    function numeroletras($xcifra)
    {
        $xarray = array(
            0 => "Cero",
            1 => "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
        //
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {
                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                } else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena .= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena .= " DE";

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena .= "UN BILLON ";
                        else
                            $xcadena .= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena .= "UN MILLON ";
                        else
                            $xcadena .= " MILLONES ";
                        break;
                    case 2:

                            $xcadena .= ""; //

                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }
}
if (! function_exists('numtoletras')) {
    function numtoletras($xcifra)
    {
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
    //
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                }
                                else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {

                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                }
                                else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena.= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena.= " DE";

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN BILLON ";
                        else
                            $xcadena.= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN MILLON ";
                        else
                            $xcadena.= " MILLONES ";
                        break;
                    case 2:
                        if ($xcifra < 1) {
                            $xcadena = "CERO DOLARES $xdecimales/100 ";
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            $xcadena = "UN DOLAR $xdecimales/100  ";
                        }
                        if ($xcifra >= 2) {
                            $xcadena.= " DOLARES $xdecimales/100  "; //
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }
    }

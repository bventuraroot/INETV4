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
    function convertir_json($compro_procesar, $tipoDte)
    {
        //dd($tipoDte);
        //var_dump($compro_procesar);
        $compro = $compro_procesar;
        $tipo_comprobante = $tipoDte;
        //dd($tipo_comprobante);
        $retorno = [];
        $uuid_generado = $compro["codigoGeneracion"]; //strtoupper(Str::uuid()->toString());
        //$retorno=[$compro, $uuid_generado];
        switch ($tipo_comprobante) {
            case '03': //CRF
                $retorno = crf($compro, $uuid_generado);
                break;
            case '01': //FAC
                $retorno = fac($compro, $uuid_generado);
                break;
            case '05':  //NCR
                $retorno = ncr($compro, $uuid_generado);
                break;
            case '06':  //NDB
                $retorno = ndb($compro, $uuid_generado);
                break;
            case '08':  //CLQ
                $retorno = clq($compro, $uuid_generado);
                break;
            case '11':  //FEX
                $retorno = fex($compro, $uuid_generado);
                break;
            case '14':  //FSE
                $retorno = fse($compro, $uuid_generado);
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

        $encabezado = $comprobante_procesar["encabezado"][0];
        //var_dump($comprobante_procesar);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"]; // "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];
        $resumenTributos = $comprobante_procesar["resumenTributo"];

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

        $documentoRelacionado = null;

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

            "codEstableMH"          => null,
            "codEstable"            => null,
            "codPuntoVentaMH"       => null,
            "codPuntoVenta"         => null,
            "correo"                => $encabezado["correo"],
        ];

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

            if ($item["iva"] != 0 and count($codigos_tributos) == 0) {
                $codigos_tributos = [
                    "codigo"        =>  "20",
                    "descripcion"   =>  "Impuesto al Valor Agregado 13%",
                    "valor"         => round((float)$item["iva"],2)
                ];
            } else {
                if ($item["iva"] != 0 and count($codigos_tributos) > 0) {
                    $iva = round( (float)($codigos_tributos["valor"] + $item["iva"]),2);
                    $codigos_tributos["valor"] = (float)$iva;
                }
            }

            $tributos_properties_items_cuerpoDocumento = ($item["iva"] != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i,
                "tipoItem"          => intval($item["tipoItem"]), //intval("2"),  //Bienes y Servicios
                "numeroDocumento"   => null,
                "cantidad"          => intval($item["cantidad"]),
                "codigo"            => $item["id_producto"],
                "codTributo"        => $item["codTributo"],
                "uniMedida"         => intval($item["uniMedida"]),
                "descripcion"       => $item["descripcion"],


                "precioUni"         => (float)($item["pre_unitario"]),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)($item["no_sujetas"]),
                "ventaExenta"       => (float)($item["excento"]),
                "ventaGravada"      => (float)($item["gravado"]),
                "tributos"          => (is_null($item["tributos"]))? null : explode(",", $item["tributos"]), //($item["gravado"] != 0) ? ["20"] : null,
                "psv"               => (float)"0.00",
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


        $pagos = $properties_items_pagos;
        if (count($codigos_tributos) > 0) {
            if ($encabezado["tot_gravado"] * 0.13 <> $codigos_tributos["valor"]) {
                $codigos_tributos["valor"] = round((float)($encabezado["tot_gravado"] * 0.13),  2);
                // $codigos_tributos["valor"] = bcdiv($encabezado["tot_gravado"] * 0.13,1,2);

            }
        }

        // $codigos_tributos["valor"] = intval($codigos_tributos["valor"]/0.01);

        foreach($resumenTributos as $rt){
            $codigos_tributos[] = [
                "codigo"        =>  $rt["idTributo"],
                "descripcion"   =>  $rt["dsTributo"],
                "valor"         => round((float)$rt["valTributo"],2)
            ];
        }

        $resumen = [
            "totalNoSuj"            => (float)$encabezado["tot_nosujeto"],
            "totalExenta"           => (float)$encabezado["tot_exento"],
            "totalGravada"          => (float)$encabezado["tot_gravado"],
            "subTotalVentas"        => (float)$encabezado["subTotalVentas"],
            "descuNoSuj"            => (float)$encabezado["descuNoSuj"],
            "descuExenta"           => (float)$encabezado["descuExenta"],
            "descuGravada"          => (float)$encabezado["descuGravada"],
            "porcentajeDescuento"   => (float)$encabezado["porcentajeDescuento"],
            "totalDescu"            => (float)$encabezado["totalDescu"],
            "tributos"              => (!empty($codigos_tributos))? [$codigos_tributos]: null,
            "subTotal"              => (float)$encabezado["subTotal"],
            "ivaPerci1"             => (float)$encabezado["ivaPerci1"],
            "ivaRete1"              => (float)$encabezado["ivaRete1"],
            "reteRenta"             => (float)$encabezado["reteRenta"],
            "montoTotalOperacion"   => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"] + $encabezado["otrosTributos"]), 2), //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado"        => (float)$encabezado["totalNoGravado"],
            "totalPagar"            => (float)$encabezado["totalPagar"],
            "totalLetras"           => $encabezado["total_letras"],
            "saldoFavor"            => (float)$encabezado["saldoFavor"],
            "condicionOperacion"    => (float)$encabezado["condicionOperacion"],
            "pagos"                 => $pagos,
            "numPagoElectronico"    => ""
        ];



        $es_mayor = ($encabezado["totalPagar"] >= 11428.57);

        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado["nombEntrega"] : null,
            "docuEntrega"   => ($es_mayor) ? $encabezado["docuEntrega"] : null,
            "nombRecibe"    => ($es_mayor) ? $encabezado["nombRecibe"] : null,
            "docuRecibe"    => ($es_mayor) ? $encabezado["docuRecibe"] : null,
            "observaciones" => ($es_mayor) ? $encabezado["observaciones"] : null,
            "placaVehiculo" => ($es_mayor) ? $encabezado["placaVehiculo"] : null
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
        $comprobante["otrosDocumentos"]          = $otrosDocumentos;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;
        //echo '<br>'. var_dump($comprobante) . '<br>';
        return ($comprobante);
    }
}

if (!function_exists('fac')) {
    function fac($comprobante_procesar, $uuid_generado)
    {

        $comprobante = [];
        $encabezado = $comprobante_procesar["encabezado"][0];
        //var_dump($encabezado);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["cod_tipo_documento"];
        $caja = $encabezado["cod_establecimiento"]; //"0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];
        $resumenTributos = $comprobante_procesar["resumenTributo"];

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
            "fecEmi"            => date('Y-m-d'), //"2022-07-20", // $encabezado["fecEmi"],    //Cambiar
            "horEmi"            => $encabezado["horEmi"],      //Cambiar
            "tipoMoneda"        => "USD"            //Cambiar
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];

        $documentoRelacionado = null;

        $direccion_emisor = [
            "departamento"  => $encabezado["departamento_emisor"],
            "municipio"     => $encabezado["municipio_emisor"],
            "complemento"   => $encabezado["complemento_emisor"]
        ];

        $emisor = [
            "nit"                   => $encabezado["nit_emisor"],
            "nrc"                   => $encabezado["nrc_emisor"],
            "nombre"                => trim($encabezado["nombre_empresa"]),
            "codActividad"          => $encabezado["codActividad"],
            "descActividad"         => $encabezado["descActividad"],
            "nombreComercial"       => $encabezado["nombreComercial"],
            "tipoEstablecimiento"   => $encabezado["tipo_establecimiento"],
            "direccion"             => $direccion_emisor,
            "telefono"              => $encabezado["telefono"],
            "codEstableMH"          => $encabezado["codEstableMH"],
            "codEstable"            => $encabezado["codEstable"],
            "codPuntoVentaMH"       => $encabezado["codPuntoVentaMH"],
            "codPuntoVenta"         => $encabezado["codPuntoVenta"],
            "correo"                => $encabezado["correo"],
        ];

        $direccion_receptor = [];

            $direccion_receptor = [
                "departamento"  => $encabezado["departamento_receptor"],
                "municipio"     => $encabezado["municipio_receptor"],
                "complemento"   => $encabezado["complemento_receptor"]
            ];


        $receptor = [
            "tipoDocumento"         => $encabezado["tipoDocumento"],
            "numDocumento"          => $encabezado["numDocumento"],
            "nrc"                   => ($encabezado["nrc_receptor"] == '') ? null : $encabezado["nrc_receptor"],
            "nombre"                => $encabezado["nombre"],
            "codActividad"          => ($encabezado["codActividad_receptor"] == '') ? null : $encabezado["codActividad_receptor"],
            "descActividad"         => ($encabezado["descActividad_receptor"] == '') ? null : $encabezado["descActividad_receptor"],

        ];

        if($encabezado["codPais_receptor"] == '9300'){

            $receptor["direccion"]      = $direccion_receptor;
        }

        if ($encabezado["telefono_receptor"] != '') {
            $receptor["telefono"] = $encabezado["telefono_receptor"];
        }
        if ($encabezado["correo_receptor"] != '') {
            $receptor["correo"] = $encabezado["correo_receptor"];
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
        //var_dump($cuerpo);
        //echo '<br>';
        foreach ($cuerpo as $item) {
            //var_dump($item);
            // echo '<br>';
            $i += 1;

            $tributos_properties_items_cuerpoDocumento = array();

            $tributos_properties_items_cuerpoDocumento = ($item["iva"] != 0) ? "20" : "C3";

            $properties_items_cuerpoDocumento = array();

            $properties_items_cuerpoDocumento = [
                "numItem"           => $i, //intval($item["corr"]),
                "tipoItem"          => intval($item["tipoItem"]),
                "numeroDocumento"   => null,
                "cantidad"          => intval($item["cantidad"]),
                "codigo"            => $item["id_producto"],
                "codTributo"        => $item["codTributo"],
                "uniMedida"         => intval($item["uniMedida"]),
                "descripcion"       => $item["descripcion"],


                "precioUni"         => round((float)($item["pre_unitario"] ), 2),
                "montoDescu"        => 0.00,
                "ventaNoSuj"        => (float)$item["no_sujetas"],
                "ventaExenta"       => (float)$item["excento"],
                "ventaGravada"      => round((float)($item["gravado"] ), 2),
                "tributos"          => (is_null($item["tributos"]))? null : explode(",", $item["tributos"]),
                "psv"               => (float)"0.00",
                "noGravado"         => (float)$item["imp_int_det"],
                "ivaItem"           => (float)$item["iva"]
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


        $pagos = $properties_items_pagos;

        foreach($resumenTributos as $rt){
            $codigos_tributos[] = [
                "codigo"        =>  $rt["idTributo"],
                "descripcion"   =>  $rt["dsTributo"],
                "valor"         => round((float)$rt["valTributo"],2)
            ];
        }

        $resumen = [
            "totalNoSuj"            => (float)$encabezado["tot_nosujeto"],
            "totalExenta"           => (float)$encabezado["tot_exento"],
            "totalGravada"          => round((float)($encabezado["tot_gravado"] + $encabezado["total_iva"]), 2),
            "subTotalVentas"        => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"]), 2),
            "descuNoSuj"            => (float)$encabezado["descuNoSuj"],
            "descuExenta"           => (float)$encabezado["descuExenta"],
            "descuGravada"          => (float)$encabezado["descuGravada"],
            "porcentajeDescuento"   => (float)$encabezado["porcentajeDescuento"],
            "totalDescu"            => (float)$encabezado["totalDescu"],
            "tributos"              => $codigos_tributos,
            "subTotal"              => round((float)($encabezado["subTotal"] + $encabezado["total_iva"]), 2),

            "ivaRete1"              => (float)$encabezado["ivaRete1"],
            "reteRenta"             => (float)$encabezado["reteRenta"],
            "montoTotalOperacion"   => round((float)($encabezado["subTotalVentas"] + $encabezado["total_iva"] + $encabezado["otrosTributos"]), 2), //(float)$encabezado["montoTotalOperacion"],
            "totalNoGravado"        => (float)$encabezado["totalNoGravado"],
            "totalPagar"            => (float)$encabezado["totalPagar"],
            "totalLetras"           => $encabezado["total_letras"],
            "totalIva"              => (float)$encabezado["total_iva"],
            "saldoFavor"            => (float)$encabezado["saldoFavor"],
            "condicionOperacion"    => (float)$encabezado["condicionOperacion"],
            "pagos"                 => $pagos,
            "numPagoElectronico"    => ""
        ];


        $es_mayor = ($encabezado["totalPagar"] >= 200);

        $extension = [
            "nombEntrega"   => ($es_mayor) ? $encabezado["nombEntrega"] : null,
            "docuEntrega"   => ($es_mayor) ? $encabezado["docuEntrega"] : null,
            "nombRecibe"    => ($es_mayor) ? $encabezado["nombRecibe"] : null,
            "docuRecibe"    => ($es_mayor) ? $encabezado["numDocumento"] : null,
            "observaciones" => ($es_mayor) ? $encabezado["observaciones"] : null,
            "placaVehiculo" => ($es_mayor) ? $encabezado["placaVehiculo"] : null
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
        $comprobante["otrosDocumentos"]          = $otrosDocumentos;
        $comprobante["ventaTercero"]             = $ventaTercero;
        $comprobante["cuerpoDocumento"]          = $cuerpoDocumento;
        $comprobante["resumen"]                  = $resumen;
        $comprobante["extension"]                = $extension;
        $comprobante["apendice"]                 = $apendice;
        //$comprobante2 = [];
        //dd($comprobante);
        return ($comprobante);
    }
}

if (!function_exists('fan')) {
    function fan($comprobante_procesar, $uuid_generado){
        date_default_timezone_set('America/El_Salvador');
        $comprobante = [];
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($encabezado);
        $cuerpo = $comprobante_procesar["detalle"];
        //dd($cuerpo);
        $uuid = $uuid_generado;
        $numero_documento = CerosIzquierda($encabezado["nu_doc"], 15);
        $tipo_documento = $encabezado["tipoDteOriginal"];
        $caja = $encabezado["cod_establecimiento"];// "0001";
        $tipo_establecimiento = CerosIzquierda($encabezado["tipo_establecimiento"], 4);
        $empresa = $comprobante_procesar["empresa"][0];
        $identificacion = [
            "version"           => intval($encabezado["version"]),
            "ambiente"          => $empresa["ambiente"],
            "codigoGeneracion"  => $uuid,
            "fecAnula"          => $encabezado["fecAnulado"],
            "horAnula"          => $encabezado["horAnulado"]
        ];
        $comprobante = [
            "identificacion" => $identificacion
        ];

        $documentoRelacionado = null;

        $emisor = [
            "nit"                   => $encabezado["nit_emisor"],
            "nombre"                => trim($encabezado["nombre_empresa"]),
            "tipoEstablecimiento"   => $encabezado["tipo_establecimiento"],
            "nomEstablecimiento"    => "casa matriz", //cambiar
            "codEstableMH"          => $encabezado["codEstableMH"],
            "codEstable"            => $encabezado["codEstable"],
            "codPuntoVentaMH"       => $encabezado["codPuntoVentaMH"],
            "codPuntoVenta"         => $encabezado["codPuntoVenta"],
            "telefono"              => $encabezado["telefono"],
            "correo"                => $encabezado["correo"],


        ];

        $documento = [
            "tipoDte"               => $encabezado["tipoDteOriginal"],
            "codigoGeneracion"      => $encabezado["codigoGeneracionOriginal"],
            "selloRecibido"         => $encabezado["selloRecibidoOriginal"],
            "numeroControl"         => "DTE-" . $tipo_documento . "-" . $tipo_establecimiento . $caja . "-" . $numero_documento,
            "fecEmi"                => $encabezado["fecEmiOriginal"],
            "montoIva"              => (float)($encabezado["total_iva"]),
            "codigoGeneracionR"     => null,
            "tipoDocumento"         => $encabezado["tipoDocumento"],
            "numDocumento"          => $encabezado["numDocumento"],
            "nombre"                => $encabezado["nombre"],

        ];
        if ($encabezado["telefono_receptor"] != '') {
            $documento["telefono"] = $encabezado["telefono_receptor"];
        }
        if ($encabezado["correo_receptor"] != '') {
            $documento["correo"] = $encabezado["correo_receptor"];
        }

       $motivo = [
            "tipoAnulacion"         => intval("2"),
            "motivoAnulacion"       => "Rescindir de la operacion realizada.",
            "nombreResponsable"     => $encabezado["nombEntrega"],
            "tipDocResponsable"    => "13",
            "numDocResponsable"     => "03839534-0", ///$encabezado["docuEntrega"],
            "nombreSolicita"        => $encabezado["nombreSolicita"],
            "tipDocSolicita"        => $encabezado["tipDocSolicita"],
            "numDocSolicita"        => $encabezado["numDocSolicita"]

       ];



        $comprobante["emisor"]      = $emisor;
        $comprobante["documento"]   = $documento;
        $comprobante["motivo"]      = $motivo;

        //$comprobante2 = [];
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
        //dd($comprobante_procesar);
        $encabezado = $comprobante_procesar["encabezado"][0];
        //dd($encabezado);
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
            "tributos"              => (!empty($codigos_tributos))?[$codigos_tributos]:null,
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
            //"numPagoElectronico"    => ""
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

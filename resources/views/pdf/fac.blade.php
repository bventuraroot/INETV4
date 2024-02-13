<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Comprobante</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: xx-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: xx-small;
        }

        .gray {
            background-color: lightgray
        }

        .cuadro{
            border:1px solid #000;
            border-spacing: 0 0;
            padding: 0;
        }
        .cuadro-izq{
        border-left:1px solid #000;
        border-spacing: 0 0;

        }
        .sumas{
        border-left:1px solid #000;
        border-bottom:1px solid #000;
        border-spacing: 0 0;
        margin: 0;

        }

        #watermark {
        position: fixed;

        /**
        Set a position in the page for your image
        This should center it vertically
        **/

        bottom: 5cm;
        left: 6.5cm;

        /** Change image dimensions**/
        width: 8cm;
        height: 8cm;

        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        transform: rotate(-45deg);

        -webkit-transform-origin: 50% 50%;
        -moz-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        -o-transform-origin: 50% 50%;
        transform-origin: 50% 50%;

        font-size: 100px;
        width: 250px;

        /** Your watermark should be behind every content**/
        z-index: 1000;
        }
    </style>

</head>

<body>
    @if ($codTransaccion == "02")
    <div id="watermark">
        ANULADO
    </div>
    @endif
<!-- Encabezado y QR -->
    <table width="100%">
        <tr valign="top">
            <td width=45%>
                <table width="100%">
                    <tr>
                        <td>


                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: x-small;">
                            <strong>{{$emisor[0]["nombre"]}}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td>NIT:{{$emisor[0]["nit"]}}</td>
                    </tr>
                    <tr>
                        <td>NRC:{{$emisor[0]["nrc"]}}</td>
                    </tr>
                    <tr>
                        <td>Actividad económica:{{$emisor[0]["descActividad"]}}</td>
                    </tr>
                    <tr>
                        <td>Dirección: {{$emisor[0]["direccion"]["complemento"]}}<br>
                            {{$MunicipioE}},{{$DepartamentoE}}</td>
                    </tr>
                    <tr>
                        <td>Número de teléfono:{{$emisor[0]["telefono"]}}</td>
                    </tr>
                    <tr>
                        <td>Correo electrónico:{{$emisor[0]["correo"]}}</td>
                    </tr>
                    <tr>
                        <td>Nombre comercial:{{$emisor[0]["nombreComercial"]}}</td>
                    </tr>
                    <tr>
                        <td>Tipo de establecimiento:
                            Casa Matriz
                        </td>
                    </tr>

                </table>
            </td>
            <td>
                <table width="100%" style="border:1px solid #000;">
                    <tr style="background-color: lightgray;">
                        <td colspan="3" align="center" style="font-size: x-small;">
                            <strong>DOCUMENTO TRIBUTARIO ELECTRÓNICO</strong><br>
                            <strong>FACTURA DE CONSUMIDOR FINAL</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Código de Generación:</strong></td>
                        <td colspan="2">{{$json["codigoGeneracion"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Sello de recepción:</strong></td>
                        <td colspan="2">{{$json["selloRecibido"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Número de Control:</strong></td>
                        <td colspan="2">{{$json["identificacion"]["numeroControl"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Modélo facturación:</strong></td>
                        <td>Previo</td>
                        <td><strong>Versión del Json:</strong> {{$documento[0]["versionjson"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipo de transmisión</strong></td>
                        <td>Normal</td>
                        <td><strong>Fecha emisión:</strong>{{date('d/m/Y', strtotime($json["fhRecibido"]))}} </td>
                    </tr>
                    <tr>
                        <td><strong>Hora de emisión:</strong></td>
                        <td>{{substr($json["fhRecibido"],12,8)}}</td>
                        <td><strong>Documento interno No:</strong> {{$documento[0]["actual"]}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">
                            <img src="data:image/png;base64,{{$qr}}" alt="">
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>

 <!-- Final de Encabezado y QR -->

 <!-- Datos Receptor -->
    <table width="100%" style="border-collapse:collapse;"">
        <tr valign="top" >

            <td width="480px">
                <table width="100%" style="border-top:1px solid #000;">

                    <tr>
                        <table>
                            <tr>
                                <td  width="100px"><strong>Nombre:</strong></td> 
                                <td colspan="2" >{{$json["receptor"]["nombre"]}}  </td>
                            </tr>
                        </table>

                    </tr>
                    <tr>
                        <td colspan="3">
                            <table width="100%">
                                <tr>
                                    <td  width="130px"><strong>Tipo Documento:</strong>    {{tipoDocumento($cliente["tipoDocumento"])}}</td>
                                    <td  width="180px"><strong>No.Documento:</strong> {{$cliente["numDocumento"]}}</td>
                                    <td ><strong>Correo electrónico:</strong>{{$cliente["correo"]}}</td>
                                </tr>
                            </table>

                        </td>

                    </tr>
                </table>
            </td>
        </tr>

    </table>

<!-- Datos Receptor -->
@if (!empty($comprobante[3]))


    <table width="100%" style="border-top:1px solid #000;">
        <tr align="center" >
            <td colspan="2"><strong>VENTA A CUENTA DE TERCEROS</strong></td>
        </tr>
        <tr>
            <td><strong>NIT:</strong>{{$comprobante[3][0]["nit"]}}</td>
            <td><strong>Nombre, denominación o razón social:</strong>{{$comprobante[3][0]["nombre"]}}</td>
        </tr>

    </table>
@endif
    <br />

    <table width="100%" style="border-collapse:collapse;page-break-after: auto;">
        <thead style="background-color: lightgray;">
            <tr>
                <th class="cuadro">No</th>
                <th class="cuadro">Cnt</th>

                <th class="cuadro">Descripcion</th>
                <th class="cuadro">Precio<br>Unitario</th>
                <th class="cuadro">Descuento<br>por Item</th>
                <th class="cuadro">Otros montos<br>no afectos</th>
                <th class="cuadro">Ventas No<br>Sujetas</th>
                <th class="cuadro">Ventas<br>Exentas</th>
                <th class="cuadro">Ventas<br>Gravadas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalle as $d)


            <tr>
                <th>{{$loop->index+1}}</th>
                <td>{{$d["cantidad"]}}</td>
                <td>{{$d["descripcion"]}}</td>
                <td align="right">{{FNumero($d["precio_unitario"]+$d["iva"])}}</td>
                <td align="right">0.00</td>
                <td align="right">{{FNumero($d["no_imponible"])}}</td>
                <td align="right">{{FNumero($d["no_sujetas"])}}</td>
                <td align="right">{{FNumero($d["exentas"])}}</td>
                <td align="right">{{FNumero($d["gravadas"]+$d["iva"])}}</td>
            </tr>
            @if (($loop->index+1) % 37 == 0)
            <tr style='page-break-after: always;'>
                <td align="right" colspan="9">Pasan ......</td>
            </tr>

            @endif
            @endforeach
        </tbody>


    </table>
    <footer>
        <div class="footer" style="position: absolute; bottom: 0;border-spacing: 0 0;border-collapse:collapse;margin-top:0;">
            <table width="100%" style="border-collapse:collapse;margin-top:0;border-spacing: 0 0;" class="cuadro">
                <tr>
                    <td width="490px">
                        <table width="100%">
                            <tr>
                                <td colspan="2"><strong>Valor en Letras:</strong> {{$totales["totalLetras"]}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" style="background-color: lightgray;"><strong>EXTENSIÓN</strong></td>
                            </tr>
                            <tr>
                                <td width="245px"><strong>Nombre entrega</strong></td>
                                <td><strong>No Documento</strong> </td>
                            </tr>
                            <tr>
                                <td><strong>Nombre recibe</strong> </td>
                                <td><strong>No Documento</strong> </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" style="background-color: lightgray;"><strong>OBSERVACIONES</strong></td>
                            </tr>
                            <tr>
                                <td width="245px">
                                   <center><strong>Forma de Pago</strong></center>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="245px">
                                <table width="100%">
                                    <tr>
                                        <td align="center"><strong>Credito</strong></td>
                                        <td align="center"><strong>Contado</strong></td>
                                        <td align="center"><strong>Tarjeta</strong></td>
                                    </tr>
                                </table>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="245px">
                                    <table width="100%">
                                        <tr>
                                            <td align="right">{{FNumero(($totales["condicionOperacion"] == "02")?$totales["totalPagar"]:0.00)}}</td>
                                            <td align="right">{{FNumero(($totales["condicionOperacion"] == "01")?$totales["totalPagar"]:0.00)}}</td>
                                            <td align="right">{{FNumero(($totales["condicionOperacion"] == "03")?$totales["totalPagar"]:0.00)}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>

                    </td>
                    <td style="border:1px solid #000;" width="230px">
                        <!--- Totales-->
                        <table style="border-spacing: 0 0;">
                            <tr>
                                <td width="80px">Sumas $</td>
                                <td align="right" width="50px" class="sumas">{{FNumero($totales["totalNoSuj"])}}</td>
                                <td align="right" width="50px" class="sumas">{{FNumero($totales["totalExenta"])}}</td>
                                <td align="right" width="50px" class="sumas">{{FNumero($totales["totalGravada"]+$totales["totalIva"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3" width="160px">Suma total de operaciones</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["subTotalVentas"]+$totales["totalIva"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">Total descuentos</td>
                                <td align="right" class="cuadro-izq">{{FNumero(0.00)}}</td>

                            </tr>
                            <tr>
                               &nbsp;

                            </tr>
                            <tr>
                                <td colspan="3">Sub-Total</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["subTotal"]+$totales["totalIva"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">IVA Percibido</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["ivaPerci1"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">IVA Retenido</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["ivaRete1"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">Monto Total de la operación</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["montoTotalOperacion"]+$totales["totalIva"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">Total otros montos no afectos</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["totalNoGravado"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3">Retencion Renta</td>
                                <td align="right" class="cuadro-izq">{{FNumero($totales["reteRenta"])}}</td>

                            </tr>
                            <tr>
                                <td colspan="3" ><strong>TOTAL A PAGAR</strong></td>
                                <td align="right" class="cuadro-izq"><strong>{{FNumero($totales["totalPagar"] -  $totales["reteRenta"])}}</strong></td>

                            </tr>

                        </table>
                        <!--- Fin Totales-->
                    </td>
                </tr>
                <tr class="cuadro">
                    <td colspan="2" style="font-size:6px;"><span style="margin:0;padding=0;"><center>Condiciones generales de los servicios prestados por
                        {{$emisor["nombre"]}}</center><br style="margin:0;padding=0;">

                    </td>
                </tr>
            </table>
        </div>
    </footer>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 530;
            $y = 10;
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = null;
            $size = 8;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>

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
        
        bottom: 10cm;
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
        anulado
    </div>
    @endif
<!-- Encabezado y QR -->
    <table width="100%">
        <tr valign="top">
            <td width=45%>
                <table width="100%">
                    <tr>
                        <td>
                            <img src="data:image/png;base64,{{$logo}}" alt="logo">
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: x-small;">
                            <strong>{{$comprobante[0][0]["nombre_empresa"]}}</strong>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>NIT:{{$comprobante[0][0]["nit_emisor"]}}</td>
                    </tr>
                    <tr>
                        <td>NRC:{{$comprobante[0][0]["nrc_emisor"]}}</td>
                    </tr>
                    <tr>
                        <td>Actividad económica:{{$comprobante[0][0]["descActividad"]}}</td>
                    </tr>
                    <tr>
                        <td>Dirección: {{$comprobante[0][0]["complemento_emisor"]}}<br>
                        {{$comprobante[0][0]["municipio_emisor"]}},{{$comprobante[0][0]["departamento_emisor"]}}</td>
                    </tr>
                    <tr>
                        <td>Número de teléfono:{{$comprobante[0][0]["telefono"]}}</td>
                    </tr>
                    <tr>
                        <td>Correo electrónico:{{$comprobante[0][0]["correo"]}}</td>
                    </tr>
                    <tr>
                        <td>Nombre comercial:{{$comprobante[0][0]["nombreComercial"]}}</td>
                    </tr>
                    <tr>
                        <td>Tipo de establecimiento:{{Tipo_Establecimiento($comprobante[0][0]["tipo_establecimiento"])}}
                             - {{$comprobante[0][0]["nombre_tienda"]}}
                        </td>
                    </tr>

                </table>
            </td>
            <td>
                <table width="100%" style="border:1px solid #000;">
                    <tr style="background-color: lightgray;">
                        <td colspan="3" align="center" style="font-size: x-small;">
                            <strong>DOCUMENTO TRIBUTARIO ELECTRÓNICO</strong><br>
                            <strong>FACTURA DE EXPORTACION</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Código de Generación:</strong></td>
                        <td colspan="2">{{$comprobante[0][0]["codigoGeneracion"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Sello de recepción:</strong></td>
                        <td colspan="2">{{$comprobante[0][0]["selloRecibido"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Número de Control:</strong></td>
                        <td colspan="2">{{$dte}}</td>
                    </tr>
                    <tr>
                        <td><strong>Modélo facturación:</strong></td>
                        <td>Previo</td>
                        <td><strong>Versión del Json:</strong> {{$comprobante[0][0]["version"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipo de transmisión</strong></td>
                        <td>Normal</td>
                        <td><strong>Fecha emisión:</strong> {{$comprobante[0][0]["fecEmi"]}}</td>
                    </tr>
                    <tr>
                        <td><strong>Hora de emisión:</strong></td>
                        <td>{{$comprobante[0][0]["horEmi"]}}</td>
                        <td><strong>Documento interno No:</strong>{{$comprobante[0][0]["nu_doc"]}}</td>
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
                        <td align="right" width="100px"><strong>Nombre:</strong></td>
                        <td colspan="2" >{{$comprobante[0][0]["id_cliente"]}} - {{$comprobante[0][0]["nombre"]}}  </td>
                    </tr>
                    <tr>
                        <td align="right"><strong>Tipo Documento:</strong></td>
                        <td width="60%">{{$comprobante[0][0]["dsTipoDocumento"]}}</td>
                        <td><strong>No.Documento:</strong> {{$comprobante[0][0]["numDocumento"]}}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>Correo electrónico:</strong></td>
                        <td colspan="2">{{$comprobante[0][0]["correo_receptor"]}}</td>
                        
                    </tr>
                    <tr>
                        <td align="right"><strong>Dirección:</strong></td>
                        <td>{{$comprobante[0][0]["complemento_receptor"]}}</td>
                        <td><strong>Teléfono:</strong> {{$comprobante[0][0]["telefono_receptor"]}}</td>
                    </tr>
                    
                    
                    <tr>
                        <td align="right"><strong>Actividad:</strong></td>
                        <td>{{$comprobante[0][0]["descActividad_receptor"]}}</td>
                        <td><strong>Forma pago:</strong> {{$comprobante[0][0]["condicionOperacion"]}}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>Pais Destino:</strong></td>
                        <td>{{$comprobante[0][0]["Pais"]}}</td>
                        <td><strong>Moneda:</strong>USD</td>
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

    <table width="100%" style="border-collapse:collapse;">
        <thead style="background-color: lightgray;">
            <tr>
                <th class="cuadro">No</th>
                <th class="cuadro">Cnt</th>

                <th class="cuadro">Descripcion</th>
                <th class="cuadro">Precio<br>Unitario</th>
                <th class="cuadro">Descuento<br>por Item</th>
                <th class="cuadro">Otros montos<br>no afectos</th>
                
                <th class="cuadro">Ventas<br>Gravadas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comprobante[1] as $d)
                
            
            <tr>
                <th>{{$loop->index+1}}</th>
                <td>1</td>
                <td>{{$d["descripcion"]}}</td>
                <td align="right">{{FNumero($d["pre_unitario"])}}</td>
                <td align="right">0.00</td>
                <td align="right">{{FNumero($d["imp_int_det"])}}</td>
               
                <td align="right">{{FNumero($d["gravado"])}}</td>
            </tr>
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
                                <td colspan="2"><strong>Valor en Letras:</strong> {{$comprobante[0][0]["total_letras"]}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" style="background-color: lightgray;"><strong>EXTENSIÓN</strong></td>
                            </tr>
                            <tr>
                                <td width="245px"><strong>Nombre entrega</strong> {{$comprobante[0][0]["nombEntrega"]}}</td>
                                <td><strong>No Documento</strong> {{$comprobante[0][0]["docuEntrega"]}}</td>
                            </tr>
                            <tr>
                                <td><strong>Nombre recibe</strong> {{$comprobante[0][0]["nombRecibe"]}}</td>
                                <td><strong>No Documento</strong> {{$comprobante[0][0]["docuRecibe"]}}</td>
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
                                            <td align="right">{{FNumero($comprobante[0][0]["credito"])}}</td>
                                            <td align="right">{{FNumero($comprobante[0][0]["contado"])}}</td>
                                            <td align="right">{{FNumero($comprobante[0][0]["tarjeta"])}}</td>
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
                                <td width="180px">Sumas $</td>
                                <td colspan="3"></td>
                             
                                <td align="right" width="50px" class="sumas" colspan="2">{{FNumero($comprobante[0][0]["tot_gravado"])}}</td>
                                
                            </tr>
                            <tr>
                                <td colspan="4" width="160px">Suma total de operaciones</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero($comprobante[0][0]["subTotalVentas"])}}</td>
                                
                            </tr>
                            <tr>
                                <td colspan="4">Total descuentos</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero(0.00)}}</td>
                                
                            </tr>
                           
                            <tr>
                                <td colspan="4">Sub-Total</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero($comprobante[0][0]["subTotal"])}}</td>
                                
                            </tr>
                            <tr>
                                <td colspan="4">Seguro</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero(0.00)}}</td>
                            
                            </tr>
                            <tr>
                                <td colspan="4">Flete</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero(0.00)}}</td>
                            
                            </tr>
                            <tr>
                                <td colspan="4">Monto Total de la operación</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero($comprobante[0][0]["subTotal"])}}</td>
                            
                            </tr>
                            <tr>
                                <td colspan="4">Total otros montos no afectos</td>
                                <td align="right" class="cuadro-izq" colspan="2">{{FNumero($comprobante[0][0]["totalNoGravado"])}}</td>
                            
                            </tr>
                            <tr>
                                <td colspan="4" ><strong>TOTAL A PAGAR</strong></td>
                                <td align="right" class="cuadro-izq" colspan="2"><strong>{{FNumero($comprobante[0][0]["totalPagar"])}}</strong></td>
                            
                            </tr>
                    
                        </table>
                        <!--- Fin Totales-->
                    </td>
                </tr> 
                <tr class="cuadro">
                    <td colspan="2" style="font-size:6px;"><span style="margin:0;padding=0;"><center>Condiciones generales de los servicios prestados por
                        {{$comprobante[0][0]["nombre_empresa"]}}</center><br style="margin:0;padding=0;">
                    • {{$comprobante[0][0]["nombre_empresa"]}}. declara expresamente que actúa como agente representante o distribuidor de
                    los
                    transportistas aéreos, que previamente la han autorizado para vender transporte aéreo de su propiedad, atendiendo a lo
                    estipulado en el Régimen respectivo del Código de Comercio de El Salvador, por ende, se sujeta estrictamente a las
                    instrucciones emanadas por ellos, sin tener injerencia alguna en cuanto al precio de tarifa , políticas de equipaje,
                    horarios de vuelos, entre otras condiciones.
                    • El contrato de transporte Aéreo se celebra entre el consumidor o pasajero y el transportista aéreo, por ende,
                    {{$comprobante[0][0]["nombre_empresa"]}}, no tiene responsabilidad alguna en casos de muerte o lesiones de los
                    pasajeros, destrucción,
                    perdida o avería de su equipaje, así como por atrasaos, huelgas, terremotos o cualquier otro acontecimiento de fuerza
                    mayor. El contrato se rige por la Ley Orgánica de Aviación Civil y Convenios Internacionales ratificados por el Estado
                    de El Salvador como el Convenio de Montreal y pacto de Varsovia.
                    • El precio cancelado en concepto de boletos aéreos no es reembolsable.
                    • Es obligación del pasajero cumplir los requisitos gubernamentales establecidos para la realización del viaje y
                    disponer de los documentos de salida, entrada, visa, permisos y demás exigencias en El Salvador y/o cualquier otro
                    Estado, así como llegar al aeropuerto a las horas señaladas por el transportista y con la antelación suficiente que le
                    permite completar los tramite de chequeo y salida.
                    • El consumidor declara que previo a la compra de su boleto aéreo o paquete vacacional, personeros de
                    {{$comprobante[0][0]["nombre_empresa"]}}, explicaron cada una de las condiciones descritas anteriormente, entendiéndolas
                    y aceptándolas, eximiéndola
                    de tal forma de cualquier responsabilidad que se derive de ellas.</span>
                    </td>    
                </tr>     
            </table>
        </div>
    </footer>
</body>

</html>
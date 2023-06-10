@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet"
    href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/tables-datatables-advanced-purchases.js') }}"></script>
@endsection

@section('title', 'Reporte de Ventas')

@section('content')
<h4 class="py-3 mb-4 fw-bold">
    <span class="text-muted fw-light">Reportes /</span> Ventas a Contribuyentes
</h4>

<!-- Advanced Search -->
<div class="card">
    <input type="hidden" name="iduser" id="iduser" value="{{ Auth::user()->id }}">
    <div class="card-header">
        <div class="row">
            <div class="col-4">
                <div class="row g-3">
                    <select class="form-control" name="company" id="company">

                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="row g-3">
                    <select class="form-control" name="year" id="year">
                        <?php
                        $year = date("Y");
                        //echo "<option value ='".$year."'>".$year."</option>";
                        for ($i=0; $i < 5 ; $i++) {
                            $yearnew = $year-$i;
                            echo "<option value ='".$yearnew."'>".$yearnew."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="row g-3">
                    <select class="form-control" name="period" id="period">
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <button type="button" id="first-filter"
                    class="btn rounded-pill btn-primary waves-effect waves-light">Buscar</button>
            </div>
        </div>
    </div>
    <!--Search Form -->
    <div id="areaImprimir" class="table-responsive">
        <table class="table" style="">
            <thead style="font-size: 13px;">
                <tr>
                    <th class="text-center" colspan="14">
                        LIBRO DE VENTAS CONTRIBUYENTES (Valores expresados en USD)
                    </th>
                </tr>
                <tr>
                    <td class="text-center" colspan="14" style="font-size: 13px;">
                        <b>Nombre del Contribuyente:</b> <?php echo @$em['nombre']; ?> &nbsp;&nbsp;<b>N.R.C.:</b> <?php echo @$em['nrc']; ?> &nbsp;&nbsp;<b>NIT:</b>&nbsp;<?php echo @$em['nit']; ?>&nbsp;&nbsp; <b>MES:</b><?php echo @$mes ?> &nbsp;&nbsp;<b>AÑO:</b> <?php echo @$anio; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="3" class="text-right" style="font-size: 11px;">
                        <b>VENTAS PROPIAS</b>
                    </td>
                    <td colspan="3" class="text-left" style="font-size: 11px;">
                        <b>A CUENTA DE TERCEROS</b>
                    </td>
                </tr>
                <tr	style="text-transform: uppercase;">
                    <td style="font-size: 10px; text-align: left;"><b>NUM. <br> CORR.</b></td>
                    <td style="font-size: 10px; text-align: left;"><b>Fecha<br>Emisiﾃｳn</b></td>
                    <td style="font-size: 10px; text-align: left;"><b>Num. <br> Doc.</b></td>
                    <td style="font-size: 10px;"><b>Nombre del Cliente</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>NRC</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Exentas</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Internas <br>Gravadas</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Debito<br>Fiscal</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>No<br>Sujetas</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Exentas</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Internas<br>Gravadas</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>Debito<br>Fiscal</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>IVA<br>Percibido</b></td>
                    <td style="font-size: 10px; text-align: right;"><b>TOTAL</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // $total_ex = 0;
                    // $total_gv = 0;
                    // $total_gv2 =0;
                    // $total_iva = 0;
                    // $total_iva2 =0;
                    // $total_ns = 0;
                    // $tot_final = 0;
                    // $vto = 0;

                    // //$dcompras = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente WHERE fecha BETWEEN '".$anio."-".$mes."-01' AND '".$anio."-".$mes."-31' AND ventas.id_empresa = '".$empresa."' AND clientes.tipo = 'J' AND id_documento = '2' ORDER BY correlativo ASC";
                    // $dcompras="select *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha from ventas a
                    //                                                     LEFT JOIN clientes b on a.id_cliente=b.id_cliente
                    //                                                                 where a.id_documento=2
                    //                                                                 AND a.fecha BETWEEN '".$anio."-".$mes."-01' AND '".$anio."-".$mes."-31'
                    //                                                                 AND a.id_empresa=$empresa
                    //                                                                 ORDER BY a.correlativo ASC";
                    //                                                 $dcompras = mysql_query($dcompras, $cn);
                    // $i = 1;


                    // while ($com = mysql_fetch_array($dcompras)) {
                ?>
                    <tr>
                        <td style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php echo @$i; ?>
                        </td>
                        <td style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php echo @$com['fecha']; ?>
                        </td>
                        <td style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php echo @$com['correlativo'] ?>
                        </td>
                        <td class="text-uppercase" style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php
                                // $estado = "SELECT estado FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);

                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "ANULADA";
                                //     }else{ //limitar caracteres
                                //         echo substr($com['nombre'],0,30);
                                //     }
                                // }
                            ?>
                        </td>
                        <td style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php echo @$com['nrc']; ?>
                        </td>
                        <td class="text-uppercase" style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php   //exentas
                                // $estado = "SELECT estado, acuenta FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);

                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "0.00";
                                //     }elseif (empty($est['acuenta'])) {
                                //         $sumEx = "SELECT SUM(exentas) AS tEx FROM detallesv WHERE id_venta = '".$com['id_venta']."'";
                                //         $sumEx = mysql_query($sumEx, $cn);
                                //         $sEx = mysql_fetch_array($sumEx);
                                //         echo number_format($sEx['tEx'], 2);

                                //         $total_ex = $total_ex + $sEx['tEx'];
                                //     }else{
                                //         echo "0.00";
                                //     }
                                // }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                                <?php
                                                                                            //gravadas
                            //  $estado = "SELECT estado, acuenta FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                            //     $estado = mysql_query($estado, $cn);

                            //     while ($est = mysql_fetch_array($estado)) {
                            //         if ($est['estado'] == "Anulada"){
                            //             echo "0.00";
                            //         }elseif (empty($est['acuenta'])) {
                            //             $sumGv = "SELECT SUM(punitario * cantidad) AS tGv FROM detallesv WHERE id_venta = '".$com['id_venta']."' AND exentas = '0' AND nosujeta = '0'";
                            //             $sumGv = mysql_query($sumGv, $cn);
                            //             $sGv = mysql_fetch_array($sumGv);

                            //             if ($sGv['tGv'] == 0) {
                            //                 echo number_format($sGv['tGv'],2);
                            //             }else{
                            //                 echo number_format($sGv['tGv'],2);
                            //                 $total_gv = $total_gv + $sGv['tGv'];
                            //             }

                            //         }else{
                            //             echo "0.00";
                            //         }
                            //     }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php
                                                                                        //debito fiscal
                                // $estado = "SELECT estado, acuenta FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);
                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "0.00";
                                //     }elseif (empty($est['acuenta'])){
                                //         $sumDb = "SELECT SUM(punitario * cantidad) AS tDf FROM detallesv WHERE id_venta = '".$com['id_venta']."' AND exentas = '0' AND nosujeta = '0'";
                                //         $sumDb = mysql_query($sumDb, $cn);
                                //         $sDf = mysql_fetch_array($sumDb);

                                //         if ($sDf['tDf'] == 0) {
                                //             echo number_format($sDf['tDf'],2);
                                //         }else{
                                //             echo $dbf = number_format($sDf['tDf'] * $viva,2);
                                //             $total_iva = $total_iva + $dbf;
                                //         }
                                //     }else{
                                //         echo "0.00";
                                //     }
                                // }
                            ?>

                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php
                                                                                        //no sujetas
                                // $estado = "SELECT estado FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);
                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "0.00";
                                //     }else{
                                //         $sumNs = "SELECT SUM(nosujeta) AS tNs FROM detallesv WHERE id_venta = '".$com['id_venta']."'";
                                //         $sumNs = mysql_query($sumNs, $cn);
                                //         $sNs = mysql_fetch_array($sumNs);

                                //         if ($sNs['tNs'] == 0) {
                                //             echo number_format($sNs['tNs'],2);
                                //         }else{
                                //             echo $ns = number_format($sNs['tNs'],2);
                                //             $total_ns = $total_ns + $ns;
                                //         }
                                //     }
                                // }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php //exentas a terceros  ?> 0.00
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                                                                    //internas gravadas a terceros
                            //  $estado = "SELECT estado, acuenta FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                            //     $estado = mysql_query($estado, $cn);

                            //     while ($est = mysql_fetch_array($estado)) {
                            //         if ($est['estado'] == "Anulada"){
                            //             echo "0.00";
                            //         }elseif (empty($est['acuenta'])) {
                            //             echo "0.00";

                            //         }else{
                            //             $sumGv = "SELECT SUM(punitario * cantidad) AS tGv FROM detallesv WHERE id_venta = '".$com['id_venta']."' AND exentas = '0' AND nosujeta = '0'";
                            //             $sumGv = mysql_query($sumGv, $cn);
                            //             $sGv = mysql_fetch_array($sumGv);

                            //             if ($sGv['tGv'] == 0) {
                            //                 echo number_format($sGv['tGv'],2);
                            //             }else{
                            //                 echo number_format($sGv['tGv'],2);
                            //                 $total_gv2 = $total_gv2 + $sGv['tGv'];
                            //             }
                            //         }
                            //     }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">

                                                                                <?php
                                                                                        //debito fiscal a terceros
                                // $estado = "SELECT estado, acuenta FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);
                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "0.00";
                                //     }elseif (empty($est['acuenta'])){
                                //         echo "0.00";
                                //     }else{
                                //         $sumDb = "SELECT SUM(punitario * cantidad) AS tDf FROM detallesv WHERE id_venta = '".$com['id_venta']."' AND exentas = '0' AND nosujeta = '0'";
                                //         $sumDb = mysql_query($sumDb, $cn);
                                //         $sDf = mysql_fetch_array($sumDb);

                                //         if ($sDf['tDf'] == 0) {
                                //             echo number_format($sDf['tDf'],2);
                                //         }else{
                                //             echo $dbf = number_format($sDf['tDf'] * $viva,2);
                                //             $total_iva2 = $total_iva2 + $dbf;
                                //         }
                                //     }
                                // }
                            ?>
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php //iva percibido ?>	0.00
                        </td>
                        <td style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                            <?php

                                // $estado = "SELECT estado FROM ventas WHERE id_venta = '".$com['id_venta']."'";
                                // $estado = mysql_query($estado, $cn);

                                // while ($est = mysql_fetch_array($estado)) {
                                //     if ($est['estado'] == "Anulada"){
                                //         echo "0.00";
                                //     }else{

                                //         echo number_format($com['total'],2);
                                //         $vto = $vto + $com['total'];
                                //     }
                                // }
                            ?>

                        </td>
                    </tr>



                <?php
                    //     ++$i;
                    // }
                ?>
                <tr style="text-align: right;">
                        <td colspan="5" class="text-right" style="font-size: 9px;">
                            <b>TOTALES DEL MES</b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_ex,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_gv,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_iva,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_ns,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b>0.00</b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_gv2,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$total_iva2,2);
                            ?></b>
                        </td>
                        <td style="font-size: 10px;">
                            <b>0.00</b>
                        </td>
                        <td style="font-size: 10px;">
                            <b><?php
                                echo number_format(@$vto,2);
                            ?></b>
                        </td>
                    </tr>
            </tbody>
        </table>
                                        <?php
                                        ?>
                                        <table style="text-align: center; font-size: 10px" align="center" border="1">
                    <tr>
                        <td rowspan="2"><b>RESUMEN OPERACIONES</b></td>
                        <td colspan="2"><b>PROPIAS</b></td>
                        <td colspan="2"><b>A CUENTA DE TERCEROS</b></td>
                    </tr>
                    <tr>
                        <td style="width: 100px;"><b>VALOR <br> NETO</b></td>
                        <td style="width: 100px;"><b>DEBITO <br> FISCAL</b></td>
                        <td style="width: 100px;"><b>VALOR <br> NETO</b></td>
                        <td style="width: 100px;"><b>DEBITO <br> FISCAL</b></td>
                        <td style="width: 100px;"><b>IVA <br> PERCIBIDO</b></td>
                    </tr>
                    <tr style="text-align: left;">
                        <td style="width: 400px;">&nbsp;&nbsp;VENTAS NETAS INTERNAS GRAVADAS A CONTRIBUYENTES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_gv,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_iva,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_gv2,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_iva2,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS GRAVADAS A CONSUMIDORES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@@$consumidor['gravadas'],2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@@$consumidor['debito_fiscal'],2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@@$consumidor['ter_gravado'],2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@@$consumidor['ter_debitofiscal'],2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS GRAVADAS</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@$total_gv+@$consumidor['gravadas'],2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@$total_iva+@$consumidor['debito_fiscal'],2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@$total_gv2+@$consumidor['ter_gravado'],2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@$total_iva2+@$consumidor['ter_debitofiscal'],2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS EXENTAS A CONTRIBUYENTES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_ex,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS EXENTAS A CONSUMIDORES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@@$consumidor['exentas'],2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">

                        <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS EXENTAS</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@$total_ex+@$consumidor['exentas'],2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS NO SUJETAS A CONTRIBUYENTES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@$total_ns,2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS NO SUJETAS A CONSUMIDORES</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                    <tr style="text-align: left;">
                        <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS NO SUJETAS</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                        <td style="text-align: right;"><b>$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</b></td>
                    </tr>
                    <tr style="text-align: left;">
                        <td>&nbsp;&nbsp;EXPORTACIONES SEGUN FACTURAS DE EXPORTACION</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;</td>
                    </tr>
                </table>
    </div><br>
</div>
<!--/ Advanced Search -->
@endsection

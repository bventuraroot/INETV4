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
    <div id="areaImprimir" class="table-responsive">
        <table class="table" style="">
        <thead>
            <tr>
            <th class="text-center" colspan="14">
                LIBRO DE VENTAS CONTRIBUYENTES
            </th>
            </tr>
            <tr>
            <td class="text-center" colspan="14">
                <b>Nombre del Contribuyente: <?php echo @$em['nombre']; ?> &nbsp;&nbsp;N.R.C.: <?php echo @$em['nrc']; ?> &nbsp;&nbsp;NIT:&nbsp;<?php echo @$em['nit']; ?>&nbsp;&nbsp; MES:<?php echo @$mes ?> &nbsp;&nbsp;AÑO: <?php echo @$anio; ?> <p>(Valores expresados en Dolares Estadounidenses)</p></b>
            </td>
            </tr>
            <tr>
            <td colspan="8" class="text-right">
                VENTAS PROPIAS
            </td>
            <td colspan="3" class="text-right">
                A CUENTA DE TERCEROS
            </td>
            </tr>
            <tr style="text-align: center; text-transform: uppercase;">
            <td style="font-size: 7px;"><b>Corr.</b></td>
            <td style="font-size: 9px;"><b>Fecha<br>Emisiﾃｳn</b></td>
            <td style="font-size: 7px;"><b>No.Doc.</b></td>
            <td style="font-size: 9px;"><b>Nombre del Cliente</b></td>
            <td style="font-size: 9px;"><b>NRC</b></td>
            <td style="font-size: 7px;"><b>Exentas</b></td>
            <td style="font-size: 7px;"><b>Internas <br>Gravadas</b></td>
            <td style="font-size: 7px;"><b>Debito<br>Fiscal</b></td>
            <td style="font-size: 7px;"><b>No<br>Sujetas</b></td>
            <td style="font-size: 7px;"><b>Exentas</b></td>
            <td style="font-size: 7px;"><b>Internas<br>Gravadas</b></td>
            <td style="font-size: 7px;"><b>Debito<br>Fiscal</b></td>
            <td style="font-size: 7px;"><b>IVA<br>Percibido</b></td>
            <td style="font-size: 7px;"><b>TOTAL</b></td>
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
            // $tot_ip = 0;
            // $vto = 0;

            //  $dcompras = "SELECT * FROM vdirectas ORDER BY femision";
            // $dcompras = mysql_query($dcompras, $cn);
            // $i = 1;


            // while (@$com = mysql_fetch_array($dcompras)) {
            ?>
            <tr>
                <td style="text-align: center; font-size: 9px;">
                <?php echo @$i; ?>
                </td>
                <td style="text-align: center; font-size: 9px;">
                <?php echo @$com['femision']; ?>
                </td>
                <td style="text-align: center; font-size: 9px;">
                <?php echo @$com['numero_doc'] ?>
                </td>
                <td class="text-uppercase" style="font-size: 9px;">
                <?php
                    // $estado = "SELECT * FROM clientes WHERE id_cliente = '".@$com['id_cliente']."'";
                    // $estado = mysql_query($estado, $cn);

                    // while ($est = mysql_fetch_array($estado)) {
                    //     echo $est['nombre'];
                    // }
                ?>
                </td>
                <td style="font-size: 9px;">
                <?php
                    // $estado = "SELECT * FROM clientes WHERE id_cliente = '".@$com['id_cliente']."'";
                    // $estado = mysql_query($estado, $cn);

                    // while ($est = mysql_fetch_array($estado)) {
                    //     echo $est['nrc'];
                    // }
                ?>
                </td>
                <td class="text-uppercase" style="text-align: right; font-size: 10px;">
                <?php echo number_format(@@$com['vtas_exentas'],2);
                    @$total_ex = $total_ex + @$com['vtas_exentas'];
                ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                    <?php echo number_format(@@$com['sumas'],2);
                    @$total_gv = $total_gv + @$com['sumas'];
                     ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php
                    $tiva = @$com['sumas']* @$viva;
                    echo number_format(@$tiva,2);
                    @$total_iva = $total_iva + $tiva;
                    ?>

                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php echo number_format(@@$com['vtas_nosujetas'],2);
                    @$total_ns = $total_ns + @$com['vtas_nosujetas'];
                    ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php echo "0.00"; ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php echo "0.00"; ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                 <?php echo "0.00"; ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php echo number_format(@@$com['ipercibido'],2) ;
                    @$tot_ip = $tot_ip + @$com['ipercibido'];
                ?>
                </td>
                <td style="text-align: right; font-size: 10px;">
                <?php
                    @$t_final = @$com['sumas']+ $tiva + @$com['vtas_nosujetas'] + @$com['vtas_exentas'];
                    echo number_format(@$t_final,2);
                    @$vto = $vto + $t_final;
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
                <b><?php
                    echo number_format(@$tot_ip,2);
                ?></b>
                </td>
                <td style="font-size: 10px;">
                <b><?php
                    echo number_format(@$vto,2);
                ?></b>
                </td>
            </tr>
        </tbody>
        </table>
    </div><br>
</div>
<!--/ Advanced Search -->
@endsection

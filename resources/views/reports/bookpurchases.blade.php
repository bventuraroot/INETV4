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
<script src="{{ asset('assets/js/tables-purchases.js') }}"></script>
@endsection

@section('title', 'Reporte de Ventas')

@section('content')
<h4 class="py-3 mb-4 fw-bold">
    <span class="text-muted fw-light">Reportes /</span> Libro de Compras
</h4>

<!-- Advanced Search -->
<div class="card">
    <form id="sendfilters" action="{{Route('report.comprassearch')}}" method="post">
        @csrf @method('POST')
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
                            if(isset($year)){
                                if($yearnew==@$yearB){
                                    $selected="selected";
                                }else {
                                    $selected="";
                                }

                            }
                            echo "<option value ='".$yearnew."' ".$selected.">".$yearnew."</option>";
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row g-3">
                        <select class="form-control" name="period" id="period">
                            <option value="01" <?php echo (@$period=='01' ) ? "selected" : "" ?>>Enero</option>
                            <option value="02" <?php echo (@$period=='02' ) ? "selected" : "" ?>>Febrero</option>
                            <option value="03" <?php echo (@$period=='03' ) ? "selected" : "" ?>>Marzo</option>
                            <option value="04" <?php echo (@$period=='04' ) ? "selected" : "" ?>>Abril</option>
                            <option value="05" <?php echo (@$period=='05' ) ? "selected" : "" ?>>Mayo</option>
                            <option value="06" <?php echo (@$period=='06' ) ? "selected" : "" ?>>Junio</option>
                            <option value="07" <?php echo (@$period=='07' ) ? "selected" : "" ?>>Julio</option>
                            <option value="08" <?php echo (@$period=='08' ) ? "selected" : "" ?>>Agosto</option>
                            <option value="09" <?php echo (@$period=='09' ) ? "selected" : "" ?>>Septiembre</option>
                            <option value="10" <?php echo (@$period=='10' ) ? "selected" : "" ?>>Octubre</option>
                            <option value="11" <?php echo (@$period=='11' ) ? "selected" : "" ?>>Noviembre</option>
                            <option value="12" <?php echo (@$period=='12' ) ? "selected" : "" ?>>Diciembre</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <button type="button" id="first-filter"
                        class="btn rounded-pill btn-primary waves-effect waves-light">Buscar</button>
                </div>
            </div>
        </div>
    </form>
    @isset($heading)
    <?php
    $mesesDelAno = array(
  "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
);

$mesesDelAnoMayuscula = array_map('strtoupper', $mesesDelAno);
    ?>
    <div class="row">
        <div class="col-12">
            <div class="box-header" style="text-align: right; margin-right: 6%;">
                <a href="#!" class='btn btn-success' title='Imprimir credito' onclick="impFAC('areaImprimir');">
                    <i class="fa-solid fa-print"> </i> &nbsp;&nbsp;Imprimir
                </a>
            </div>
        </div>
    </div>
    <div id="areaImprimir">
        <table class="table" style="font-size: 8px;">
            <thead style="font-size: 12px;">
                <tr>
                    <th class="text-center" colspan="13">
                        <b>LIBRO DE COMPRAS (Valores expresados en USD)</b>
                    </th>
                </tr>
                <tr>
                    <td class="text-center" colspan="13">
                        <b>Nombre del Contribuyente:</b>
                        <?php echo $heading['name']; ?>
                        <b>N.R.C.:</b>
                        <?php echo $heading['ncr']; ?>
                        <b>NIT.:</b>
                        <?php echo $heading['nit']; ?>
                        <b>MES:</b>
                        <?php echo $mesesDelAnoMayuscula[(int)$period-1] ?>
                        <b>Año:</b>
                        <?php echo $yearB; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="3" class="text-right" style="font-size: 11px;">
                        <b>COMPRAS EXENTAS</b>
                    </td>
                    <td colspan="3" class="text-left" style="font-size: 11px;">
                        <b>COMPRAS GRAVADAS</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 10px; width: 2%;"><b>NUM.<br>CORR</b></td>
                    <td style="font-size: 10px; width: 4%;"><b>FECHA<br>EMISION</b></td>
                    <td style="font-size: 10px; width: 3%;"><b>NUM.<br>DOC.</b></td>
                    <td style="font-size: 10px; width: 8%;"><b>NRC</b></td>
                    <td style="font-size: 10px; width: 45%;"><b>PROVEEDOR</b></td>
                    <td style="font-size: 10px; width: 6%; text-align: center;"><b>FOV</b></td>
                    <td style="font-size: 10px; width: 6%; text-align: center;"><b>COT</b></td>
                    <td style="font-size: 10px; width: 6%; text-align: center;"><b>CESC</b></td>
                    <td style="font-size: 10px; width: 6%; text-align: center;"><b>INTER</b></td>
                    <td style="font-size: 10px; width: 6%; text-align: center;"><b>IMPORT.</b></td>
                    <td style="font-size: 10px; width: 5%; text-align: center;"><b>INTER</b></td>
                    <td style="font-size: 10px; width: 5%; text-align: center;"><b>IMPORT.</b></td>
                    <td style="font-size: 10px; width: 5%; text-align: center;"><b>CREDITO <br>FISCAL</b></td>
                    <td style="font-size: 10px; width: 5%; text-align: center;"><b>IVA <br>RET</b></td>
                    <td style="font-size: 10px; width: 5%; text-align: center;"><b>TOTAL </b></td>

                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    $tvg = 0;
                    $tcf = 0;
                    $tto = 0;
                    $t_ex = 0;
                    $tot_fovial = 0;
                    $tot_cesc = 0;
                    $tot_cot = 0;
                    $total_iretenido = 0;
                    $vt = 0;
                ?>
                @foreach ($purchases as $purchase)
                <tr>
                    <td
                        style="font-size: 10px; text-align: center; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo $i;
                            ?>
                    </td>

                    <td
                        style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo $purchase['dateF'];
                            ?>
                    </td>

                    <td
                        style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo $purchase['number'];
                            ?>
                    </td>

                    <td
                        style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo $purchase['nrc'];
                            ?>
                    </td>
                    <td class="text-uppercase"
                        style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                $nprov = substr($purchase['razonsocial'],0,30);
                                echo $nprov;
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['fovial'],2);
                                $tot_fovial += $purchase['fovial'];
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['contrns'],2);
                                $tot_cot += $purchase['contrns'];
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['cesc'],2);
                                $tot_cesc += $purchase['cesc'];
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['exenta'],2);
                                $t_ex += $purchase['exenta'];
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        0.00
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                $tvg = $tvg + $purchase['gravada'];
                                echo number_format($purchase['gravada'],2);
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        0.00
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                $tcf = $tcf + $purchase['iva'];
                                echo number_format($purchase['iva'],2);
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['iretenido'],2);
                                $total_iretenido += $purchase['iretenido'];
                            ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php
                                echo number_format($purchase['total'],2);
                                $vt = $vt + $purchase['total'];
                            ?>
                    </td>
                </tr>
                <?php ++$i; ?>
                @endforeach
                <tr>
                    <td colspan="5" class="text-center" style="font-size: 10px;">
                        <b>TOTALES</b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($tot_fovial,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($tot_cot,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($tot_cesc,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($t_ex,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>0.00</b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($tvg,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>0.00</b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($tcf,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($total_iretenido,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px; text-align: right;">
                        <b>
                            <?php
                                echo number_format($vt,2);
                            ?>
                        </b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endisset
<!--Search Form -->
<!--/ Advanced Search -->
@endsection

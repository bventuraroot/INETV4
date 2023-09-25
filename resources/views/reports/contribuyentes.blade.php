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
<script src="{{ asset('assets/js/tables-contribuyentes.js') }}"></script>
@endsection

@section('title', 'Reporte de Ventas')

@section('content')
<h4 class="py-3 mb-4 fw-bold">
    <span class="text-muted fw-light">Reportes /</span> Ventas a Contribuyentes
</h4>

<!-- Advanced Search -->
<div class="card">
    <form id="sendfilters" action="{{Route('report.contribusearch')}}" method="post">
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
                            <?php if(empty($period)){
                                $period = date('m')-1;
                            } ?>
                            <option value="01" <?php echo (@$period == '01') ? "selected" : "" ?>>Enero</option>
                            <option value="02" <?php echo (@$period == '02') ? "selected" : "" ?>>Febrero</option>
                            <option value="03" <?php echo (@$period == '03') ? "selected" : "" ?>>Marzo</option>
                            <option value="04" <?php echo (@$period == '04') ? "selected" : "" ?>>Abril</option>
                            <option value="05" <?php echo (@$period == '05') ? "selected" : "" ?>>Mayo</option>
                            <option value="06" <?php echo (@$period == '06') ? "selected" : "" ?>>Junio</option>
                            <option value="07" <?php echo (@$period == '07') ? "selected" : "" ?>>Julio</option>
                            <option value="08" <?php echo (@$period == '08') ? "selected" : "" ?>>Agosto</option>
                            <option value="09" <?php echo (@$period == '09') ? "selected" : "" ?>>Septiembre</option>
                            <option value="10" <?php echo (@$period == '10') ? "selected" : "" ?>>Octubre</option>
                            <option value="11" <?php echo (@$period == '11') ? "selected" : "" ?>>Noviembre</option>
                            <option value="12" <?php echo (@$period == '12') ? "selected" : "" ?>>Diciembre</option>
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
                        <b>Nombre del Contribuyente:</b>
                        <?php echo $heading['name']; ?> &nbsp;&nbsp;<b>N.R.C.:</b>
                        <?php echo $heading['nrc']; ?> &nbsp;&nbsp;<b>NIT:</b>&nbsp;
                        <?php echo $heading['nit']; ?>&nbsp;&nbsp; <b>MES:</b>
                        <?php echo $mesesDelAnoMayuscula[(int)$period-1] ?> &nbsp;&nbsp;<b>AÑO:</b>
                        <?php echo $yearB; ?>
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
                <tr style="text-transform: uppercase;">
                    <td style="font-size: 10px; text-align: left;"><b>NUM. <br> CORR.</b></td>
                    <td style="font-size: 10px; text-align: left;"><b>Fecha<br>Emisión</b></td>
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
                    $total_ex = 0;
                    $total_gv = 0;
                    $total_gv2 =0;
                    $total_iva = 0;
                    $total_iva2 =0;
                    $total_ns = 0;
                    $tot_final = 0;
                    $vto = 0;
                    $total_iva2P = 0;
                    $i = 1;
                ?>
                @foreach ($sales as $sale)
                <tr>
                    <td
                        style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php echo $i; ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php echo $sale['dateF']; ?>
                    </td>
                    <td
                        style="font-size: 10px; text-align: left; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php echo $sale['correlativo'] ?>
                    </td>
                    <td class="text-uppercase"
                        style="font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                            @if($sale['tpersona']=='J')
                                {{$sale['comercial_name']}}
                            @endif
                            @if($sale['tpersona']=='N')
                                {{$sale['firstname'] .' '.$sale['firstlastname'] }}
                            @endif
                        @endif

                    </td>
                    <td
                        style="font-size: 10px; text-align: right; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php echo $sale['ncrC']; ?>
                    </td>
                    <td class="text-uppercase"
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['exenta'], 2) }}
                        @endif
                        <?php
                            $total_ex = $total_ex + $sale['exenta'];
                            ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['gravada'], 2) }}
                        @endif
                        <?php
                        $total_gv = $total_gv + $sale['gravada'];
                            ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['iva'], 2) }}
                        @endif
                        <?php
                        $total_iva = $total_iva + $sale['iva'];
                            ?>

                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['nosujeta'], 2) }}
                        @endif
                        <?php
                        $total_ns = $total_ns + $sale['nosujeta'];
                            ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        <?php //exentas a terceros  ?>$ 0.00
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['gravada'], 2) }}
                        @endif
                        <?php
                        $total_gv2 = $total_gv2 + $sale['gravada'];
                            ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['iva'], 2) }}
                        @endif
                        <?php
                        $total_iva2 = $total_iva2 + $sale['iva'];
                            ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['ivaP'], 2) }}
                        @endif
                        <?php $total_iva2P = $total_iva2P + $sale['ivaP']; ?>
                    </td>
                    <td
                        style="text-align: right; font-size: 10px; padding-top: 0px; margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">
                        @if($sale['typesale']=='0')
                            ANULADO
                            @else
                             {{ number_format($sale['totalamount'], 2) }}
                        @endif
                        <?php
                            $vto = $vto + $sale['totalamount'];
                            ?>

                    </td>
                </tr>
                <?php
                    ++$i;
                ?>
                @endforeach

                <tr style="text-align: right;">
                    <td colspan="5" class="text-right" style="font-size: 9px;">
                        <b>TOTALES DEL MES</b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_ex,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_gv,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_iva,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_ns,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>0.00</b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_gv2,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($total_iva2,2);
                            ?>
                        </b>
                    </td>
                    <td style="font-size: 10px;">
                        <b><?php
                            echo number_format($total_iva2P,2);
                        ?></b>
                    </td>
                    <td style="font-size: 10px;">
                        <b>
                            <?php
                                echo number_format($vto,2);
                            ?>
                        </b>
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
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_gv,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_iva,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_gv2,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_iva2,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS GRAVADAS A CONSUMIDORES</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@@$consumidor['gravadas'],2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@@$consumidor['debito_fiscal'],2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@@$consumidor['ter_gravado'],2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@@$consumidor['ter_debitofiscal'],2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">
                <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS GRAVADAS</b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@$total_gv+@$consumidor['gravadas'],2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@$total_iva+@$consumidor['debito_fiscal'],2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@$total_gv2+@$consumidor['ter_gravado'],2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@$total_iva2+@$consumidor['ter_debitofiscal'],2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS EXENTAS A CONTRIBUYENTES</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_ex,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS EXENTAS A CONSUMIDORES</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@@$consumidor['exentas'],2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">

                <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS EXENTAS</b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@$total_ex+@$consumidor['exentas'],2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS NO SUJETAS A CONTRIBUYENTES</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@$total_ns,2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;VENTAS NETAS INTERNAS NO SUJETAS A CONSUMIDORES</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
            <tr style="text-align: left;">
                <td><b>&nbsp;&nbsp;TOTAL OPERACIONES INTERNAS NO SUJETAS</b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
                <td style="text-align: right;"><b>$
                        <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                    </b></td>
            </tr>
            <tr style="text-align: left;">
                <td>&nbsp;&nbsp;EXPORTACIONES SEGUN FACTURAS DE EXPORTACION</td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
                <td style="text-align: right;">$
                    <?php echo number_format(@'0.00',2) ?>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </div><br>
</div>
    @endisset
    <!--Search Form -->
<!--/ Advanced Search -->
@endsection

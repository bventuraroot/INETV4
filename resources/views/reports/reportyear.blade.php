@php
$configData = Helper::appClasses();

$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
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
    <span class="text-muted fw-light">Reportes /</span>Consolidado de ventas y compras por año
</h4>
<style>
    table.paleBlueRows {
        font-family: "Times New Roman", Times, serif;
        border: 1px solid #FFFFFF;
        width: 350px;
        height: 200px;
        text-align: center;
        border-collapse: collapse;
    }

    table.paleBlueRows td,
    table.paleBlueRows th {
        border: 1px solid #FFFFFF;
        padding: 3px 2px;
    }

    table.paleBlueRows tbody td {
        font-size: 13px;
    }

    table.paleBlueRows tr:nth-child(even) {
        background: #D0E4F5;
    }

    table.paleBlueRows thead {
        background: #0B6FA4;
        border-bottom: 5px solid #FFFFFF;
    }

    table.paleBlueRows thead th {
        font-size: 17px;
        font-weight: bold;
        color: #FFFFFF;
        text-align: center;
        border-left: 2px solid #FFFFFF;
    }

    table.paleBlueRows thead th:first-child {
        border-left: none;
    }

    table.paleBlueRows tfoot td {
        font-size: 14px;
    }
</style>
<!-- Advanced Search -->
<div class="card">
    <form id="sendfilters" action="{{Route('report.yearsearch')}}" method="post">
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
        <table id="consolidado" border="4" class="text-center paleBlueRows" style="width: 100%">
            <thead>
                <tr>
                    <td colspan="8" style="color: white;">{{$heading['name']}}</td>
                </tr>
                <tr style="color: black;">
                    <td><?php echo $anio = (isset($_REQUEST['anio'])) ? @$_REQUEST['anio'] : date('Y'); ?></td>
                    <td colspan="3">VENTAS</td>
                    <td colspan="3">COMPRAS</td>
                    <td rowspan="2">DIFERENCIA</td>
                </tr>
                <tr style="color: white;">
                    <td>MESES</td>
                    <td>GRAVADAS</td>
                    <td>DEBITO</td>
                    <td>TOTAL</td>
                    <td>INTERNAS</td>
                    <td>CREDITO</td>
                    <td>TOTAL</td>
                </tr>
            </thead>
            <!-- ... Tu código HTML anterior ... -->

            <tbody>
                @php
                    $totalgravadas = 0;
                    $totaldebito = 0;
                    $totalventas = 0;
                    $totalinternas = 0;
                    $totalcredito = 0;
                    $totalcompras = 0;
                    $totaldiferencia = 0;
                @endphp
                @for ($i = 1; $i <= 12; $i++)
                    <tr>
                        <th>{{ $meses[$i-1] }}</th>
                        @php
                            $ventasMes = 0;
                            $comprasMes = 0;

                                $ventasEncontrados = $sales->filter(function($sale) use ($i) {
                                    return $sale->monthsale == $i;
                                });
                            @endphp

                            @if (!$ventasEncontrados->isEmpty())
                                @foreach ($ventasEncontrados as $sale)
                                    <th>$ {{ number_format($sale->GRAVADAS, 2) }}</th>
                                    <th>$ {{ number_format($sale->DEBITO, 2) }}</th>
                                    <th>$ {{ number_format($sale->TOTALV, 2) }}</th>
                                    @php
                                        $totalgravadas += $sale->GRAVADAS;
                                        $totaldebito += $sale->DEBITO;
                                        $totalventas += $sale->TOTALV;
                                        $ventasMes += $sale->TOTALV;
                                    @endphp
                                @endforeach
                            @else
                                <th>$ 0.00</th>
                                <th>$ 0.00</th>
                                <th>$ 0.00</th>
                            @endif

                            @php
                                $comprasEncontradas = $purchases->filter(function($purchase) use ($i) {
                                    return $purchase->monthpurchase == $i;
                                });
                            @endphp

                            @if (!$comprasEncontradas->isEmpty())
                                @foreach ($comprasEncontradas as $purchase)
                                    <th>$ {{ number_format($purchase->INTERNASPU, 2) }}</th>
                                    <th>$ {{ number_format($purchase->CREDITOPU, 2) }}</th>
                                    <th>$ {{ number_format($purchase->TOTALC, 2) }}</th>
                                    @php
                                        $totalinternas += $purchase->INTERNASPU;
                                        $totalcredito += $purchase->CREDITOPU;
                                        $totalcompras += $purchase->TOTALC;
                                        $comprasMes += $purchase->TOTALC;
                                    @endphp
                                @endforeach
                            @else
                                <th>$ 0.00</th>
                                <th>$ 0.00</th>
                                <th>$ 0.00</th>
                            @endif

                        <!-- Celda de diferencia para este mes -->
                        <th>$ {{ number_format($ventasMes - $comprasMes, 2) }}</th>

                        <!-- Actualizar totales generales -->
                        @php
                            $totaldiferencia += ($ventasMes - $comprasMes);
                        @endphp
                    </tr>
                @endfor

                <!-- Fila de totales generales -->
                <tr style="color: yellow; background-color: black;">
                    <th>TOTALES</th>
                    <th>$ {{ number_format($totalgravadas, 2) }}</th>
                    <th>$ {{ number_format($totaldebito, 2) }}</th>
                    <th>$ {{ number_format($totalventas, 2) }}</th>
                    <th>$ {{ number_format($totalinternas, 2) }}</th>
                    <th>$ {{ number_format($totalcredito, 2) }}</th>
                    <th>$ {{ number_format($totalcompras, 2) }}</th>
                    <th>$ {{ number_format($totaldiferencia, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    @endisset
    <!--Search Form -->
<!--/ Advanced Search -->
@endsection

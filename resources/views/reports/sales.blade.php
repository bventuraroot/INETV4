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
<script src="{{ asset('assets/js/tables-datatables-advanced.js') }}"></script>
@endsection

@section('title', 'Reporte de Ventas')

@section('content')
<h4 class="py-3 mb-4 fw-bold">
    <span class="text-muted fw-light">Reportes /</span> Ventas
</h4>

<!-- Advanced Search -->
<div class="card">
    <input type="hidden" name="iduser" id="iduser" value="{{ Auth::user()->id }}">
    <!--Search Form -->
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
    <div id="body-table-sales" style="display: none;">
        <div class="card-body">
            <form class="dt_adv_search" method="POST">
                <div class="row">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Cliente:</label>
                                <input type="text" class="form-control dt-input dt-full-name" data-column=4
                                    placeholder="Cliente" data-column-index="4">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Tipo Documento:</label>
                                <input type="text" class="form-control dt-input" data-column=3
                                    placeholder="#12456" data-column-index="3">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Forma de Pago:</label>
                                <input type="text" class="form-control dt-input" data-column=6
                                    placeholder="" data-column-index="6">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Estado:</label>
                                <input type="text" class="form-control dt-input" data-column=7 placeholder="Estado"
                                    data-column-index="7">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Fecha:</label>
                                <div class="mb-0">
                                    <input type="text" class="form-control dt-date flatpickr-range dt-input"
                                        data-column="2" placeholder="StartDate to EndDate" data-column-index="2"
                                        name="dt_date" />
                                    <input type="hidden" class="form-control dt-date start_date dt-input"
                                        data-column="2" data-column-index="2" name="value_from_start_date" />
                                    <input type="hidden" class="form-control dt-date end_date dt-input"
                                        name="value_from_end_date" data-column="2" data-column-index="2" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Monto:</label>
                                <input type="text" class="form-control dt-input" data-column=8 placeholder="10000"
                                    data-column-index="8">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <hr class="mt-0">
        <div class="card-datatable table-responsive">
            <table class="table dt-advanced-search">
                <thead>
                    <tr>
                        <th>CORRELATIVO</th>
                        <th>A CUENTA DE</th>
                        <th>FECHA</th>
                        <th>TIPO</th>
                        <th>CLIENTE</th>
                        <th>EMPRESA</th>
                        <th>FORMA DE PAGO</th>
                        <th>ESTADO</th>
                        <th>TOTAL</th>
                        <th>PERIODO</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!--/ Advanced Search -->
@endsection

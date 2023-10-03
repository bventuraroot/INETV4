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
    <script src="{{ asset('assets/js/app-credit-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-credit.js') }}"></script>
@endsection

@section('title', 'Creditos')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Registro de Creditos</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-products border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>ID</th>
                        <th>FECHA DE VENTA</th>
                        <th>FECHA DE PAGO</th>
                        <th>CLIENTE</th>
                        <th>EMPRESA</th>
                        <th>ESTADO</th>
                        <th>MONTO INICIAL</th>
                        <th>MONTO ACTUAL</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($credits)
                        @forelse($credits as $credit)
                            <tr>
                                <td></td>
                                <td>{{ $credit->corr }}</td>
                                <td>{{ $credit->date }}</td>
                                <td>{{ $credit->date }}</td>
                                @switch( Str::lower($credit->tpersona) )
                                @case('j')
                                <td>{{ $credit->name_contribuyente }} ( {{ $credit->comercial_name }} )</td>
                                @break
                                @case('n')
                                <td>{{ $credit->client_firstname }} {{ $credit->client_secondname }}</td>
                                @break
                                @default
                            @endswitch
                                <td>{{ $credit->NameCompany }}</td>
                                <td>{{ $credit->state_credit }}</td>
                                <td>$ {{ $credit->totalamount }}</td>
                                <td>$ {{ number_format($credit->current == "" ? $credit->totalamount : ($credit->totalamount)-($credit->current),2,'.',',') }}</td>
                                @if($credit->state_credit=="PAGADO")
                                <td></td>
                                @else
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: paycredit({{ $credit->idsale }});" class="dropdown-item"><i
                                        class="ti ti-credit-card ti-sm me-2"></i>Abonar</a>
                                </div></td>
                                @endif
                            </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>No hay datos</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
    </div>
       <!-- Pay Credits Modal -->
<div class="modal fade" id="PayCreditsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Abonar a crédito</h3>
            <h4 class="mb-1 bg-label-danger">Saldo: $<span id="pendingamount"></span></h4>
          </div>
          <form id="paycreditForm" class="row" action="{{Route('credit.addpay')}}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <input type="hidden" name="idsale" id="idsale">
            <input type="hidden" name="currentamount" id="currentamount" value="0">
            <div class="mb-3 col-12">
              <label class="form-label" for="amountpay">Monto a abonar</label>
              <input type="number" step="any" min="0.00" value="0.00" id="amountpay" name="amountpay" class="form-control" autofocus required/>
            </div>
            <div class="text-center col-12 demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1" disabled id="savepay">Abonar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
    @endsection

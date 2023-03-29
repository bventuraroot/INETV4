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
    <script src="{{ asset('assets/js/app-sale-list.js') }}"></script>
@endsection

@section('title', 'Ventas')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Ventas</h5>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-sale table border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>CORRELATIVO</th>
                        <th>A CUENTA DE</th>
                        <th>FECHA</th>
                        <th>TIPO</th>
                        <th>CLIENTE</th>
                        <th>EMPRESA</th>
                        <th>FORMA DE PAGO</th>
                        <th>ESTADO</th>
                        <th>TOTAL</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($sales)
                        @forelse($sales as $sale)
                            <tr>
                                <td></td>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->acuenta }}</td>
                                <td>{{ $sale->date }}</td>
                                <td>{{ $sale->typedocument_id }}</td>
                                <td>{{ $sale->client_id }}</td>
                                <td>{{ $sale->company_id }}</td>
                                <td>{{ $sale->waytopay }}</td>
                                <td>{{ $sale->state }}</td>
                                <td>{{ $sale->totalamount }}</td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: printsale({{ $sale->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>imprimir</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:cancelsale({{ $sale->id }});" class="dropdown-item"><i
                                                class="ti ti-eraser ti-sm me-2"></i>Anular</a>

                                    </div>
                                </div></td>
                            </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>No hay datos</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <!-- select type document to create -->
            <div class="modal fade" id="selectDocumentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-simple modal-pricing">
                  <div class="modal-content p-3 p-md-5">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                      <div class="text-center mb-4">
                        <h3 class="mb-2">Documentos disponibles</h3>
                      </div>
                      <form id="selectDocumentForm" class="row" action="{{Route('sale.create')}}" method="GET">
                        @csrf @method('GET')
                        <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
                        <div id="wizard-create-deal" class="bs-stepper vertical mt-2">
                            <div class="bs-stepper-content">
                                <!-- Deal Type -->
                                <div id="deal-type" class="content">
                                  <div class="row g-3">
                                    <div class="col-12 d-flex justify-content-center border rounded pt-4">
                                      <img src="{{ asset('assets/img/illustrations/auth-register-illustration-'.$configData['style'].'.png') }}" alt="wizard-create-deal" data-app-light-img="illustrations/auth-register-illustration-light.png" data-app-dark-img="illustrations/auth-register-illustration-dark.png" width="250" class="img-fluid">
                                    </div>
                                    <div class="col-12 pb-2">
                                      <div class="row">
                                        <div class="col-md mb-md-0 mb-2">
                                          <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="factura">
                                              <span class="custom-option-body">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"></path>
                                                    <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5"></path>
                                                 </svg>

                                                <span class="custom-option-title">Factura</span>
                                                <small>Creación de factura para personas naturales contribuyentes o no contribuyentes</small>
                                              </span>
                                              <input name="typedocument" class="form-check-input" type="radio" value="factura" id="factura" checked />
                                              <input type="hidden" name="typedocumentid" id="typedocumentid" value="6">
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-md mb-md-0 mb-2">
                                          <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="fiscal">
                                              <span class="custom-option-body">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2"></path>
                                                 </svg>

                                                <span class="custom-option-title">Credito Fiscal</span>
                                                <small>Creación de documentos donde necesitas una persona natural o jurídica que declare IVA</small>
                                              </span>
                                              <input name="typedocument" class="form-check-input" type="radio" value="fiscal" id="fiscal" />
                                              <input type="hidden" name="typedocumentid" id="typedocumentid" value="3">
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-md mb-md-0 mb-2">
                                          <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="nota">
                                              <span class="custom-option-body">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt-refund" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"></path>
                                                    <path d="M15 14v-2a2 2 0 0 0 -2 -2h-4l2 -2m0 4l-2 -2"></path>
                                                 </svg>
                                                <span class="custom-option-title">Nota de crédito</span>
                                                <small>Creación de documento para modificar un crédito fiscal, requisitos como un crédito fiscal</small>
                                              </span>
                                              <input name="typedocument" class="form-check-input" type="radio" value="nota" id="nota" />
                                              <input type="hidden" name="typedocumentid" id="typedocumentid" value="9">
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center mt-4">
                                            <button class="btn btn-success btn-submit btn-next"><span class="align-center d-sm-inline-block d-none me-sm-1">Comenzar</span><i class="ti ti-arrows-join-2 ti-xs"></i></button>
                                          </div>
                                      </div>
                                    </div>
                            </div>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
    @endsection

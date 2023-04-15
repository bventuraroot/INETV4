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
            <h5 class="mb-3 card-title">Ventas</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-sale border-top nowrap">
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
                                <td>{{ $sale->document_name }}</td>
                                <td>{{ $sale->firstname . ' ' . $sale->secondname  }}</td>
                                <td>{{ $sale->company_name }}</td>
                                <td>
                                    @switch($sale->waytopay)
                                        @case(1)
                                            CONTADO
                                        @break

                                        @case(2)
                                            CRÉDITO
                                        @break

                                        @case(3)
                                            OTRO
                                        @break

                                        @default
                                    @endswitch</td>
                                <td>
                                    @switch($sale->state)
                                        @case(0)
                                            ANULADO
                                        @break

                                        @case(1)
                                            CONFIRMADO
                                        @break

                                        @case(2)
                                            PENDIENTE
                                        @break

                                        @case(3)
                                            FACTURADO
                                        @break

                                        @default
                                    @endswitch</td>
                                <td>$ {{ $sale->totalamount }}</td>
                                <td>
                                    @switch($sale->typesale)
                                        @case(1)
                                        <div class="d-flex align-items-center">
                                            <a href="javascript: printsale({{ $sale->id }});" class="dropdown-item"><i
                                                class="ti ti-edit ti-sm me-2"></i>imprimir</a>
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="mx-1 ti ti-dots-vertical ti-sm"></i></a>
                                            <div class="m-0 dropdown-menu dropdown-menu-end">
                                                <a href="javascript:cancelsale({{ $sale->id }});" class="dropdown-item"><i
                                                        class="ti ti-eraser ti-sm me-2"></i>Anular</a>
                                            </div>
                                        </div>
                                        @break

                                        @case(2)
                                        <div class="d-flex align-items-center">
                                            <a href="javascript: retomarsale({{ $sale->id }}, {{ $sale->typedocument_id}});" class="dropdown-item"><i
                                                class="ti ti-edit ti-sm me-2"></i>Retomar</a>
                                        </div>
                                        @break

                                        @default
                                    @endswitch
                                </td>
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
                  <div class="p-3 modal-content p-md-5">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                      <div class="mb-4 text-center">
                        <h3 class="mb-2">Documentos disponibles</h3>
                      </div>
                      <form id="selectDocumentForm" class="row" action="{{Route('sale.create')}}" method="GET">
                        @csrf @method('GET')
                        <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
                        <div id="wizard-create-deal" class="mt-2 bs-stepper vertical">
                            <div class="bs-stepper-content">
                                <!-- Deal Type -->
                                <div id="deal-type" class="content">
                                  <div class="row g-3">
                                    <div class="pt-4 border rounded col-12 d-flex justify-content-center">
                                      <img src="{{ asset('assets/img/illustrations/auth-register-illustration-'.$configData['style'].'.png') }}" alt="wizard-create-deal" data-app-light-img="illustrations/auth-register-illustration-light.png" data-app-dark-img="illustrations/auth-register-illustration-dark.png" width="250" class="img-fluid">
                                    </div>
                                    <div class="pb-2 col-12">
                                      <div class="row">
                                        <div class="mb-2 col-md mb-md-0">
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
                                              <input name="typedocument" class="form-check-input" type="radio" value="6" id="factura" checked />
                                            </label>
                                          </div>
                                        </div>
                                        <div class="mb-2 col-md mb-md-0">
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
                                              <input name="typedocument" class="form-check-input" type="radio" value="3" id="fiscal" />
                                            </label>
                                          </div>
                                        </div>
                                        <div class="mb-2 col-md mb-md-0">
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
                                              <input name="typedocument" class="form-check-input" type="radio" value="9" id="nota" />
                                            </label>
                                          </div>
                                        </div>
                                        <div class="mt-4 col-12 d-flex justify-content-center">
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


              <div class="container" style="display: none" id="imprimirdoc">
                <style type="text/css">
                    .container{
                        border-color: black;
                        border-width: 1.5px;
                        border-style: solid;
                        border-radius: 25px;
                        line-height: 1.5;
                    }
                    .nofacfinal{
                        border-color: black;
                        border-width: 0.5px;
                        border-style: solid;
                        border-radius: 15px;
                        margin-top: 4%;
                        width: 20%;
                        text-align: center;
                        background-color: #cccccc;
                        color: black;
                    }
                    #logodocfinal{
                        display:block;
                    }
                    .interlineado-nulo{
                        line-height: 1;
                    }
                    .porsi{
                        border-color: black;
                        border-width: 0.5px;
                        border-style: solid;
                        border-radius: 25px;
                    }
                    .cuerpodocfinal{
                        margin-top: 0%;
                        margin-bottom: 5%;
                        width: 100%;
                    }
                    .camplantilla{
                        padding: 5px;
                        width: 14.2%;
                    }
                    .dataplantilla{
                        padding: 5px;
                        width: 58.5%;
                        border-bottom-color: black;
                        border-bottom-width: 1px;
                    }
                    table.desingtable{
                        margin: 2%;
                    }
                    table.sample {
                        margin: 2%;
                    }
                    .details_products_documents{
                        width: 100%
                    }
                    .table_details{
                        margin-bottom: 2%;
                        width: 100%;
                        line-height: 30px;
                    }
                    .head_details{
                        margin: 1%;
                        color: black;
                        border-width: 1px;
                        border-radius: 25px;
                        border-style: solid;
                    }
                    .th_details{
                        text-align: center;
                    }
                    .td_details{
                        width: 5px;
                        text-align: center;

                    }
                    .tfoot_details{
                        border-top-width: 1px;
                        padding-top: 2%;
                        margin-top: 2%;
                        margin-bottom: 5%;
                        text-align: right;
                    }
                </style>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <img  id="logodocfinal" src="">
                    </div>
                    <div class="col-sm-6" style="margin-top: 4%;">
                        <p class="interlineado-nulo" id="addressdcfinal"></p>
                          <p class="interlineado-nulo" id="phonedocfinal"></p>
                          <p class="interlineado-nulo" id="emaildocfinal"></p>
                    </div>
                    <div class="col-sm-3 nofacfinal" >
                        <b style="font-size: 17.5pt;" id="name_type_documents_details">FACTURA</b></br>
                        <small class="interlineado-nulo" id="corr_details"><b>1792067464001<b></small></br>
                        <small class="interlineado-nulo" id="NCR_details"><b>NCR: <b></small></br>
                        <small class="interlineado-nulo" id="NIT_details"><b>NIT: <b></small></br>
                    </div>
                    <div class="col-sm-8 cuerpodocfinal">
                        <table class="sample">
                                <tr>
                                    <td class="camplantilla">
                                        Señor (es):
                                    </td>
                                    <td class="dataplantilla" id="name_client">

                                    </td>
                                    <td class="camplantilla" style="padding-left: 1%;">
                                        Fecha:
                                    </td>
                                    <td class="dataplantilla" id="date_doc">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="camplantilla">
                                        Dirección:
                                    </td>
                                    <td class="dataplantilla" id="address_doc">

                                    </td>
                                    <td class="camplantilla" style="padding-left: 1%;">
                                        DUI o NIT:
                                    </td>
                                    <td class="dataplantilla" id="duinit">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="camplantilla">
                                        Municipio:
                                    </td>
                                    <td class="dataplantilla" id="municipio_name">

                                    </td>
                                    <td class="camplantilla" style="padding-left: 1%;">
                                        Giro:
                                    </td>
                                    <td class="dataplantilla" id="giro_name">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="camplantilla">
                                        Departamento:
                                    </td>
                                    <td class="dataplantilla" id="departamento_name">

                                    </td>
                                    <td class="camplantilla" style="padding-left: 1%;">
                                        Forma de pago:
                                    </td>
                                    <td class="dataplantilla" id="forma_pago_name">

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">

                                    </td>
                                    <td class="camplantilla" style="padding-left: 1%;">
                                        Venta a cuenta de:
                                    </td>
                                    <td class="dataplantilla" id="acuenta_de">

                                    </td>
                                </tr>
                        </table>
                    </div>
                    <div class="col-sm-8 details_products_documents" id="details_products_documents">

                    </div>
                </div>
            </div>
    @endsection

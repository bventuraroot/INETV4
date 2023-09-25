@extends('layouts/layoutMaster')

@section('title', 'Nuevo documento')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-wizard-icons.js') }}"></script>
@endsection

@section('content')
<style>
    .imagen-producto-select2 {
    width: 50px;
    height: 50px;
    margin-right: 10px;
    vertical-align: middle;
}
</style>

@php
    switch (request('typedocument') ) {
        case '6':
            $document = 'Factura';
            break;
        case '9':
            $document = 'Nota de Crédito';
            break;
        case '3':
            $document = 'Crédito Fiscal';
            break;
    }
@endphp
    <!-- Default Icons Wizard -->
    <div class="mb-4 col-12">
        <h4 class="py-3 mb-4 fw-bold">
            <span class="text-center fw-semibold">Creación de {{ $document }}
        </h4>
        <div class="mt-2 bs-stepper wizard-icons wizard-icons-example">
            <div class="bs-stepper-header">
                <div class="step" data-target="#company-select">
                    <button type="button" class="step-trigger" disabled>
                        <span class="bs-stepper-icon">
                            <svg viewBox="0 0 54 54">
                                <use xlink:href='{{ asset('assets/svg/icons/form-wizard-account.svg#wizardAccount') }}'>
                                </use>
                            </svg>
                        </span>
                        <span class="bs-stepper-label">Seleccionar Empresa</span>
                    </button>
                </div>
                <div class="line">
                    <i class="ti ti-chevron-right"></i>
                </div>
                <div class="step" data-target="#personal-info">
                    <button type="button" class="step-trigger" disabled>
                        <span class="bs-stepper-icon">
                            <svg viewBox="0 0 58 54">
                                <use xlink:href='{{ asset('assets/svg/icons/form-wizard-personal.svg#wizardPersonal') }}'>
                                </use>
                            </svg>
                        </span>
                        <span class="bs-stepper-label">Información {{ $document }}</span>
                    </button>
                </div>
                <div class="line">
                    <i class="ti ti-chevron-right"></i>
                </div>
                <div class="step" data-target="#products" id="step-products">
                    <button type="button" id="button-products" class="step-trigger" disabled>
                        <span class="bs-stepper-icon">
                            <svg viewBox="0 0 54 54">
                                <use xlink:href='{{ asset('assets/svg/icons/wizard-checkout-cart.svg#wizardCart') }}'>
                                </use>
                            </svg>
                        </span>
                        <span class="bs-stepper-label">Productos</span>
                    </button>
                </div>
                <div class="line">
                    <i class="ti ti-chevron-right"></i>
                </div>
                <div class="step" data-target="#review-submit">
                    <button type="button" class="step-trigger" disabled>
                        <span class="bs-stepper-icon">
                            <svg viewBox="0 0 54 54">
                                <use xlink:href='{{ asset('assets/svg/icons/form-wizard-submit.svg#wizardSubmit') }}'>
                                </use>
                            </svg>
                        </span>
                        <span class="bs-stepper-label">Revisión & Creación</span>
                    </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <form onSubmit="return false">
                    <!-- select company -->
                    <div id="company-select" class="content">
                        <input type="hidden" name="iduser" id="iduser" value="{{ Auth::user()->id }}">
                        <div class="row g-5">
                            <div class="col-sm-12">
                                <label for="company" class="form-label">
                                    <h6>Empresa</h6>
                                </label>
                                <select class="select2company form-select" id="company" name="company"
                                    onchange="aviablenext(this.value)" aria-label="Seleccionar opcion">
                                </select>
                                <input type="hidden" name="typedocument" id="typedocument" value="{{request('typedocument')}}">
                                <input type="hidden" name="typecontribuyente" id="typecontribuyente">
                                <input type="hidden" name="iva" id="iva">
                                <input type="hidden" name="iva_entre" id="iva_entre">
                                <input type="hidden" name="valcorr" id="valcorr" value="{{ request('corr')!='' ? request('corr') : '' }}">
                                <input type="hidden" name="valdraft" id="valdraft" value="{{ request('draft')!='' ? request('draft') : '' }}">
                                <input type="hidden" name="operation" id="operation" value="{{ request('operation')!='' ? request('operation') : '' }}">
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-label-secondary btn-prev" disabled> <i
                                        class="ti ti-arrow-left me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button id="step1" class="btn btn-primary btn-next" disabled> <span
                                        class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i
                                        class="ti ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- details document -->
                    <div id="personal-info" class="content">
                        <div class="mb-3 content-header">
                            <h6 class="mb-0">Detalles de {{ $document }}</h6>
                            <small>Ingresa los campos requeridos</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-2">
                                <label class="form-label" for="corr">Correlativo</label>
                                <input type="text" id="corr" name="corr" class="form-control" readonly />
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label" for="date">Fecha</label>
                                <input type="date" id="date" name="date" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" readonly />
                            </div>
                            <div class="col-sm-8">
                                <label for="client" class="form-label">Cliente</label>
                                <select class="select2client form-select" id="client" name="client" onchange="valtrypecontri(this.value)"
                                    aria-label="Seleccionar opcion">
                                </select>
                                <input type="hidden" name="typecontribuyenteclient" id="typecontribuyenteclient">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="fpago">Forma de pago</label>
                                <select class="select2" id="fpago" name="fpago" onchange="valfpago(this.value)">
                                    <option value="0">Seleccione</option>
                                    <option value="1">Contado</option>
                                    <option value="2">A crédito</option>
                                    <option value="3">Otro</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label" for="acuenta">Venta a cuenta de</label>
                                <input type="text" id="acuenta" name="acuenta" class="form-control"
                                    placeholder="" />
                            </div>
                            <div class="col-sm-3" style="display: none;" id="isfcredito">
                                <label class="form-label" for="datefcredito">Fecha</label>
                                <input type="date" id="datefcredito" name="datefcredito" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}" />
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-label-secondary btn-prev"> <i class="ti ti-arrow-left me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button id="step2" class="btn btn-primary btn-next"> <span
                                        class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i
                                        class="ti ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Products -->
                    <div id="products" class="content">
                        <div class="mb-3 content-header">
                            <h6 class="mb-0">Productos</h6>
                            <small>Agregue los productos necesarios.</small>
                        </div>
                        <div class="row g-3 col-12" style="margin-bottom: 3%">
                            <div class="col-sm-6">
                                <label class="form-label" for="psearch">Buscar Producto</label>
                                <select class="select2psearch" id="psearch" name="psearch" onchange="searchproduct(this.value)">
                                </select>
                                <input type="hidden" id="productname" name="productname">
                                <input type="hidden" id="productid" name="productid">
                                <input type="hidden" id="productdescription" name="productdescription">
                                <input type="hidden" id="productunitario" name="productunitario">
                                <input type="hidden" id="sumas" value="0" name="sumas">
                                <input type="hidden" id="13iva" value="0" name="13iva">
                                <input type="hidden" id="ivaretenido" value="0" name="ivaretenido">
                                <input type="hidden" id="ventasnosujetas" value="0" name="ventasnosujetas">
                                <input type="hidden" id="ventasexentas" value="0" name="ventasexentas">
                                <input type="hidden" id="ventatotal" value="0" name="ventatotal">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label" for="cantidad">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="1" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label" for="precio">Precio</label>
                                <input type="number" id="precio" name="precio" step="0.01" min="1" max="10000" placeholder="0.00" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label" for="typesale">Tipo de venta</label>
                                <select class="form-select" id="typesale" name="typesale" onchange="changetypesale(this.value)">
                                    <option value="gravada">Gravadas</option>
                                    <option value="exenta">Exenta</option>
                                    <option value="nosujeta">No Sujeta</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label" for="ivarete13">Iva 13%</label>
                                <input type="number" id="ivarete13" name="ivarete13" step="0.01" max="10000" placeholder="0.00" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label" for="ivarete">Iva Percibido</label>
                                <input type="number" id="ivarete" name="ivarete" step="0.01" max="10000" placeholder="0.00" class="form-control">
                            </div>
                            <div class="col-sm-4" style="margin-top: 3%">
                                <button type="button" class="btn btn-primary" onclick="agregarp()">
                                    <span class="ti ti-playlist-add"></span> &nbsp;&nbsp;&nbsp;Agregar
                                </button>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive" id="resultados">
                            <div class="panel">
                                <table class="table table-sm animated table-hover table-striped table-bordered fadeIn" id="tblproduct">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th class="text-center text-white">CANT.</th>
                                            <th class="text-white">DESCRIPCION</th>
                                            <th class="text-right text-white">PRECIO UNIT.</th>
                                            <th class="text-right text-white">NO SUJETAS</th>
                                            <th class="text-right text-white">EXENTAS</th>
                                            <th class="text-right text-white">GRAVADAS</th>
                                            <th class="text-right text-white">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td rowspan="8" colspan="5"></td>
                                            <td class="text-right">SUMAS</td>
                                            <td class="text-center" id="sumasl">$ 0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">IVA 13%</td>
                                            <td class="text-center" id="13ival">$ 0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">(-) IVA Percibido</td>
                                            <td class="text-center" id="ivaretenidol">$0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Ventas No Sujetas</td>
                                            <td class="text-center" id="ventasnosujetasl">$0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Ventas Exentas</td>
                                            <td class="text-center" id="ventasexentasl">$0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Venta Total</td>
                                            <td class="text-center" id="ventatotall">$ 0.00</td>
                                            <td class="quitar_documents"></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <button class="btn btn-label-secondary btn-prev"> <i class="ti ti-arrow-left me-sm-1"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </button>
                            <button id="step3" class="btn btn-primary btn-next"> <span
                                    class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i
                                    class="ti ti-arrow-right"></i></button>
                        </div>
                    </div>
                    <!-- Social Links -->
                    <div id="social-links" class="content">
                        <div class="mb-3 content-header">
                            <h6 class="mb-0">Social Links</h6>
                            <small>Enter Your Social Links.</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="twitter">Twitter</label>
                                <input type="text" id="twitter" class="form-control"
                                    placeholder="https://twitter.com/abc" />
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="facebook">Facebook</label>
                                <input type="text" id="facebook" class="form-control"
                                    placeholder="https://facebook.com/abc" />
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="google">Google+</label>
                                <input type="text" id="google" class="form-control"
                                    placeholder="https://plus.google.com/abc" />
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="linkedin">Linkedin</label>
                                <input type="text" id="linkedin" class="form-control"
                                    placeholder="https://linkedin.com/abc" />
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button class="btn btn-label-secondary btn-prev"> <i class="ti ti-arrow-left me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next"> <span
                                        class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i
                                        class="ti ti-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Review -->
                    <div id="review-submit" class="content">
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
                                height: 120%;
                                width: 20%;
                                text-align: center;
                                background-color: #CCCCCC;
                                color: black;
                            }
                            #logodocfinal{
                                display:block;
                                width: 80%;
                                height: 100%;
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

                        <!-- plantilla de factura -->

                        <div class="container">
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
                        <!-- Fin plantilla de factura -->

                        <div class="col-12 d-flex justify-content-between" style="margin-top: 3%;">
                            <button class="btn btn-label-secondary btn-prev"> <i class="ti ti-arrow-left me-sm-1"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </button>
                            <button class="btn btn-success btn-submit">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Default Icons Wizard -->
    </div>
@endsection

@extends('layouts/layoutMaster')

@section('title', 'Wizard Icons - Forms')

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
    <!-- Default Icons Wizard -->
    <div class="mb-4 col-12">
        <h4 class="py-3 mb-4 fw-bold">
            <span class="text-center fw-semibold">Creación de {{ request('typedocument') }}
        </h4>
        <div class="mt-2 bs-stepper wizard-icons wizard-icons-example">
            <div class="bs-stepper-header">
                <div class="step" data-target="#company-select">
                    <button type="button" class="step-trigger2">
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
                    <button type="button" class="step-trigger2">
                        <span class="bs-stepper-icon">
                            <svg viewBox="0 0 58 54">
                                <use xlink:href='{{ asset('assets/svg/icons/form-wizard-personal.svg#wizardPersonal') }}'>
                                </use>
                            </svg>
                        </span>
                        <span class="bs-stepper-label">Información {{ request('typedocument') }}</span>
                    </button>
                </div>
                <div class="line">
                    <i class="ti ti-chevron-right"></i>
                </div>
                <div class="step" data-target="#products">
                    <button type="button" class="step-trigger2">
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
                    <button type="button" class="step-trigger2">
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
                                    onchange="aviablenext()" aria-label="Seleccionar opcion">
                                </select>
                                <input type="hidden" name="typedocument" id="typedocument" value="{{request('typedocumentid')}}">
                                <input type="hidden" name="valcorr" id="valcorr">
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
                            <h6 class="mb-0">Detalles de {{ request('typedocument') }}</h6>
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
                                <select class="select2client form-select" id="client" name="client"
                                    aria-label="Seleccionar opcion">
                                </select>
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
                                <button class="btn btn-primary btn-next"> <span
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
                                <input type="number" id="precio" name="precio" step="0.01" min="1" max="10000" placeholder="0.00" class="form-control" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label" for="typesale">Tipo de venta</label>
                                <select class="form-select" id="typesale" name="typesale">
                                    <option value="gravada">Gravadas</option>
                                    <option value="exenta">Exenta</option>
                                    <option value="nosujeta">No Sujeta</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label" for="ivarete">Iva Retenido</label>
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
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">IVA 13%</td>
                                            <td class="text-center" id="13ival">$ 0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">(-) IVA Retenido</td>
                                            <td class="text-center" id="ivaretenidol">$0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Ventas No Sujetas</td>
                                            <td class="text-center" id="ventasnosujetasl">$0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Ventas Exentas</td>
                                            <td class="text-center" id="ventasexentasl">$0.00</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="text-right">Venta Total</td>
                                            <td class="text-center" id="ventatotall">$ 0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
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

                        <p class="mb-2 fw-semibold">Account</p>
                        <ul class="list-unstyled">
                            <li>Username</li>
                            <li>exampl@email.com</li>
                        </ul>
                        <hr>
                        <p class="mb-2 fw-semibold">Personal Info</p>
                        <ul class="list-unstyled">
                            <li>First Name</li>
                            <li>Last Name</li>
                            <li>Country</li>
                            <li>Language</li>
                        </ul>
                        <hr>
                        <p class="mb-2 fw-semibold">Address</p>
                        <ul class="list-unstyled">
                            <li>Address</li>
                            <li>Landmark</li>
                            <li>Pincode</li>
                            <li>City</li>
                        </ul>
                        <hr>
                        <p class="mb-2 fw-semibold">Social Links</p>
                        <ul class="list-unstyled">
                            <li>https://twitter.com/abc</li>
                            <li>https://facebook.com/abc</li>
                            <li>https://plus.google.com/abc</li>
                            <li>https://linkedin.com/abc</li>
                        </ul>
                        <div class="col-12 d-flex justify-content-between">
                            <button class="btn btn-label-secondary btn-prev"> <i class="ti ti-arrow-left me-sm-1"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </button>
                            <button class="btn btn-success btn-submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Default Icons Wizard -->
    </div>

@endsection

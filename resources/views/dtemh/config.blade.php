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
    <script src="{{ asset('assets/js/app-config-list.js') }}"></script>
@endsection

@section('title', 'Configuraciones Credenciales Facturacion Electronica SV-DTE')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Configuraciones</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-config border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>ID</th>
                        <th>EMPRESA</th>
                        <th>VERSION</th>
                        <th>AMBIENTE</th>
                        <!--<th>TIPO MODELO</th>
                        <th>TIPO TRANSMISION</th>
                        <th>TIPO CONTINGENCIA</th>-->
                        <th>VERSION JSON</th>
                        <th>PASS_PRIVATE_KEY</th>
                        <th>PASS_PUBLIC_KEY</th>
                        <th>PASS_MH</th>
                        <th>CODE COUNTRY</th>
                        <th>NAME COUNTRY</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($configs)
                        @forelse($configs as $config)
                            <tr>
                                <td></td>
                                <td>{{ $config->id }}</td>
                                <td>{{ $config->name_company }}</td>
                                <td>{{ $config->version }}</td>
                                <td>{{ $config->ambiente }}</td>
                                <!--<td></td>
                                <td></td>
                                <td></td>-->
                                <td>{{ $config->versionJson }}</td>
                                <td>{{ $config->passPrivateKey }}</td>
                                <td>{{ $config->passkeyPublic }}</td>
                                <td>{{ $config->passPpassMHrivateKey }}</td>
                                <td>{{ $config->codeCountry }}</td>
                                <td>{{ $config->nameCountry }}</td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editconfig({{ $config->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="mx-1 ti ti-dots-vertical ti-sm"></i></a>
                                    <div class="m-0 dropdown-menu dropdown-menu-end">
                                        <a href="javascript:deleteconfig({{ $config->id }});" class="dropdown-item"><i
                                                class="ti ti-eraser ti-sm me-2"></i>Eliminar</a>

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

            <!-- Add config Modal -->
<div class="modal fade" id="addConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Crear nueva configuracion</h3>
          </div>
          <form id="addproductForm" class="row" action="{{Route('config.store')}}" method="POST">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Empresa</label>
              <select class="select2 form-select" id="company" name="company" aria-label="Seleccionar opcion"></select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Version</label>
              <input type="text" id="version" name="version" class="form-control" placeholder="Version" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Ambiente</label>
              <select class="select2 form-select" id="ambiente" name="ambiente" aria-label="Seleccionar opcion">
                <option value="1">Ambiente Desarrollo</option>
                <option value="2">Ambiente Produccion</option>
              </select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Modelo</label>
              <input type="text" id="typemodel" name="typemodel" class="form-control" placeholder="Tipo Modelo" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Transmision</label>
              <input type="text" id="typetransmission" name="typetransmission" class="form-control" placeholder="Tipo Transmision" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Contingencia</label>
              <input type="text" id="typecontingencia" name="typecontingencia" class="form-control" placeholder="Tipo Contingencia" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Version Json</label>
              <select class="select2versionjson form-select" id="versionjson" name="versionjson" aria-label="Seleccionar opcion">
                <option value="1">v1</option>
                <option value="2">v2</option>
                <option value="3">v3</option>
                <option value="4">v4</option>
              </select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña Llave Privada</label>
              <input type="text" id="passprivatekey" name="passprivatekey" class="form-control" placeholder="Private key" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña Llave Publica</label>
              <input type="text" id="passpublickey" name="passpublickey" class="form-control" placeholder="Public key" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña MH</label>
              <input type="text" id="passmh" name="passmh" class="form-control" placeholder="Pass MH" required/>
            </div>
            <div class="text-center col-12 demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Crear</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

             <!-- Add update Modal -->
<div class="modal fade" id="updateConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Editar Config</h3>
          </div>
          <form id="addproductForm" class="row" action="{{Route('config.update')}}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <input type="hidden" name="idedit" id="idedit">
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Empresa</label>
              <select class="select2 form-select" id="companyedit" name="companyedit" aria-label="Seleccionar opcion"></select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Version</label>
              <input type="text" id="versionedit" name="versionedit" class="form-control" placeholder="Version" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Ambiente</label>
              <select class="select2 form-select" id="ambienteedit" name="ambienteedit" aria-label="Seleccionar opcion">
                <option value="1">Ambiente Desarrollo</option>
                <option value="2">Ambiente Produccion</option>
              </select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Modelo</label>
              <input type="text" id="typemodeledit" name="typemodeledit" class="form-control" placeholder="Tipo Modelo" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Transmision</label>
              <input type="text" id="typetransmissionedit" name="typetransmissionedit" class="form-control" placeholder="Tipo Transmision" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Tipo Contingencia</label>
              <input type="text" id="typecontingenciaedit" name="typecontingenciaedit" class="form-control" placeholder="Tipo Contingencia" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Version Json</label>
              <select class="select2 form-select" id="versionjsonedit" name="versionjsonedit" aria-label="Seleccionar opcion">
                <option value="1">v1</option>
                <option value="2">v2</option>
                <option value="3">v3</option>
                <option value="4">v4</option>
              </select>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña Llave Privada</label>
              <input type="text" id="passprivatekeyedit" name="passprivatekeyedit" class="form-control" placeholder="Private key" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña Llave Publica</label>
              <input type="text" id="passpublickeyedit" name="passpublickeyedit" class="form-control" placeholder="Public key" required/>
            </div>
            <div class="mb-3 col-12">
              <label class="form-label" for="name">Contraseña MH</label>
              <input type="text" id="passmhedit" name="passmhedit" class="form-control" placeholder="Pass MH" required/>
            </div>
            <div class="text-center col-12 demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Guardar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
    @endsection

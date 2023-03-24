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
    <script src="{{ asset('assets/js/app-provider-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-provider.js') }}"></script>
@endsection

@section('title', 'Proveedores')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Proveedores</h5>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-provider table border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Razon Social</th>
                        <th>NCR</th>
                        <th>NIT</th>
                        <th>TELEFONOS</th>
                        <th>DIRECCION</th>
                        <th>CORREO</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($providers)
                        @forelse($providers as $provider)
                            <tr>
                                <td></td>
                                <td><h5>{{ $provider->razonsocial }}</h5>
                                <h5 class="badge bg-label-primary rounded p-2"> <li class="ti ti-users ti-sm"></li> Empresa: {{$provider->company}}</h5></td>
                                <td>{{ $provider->ncr }}</td>
                                <td>{{ $provider->nit }}</td>
                                <td>{{ $provider->tel1 }} <br>
                                    {{ $provider->tel2 }}</td>
                                <td>
                                    <span>{{ Str::upper($provider->pais)  }}</span><br>
                                    <span>{{ $provider->departamento }}</span><br>
                                    <span>{{ $provider->municipio }}</span><br>
                                    <span><span class="badge bg-label-primary rounded p-2">{{ $provider->address }}</span></span><br>
                                </td>
                                <td><span class="badge bg-label-warning rounded p-2"> <li class="ti ti-mail ti-sm"></li> {{ $provider->email }}</span></td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editProvider({{ $provider->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:deleteProvider({{ $provider->id }});" class="dropdown-item"><i
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
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>

            <!-- Add provider Modal -->
<div class="modal fade" id="addProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Crear nuevo proveedor</h3>
          </div>
          <form id="addProviderForm" class="row" action="{{Route('provider.store')}}" method="POST">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="col-12 mb-3">
              <label class="form-label" for="razonsocial">Razon Social</label>
              <input type="text" id="razonsocial" name="razonsocial" class="form-control" placeholder="Razon Social" autofocus required/>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="ncr">NCR</label>
                <input type="text" id="ncr" class="form-control" placeholder="xxxxxx-x"
                    aria-label="ncr" name="ncr" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="nit">DUI/NIT</label>
                <input type="text" id="nit" class="form-control" placeholder="xxxxxxxx-x"
                    aria-label="nit" name="nit" />
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="email">Correo</label>
                <input type="text" id="email" class="form-control" placeholder="john.doe@example.com"
                    aria-label="john.doe@example.com" name="email" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="tel1">Teléfono</label>
                <input type="text" id="tel1" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel1" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="tel2">Teléfono 2</label>
                <input type="text" id="tel2" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel2" />
            </div>
            <div class="col-8 mb-3">
                <label for="company" class="form-label">Empresa</label>
                <select class="select2company form-select" id="company" name="company"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="col-8 mb-3">
                <label for="country" class="form-label">País</label>
                <select class="select2country form-select" id="country" name="country"
                    aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                    <option>Seleccione</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="departament" class="form-label">Departamento</label>
                <select class="select2dep form-select" id="departament" name="departament"
                    aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="municipio" class="form-label">Municipio</label>
                <select class="select2muni form-select" id="municipio" name="municipio"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="address">Dirección</label>
                <input type="text" id="address" class="form-control" placeholder="Av. 5 Norte "
                    aria-label="Direccion" name="address" />
            </div>
            <div class="col-12 text-center demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Crear</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

   <!-- Update provider Modal -->
<div class="modal fade" id="updateProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Editar proveedor</h3>
          </div>
          <form id="addProviderForm" class="row" action="{{Route('provider.update')}}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" id="idupdate" name="idupdate">
            <div class="col-12 mb-3">
              <label class="form-label" for="razonsocialupdate">Razon Social</label>
              <input type="text" id="razonsocialupdate" name="razonsocialupdate" class="form-control" placeholder="Razon Social" autofocus required/>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="ncrupdate">NCR</label>
                <input type="text" id="ncrupdate" class="form-control" placeholder="xxxxxx-x"
                    aria-label="ncr" name="ncrupdate" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="nitupdate">DUI/NIT</label>
                <input type="text" id="nitupdate" class="form-control" placeholder="xxxxxxxx-x"
                    aria-label="nit" name="nitupdate" />
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="emailupdate">Correo</label>
                <input type="text" id="emailupdate" class="form-control" placeholder="john.doe@example.com"
                    aria-label="john.doe@example.com" name="emailupdate" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="tel1update">Teléfono</label>
                <input type="text" id="tel1update" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel1update" />
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="tel2update">Teléfono 2</label>
                <input type="text" id="tel2update" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel2update" />
                    <input type="hidden" name="phone_idupdate" id="phone_idupdate">
            </div>
            <div class="col-8 mb-3">
                <label for="companyupdate" class="form-label">Empresa</label>
                <select class="select2companyedit form-select" id="companyupdate" name="companyupdate"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="col-8 mb-3">
                <label for="countryedit" class="form-label">País</label>
                <select class="select2countryedit form-select" id="countryedit" name="countryedit"
                    aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                    <option>Seleccione</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="departamentedit" class="form-label">Departamento</label>
                <select class="select2depedit form-select" id="departamentedit" name="departamentedit"
                    aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="municipioedit" class="form-label">Municipio</label>
                <select class="select2muniedit form-select" id="municipioedit" name="municipioedit"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="addressupdate">Dirección</label>
                <input type="text" id="addressupdate" class="form-control" placeholder="Av. 5 Norte "
                    aria-label="Direccion" name="addressupdate" />
                    <input type="hidden" name="address_idupdate" id="address_idupdate">
            </div>
            <div class="col-12 text-center demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Actualizar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    @endsection

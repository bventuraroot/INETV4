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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-provider-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-provider.js') }}"></script>
@endsection

@section('title', 'Proveedores')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Proveedores</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-provider border-top">
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
                                <h5 class="p-2 rounded badge bg-label-primary"> <li class="ti ti-users ti-sm"></li> Empresa: {{$provider->company}}</h5></td>
                                <td>{{ $provider->ncr }}</td>
                                <td>{{ $provider->nit }}</td>
                                <td>{{ $provider->tel1 }} <br>
                                    {{ $provider->tel2 }}</td>
                                <td>
                                    <span>{{ Str::upper($provider->pais)  }}</span><br>
                                    <span>{{ $provider->departamento }}</span><br>
                                    <span>{{ $provider->municipio }}</span><br>
                                    <span><span class="p-2 rounded badge bg-label-primary">{{ $provider->address }}</span></span><br>
                                </td>
                                <td><span class="p-2 rounded badge bg-label-warning"> <li class="ti ti-mail ti-sm"></li> {{ $provider->email }}</span></td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editProvider({{ $provider->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="mx-1 ti ti-dots-vertical ti-sm"></i></a>
                                    <div class="m-0 dropdown-menu dropdown-menu-end">
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
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Crear nuevo proveedor</h3>
          </div>
          <form id="addProviderForm" class="row" action="{{Route('provider.store')}}" method="POST">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="mb-3 col-12">
              <label class="form-label" for="razonsocial">Razon Social</label>
              <input type="text" id="razonsocial" name="razonsocial" class="form-control" placeholder="Razon Social" autofocus required/>
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="ncr">NCR</label>
                <input type="text" id="ncr" class="form-control" onkeyup="NRCMask(this);" maxlength="15" aria-label="ncr" name="ncr" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="nit">DUI/NIT</label>
                <input type="text" id="nit" class="form-control" onkeyup="nitDuiMask(this);" aria-label="nit" maxlength="25" name="nit" />
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="email">Correo</label>
                <input type="email" id="email" class="form-control"  aria-label="john.doe@example.com" name="email"  />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="tel1">Teléfono</label>
                <input type="text" id="tel1" class="form-control" aria-label="xxxx-xxxx" name="tel1" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="tel2">Teléfono 2</label>
                <input type="text" id="tel2" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel2" />
            </div>
            <div class="mb-3 col-8">
                <label for="company" class="form-label">Empresa</label>
                <select class="select2company form-select" id="company" name="company"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="mb-3 col-8">
                <label for="country" class="form-label">País</label>
                <select class="select2country form-select" id="country" name="country"
                    aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                    <option>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-6">
                <label for="departament" class="form-label">Departamento</label>
                <select class="select2dep form-select" id="departament" name="departament"
                    aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-6">
                <label for="municipio" class="form-label">Municipio</label>
                <select class="select2muni form-select" id="municipio" name="municipio"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="address">Dirección</label>
                <input type="text" id="address" class="form-control" placeholder="Av. 5 Norte "
                    aria-label="Direccion" name="address" />
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

   <!-- Update provider Modal -->
<div class="modal fade" id="updateProviderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Editar proveedor</h3>
          </div>
          <form id="addProviderForm" class="row" action="{{Route('provider.update')}}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" id="idupdate" name="idupdate">
            <div class="mb-3 col-12">
              <label class="form-label" for="razonsocialupdate">Razon Social</label>
              <input type="text" id="razonsocialupdate" name="razonsocialupdate" class="form-control" placeholder="Razon Social" autofocus required/>
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="ncrupdate">NCR</label>
                <input type="text" id="ncrupdate" class="form-control" placeholder="xxxxxx-x"
                    aria-label="ncr" name="ncrupdate" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="nitupdate">DUI/NIT</label>
                <input type="text" id="nitupdate" class="form-control" placeholder="xxxxxxxx-x"
                    aria-label="nit" name="nitupdate" />
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="emailupdate">Correo</label>
                <input type="text" id="emailupdate" class="form-control" placeholder="john.doe@example.com"
                    aria-label="john.doe@example.com" name="emailupdate" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="tel1update">Teléfono</label>
                <input type="text" id="tel1update" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel1update" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="tel2update">Teléfono 2</label>
                <input type="text" id="tel2update" class="form-control" placeholder="xxxx-xxxx"
                    aria-label="xxxx-xxxx" name="tel2update" />
                    <input type="hidden" name="phone_idupdate" id="phone_idupdate">
            </div>
            <div class="mb-3 col-8">
                <label for="companyupdate" class="form-label">Empresa</label>
                <select class="select2companyedit form-select" id="companyupdate" name="companyupdate"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="mb-3 col-8">
                <label for="countryedit" class="form-label">País</label>
                <select class="select2countryedit form-select" id="countryedit" name="countryedit"
                    aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                    <option>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-6">
                <label for="departamentedit" class="form-label">Departamento</label>
                <select class="select2depedit form-select" id="departamentedit" name="departamentedit"
                    aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-6">
                <label for="municipioedit" class="form-label">Municipio</label>
                <select class="select2muniedit form-select" id="municipioedit" name="municipioedit"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                </select>
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="addressupdate">Dirección</label>
                <input type="text" id="addressupdate" class="form-control" placeholder="Av. 5 Norte "
                    aria-label="Direccion" name="addressupdate" />
                    <input type="hidden" name="address_idupdate" id="address_idupdate">
            </div>
            <div class="text-center col-12 demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Actualizar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    @endsection

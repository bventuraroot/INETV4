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
    <script src="{{ asset('assets/js/app-client-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-client.js') }}"></script>
@endsection

@section('title', 'Clientes')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Empresa</h5>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-client table border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Primer Nombre</th>
                        <th>Segundo Nombre</th>
                        <th>Tipo</th>
                        <th>Contribuyente</th>
                        <th>Nombre Comercial</th>
                        <th>Representante Legal</th>
                        <th>GIRO</th>
                        <th>NIT</th>
                        <th>NCR</th>
                        <th>Acciones</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @isset($clients)
                        @forelse($clients as $client)
                            <tr>
                                <td></td>
                                <td>{{ $client->firstname }}</td>
                                <td>{{ $client->secondname }}</td>
                                <td>
                                    @switch( Str::lower($client->tpersona) )
                                        @case('j')
                                            JURIDICA
                                        @break

                                        @case('n')
                                            NATURAL
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                <td>
                                @if ($client->contribuyente=="1")
                                    TRUE
                                @else
                                FALSE
                                @endif
                                </td>
                                <td>{{ $client->empresa }}</td>
                                <td>{{ $client->legal }}</td>
                                <td>{{ $client->giro }}</td>
                                <td>{{ $client->nit }}</td>
                                <td>{{ $client->ncr }}</td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editClient({{ $client->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:deleteClient({{ $client->id }});" class="dropdown-item"><i
                                                class="ti ti-eraser ti-sm me-2"></i>Eliminar</a>

                                    </div>

                                </div></td>
                                <td>{{ $client->email }}</td>
                                <td></td>
                            </tr>
                            @empty
                                <tr>
                                    <td></td>
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
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddClient" aria-labelledby="offcanvasAddUserLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Nuevo Cliente</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewClientForm" action="{{ route('client.store') }}" method="POST">
                        @csrf @method('POST')
                        <input type="hidden" id="companyselected" name="companyselected"
                            value="{{ isset($companyselected) ? $companyselected : 0 }}">
                        <div class="mb-3">
                            <label class="form-label" for="firstname">Primer Nombre</label>
                            <input type="text" class="form-control" id="firstname" placeholder="Primer Nombre"
                                name="firstname" aria-label="Primer Nombre" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="secondname">Segundo Nombre</label>
                            <input type="text" class="form-control" id="secondname" placeholder="Segundo Nombre"
                                name="secondname" aria-label="Segundo Nombre" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel1">Tel??fono</label>
                            <input type="text" id="tel1" class="form-control" placeholder="7488-8811"
                                aria-label="7488-8811" name="tel1" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel2">Tel??fono Fijo</label>
                            <input type="text" id="tel2" class="form-control" placeholder="2422-5654"
                                aria-label="2422-5654" name="tel2" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo</label>
                            <input type="text" id="email" class="form-control" placeholder="john.doe@example.com"
                                aria-label="john.doe@example.com" name="email" />
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Pa??s</label>
                            <select class="select2country form-select" id="country" name="country"
                                aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                                <option>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="departament" class="form-label">Departamento</label>
                            <select class="select2dep form-select" id="departament" name="departament"
                                aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="municipio" class="form-label">Municipio</label>
                            <select class="select2muni form-select" id="municipio" name="municipio"
                                aria-label="Seleccionar opcion">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address">Direcci??n</label>
                            <input type="text" id="address" class="form-control" placeholder="Av. 5 Norte "
                                aria-label="Direccion" name="address" />
                        </div>
                        <div class="mb-3">
                            <label for="tpersona" class="form-label">Tipo de cliente</label>
                            <select class="select2typeperson form-select" id="tpersona" name="tpersona"
                                aria-label="Seleccionar opcion" onchange="typeperson(this.value)">
                                <option value="0" selected>Seleccione</option>
                                <option value="N">NATURAL</option>
                                <option value="J">JURIDICA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="switch switch-success" id="contribuyentelabel" name="contribuyentelabel"
                                style="display: none;">
                                <input type="checkbox" class="switch-input" id="contribuyente" name="contribuyente" onclick="escontri()" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">??Es Contribuyente?</span>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nit">DUI/NIT</label>
                            <input type="text" id="nit" class="form-control" placeholder="xxxxxxxx-x"
                                aria-label="nit" name="nit" />
                        </div>
                        <div id="siescontri" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="legal">Representante Legal</label>
                                <input type="text" id="legal" class="form-control" placeholder="Representante Legal"
                                    aria-label="legal" name="legal" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ncr">NCR</label>
                                <input type="text" id="ncr" class="form-control" placeholder="xxxxxx-x"
                                    aria-label="ncr" name="ncr" />
                            </div>
                            <div class="mb-3">
                                <label for="acteconomica" class="form-label">Actividad Econ??mica</label>
                                <select class="select2act form-select" id="acteconomica" name="acteconomica"
                                    aria-label="Seleccionar opcion">
                                    <option value="0" selected>Seleccione</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="giro">GIRO</label>
                                <input type="text" id="giro" class="form-control" placeholder="giro"
                                    aria-label="giro" name="giro" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="empresa">Nombre Comercial</label>
                                <input type="text" id="empresa" class="form-control" placeholder="Nombre Comercial"
                                    aria-label="empresa" name="empresa" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Fecha de Nacimiento</label>
                            <input type="text" class="form-control" placeholder="DD-MM-YY" id="birthday" name="birthday" />
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    </form>
                </div>
            </div>

            <!-- Update client-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateClient"
                aria-labelledby="offcanvasUpdateClientLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasUpdateClientLabel" class="offcanvas-title">Editar Cliente</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewClientForm" action="{{ route('client.update') }}"
                        method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" id="companyselectededit" name="companyselectededit"
                            value="{{ isset($companyselected) ? $companyselected : 0 }}">
                        <input type="hidden" name="idedit" id="idedit">
                        <div class="mb-3">
                            <label class="form-label" for="firstnameedit">Primer Nombre</label>
                            <input type="text" class="form-control" id="firstnameedit" placeholder="Primer Nombre"
                                name="firstnameedit" aria-label="Primer Nombre" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="secondnameedit">Segundo Nombre</label>
                            <input type="text" class="form-control" id="secondnameedit" placeholder="Segundo Nombre"
                                name="secondnameedit" aria-label="Segundo Nombre" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel1edit">Tel??fono</label>
                            <input type="text" id="tel1edit" class="form-control" placeholder="7488-8811"
                                aria-label="7488-8811" name="tel1edit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel2edit">Tel??fono Fijo</label>
                            <input type="text" id="tel2edit" class="form-control" placeholder="2422-5654"
                                aria-label="2422-5654" name="tel2edit" />
                            <input type="hidden" name="phoneeditid" id="phoneeditid">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="emailedit">Correo</label>
                            <input type="text" id="emailedit" class="form-control" placeholder="john.doe@example.com"
                                aria-label="john.doe@example.com" name="emailedit" />
                        </div>
                        <div class="mb-3">
                            <label for="countryedit" class="form-label">Pa??s</label>
                            <select class="select2countryedit form-select" id="countryedit" name="countryedit"
                                aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value,'','','')">
                                <option>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="departamentedit" class="form-label">Departamento</label>
                            <select class="select2depedit form-select" id="departamentedit" name="departamentedit"
                                aria-label="Seleccionar opcion" onchange="getmunicipio(this.value,'','')">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="municipioedit" class="form-label">Municipio</label>
                            <select class="select2muniedit form-select" id="municipioedit" name="municipioedit"
                                aria-label="Seleccionar opcion">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="addressedit">Direcci??n</label>
                            <input type="text" id="addressedit" class="form-control" placeholder="john.doe@example.com"
                                aria-label="Direccion" name="addressedit" />
                                <input type="hidden" name="addresseditid" id="addresseditid">
                        </div>
                        <div class="mb-3">
                            <label for="tpersonaedit" class="form-label">Tipo de cliente</label>
                            <select class="select2typepersonedit form-select" id="tpersonaedit" name="tpersonaedit"
                                aria-label="Seleccionar opcion" onchange="typepersonedit(this.value)">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="switch switch-success" id="contribuyentelabeledit" name="contribuyentelabeledit"
                                style="display: none;">
                                <input type="checkbox" class="switch-input" id="contribuyenteedit" name="contribuyenteedit" onclick="escontriedit()" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">??Es Contribuyente?</span>
                            </label>
                            <input type="hidden" value="0" name="contribuyenteeditvalor" id="contribuyenteeditvalor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nitedit">DUI/NIT</label>
                            <input type="text" id="nitedit" class="form-control" placeholder="xxxxxxxx-x"
                                aria-label="nit" name="nitedit" />
                        </div>
                        <div id="siescontriedit" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="legaledit">Representante Legal</label>
                                <input type="text" id="legaledit" class="form-control" placeholder="Representante Legal"
                                    aria-label="legal" name="legaledit" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ncredit">NCR</label>
                                <input type="text" id="ncredit" class="form-control" placeholder="xxxxxx-x"
                                    aria-label="ncr" name="ncredit" />
                            </div>
                            <div class="mb-3">
                                <label for="acteconomicaedit" class="form-label">Actividad Econ??mica</label>
                                <select class="select2actedit form-select" id="acteconomicaedit" name="acteconomicaedit"
                                    aria-label="Seleccionar opcion">
                                    <option value="0"  selected>Seleccione</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="giroedit">GIRO</label>
                                <input type="text" id="giroedit" class="form-control" placeholder="giro" aria-label="giro"
                                    name="giroedit" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="empresaedit">Nombre Comercial</label>
                                <input type="text" id="empresaedit" class="form-control" placeholder="Nombre Comercial"
                                    aria-label="empresa" name="empresaedit" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birthdayedit" class="form-label">Fecha de Nacimiento</label>
                            <input type="text" class="form-control" placeholder="DD-MM-YY" id="birthdayedit" name="birthdayedit" />
                        </div>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>


    @endsection

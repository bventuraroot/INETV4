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
    <script src="{{ asset('assets/js/app-client-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-client.js') }}"></script>
@endsection

@section('title', 'Clientes')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Empresa</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-client border-top nowrap">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Contri</th>
                        <th>Tipo</th>
                        <th>Legal</th>
                        <th>NIT</th>
                        <th>NCR</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Direccion</th>
                        <th>Fecha Nacimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($clients)
                        @forelse($clients as $client)
                            <tr>
                                <td></td>
                                @switch( Str::lower($client->tpersona) )
                                        @case('j')
                                        <td>{{ $client->comercial_name }} {{ $client->empresa }}</td>
                                        @break

                                        @case('n')
                                        <td>{{ $client->firstname }} {{ $client->secondname }}</td>
                                        @break

                                        @default
                                    @endswitch

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
                                <td align="center">
                                @if ($client->contribuyente=="1" || Str::lower($client->tpersona)=='j')
                                <img src="{{ asset('assets/img/icons/misc/accept.png') }}" alt="image" width="25px">
                                @else

                                @endif
                                </td>
                                <td class="text-center">
                                    @switch($client->tipoContribuyente)
                                        @case('GRA')
                                            Grande
                                        @break

                                        @case('MED')
                                            Mediano
                                        @break

                                        @case('PEQU')
                                            Pequeño
                                        @break

                                        @case('OTR')
                                            Otro
                                        @break
                                        @default
                                    @endswitch
                                </td>
                                <td style="width: 16%">{{ $client->legal }}</td>
                                <td>{{ $client->nit }}</td>
                                <td>{{ $client->ncr }}</td>
                                <td>Cel: {{ $client->phone }} <br> Fijo: {{ $client->phone_fijo }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ Str::upper($client->pais  . ', ' . $client->departamento . ', ' . $client->municipioname . ', ' . $client->address)}}</td>
                                <td>{{ $client->birthday }}</td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editClient({{ $client->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="mx-1 ti ti-dots-vertical ti-sm"></i></a>
                                    <div class="m-0 dropdown-menu dropdown-menu-end">
                                        <a href="javascript:deleteClient({{ $client->id }});" class="dropdown-item"><i
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
                <div class="flex-grow-0 pt-0 mx-0 offcanvas-body h-100">
                    <form class="pt-0 add-new-user" id="addNewClientForm" action="{{ route('client.store') }}" method="POST">
                        @csrf @method('POST')
                        <input type="hidden" id="companyselected" name="companyselected"
                            value="{{ isset($companyselected) ? $companyselected : 0 }}">
                            <div class="mb-3">
                                <label for="tpersona" class="form-label">Tipo de cliente</label>
                                <select class="select2typeperson form-select" id="tpersona" name="tpersona"
                                    aria-label="Seleccionar opcion" onchange="typeperson(this.value)">
                                    <option value="0" selected>Seleccione</option>
                                    <option value="N">NATURAL</option>
                                    <option value="J">JURIDICA</option>
                                </select>
                            </div>
                            <div id="fields_natural" style="display: none;">
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
                                    <label class="form-label" for="firstlastname">Primer Apellido</label>
                                    <input type="text" class="form-control" id="firstlastname" placeholder="Primer Apellido"
                                        name="firstlastname" aria-label="Primer Apellido" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="secondlastname">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="secondlastname" placeholder="Segundo Apellido"
                                        name="secondlastname" aria-label="Segundo Apellido" />
                                </div>
                            </div>
                            <div id="fields_juridico" style="display: none">
                                <div class="mb-3">
                                    <label class="form-label" for="comercial_name">Nombre Comercial</label>
                                    <input type="text" class="form-control" id="comercial_name" placeholder="Nombre Comercial"
                                        name="comercial_name" aria-label="Nombre Comercial" />
                                </div>
                            </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel1">Teléfono</label>
                            <input type="text" id="tel1" class="form-control" placeholder="7488-8811"
                                aria-label="7488-8811" name="tel1" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel2">Teléfono Fijo</label>
                            <input type="text" id="tel2" class="form-control" placeholder="2422-5654"
                                aria-label="2422-5654" name="tel2" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo</label>
                            <input type="email" id="email" class="form-control" placeholder="john.doe@example.com"
                                aria-label="john.doe@example.com" name="email" />
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">País</label>
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
                            <label class="form-label" for="address">Dirección</label>
                            <input type="text" id="address" class="form-control" placeholder="Av. 5 Norte "
                                aria-label="Direccion" name="address" />
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
                                <span class="switch-label">¿Es Contribuyente?</span>
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
                                <label for="tipocontribuyente" class="form-label">Tipo de contribuyente</label>
                                <select class="select2tipocontri form-select" id="tipocontribuyente" name="tipocontribuyente"
                                    aria-label="Seleccionar opcion">
                                    <option selected>Seleccione</option>
                                    <option value="GRA">Gran Contribuyente</option>
                                    <option value="MED">Mediano</option>
                                    <option value="PEQU">Pequeño</option>
                                    <option value="OTR">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="acteconomica" class="form-label">Actividad Económica</label>
                                <select class="select2act form-select" id="acteconomica" name="acteconomica"
                                    aria-label="Seleccionar opcion">
                                    <option value="0" selected>Seleccione</option>
                                </select>
                            </div>
                            <div class="mb-3" style="display: none;">
                                <label class="form-label" for="giro">GIRO</label>
                                <input type="text" id="giro" class="form-control" placeholder="giro"
                                    aria-label="giro" name="giro" />
                            </div>
                            <div class="mb-3" style="display: none;">
                                <label class="form-label" for="empresa">Nombre Comercial</label>
                                <input type="text" id="empresa" class="form-control" placeholder="Nombre Comercial"
                                    aria-label="empresa" name="empresa" />
                            </div>
                        </div>
                        <div class="mb-3" id="nacimientof">
                            <label for="birthday" class="form-label">Fecha de Nacimiento</label>
                            <input type="text" class="form-control" placeholder="DD-MM-YY" id="birthday" name="birthday" />
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id="btnsavenewclient">Guardar</button>
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
                <div class="flex-grow-0 pt-0 mx-0 offcanvas-body h-100">
                    <form class="pt-0 add-new-user" id="addNewClientForm" action="{{ route('client.update') }}"
                        method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" id="companyselectededit" name="companyselectededit"
                            value="{{ isset($companyselected) ? $companyselected : 0 }}">
                        <input type="hidden" name="idedit" id="idedit">
                        <div class="mb-3">
                            <label for="tpersonaedit" class="form-label">Tipo de cliente</label>
                            <select class="select2typepersonedit form-select" id="tpersonaedit" name="tpersonaedit"
                                aria-label="Seleccionar opcion" onchange="typepersonedit(this.value)">
                            </select>
                        </div>
                        <div id="fields_natural_edit">
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
                                <label class="form-label" for="firstlastnameedit">Primer Apellido</label>
                                <input type="text" class="form-control" id="firstlastnameedit" placeholder="Primer Apellido"
                                    name="firstlastnameedit" aria-label="Primer Apellido" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="secondlastnameedit">Segundo Apellido</label>
                                <input type="text" class="form-control" id="secondlastnameedit" placeholder="Segundo Apellido"
                                    name="secondlastnameedit" aria-label="Segundo Apellido" />
                            </div>
                        </div>
                        <div id="fields_juridico_edit">
                            <div class="mb-3">
                                <label class="form-label" for="empresaedit">Nombre Comercial</label>
                                <input type="text" id="empresaedit" class="form-control" placeholder="Nombre Comercial"
                                    aria-label="empresa" name="empresaedit" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel1edit">Teléfono</label>
                            <input type="text" id="tel1edit" class="form-control" placeholder="7488-8811"
                                aria-label="7488-8811" name="tel1edit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tel2edit">Teléfono Fijo</label>
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
                            <label for="countryedit" class="form-label">País</label>
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
                            <label class="form-label" for="addressedit">Dirección</label>
                            <input type="text" id="addressedit" class="form-control" placeholder="john.doe@example.com"
                                aria-label="Direccion" name="addressedit" />
                                <input type="hidden" name="addresseditid" id="addresseditid">
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
                                <span class="switch-label">¿Es Contribuyente?</span>
                            </label>
                            <input type="hidden" value="0" name="contribuyenteeditvalor" id="contribuyenteeditvalor">
                        </div>
                        <div class="mb-3" id="dui_fields">
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
                                <label for="tipocontribuyenteedit" class="form-label">Tipo de contribuyente</label>
                                <select class="select2tipocontri form-select" id="tipocontribuyenteedit" name="tipocontribuyenteedit"
                                    aria-label="Seleccionar opcion">
                                    <option selected>Seleccione</option>
                                    <option value="GRA">Gran Contribuyente</option>
                                    <option value="MED">Mediano</option>
                                    <option value="PEQU">Pequeño</option>
                                    <option value="OTR">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="acteconomicaedit" class="form-label">Actividad Económica</label>
                                <select class="select2actedit form-select" id="acteconomicaedit" name="acteconomicaedit"
                                    aria-label="Seleccionar opcion">
                                    <option value="0"  selected>Seleccione</option>
                                </select>
                            </div>
                            <div class="mb-3" style="display: none;">
                                <label class="form-label" for="giroedit">GIRO</label>
                                <input type="text" id="giroedit" class="form-control" placeholder="giro" aria-label="giro"
                                    name="giroedit" />
                            </div>
                        </div>
                        <div class="mb-3" id="DOB_field">
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

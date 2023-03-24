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
    <script src="{{ asset('assets/js/app-company-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-company.js') }}"></script>
@endsection

@section('title', 'Empresas')

@section('content')
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-company table border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Logo</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>NIT</th>
                        <th>NCR</th>
                        <th>No Cuenta</th>
                        <th>Actividad Economica</th>
                        <th>Tipo</th>
                        <th>Contribuyente</th>
                        <th>Ubicacion</th>
                        <th>Direccion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($companies)
                        @forelse($companies as $company)
                            <tr>
                                <td></td>
                                <td><img src="{{ asset('assets/img/logo/' . $company->logo) }}" alt="logo" width="150px">
                                </td>
                                <td>{{ $company->name }}</td>
                                <td>{{ nl2br(wordwrap($company->email, 10, ' ', 1)) }}</td>
                                <td>{{ $company->nit }}</td>
                                <td>{{ $company->ncr }}</td>
                                <td>{{ $company->cuenta_no }}</td>
                                <td>{{ $company->econo }}</td>
                                <td>
                                    @switch($company->tipoEstablecimiento)
                                        @case('01')
                                            Sucursal
                                        @break

                                        @case('02')
                                            Casa Matriz
                                        @break

                                        @case('04')
                                            Bodega
                                        @break

                                        @case('07')
                                            Predio
                                        @break

                                        @case('20')
                                            Otro
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @switch($company->tipoContribuyente)
                                        @case('GRA')
                                            Grande
                                        @break

                                        @case('MED')
                                            Mediano
                                        @break

                                        @case('PEQ')
                                            Pequeño
                                        @break

                                        @case('OTR')
                                            Otro
                                        @break
                                        @default
                                    @endswitch
                                </td>
                                <td>{{ $company->pais }} <br>
                                    {{ $company->departamento }} <br>
                                    {{ $company->municipio }} </td>
                                <td>{{ $company->address }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('company.view',$company->id)}}" class="dropdown-item"><i
                                            class="ti ti-eye ti-sm me-2"></i>Ver</a>
                                        <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end m-0">
                                            <a href="javascript: editCompany({{ $company->id }});" class="dropdown-item"><i
                                                class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                            <a href="javascript:deleteCompany({{ $company->id }});" class="dropdown-item"><i
                                                    class="ti ti-eraser ti-sm me-2"></i>Eliminar</a>

                                        </div>
                                    </div>
                                </td>
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
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCompany"
                aria-labelledby="offcanvasAddCompanyLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCompanyLabel" class="offcanvas-title">Nueva Empresa</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewClientForm" action="{{ route('company.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf @method('POST')
                        <div class="mb-3">
                            <label class="form-label" for="name">Nombre Empresa</label>
                            <input type="text" class="form-control" id="name" placeholder="Primer Nombre" name="name"
                                aria-label="Company Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo Eléctronico</label>
                            <input type="text" class="form-control" id="email" placeholder="E-mail" name="email"
                                aria-label="inet@admin.com" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Teléfono</label>
                            <input type="text" id="phone" class="form-control" placeholder="xxxx-xxxx"
                                aria-label="7489-8555" name="phone" />
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
                            <input type="text" id="address" class="form-control" placeholder="Direccion complementaria"
                                aria-label="Direccion" name="address" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nit">DUI/NIT</label>
                            <input type="text" id="nit" class="form-control" placeholder="xxxxxxxx-x"
                                aria-label="nit" name="nit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ncr">NCR</label>
                            <input type="text" id="ncr" class="form-control" placeholder="xxxxxx-x"
                                aria-label="ncr" name="ncr" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="giro">GIRO</label>
                            <input type="text" id="giro" class="form-control" placeholder="giro" aria-label="giro"
                                name="giro" />
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
                            <label for="tipoEstablecimiento" class="form-label">Tipo de Establecimiento</label>
                            <select class="select2tipoes form-select" id="tipoEstablecimiento" name="tipoEstablecimiento"
                                aria-label="Seleccionar opcion">
                                <option selected>Seleccione</option>
                                <option value="01">Sucursal/Agencia</option>
                                <option value="02">Casa Matriz</option>
                                <option value="04">Bodega</option>
                                <option value="07">Predio y/o Patio</option>
                                <option value="20">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="acteconomica" class="form-label">Actividad Económica</label>
                            <select class="select2act form-select" id="acteconomica" name="acteconomica"
                                aria-label="Seleccionar opcion">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cuenta_no">Número de cuenta</label>
                            <input type="text" id="cuenta_no" class="form-control" placeholder="No Banco"
                                name="cuenta_no" />
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input class="form-control" type="file" id="logo" name="logo">
                        </div>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    </form>
                </div>
            </div>
            <!-- Form Update Company-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateCompany"
                aria-labelledby="offcanvasUpdateCompanyLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCompanyLabel" class="offcanvas-title">Editar Empresa</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewUpdateForm" action="{{ route('company.update') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf @method('PATCH')
                        <input type="hidden" name="idedit" id="idedit">
                        <div class="mb-3">
                            <label class="form-label" for="nameedit">Nombre Empresa</label>
                            <input type="text" class="form-control" id="nameedit" placeholder="Primer Nombre"
                                name="nameedit" aria-label="Company Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="emailedit">Correo Eléctronico</label>
                            <input type="text" class="form-control" id="emailedit" placeholder="E-mail" name="emailedit"
                                aria-label="inet@admin.com" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phoneedit">Teléfono</label>
                            <input type="text" id="phoneedit" class="form-control" placeholder="xxxx-xxxx"
                                aria-label="7489-8555" name="phoneedit" />
                                <input type="hidden" name="phoneeditid" id="phoneeditid">
                        </div>
                        <div class="mb-3">
                            <label for="countryedit" class="form-label">País</label>
                            <select class="select2countryedit form-select" id="countryedit" name="countryedit"
                                aria-label="Seleccionar opcion" onchange="getdepartamentos(this.value, 'edit','','')">
                                <option>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="departamentedit" class="form-label">Departamento</label>
                            <select class="select2depedit form-select" id="departamentedit" name="departamentedit"
                                aria-label="Seleccionar opcion" onchange="getmunicipio(this.value, 'edit', '')">
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
                            <input type="text" id="addressedit" class="form-control"
                                placeholder="Direccion complementaria" aria-label="Direccion" name="addressedit" />
                                <input type="hidden" name="addresseditid" id="addresseditid">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nitedit">DUI/NIT</label>
                            <input type="text" id="nitedit" class="form-control" placeholder="xxxxxxxx-x"
                                aria-label="nit" name="nitedit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ncredit">NCR</label>
                            <input type="text" id="ncredit" class="form-control" placeholder="xxxxxx-x"
                                aria-label="ncr" name="ncredit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="giroedit">GIRO</label>
                            <input type="text" id="giroedit" class="form-control" placeholder="giro" aria-label="giro"
                                name="giroedit" />
                        </div>
                        <label for="tipocontribuyenteedit" class="form-label">Tipo de contribuyente</label>
                        <select class="select2tipocontriedit form-select" id="tipocontribuyenteedit" name="tipocontribuyenteedit"
                            aria-label="Seleccionar opcion">
                            <option selected>Seleccione</option>
                            <option value="GRA">Gran Contribuyente</option>
                            <option value="MED">Mediano</option>
                            <option value="PEQU">Pequeño</option>
                            <option value="OTR">Otro</option>
                        </select>
                        <div class="mb-3">
                            <label for="tipoEstablecimientoedit" class="form-label">Tipo de Establecimiento</label>
                            <select class="select2tipoesedit form-select" id="tipoEstablecimientoedit"
                                name="tipoEstablecimientoedit" aria-label="Seleccionar opcion">
                                <option>Seleccione</option>
                                <option value="01">Sucursal/Agencia</option>
                                <option value="02">Casa Matriz</option>
                                <option value="04">Bodega</option>
                                <option value="07">Predio y/o Patio</option>
                                <option value="20">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="acteconomicaedit" class="form-label">Actividad Económica</label>
                            <select class="select2actedit form-select" id="acteconomicaedit" name="acteconomicaedit"
                                aria-label="Seleccionar opcion">
                                <option selected>Seleccione</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cuenta_noedit">Número de cuenta</label>
                            <input type="text" id="cuenta_noedit" class="form-control" placeholder="No Banco"
                                name="cuenta_noedit" />
                        </div>
                        <div class="mb-3" id="logoview">
                        </div>
                        <div class="mb-3">
                            <label for="logoedit" class="form-label">Logo</label>
                            <input class="form-control" type="file" id="logoedit" name="logoedit">
                        </div>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>


    @endsection

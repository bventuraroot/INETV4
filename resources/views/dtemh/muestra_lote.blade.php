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
    <script src="{{ asset('assets/js/app-muestracola-list.js') }}"></script>
@endsection

@section('title', 'Muestra Lote')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Muestra Lote</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-provider border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Doc</th>
                        <th>Fecha</th>
                        <th>Tipo<br>Transaccion</th>
                        <th>No.Id</th>
                        <th>Estado<br>Recibido</th>
                        <th>Estado</th>
                        <th>Mensaje</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($dtes)
                        @forelse($dtes as $d)
                            <tr  @if ($d->desTransaccion == "Invalidacion") style="color:red;" @endif>
                                <td></td>
                                <td>{{$d->tipo_doc}} &nbsp; {{$d->numero_factura}}</td>
                                <td>{{$d->created_at}}</td>
                                <td >{{$d->desTransaccion}}</td>
                                <td>{{$d->id_doc}}</td>
                                <td >Codigo Generacion: {{$d->codigoGeneracion}}<br>
                                    Sello: {{$d->selloRecibido}}<br>
                                    Fecha Recibido: {{$d->fhRecibido}}
                                    </td>
                                <td>{{$d->estadoHacienda}}</td>
                                <td>{{$d->descripcionMsg}}</td>
                                <td>{!!$d->observacionesMsg!!}</td>
                                <td><div class="d-flex align-items-center">
                                    @if ($d->tipoModelo == 2)
                                    <a href="{{route('factura.print', $d->id_factura)}}" class="btn btn-icon btn-secondary btn-xs" target="_blank"><i
                                        class="fas fa-print"></i></a>
                                        <a href="#"
                                        onclick="EnviarCorreo({{$d->id_factura}} ,'{{ $d->email_cliente}}',{{$d->numero_factura }},'{{ $d->nombre_cliente}}')"
                                        class="btn btn-icon btn-success btn-xs"><i class="fas fa-paper-plane"></i></a>
                                    @endif</td>
                            </tr>
                            @empty
                                <tr>
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
                                </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>

            <!-- Add provider Modal -->
<div class="modal fade" id="addContigenciaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Crear Contingencia</h3>
          </div>
          <form id="addContingenciaForm" class="row" action="{{Route('factmh.store')}}" method="POST">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="mb-3 col-6">
              <label class="form-label" for="versionJson">Version Json</label>
              <input type="text" id="versionJson" name="versionJson" class="form-control" placeholder="Version Json" value="3" readonly/>
            </div>
            <div class="mb-3 col-6">
                <label for="ambiente" class="form-label">Ambiente</label>
                <select class="select2ambiente form-select" id="ambiente" name="ambiente" aria-label="Seleccionar opcion" >
                    <option value="00">Prueba</option>
                    <option value="01">Produccion</option>
                </select>
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="name">Empresa</label>
                <select class="select2 form-select" id="company" name="company" aria-label="Seleccionar opcion"></select>
              </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="fechaCreacion">Fecha y Hora Creación</label>
                <input type="datetime-local" id="fechaCreacion" class="form-control" value="{{Now()}}" maxlength="15" aria-label="fechaCreacion" name="fechaCreacion" />
            </div>
            <div class="mb-3 col-6">
                <label class="form-label" for="fechaInicioFin">Fecha y Hora Inicio</label>
                <input type="datetime-local" id="fechaInicioFin" class="form-control" value="{{Now()}}" maxlength="15" aria-label="fechaInicioFin" name="fechaInicioFin" />
            </div>
            <div class="mb-3 col-12">
                <label for="tipoContingencia" class="form-label">Tipo Contingencia</label>
                <select class="select2tipoconti form-select" id="tipoContingencia" name="tipoContingencia" aria-label="Seleccionar opcion" required>
                    <option value="1">No disponibilidad de sistema del MH</option>
                    <option value="2">No disponibilidad de sistema del emisor</option>
                    <option value="3">Falla en el suministro de servicio de Internet del Emisor</option>
                    <option value="4">Falla en el suministro de servicio de energia eléctrica del emisor que impida la transmisión de los DTE</option>
                    <option value="5">Otro (deberá digitar un máximo de 500 caracteres explicando el motivo)</option>
                </select>
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="motivoContingencia">Motivo Contingencia</label>
                <input type="text" id="motivoContingencia" class="form-control" aria-label="Direccion" name="motivoContingencia" required/>
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="nombreResponsable">Nombre del Responsable</label>
                <input type="text" id="nombreResponsable" class="form-control" aria-label="Direccion" name="nombreResponsable" required/>
            </div>
            <div class="mb-3 col-5">
                <label class="form-label" for="tipoDocResponsable">Tipo Documento</label>
                <select class="select2tipodocumento form-select" id="tipoDocResponsable" name="tipoDocResponsable" aria-label="Seleccionar opcion" required>
                    <option value="36">NIT</option>
                    <option value="13">DUI</option>
                    <option value="37">Otro</option>
                    <option value="03">Pasaporte</option>
                    <option value="02">Carnet de Residente</option>
                </select>
            </div>
            <div class="mb-3 col-7">
                <label class="form-label" for="nuDocResponsable">Numero de Documento</label>
                <input type="text" id="nuDocResponsable"  onkeyup="nitDuiMask(this);" class="form-control" name="nuDocResponsable" required/>
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

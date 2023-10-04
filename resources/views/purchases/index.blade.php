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
    <script src="{{ asset('assets/js/app-purchase-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-purchase.js') }}"></script>
@endsection

@section('title', 'Compras')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3 card-title">Compras</h5>
            <div class="gap-3 pb-2 d-flex justify-content-between align-items-center row gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="table datatables-purchase border-top nowrap">
                <thead>
                    <tr>
                        <th>VER</th>
                        <th>NUMERO</th>
                        <th>TIPO DOC</th>
                        <th>FECHA</th>
                        <th>EXENTA</th>
                        <th>GRAVADA</th>
                        <th>IVA</th>
                        <th>OTROS</th>
                        <th>TOTAL</th>
                        <th>PROVEEDOR</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($purchases)
                        @forelse($purchases as $purchase)
                            <tr>
                                <td></td>
                                <td>{{ $purchase->number }}</td>
                                <td>{{ $purchase->namedoc }}</td>
                                <td>{{ date('d-M-Y', strtotime($purchase->date)) }}</td>
                                <td>{{ ($purchase->exenta=="" ? "0.00" : $purchase->exenta) }}</td>
                                <td>{{ ($purchase->gravada=="" ? "0.00" : $purchase->gravada)  }}</td>
                                <td>{{ ($purchase->iva=="" ? "0.00" : $purchase->iva)  }}</td>
                                <td>{{ ($purchase->otros=="" ? "0.00" : $purchase->otros)  }}</td>
                                <td>{{ ($purchase->total=="" ? "0.00" : $purchase->total)  }}</td>
                                <td>{{ $purchase->name_provider }}</td>
                                <td>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript: editpurchase({{ $purchase->idpurchase }});" class="dropdown-item"><i
                                                class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="mx-1 ti ti-dots-vertical ti-sm"></i></a>
                                            <div class="m-0 dropdown-menu dropdown-menu-end">
                                                <a href="javascript:deletepurchase({{ $purchase->idpurchase }});" class="dropdown-item"><i
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
 <!-- Add product Modal -->
 <div class="modal fade" id="addPurchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-pricing">
      <div class="p-3 modal-content p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="mb-4 text-center">
            <h3 class="mb-2">Ingresar compra</h3>
          </div>
          <form id="addpurchaseForm" class="row" action="{{Route('purchase.store')}}" method="POST" enctype="multipart/form-data">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="mb-3 col-4">
              <label class="form-label" for="number">Numero</label>
              <input type="text" id="number" name="number" class="form-control" placeholder="Numero de comprobante" autofocus required/>
            </div>
            <div class="mb-3 col-4">
                <label for="period" class="form-label">Periodo</label>
                <select class="select2purchase form-select" id="period" name="period"
                    aria-label="Seleccionar opcion">
                    <?php           $mes = date('m');
									$meses = date('m');
									for ($i = 1; $i <= $meses; $i++) {
										setlocale(LC_TIME, 'spanish');
										$fecha = DateTime::createFromFormat('!m', $i);
										$nmes = strftime("%B", $fecha->getTimestamp());
										?>
											<option <?php if($mes == $i){ echo "selected"; } ?> value="<?php if($i < 10){ echo "0".$i; }else{ echo $i; } ?>">
												<?php echo ucfirst($nmes); ?>
											</option>
										<?php
									}
								?>
                </select>
            </div>
            <div class="mb-3 col-4">
                <label for="company" class="form-label">Empresa</label>
                <select class="select2company form-select" id="company" name="company"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="mb-3 col-4">
                <label for="document" class="form-label">Tipo Documento</label>
                <select class="select2document form-select" id="document" name="document"
                    aria-label="Seleccionar opcion">
                    <option selected>Elije una opcion</option>
                    <option value="6">FACTURA</option>
                    <option value="3">COMPROBANTE DE CREDITO FISCAL</option>
                    <option value="9">NOTA DE CREDITO</option>
                </select>
            </div>
            <div class="mb-3 col-4">
                <label for="date" class="form-label">Fecha de Comprobante</label>
                <input type="text" class="form-control" placeholder="DD-MM-YYYY" id="date" name="date" />
            </div>
            <div class="mb-3 col-4">
                <label for="provider" class="form-label">Proveedor</label>
                <select class="select2provider form-select" id="provider" name="provider"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="exenta">Exenta</label>
                <input type="number" step="0.01" min="0.00" id="exenta" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="Exenta $" name="exenta" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="gravada">Gravada</label>
                <input type="number" step="0.01" id="gravada" value="0.00" class="form-control" onkeyup="calculaiva(this.value)" placeholder="$" aria-label="Gravada $" name="gravada" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="iva">IVA</label>
                <input type="number" step="0.01" id="iva" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="IVA $" name="iva" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="contrans">Contrans</label>
                <input type="number" step="0.01" id="contrans" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="Contrans $" name="contrans" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="fovial">FOVIAL</label>
                <input type="number" step="0.01" id="fovial" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="FOVIAL $" name="fovial" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="cesc">CESC</label>
                <input type="number" step="0.01" id="cesc" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="CESC $" name="cesc" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="iretenido">IVA Retenido</label>
                <input type="number" step="0.01" id="iretenido" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="IVA Retenido $" name="iretenido" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="others">Otros</label>
                <input type="number" step="0.01" id="others" value="0.00" class="form-control" onkeyup="suma()" placeholder="$" aria-label="Otros $" name="others" />
            </div>
            <div class="mb-3 col-4">
                <label class="form-label" for="total">Total</label>
                <input type="number" step="0.01" id="total" class="form-control" placeholder="$" aria-label="Total $" name="total" />
            </div>
            <div class="text-center col-12 demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Agregar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

             <!-- Add update Modal -->
             <div class="modal fade" id="updatePurchaseModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-simple modal-pricing">
                  <div class="p-3 modal-content p-md-5">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                      <div class="mb-4 text-center">
                        <h3 class="mb-2">Editar compra</h3>
                      </div>
                      <form id="updatepurchaseForm" class="row" action="{{Route('purchase.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PATCH')
                        <input type="hidden" name="iduseredit" id="iduseredit" value="{{Auth::user()->id}}">
                        <input type="hidden" name="idedit" id="idedit">
                        <div class="mb-3 col-4">
                          <label class="form-label" for="numberedit">Numero</label>
                          <input type="text" id="numberedit" name="numberedit" class="form-control" placeholder="Numero de comprobante" autofocus required/>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="periodedit" class="form-label">Periodo</label>
                            <select class="select2purchaseedit form-select" id="periodedit" name="periodedit"
                                aria-label="Seleccionar opcion">
                                <?php           $mes = date('m');
                                                $meses = date('m');
                                                for ($i = 1; $i <= $meses; $i++) {
                                                    setlocale(LC_TIME, 'spanish');
                                                    $fecha = DateTime::createFromFormat('!m', $i);
                                                    $nmes = strftime("%B", $fecha->getTimestamp());
                                                    ?>
                                                        <option <?php if($mes == $i){ echo "selected"; } ?> value="<?php if($i < 10){ echo "0".$i; }else{ echo $i; } ?>">
                                                            <?php echo ucfirst($nmes); ?>
                                                        </option>
                                                    <?php
                                                }
                                            ?>
                            </select>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="companyedit" class="form-label">Empresa</label>
                            <select class="select2companyedit form-select" id="companyedit" name="companyedit"
                                aria-label="Seleccionar opcion">
                            </select>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="documentedit" class="form-label">Tipo Documento</label>
                            <select class="select2documentedit form-select" id="documentedit" name="documentedit"
                                aria-label="Seleccionar opcion">
                                <option selected>Elije una opcion</option>
                                <option value="6">FACTURA</option>
                                <option value="3">COMPROBANTE DE CREDITO FISCAL</option>
                                <option value="9">NOTA DE CREDITO</option>
                            </select>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="dateedit" class="form-label">Fecha de Comprobante</label>
                            <input type="text" class="form-control" placeholder="DD-MM-YYYY" id="dateedit" name="dateedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label for="provideredit" class="form-label">Proveedor</label>
                            <select class="select2provideredit form-select" id="provideredit" name="provideredit"
                                aria-label="Seleccionar opcion">
                            </select>
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="exentaedit">Exenta</label>
                            <input type="number" step="0.01" min="0.00" id="exentaedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="Exenta $" name="exentaedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="gravadaedit">Gravada</label>
                            <input type="number" step="0.01" id="gravadaedit" value="0.00" class="form-control" onkeyup="calculaivaedit(this.value)" placeholder="$" aria-label="Gravada $" name="gravadaedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="ivaedit">IVA</label>
                            <input type="number" step="0.01" id="ivaedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="IVA $" name="ivaedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="contransedit">Contrans</label>
                            <input type="number" step="0.01" id="contransedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="Contrans $" name="contransedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="fovialedit">FOVIAL</label>
                            <input type="number" step="0.01" id="fovialedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="FOVIAL $" name="fovialedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="cescedit">CESC</label>
                            <input type="number" step="0.01" id="cescedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="CESC $" name="cescedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="iretenidoedit">IVA Retenido</label>
                            <input type="number" step="0.01" id="iretenidoedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="IVA Retenido $" name="iretenidoedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="othersedit">Otros</label>
                            <input type="number" step="0.01" id="othersedit" value="0.00" class="form-control" onkeyup="sumaedit()" placeholder="$" aria-label="Otros $" name="othersedit" />
                        </div>
                        <div class="mb-3 col-4">
                            <label class="form-label" for="totaledit">Total</label>
                            <input type="number" step="0.01" id="totaledit" class="form-control" placeholder="$" aria-label="Total $" name="totaledit" />
                        </div>
                        <div class="text-center col-12 demo-vertical-spacing">
                          <button type="submit" class="btn btn-primary me-sm-3 me-1">Agregar</button>
                          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
    @endsection

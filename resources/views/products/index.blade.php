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
    <script src="{{ asset('assets/js/app-product-list.js') }}"></script>
    <script src="{{ asset('assets/js/forms-product.js') }}"></script>
@endsection

@section('title', 'Productos')

@section('content')
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Productos</h5>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 companies"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-products table border-top">
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>IMAGEN</th>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>PROVEEDOR</th>
                        <th>C. FISCAL</th>
                        <th>TIPO</th>
                        <th>ESTADO</th>
                        <th>PRECIO</th>
                        <th>DESCRIPCION</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($products)
                        @forelse($products as $product)
                            <tr>
                                <td></td>
                                <td><img src="{{ asset('assets/img/products/' . $product->image) }}" alt="image" width="150px">
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->nameprovider }}</td>
                                <td>{{ $product->cfiscal }}</td>
                                <td>{{ $product->type }}</td>
                                <td>{{ $product->state }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->description }}</td>
                                <td><div class="d-flex align-items-center">
                                    <a href="javascript: editproduct({{ $product->id }});" class="dropdown-item"><i
                                        class="ti ti-edit ti-sm me-2"></i>Editar</a>
                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:deleteproduct({{ $product->id }});" class="dropdown-item"><i
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
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Crear nuevo producto</h3>
          </div>
          <form id="addproductForm" class="row" action="{{Route('product.store')}}" method="POST" enctype="multipart/form-data">
            @csrf @method('POST')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <div class="col-12 mb-3">
              <label class="form-label" for="name">Nombre Producto</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Nombre del producto" autofocus required/>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="description">Descripcion</label>
                <input type="text" id="description" class="form-control" placeholder="Descripcion"
                    aria-label="Descripcion" name="description" />
            </div>
            <div class="col-12 mb-3">
                <label for="provider" class="form-label">Proveedor</label>
                <select class="select2provider form-select" id="provider" name="provider"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="cfiscal" class="form-label">Clasificación Fiscal</label>
                <select class="select2cfiscal form-select" id="cfiscal" name="cfiscal"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                    <option value="gravado">Gravado</option>
                    <option value="exento">Exento</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="type" class="form-label">Tipo</label>
                <select class="select2type form-select" id="type" name="type"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                    <option value="gravado">Directo</option>
                    <option value="exento">Tercero</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="price">Precio</label>
                <input type="text" id="price" class="form-control" placeholder="$"
                    aria-label="Precio $" name="price" />
            </div>
            <div class="col-12 mb-3">
                <label for="image" class="form-label">Imagen</label>
                <input class="form-control" type="file" id="image" name="image">
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

             <!-- Add update Modal -->
<div class="modal fade" id="updateProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="mb-2">Editar producto</h3>
          </div>
          <form id="addproductForm" class="row" action="{{Route('product.update')}}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <input type="hidden" name="iduser" id="iduser" value="{{Auth::user()->id}}">
            <input type="hidden" name="idedit" id="idedit">
            <div class="col-12 mb-3">
              <label class="form-label" for="nameedit">Nombre Producto</label>
              <input type="text" id="nameedit" name="nameedit" class="form-control" placeholder="Nombre del producto" autofocus required/>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label" for="descriptionedit">Descripcion</label>
                <input type="text" id="descriptionedit" class="form-control" placeholder="Descripcion"
                    aria-label="Descripcion" name="descriptionedit" />
            </div>
            <div class="col-12 mb-3">
                <label for="provideredit" class="form-label">Proveedor</label>
                <select class="select2provideredit form-select" id="provideredit" name="provideredit"
                    aria-label="Seleccionar opcion">
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="cfiscaledit" class="form-label">Clasificación Fiscal</label>
                <select class="select2cfiscaledit form-select" id="cfiscaledit" name="cfiscaledit"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                    <option value="gravado">Gravado</option>
                    <option value="exento">Exento</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="typeedit" class="form-label">Tipo</label>
                <select class="select2typeedit form-select" id="typeedit" name="typeedit"
                    aria-label="Seleccionar opcion">
                    <option selected>Seleccione</option>
                    <option value="directo">Directo</option>
                    <option value="tercero">Tercero</option>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label" for="priceedit">Precio</label>
                <input type="text" id="priceedit" class="form-control" placeholder="$"
                    aria-label="Precio $" name="priceedit" />
            </div>
            <div class="col-6 mb-3" id="imageview">
            </div>
            <div class="col-12 mb-3">
                <label for="imageedit" class="form-label">Imagen</label>
                <input class="form-control" type="file" id="image" name="image">
            </div>
            <div class="col-12 text-center demo-vertical-spacing">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Guardar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Descartar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
    @endsection

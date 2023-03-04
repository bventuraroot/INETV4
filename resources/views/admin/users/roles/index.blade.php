@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/modal-add-role.js')}}"></script>
@endsection

@section('content')
<h4 class="fw-semibold mb-4">Lista de Roles</h4>

<p class="mb-4">Un rol proporcionaba acceso a menús y funciones predefinidos para que, dependiendo de <br> rol asignado un administrador puede tener acceso a lo que el usuario necesita.</p>
<!-- Role cards -->
<div class="row g-4">
    @foreach ($roles as $rol)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h6 class="fw-normal mb-2">Total {{$rol->countusers}} usuarios</h6>
              <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                @foreach ($rol->userdata as $index)
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{$index->name}}" class="avatar avatar-sm pull-up">
                    <img class="rounded-circle" src="{{ asset('assets/img/avatars/'.$index->image.'') }}" alt="Avatar">
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-1">
              <div class="role-heading">
                <h4 class="mb-1">{{ $rol->name}}</h4>
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#UpdateRoleModal{{$rol->id}}" class=""><span>Editar Rol</span></a>
              </div>
              <a href="javascript:void(0);" class="text-muted"><i class="ti ti-copy ti-md"></i></a>
            </div>
          </div>
        </div>
      </div>

    @endforeach
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="row h-100">
        <div class="col-sm-5">
          <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
            <img src="{{ asset('assets/img/illustrations/page-pricing-enterprise.png') }}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83">
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap add-new-role">Nuevo Rol</button>
            <p class="mb-0 mt-1">Agregar un nuevo rol</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Role cards -->

<!-- Add Role Modal -->
@include('_partials/_modals/modal-add-role')
<!-- / Add Role Modal -->
@endsection

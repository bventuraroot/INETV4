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
    <script src="{{ asset('assets/js/tables-datatables-advanced.js') }}"></script>
@endsection

@section('title', 'Reporte de Ventas')

@section('content')
<h4 class="py-3 mb-4 fw-bold">
    <span class="text-muted fw-light">Reportes /</span> Ventas
  </h4>

  <!-- Advanced Search -->
  <div class="card">
    <h5 class="card-header">Advanced Search</h5>
    <!--Search Form -->
    <div class="card-body">
      <form class="dt_adv_search" method="POST">
        <div class="row">
          <div class="col-12">
            <div class="row g-3">
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Name:</label>
                <input type="text" class="form-control dt-input dt-full-name" data-column=1 placeholder="Alaric Beslier" data-column-index="0">
              </div>
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Email:</label>
                <input type="text" class="form-control dt-input" data-column=2 placeholder="demo@example.com" data-column-index="1">
              </div>
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Post:</label>
                <input type="text" class="form-control dt-input" data-column=3 placeholder="Web designer" data-column-index="2">
              </div>
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">City:</label>
                <input type="text" class="form-control dt-input" data-column=4 placeholder="Balky" data-column-index="3">
              </div>
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Date:</label>
                <div class="mb-0">
                  <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                  <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                  <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Salary:</label>
                <input type="text" class="form-control dt-input" data-column=6 placeholder="10000" data-column-index="5">
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <hr class="mt-0">
    <div class="card-datatable table-responsive">
      <table class="table dt-advanced-search">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Post</th>
            <th>City</th>
            <th>Date</th>
            <th>Salary</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Post</th>
            <th>City</th>
            <th>Date</th>
            <th>Salary</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <!--/ Advanced Search -->
    @endsection

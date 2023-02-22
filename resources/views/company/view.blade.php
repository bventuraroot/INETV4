@extends('layouts/layoutMaster')

@section('title', 'Company View')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-user-view.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/company-analytics.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"> <a href="{{ url('company/index') }}">Company</a> / </span> Detalles
    </h4>
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            <img class="img-fluid rounded mb-3 pt-1 mt-4"
                                src="{{ asset('assets/img/logo/' . $company[0]->logo) }}" height="180" width="180"
                                alt="Logo Company" />
                            <div class="user-info text-center">
                                <h4 class="mb-2 bg-label-info">{{ $company[0]->name }}</h4>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-uppercase text-muted">Detalles</p>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="fw-semibold me-1">Correo Electronico:&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-primary">{{ $company[0]->email }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">NIT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-primary">{{ $company[0]->nit }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">NCR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-primary">{{ $company[0]->ncr }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Cuenta Bancaria:&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-primary">{{ $company[0]->cuenta_no }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Giro:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </span>
                                <span class="badge bg-label-primary">{{ $company[0]->giro }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Tipo de Establecimiento:</span>
                                <span class="badge bg-label-primary">
                                    @switch($company[0]->tipoEstablecimiento)
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
                                </span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Tipo de
                                    Actividad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                </span>
                                <span class="badge bg-label-primary">{{ $company[0]->econo }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span
                                    class="fw-semibold me-1">Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span
                                    class="badge bg-label-primary">{{ Str::upper($company[0]->pais) . ' ( ' . $company[0]->departamento . ', ' . $company[0]->municipio . ' )' }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Dirección Referencia:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-primary">{{ Str::upper($company[0]->address) }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Fecha de
                                    creación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-info">{{ $company[0]->created_at->format('d-m-Y') }}</span>
                            </li>
                            <li class="pt-1">
                                <span class="fw-semibold me-1">Ultima Actualización:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <span class="badge bg-label-success">{{ $company[0]->updated_at->format('d-m-Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Content -->

        <!-- Support Tracker -->
        <div class="col-12 col-md-8 mb-4">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Ventas Mensuales</h5>
                        <small class="text-muted">Update {{ date('d-m-Y H:i'); }}</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="supportTrackerMenu" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="supportTrackerMenu">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                    <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                        <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                            <h3 class="mb-0">$164.00</h3>
                            <p class="mb-0">Ventas</p>
                            <h3 class="mb-0">$60.00</h3>
                            <p class="mb-0">Costos</p>
                            <h3 class="mb-0">$104.00</h3>
                            <p class="mb-0">Utilidad</p>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">New Tickets</h6>
                                    <small class="text-muted">142</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Open Tickets</h6>
                                    <small class="text-muted">28</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center pb-1">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Response Time</h6>
                                    <small class="text-muted">1 Day</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">New Tickets</h6>
                                    <small class="text-muted">142</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center pb-1">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Response Time</h6>
                                    <small class="text-muted">1 Day</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                        <div id="supportTracker"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales last 6 months -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Sales</h5>
                        <small class="text-muted">Last 6 Months</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="salesLastMonthMenu" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesLastMonthMenu">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="salesLastMonth"></div>
                </div>
            </div>
        </div>

        <!-- Revenue Report -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card">
                <div class="card-header pb-3">
                    <h5 class="m-0 card-title">Revenue Report</h5>
                </div>
                <div class="card-body">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <div id="totalRevenueChart"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mt-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                        id="budgetId" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="budgetId">
                                        <a class="dropdown-item prev-year1" href="javascript:void(0);">
                                            <script>
                                                document.write(new Date().getFullYear() - 1)
                                            </script>
                                        </a>
                                        <a class="dropdown-item prev-year2" href="javascript:void(0);">
                                            <script>
                                                document.write(new Date().getFullYear() - 2)
                                            </script>
                                        </a>
                                        <a class="dropdown-item prev-year3" href="javascript:void(0);">
                                            <script>
                                                document.write(new Date().getFullYear() - 3)
                                            </script>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-center pt-4 mb-0">$25,825</h3>
                            <p class="mb-4 text-center"><span class="fw-semibold">Budget: </span>56,800</p>
                            <div class="px-3">
                                <div id="budgetChart"></div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary">Increase Button</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Status -->
        <div class="col-12 col-xl-4 mb-4 col-md-6 order-2 order-xl-1">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0 card-title">Project Status</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="projectStatusId" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectStatusId">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i
                                class="ti ti-currency-dollar ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                            <div class="me-2">
                                <h6 class="mb-0">$4,3742</h6>
                                <small class="text-muted">Your Earnings</small>
                            </div>
                            <p class="mb-0 text-success">+10.2%</p>
                        </div>
                    </div>
                    <div id="projectStatusChart"></div>
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="mb-0">Donates</h6>
                        <div class="d-flex">
                            <p class="mb-0 me-3">$756.26</p>
                            <p class="mb-0 text-danger">-139.34</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-1">
                        <h6 class="mb-0">Podcasts</h6>
                        <div class="d-flex">
                            <p class="mb-0 me-3">$2,207.03</p>
                            <p class="mb-0 text-success">+576.24</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earning Reports Tabs-->
        <div class="col-12 col-xl-8 mb-4 order-1 order-xl-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0">
                        <h5 class="mb-0">Earning Reports</h5>
                        <small class="text-muted">Yearly Earnings Overview</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="earningReportsTabsId" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsTabsId">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                                class="nav-link btn active d-flex flex-column align-items-center justify-content-center"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-orders-id"
                                aria-controls="navs-orders-id" aria-selected="true">
                                <div class="badge bg-label-secondary rounded p-2"><i
                                        class="ti ti-shopping-cart ti-sm"></i></div>
                                <h6 class="tab-widget-title mb-0 mt-2">Orders</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                                class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-sales-id"
                                aria-controls="navs-sales-id" aria-selected="false">
                                <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-chart-bar ti-sm"></i>
                                </div>
                                <h6 class="tab-widget-title mb-0 mt-2"> Sales</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                                class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-profit-id"
                                aria-controls="navs-profit-id" aria-selected="false">
                                <div class="badge bg-label-secondary rounded p-2"><i
                                        class="ti ti-currency-dollar ti-sm"></i></div>
                                <h6 class="tab-widget-title mb-0 mt-2">Profit</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                                class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                                role="tab" data-bs-toggle="tab" data-bs-target="#navs-income-id"
                                aria-controls="navs-income-id" aria-selected="false">
                                <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-chart-pie-2 ti-sm"></i>
                                </div>
                                <h6 class="tab-widget-title mb-0 mt-2">Income</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                                class="nav-link btn d-flex align-items-center justify-content-center disabled"
                                role="tab" data-bs-toggle="tab" aria-selected="false">
                                <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-plus ti-sm"></i></div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-0 ms-0 ms-sm-2">
                        <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                            <div id="earningReportsTabsOrders"></div>
                        </div>
                        <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                            <div id="earningReportsTabsSales"></div>
                        </div>
                        <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                            <div id="earningReportsTabsProfit"></div>
                        </div>
                        <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                            <div id="earningReportsTabsIncome"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

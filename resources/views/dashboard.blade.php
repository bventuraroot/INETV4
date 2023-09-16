@extends('layouts/layoutMaster')

@section('title', 'Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}">
</script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')

<div class="row">
  <!-- Website Analytics -->
  <div class="mb-4 col-lg-12" style="display: none;">
    <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg" id="swiper-with-pagination-cards">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="mt-2 mb-0 text-white">Website Analytics</h5>
              <small>Total 28.5% Conversion Rate</small>
            </div>
            <div class="row">
              <div class="order-2 col-lg-7 col-md-9 col-12 order-md-1">
                <h6 class="mt-0 mb-3 text-white mt-md-3">Traffic</h6>
                <div class="row">
                  <div class="col-6">
                    <ul class="mb-0 list-unstyled">
                      <li class="mb-4 d-flex align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">28%</p>
                        <p class="mb-0">Sessions</p>
                      </li>
                      <li class="mb-2 d-flex align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                        <p class="mb-0">Leads</p>
                      </li>
                    </ul>
                  </div>
                  <div class="col-6">
                    <ul class="mb-0 list-unstyled">
                      <li class="mb-4 d-flex align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">3.1k</p>
                        <p class="mb-0">Page Views</p>
                      </li>
                      <li class="mb-2 d-flex align-items-center">
                        <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12%</p>
                        <p class="mb-0">Conversions</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="order-1 my-4 text-center col-lg-5 col-md-3 col-12 order-md-2 my-md-0">
                <img src="{{asset('assets/img/illustrations/card-website-analytics-1.png')}}" alt="Website Analytics" width="170" class="card-website-analytics-img">
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="mt-2 mb-0 text-white">Website Analytics</h5>
              <small>Total 28.5% Conversion Rate</small>
            </div>
            <div class="order-2 col-lg-7 col-md-9 col-12 order-md-1">
              <h6 class="mt-0 mb-3 text-white mt-md-3">Spending</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="mb-0 list-unstyled">
                    <li class="mb-4 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12h</p>
                      <p class="mb-0">Spend</p>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">127</p>
                      <p class="mb-0">Order</p>
                    </li>
                  </ul>
                </div>
                <div class="col-6">
                  <ul class="mb-0 list-unstyled">
                    <li class="mb-4 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">18</p>
                      <p class="mb-0">Order Size</p>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">2.3k</p>
                      <p class="mb-0">Items</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="order-1 my-4 text-center col-lg-5 col-md-3 col-12 order-md-2 my-md-0">
              <img src="{{asset('assets/img/illustrations/card-website-analytics-2.png')}}" alt="Website Analytics" width="170" class="card-website-analytics-img">
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="mt-2 mb-0 text-white">Website Analytics</h5>
              <small>Total 28.5% Conversion Rate</small>
            </div>
            <div class="order-2 col-lg-7 col-md-9 col-12 order-md-1">
              <h6 class="mt-0 mb-3 text-white mt-md-3">Revenue Sources</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="mb-0 list-unstyled">
                    <li class="mb-4 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">268</p>
                      <p class="mb-0">Direct</p>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">62</p>
                      <p class="mb-0">Referral</p>
                    </li>
                  </ul>
                </div>
                <div class="col-6">
                  <ul class="mb-0 list-unstyled">
                    <li class="mb-4 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">890</p>
                      <p class="mb-0">Organic</p>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                      <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                      <p class="mb-0">Campaign</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="order-1 my-4 text-center col-lg-5 col-md-3 col-12 order-md-2 my-md-0">
              <img src="{{asset('assets/img/illustrations/card-website-analytics-3.png')}}" alt="Website Analytics" width="170" class="card-website-analytics-img">
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
  <!--/ Website Analytics -->
  <!-- Earning Reports -->
  <div class="mb-4 col-lg-12">
    <div class="card h-100">
      <div class="pb-0 card-header d-flex justify-content-between mb-lg-n4">
        <div class="mb-0 card-title">
          <h5 class="mb-0"></h5>
          <small class="text-muted"></small>
        </div>
        <!-- </div> -->
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4 d-flex flex-column align-self-end">
            <div class="flex-wrap gap-2 pb-1 mb-2 d-flex align-items-center">
              <h3 class="mb-0">Informacion General</h3>
              <div class="rounded badge bg-label-success"></div>
            </div>
            <small class="text-muted">01-09-2023 al 30-09-2023</small>
          </div>
          <div class="col-12 col-md-8">
            <div id="weeklyEarningReports"></div>
          </div>
        </div>
        <div class="p-3 mt-2 border rounded">
          <div class="gap-4 row gap-sm-0">
            <div class="col-12 col-sm-4">
              <div class="gap-2 d-flex align-items-center">
                <div class="p-1 rounded badge bg-label-primary"><i class="ti ti-users ti-sm"></i></div>
                <h6 class="mb-0">Clientes</h6>
              </div>
              <h4 class="pt-1 my-2">{{$tclientes}}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="{{$tclientes}}" aria-valuemin="0" aria-valuemax="10"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="gap-2 d-flex align-items-center">
                  <div class="p-1 rounded badge bg-label-info"><i class="ti ti-pencil ti-sm"></i></div>
                  <h6 class="mb-0">Ventas</h6>
                </div>
                <h4 class="pt-1 my-2">{{$tproducts}}</h4>
                <div class="progress w-75" style="height:4px">
                  <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="{{$tsales}}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            <div class="col-12 col-sm-4">
              <div class="gap-2 d-flex align-items-center">
                <div class="p-1 rounded badge bg-label-info"><i class="ti ti-truck ti-sm"></i></div>
                <h6 class="mb-0">Proveedores</h6>
              </div>
              <h4 class="pt-1 my-2">{{$tproviders}}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="{{$tproviders}}" aria-valuemin="0" aria-valuemax="10"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4" style="margin-top: 5%">
              <div class="gap-2 d-flex align-items-center">
                <div class="p-1 rounded badge bg-label-danger"><i class="ti ti-brand-paypal ti-sm"></i></div>
                <h6 class="mb-0">Productos</h6>
              </div>
              <h4 class="pt-1 my-2">{{$tproducts}}</h4>
              <div class="progress w-75" style="height:4px">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="{{$tproducts}}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4" style="margin-top: 5%">
                <div class="gap-2 d-flex align-items-center">
                  <div class="p-1 rounded badge bg-label-primary"><i class="ti ti-rss ti-sm"></i></div>
                  <h6 class="mb-0">DTES Enviados</h6>
                </div>
                <h4 class="pt-1 my-2">0</h4>
                <div class="progress w-75" style="height:4px">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <div class="col-12 col-sm-4" style="margin-top: 5%">
                <div class="gap-2 d-flex align-items-center">
                  <div class="p-1 rounded badge bg-label-danger"><i class="ti ti-brand-paypal ti-sm"></i></div>
                  <h6 class="mb-0">DTES Pendientes</h6>
                </div>
                <h4 class="pt-1 my-2">0</h4>
                <div class="progress w-75" style="height:4px">
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Earning Reports -->
</div>

@endsection

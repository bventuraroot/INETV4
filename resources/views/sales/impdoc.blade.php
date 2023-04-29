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
    <script src="{{ asset('assets/js/impdoc.js') }}"></script>
    <script src="{{ asset('assets/js/printThis.js') }}"></script>
    <script src="{{ asset('assets/js/numerosaletras.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('title', 'Impresion')

@section('content')
    <input type="button" class="btn btn-label-primary" style="color: purple" value="SAVE PDF" onclick="savepdf()">
<div class="container2" style="" id="imprimirdoc">
    <style type="text/css">
       .container{
                                border-color: black;
                                border-width: 1.5px;
                                border-style: solid;
                                border-radius: 25px;
                                line-height: 1.5;
                                font-size: 8pt;
                            }
                            .nofacfinal{
                                border-color: black;
                                border-width: 0.5px;
                                border-style: solid;
                                border-radius: 15px;
                                margin-top: 4%;
                                height: 120%;
                                width: 20%;
                                text-align: center;
                                background-color: #CCCCCC;
                                color: black;
                            }
                            #logodocfinal{
                                width: 80%;
                            }
                            .interlineado-nulo{
                                line-height: 1;
                            }
                            .porsi{
                                border-color: black;
                                border-width: 0.5px;
                                border-style: solid;
                                border-radius: 25px;
                            }
                            .cuerpodocfinal{
                                margin-top: 0%;
                                margin-bottom: 5%;
                                width: 100%;
                            }
                            .camplantilla{
                                padding: 5px;
                                width: 10%;
                            }
                            .dataplantilla{
                                padding: 5px;
                                width: 30%;
                                border-bottom-color: black;
                                border-bottom-width: 1px;
                            }
                            table.desingtable{
                                margin: 2%;
                            }
                            table.sample {
                                margin: 2%;
                            }
                            .details_products_documents{
                                width: 100%
                            }
                            .table_details{
                                margin-bottom: 2%;
                                width: 100%;
                                line-height: 30px;
                            }
                            .head_details{
                                margin: 1%;
                                color: black;
                                border-width: 1px;
                                border-radius: 25px;
                                border-style: solid;
                            }
                            #tbodydetails {
                                text-align: center;
                            }
                            .th_details{
                                text-align: center;
                            }
                            .td_details{
                                width: 5px;
                                text-align: center;

                            }
                            .tfoot_details{
                                border-top-width: 1px;
                                padding-top: 2%;
                                margin-top: 2%;
                                margin-bottom: 5%;
                                text-align: right;
                            }
    </style>
    <input type="hidden" name="corr" id="corr" value="{{ request('corr')!='' ? request('corr') : ''}}">
    <div class="container">
    <div class="row g-3">
        <div class="col-sm-8">
            <table border="0">
                <tr>
                    <td style="width: 250px">
                            <img  id="logodocfinal" src="">
                    </td>
                    <td style="width: 250px">
                            <p class="interlineado-nulo" id="addressdcfinal"></p>
                              <p class="interlineado-nulo" id="phonedocfinal"></p>
                              <p class="interlineado-nulo" id="emaildocfinal"></p>
                    </td>
                    <td style="width: 150px" class="">
                        <div class="nofacfinal" style="width: 250px; height: 100px;">
                            <b style="font-size: 17.5pt;" id="name_type_documents_details">FACTURA</b></br>
                            <small class="interlineado-nulo" id="corr_details"><b>1792067464001<b></small></br>
                            <small class="interlineado-nulo" id="NCR_details"><b>NCR: <b></small></br>
                            <small class="interlineado-nulo" id="NIT_details"><b>NIT: <b></small></br>
                        </div>

                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-8 cuerpodocfinal">
            <table class="sample">
                    <tr>
                        <td class="camplantilla">
                            Señor (es):
                        </td>
                        <td class="dataplantilla" id="name_client">

                        </td>
                        <td class="camplantilla" style="padding-left: 1%;">
                            Fecha:
                        </td>
                        <td class="dataplantilla" id="date_doc">

                        </td>
                    </tr>
                    <tr>
                        <td class="camplantilla">
                            Dirección:
                        </td>
                        <td class="dataplantilla" id="address_doc">

                        </td>
                        <td class="camplantilla" style="padding-left: 1%;">
                            DUI o NIT:
                        </td>
                        <td class="dataplantilla" id="duinit">

                        </td>
                    </tr>
                    <tr>
                        <td class="camplantilla">
                            Municipio:
                        </td>
                        <td class="dataplantilla" id="municipio_name">

                        </td>
                        <td class="camplantilla" style="padding-left: 1%;">
                            Giro:
                        </td>
                        <td class="dataplantilla" id="giro_name">

                        </td>
                    </tr>
                    <tr>
                        <td class="camplantilla">
                            Departamento:
                        </td>
                        <td class="dataplantilla" id="departamento_name">

                        </td>
                        <td class="camplantilla" style="padding-left: 1%;">
                            Forma de pago:
                        </td>
                        <td class="dataplantilla" id="forma_pago_name">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">

                        </td>
                        <td class="camplantilla" style="padding-left: 1%;">
                            Venta a cuenta de:
                        </td>
                        <td class="dataplantilla" id="acuenta_de">

                        </td>
                    </tr>
            </table>
        </div>
        <div class="col-sm-8 details_products_documents" id="details_products_documents">
            <table class="table_details" id="tblproduct">
                <thead class="head_details">
                    <tr>
                        <th class="text-center th_details">CANT.</th>
                        <th class="th_details">DESCRIPCION</th>
                        <th class="text-right th_details">PRECIO UNIT.</th>
                        <th class="text-right th_details">NO SUJETAS</th>
                        <th class="text-right th_details">EXENTAS</th>
                        <th class="text-right th_details">GRAVADAS</th>
                        <th class="text-right th_details">TOTAL</th>
                    </tr>
                </thead>
                <tbody id="tbodydetails">
                </tbody>
                <tfoot class="tfoot_details">
                    <tr>
                        <td rowspan="7" colspan="4" class="td_details" id="numtoletters" style="text-align: left; font-size: 8pt;"></td>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">SUMAS</td>
                        <td class="text-center td_details" id="sumasl">$ 0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">IVA 13%</td>
                        <td class="text-center td_details" id="13ival">$ 0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">(-) IVA Percibido</td>
                        <td class="text-center td_details" id="ivaretenidol">$0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">Ventas No Sujetas</td>
                        <td class="text-center td_details" id="ventasnosujetasl">$0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">Ventas Exentas</td>
                        <td class="text-center td_details" id="ventasexentasl">$0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right td_details" style="text-align: right; padding-right: 3%">Venta Total</td>
                        <td class="text-center td_details" id="ventatotall">$ 0.00</td>
                        <td class="quitar_documents td_details"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
  </div>
    @endsection

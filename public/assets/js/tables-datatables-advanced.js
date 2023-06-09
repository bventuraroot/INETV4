/**
 * DataTables Advanced (jquery)
 */

'use strict';

$(function () {

//Get companies avaibles
var iduser = $("#iduser").val();
$.ajax({
    url: "/company/getCompanybyuser/" + iduser,
    method: "GET",
    success: function (response) {
        //$("#company").append('<option value="0">Seleccione</option>');
        $.each(response, function (index, value) {
            $("#company").append(
                '<option value="' +
                    value.id +
                    '">' +
                    value.name.toUpperCase() +
                    "</option>"
            );
        });
    },
});

  var   dt_adv_filter_table = $('.dt-advanced-search'),
        startDateEle = $('.start_date'),
        endDateEle = $('.end_date');

    $("#first-filter").click(function(){
        var company = $('#company').val();
        var year=$('#year').val();
        var period=$('#period').val();
        var filtros="/"+company+"/"+year+"/"+period;
	    $('#body-table-sales').css('display','');


  // Advanced Search Functions Starts
  // --------------------------------------------------------------------

  // Datepicker for advanced filter
  var rangePickr = $('.flatpickr-range'),
    dateFormat = 'DD/MM/YYYY';

  if (rangePickr.length) {
    rangePickr.flatpickr({
      mode: 'range',
      dateFormat: 'd/m/Y',
      orientation: isRtl ? 'auto right' : 'auto left',
      locale: {
        format: dateFormat
      },
      onClose: function (selectedDates, dateStr, instance) {
        var startDate = '',
          endDate = new Date();
        if (selectedDates[0] != undefined) {
          startDate = moment(selectedDates[0]).format('DD/MM/YYYY');
          startDateEle.val(startDate);
        }
        if (selectedDates[1] != undefined) {
          endDate = moment(selectedDates[1]).format('DD/MM/YYYY');
          endDateEle.val(endDate);
        }
        $(rangePickr).trigger('change').trigger('keyup');
      }
    });
  }

  // Filter column wise function
  function filterColumn(i, val) {
    if (i == 2) {
      var startDate = startDateEle.val(),
        endDate = endDateEle.val();
      if (startDate !== '' && endDate !== '') {
        $.fn.dataTableExt.afnFiltering.length = 0; // Reset datatable filter
        dt_adv_filter_table.dataTable().fnDraw(); // Draw table after filter
        filterByDate(i, startDate, endDate); // We call our filter function
      }
      dt_adv_filter_table.dataTable().fnDraw();
    } else {
      dt_adv_filter_table.DataTable().column(i).search(val, false, true).draw();
    }
  }

  // Advance filter function
  // We pass the column location, the start date, and the end date
  $.fn.dataTableExt.afnFiltering.length = 0;
  var filterByDate = function (column, startDate, endDate) {
    // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
      var rowDate = normalizeDate(aData[column]),
        start = normalizeDate(startDate),
        end = normalizeDate(endDate);
      // If our date from the row is between the start and end
      if (start <= rowDate && rowDate <= end) {
        return true;
      } else if (rowDate >= start && end === '' && start !== '') {
        return true;
      } else if (rowDate <= end && start === '' && end !== '') {
        return true;
      } else {
        return false;
      }
    });
  };

  // converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
  var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    //var normalized = date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
    var normalized = dateString;
    return normalized;
  };
  // Advanced Search Functions Ends

  // Advanced Search
  // --------------------------------------------------------------------

  // Advanced Filter table
  if (dt_adv_filter_table.length) {
    var dt_adv_filter = dt_adv_filter_table.DataTable({
      dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
      bDestroy: true,
      ajax: {
        url: 'reportsales' + filtros,
        dataSrc: 'data'
    },
    columns: [
        { data: 'id' },
        { data: 'acuenta' },
        { data: 'date' },
        { data: 'document_name' },
        { data: 'firstname' },
        { data: 'company_name' },
        { data: 'waytopay' },
        { data: 'state' },
        { data: 'totalamount' },
        { data: 'created_at' },
      ],
      columnDefs: [
        {
           targets: 6,
           render: function (data, type, full, meta) {
             var $formadepago = full['waytopay'];
             var $output;
             switch ($formadepago) {
                case "1":
                    $output = "CONTADO";
                    break;
                case "2":
                    $output = "CREDITO";
                    break;
                case "3":
                    $output = "OTRO";
                    break;
                default:
                    $output = "Nothing data";
                    break;
             }
             return (
               "<span class='text-truncate d-flex align-items-center'>" +
               $output +
               '</span>'
             );
           }
        },
        {
            targets: 2,
             render: function (data, type, full, meta) {
               var $date = full['date'];
               var $outputdate;
               $outputdate = moment($date, 'YYYY-MM-DD').format('DD/MM/YYYY');
               return (
                 "<span class='text-truncate d-flex align-items-center'>" +
                 $outputdate +
                 '</span>'
               );
             }
        },
        {
             targets: 7,
             render: function (data, type, full, meta) {
               var $state = full['state'];
               var $outputstate;

               switch ($state) {
                case 0:
                    $outputstate = "ANULADO";
                    break;
                case 1:
                    $outputstate = "CONFIRMADO";
                    break;
                case 2:
                    $outputstate = "PENDIENTE";
                    break;
                case 3:
                    $outputstate = "FACTURADO";
                    break;
                default:
                    $outputstate = "Nothing data";
                    break;
               }
               return (
                 "<span class='text-truncate d-flex align-items-center'>" +
                 $outputstate +
                 '</span>'
               );
             }
        },
        {
            targets: 9,
            render: function (data, type, full, meta) {
              var $month = full['created_at'];
              var $outputmonth = moment($month, 'YYYY-MM-DD').format('M');
              return (
                "<span class='text-truncate d-flex align-items-center'>" +
                $outputmonth +
                '</span>'
              );
            }
       },
        {
            targets: 8,
            render: function (data, type, full, meta) {
              var $amount = full['totalamount'];
              return (
                "<span class='text-truncate d-flex align-items-center'>$ " +
                $amount +
                '</span>'
              );
            }
       }
      ],
      orderCellsTop: true,
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  // on key up from input field
  $('input.dt-input').on('keyup', function () {
    filterColumn($(this).attr('data-column'), $(this).val());
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 200);
});
});

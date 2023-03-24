/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_user_table = $('.datatables-provider'),
    select2 = $('.select2country');

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Pais',
      dropdownParent: $this.parent()
    });
  }

  var selectdep = $('.select2dep');

  if (selectdep.length) {
    var $this = selectdep;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Departamento',
      dropdownParent: $this.parent()
    });
  }

  var selectdmuni = $('.select2muni');

  if (selectdmuni.length) {
    var $this = selectdmuni;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Municipio',
      dropdownParent: $this.parent()
    });
  }

  var selectdcompany = $('.select2company');

  if (selectdcompany.length) {
    var $this = selectdcompany;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar empresa',
      dropdownParent: $this.parent()
    });
  }

  var selectdacteconomica = $('.select2act');

  if (selectdacteconomica.length) {
    var $this = selectdacteconomica;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Actividad economica',
      dropdownParent: $this.parent()
    });
  }

  var selectdtype = $('.select2typeperson');

  if (selectdtype.length) {
    var $this = selectdtype;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }

  var select2countryedit = $('.select2countryedit');

  if (select2countryedit.length) {
    var $this = select2countryedit;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }

  var select2depedit = $('.select2depedit');

  if (select2depedit.length) {
    var $this = select2depedit;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }
  var select2muniedit = $('.select2muniedit');

  if (select2muniedit.length) {
    var $this = select2muniedit;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }
  var select2typepersonedit = $('.select2typepersonedit');

  if (select2typepersonedit.length) {
    var $this = select2typepersonedit;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }
  var select2actedit = $('.select2actedit');

  if (select2actedit.length) {
    var $this = select2actedit;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Seleccionar Cliente',
      dropdownParent: $this.parent()
    });
  }
  // Client datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 0,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"row me-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Buscar'
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-3',
          text: '<i class="ti ti-screen-share me-1 ti-xs"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Agregar Proveedor</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addProviderModal'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Detalles proveedor ' + data[0] + ' ' + data[1];
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

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

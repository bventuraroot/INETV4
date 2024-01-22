/**
 * Page sale List
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
  var dt_sale_table = $('.datatables-sale');
  // Client datatable
  if (dt_sale_table.length) {
    var dt_sale = dt_sale_table.DataTable({
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
        },
        {
            responsivePriority: 1,
            targets: 8
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
                      if (item.classList !== undefined && item.classList.contains('sale-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('sale-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('sale-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('sale-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('sale-name')) {
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
          text: '<i class="ti ti-report-money ti-tada me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Nueva Venta</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#selectDocumentModal'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Detalles venta ' + data[0] + ' ' + data[1];
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

function retomarsale(corr, document){
    window.location.href ="create?corr=" + corr + "&draft=true&typedocument=" + document +"&operation=delete";
  }

  function cancelsale(id){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })

      swalWithBootstrapButtons.fire({
        title: 'Anular?',
        text: "Esta accion no tiene retorno",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Anular!',
        cancelButtonText: 'No, Cancelar!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/sale/destroy/"+btoa(id),
                method: "GET",
                success: function(response){
                        if(response.res==1){
                            Swal.fire({
                                title: 'Documento Invalidado correctamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                  location.reload();
                                }
                              })

                        }else if(response.res==0){
                            swalWithBootstrapButtons.fire(
                                'Problemas!',
                                'Algo sucedio y no pudo invalidar el documento, favor comunicarse con el administrador.',
                                'success'
                              )
                        }
            }
            });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelado',
            'No hemos hecho ninguna accion :)',
            'error'
          )
        }
      })
   }


   function ncr(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons
        .fire({
            title: "Nota de Crédito?",
            text: "Esta accion no tiene retorno",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Si, Crear!",
            cancelButtonText: "No, espera!",
            reverseButtons: true,
            showLoaderOnConfirm: true, // Agrega un ícono de espera en el botón de confirmación
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        url: "/sale/ncr/"+btoa(id),
                        method: "GET",
                        success: function (response) {
                            console.log(response);
                            if (response.res == 1) {
                                resolve(response); // Resuelve la promesa si la solicitud es exitosa
                            } else if (response.res == 0) {
                                reject("Algo salió mal"); // Rechaza la promesa si hay un problema
                            }
                        },
                    });
                });
            },
        })
        .then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Nota de Crédito Creado Correctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index";
                    }
                });
            }
        })
        .catch((error) => {
            swalWithBootstrapButtons.fire(
                "Problemas!",
                'Algo sucedio y no se creo la nota de crédito, favor comunicarse con el administrador.',
                "success"
            );
        });
}


  function printsale(corr){
    var url = 'impdoc/'+corr;
    window.open(url, '_blank');
  }


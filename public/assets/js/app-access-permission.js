/**
 * App user list (jquery)
 */

'use strict';

$(function () {
  var dataTablePermissions = $('.datatables-permissions'),
    dt_permission,
    userList = baseUrl;

  // Users List datatable
  if (dataTablePermissions.length) {
    dt_permission = dataTablePermissions.DataTable({
        ajax: {
            url: 'getpermission',
            dataSrc: 'data',
        },
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 1,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // Name
          targets: 1,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            return '<span class="text-nowrap m-2">' + $name.toUpperCase() + '</span>';
          }
        },
        {
          // User Role
          targets: 2,
          orderable: false,
          render: function (data, type, full, meta) {
            var $assignedTo = full['roles'],
              $output = '';
            var roleBadgeObj = {
              Admin: '<span class="badge bg-label-primary m-1">Administrator</span></a>',
              Contabilidad: '<span class="badge bg-label-warning m-1">Contabilidad</span></a>',
              Ventas: '<span class="badge bg-label-success m-1">Ventas</span></a>',
              Caja: '<span class="badge bg-label-info m-1">Caja</span></a>',
              Restricted:'<span class="badge bg-label-danger m-1">Restricted User</span></a>'
            };
            for (var i = 0; i < $assignedTo.length; i++) {
              var val = $assignedTo[i];
              val = val.rolename;
              $output += (roleBadgeObj[val]!='undefined')?'<span class="badge bg-label-primary m-1">'+ val +'</span></a>':roleBadgeObj[val];
            }
            return '<span class="text-nowrap">' + $output + '</span>';
          }
        },
        {
          // remove ordering from Name
          targets: 3,
          orderable: false,
          render: function (data, type, full, meta) {
            var $date = full['created_at'];
            var $datefull = $date.split('-');
            var $horafull = $date.split(':');
            return '<span class="text-nowrap badge bg-label-info m-1">' + $datefull[2].substring(0,2) +'-' + $datefull[1] +'-' + $datefull[0] + ' ' + $horafull[0].substring(0,2) + ':' + $horafull[1] + ':' + $horafull[2].substring(0,2) + '</span>';
          }
        },
        {
          // Actions
          targets: 4,
          searchable: false,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            var $id = full['id'];
            var $name = full['name'];
            return (
              '<span class="text-nowrap"><button class="btn btn-sm btn-icon me-2" onclick="javascript:editpermission('+
              $id + ','+ "'" + $name + "'"
               +');"><i class="ti ti-edit"></i></button>' +
              '<button class="btn btn-sm btn-icon onclick="javascript:deletepermission('+
              $id
              +');"><i class="ti ti-trash"></i></button></span>'
            );
          }
        }
      ],
      order: [[3, 'desc']],
      dom:
        '<"row mx-1"' +
        '<"col-sm-12 col-md-3" l>' +
        '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-3"f>B>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Buscar',
        searchPlaceholder: 'Buscar..'
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: 'Agregar Permiso',
          className: 'add-new btn btn-primary mb-3 mb-md-0',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#addPermissionModal'
          },
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Detalles de ' + data['name'];
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

function editpermission(id, name){
    $('#editPermissionName').val(name);
    $('#editPermissionid').val(id);
    $("#editPermissionModal").modal("show");
}

function deletepermission(id){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })

      swalWithBootstrapButtons.fire({
        title: '¿Eliminar?',
        text: "Esta accion no tiene retorno",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminarlo!',
        cancelButtonText: 'No, Cancelar!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "destroy/"+btoa(id),
                method: "GET",
                success: function(response){
                        if(response.res==1){
                            Swal.fire({
                                title: 'Eliminado',
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
                                'Algo sucedio y no pudo eliminar la empresa, favor comunicarse con el administrador.',
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

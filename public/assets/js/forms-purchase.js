/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){
    //Get providers avaibles
    var iduser = $('#iduser').val();
    $.ajax({
        url: "/provider/getproviders",
        method: "GET",
        success: function(response){
            //console.log(response);
            $('#provider').append('<option value="0">Seleccione</option>');
            $.each(response, function(index, value) {
                $('#provider').append('<option value="'+value.id+'">'+value.razonsocial.toUpperCase()+'</option>');
                $('#provideredit').append('<option value="'+value.id+'">'+value.razonsocial.toUpperCase()+'</option>');
              });
        }
    });

    $.ajax({
        url: "/company/getCompanybyuser/" + iduser,
        method: "GET",
        success: function (response) {
            $("#company").append('<option value="0">Seleccione</option>');
            $.each(response, function (index, value) {
                $("#company").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name.toUpperCase() +
                        "</option>"
                );
                $("#companyedit").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name.toUpperCase() +
                        "</option>"
                );
            });
        },
    });
});

function calculaiva(monto){
    monto=parseFloat(monto*13/100).toFixed(2);
    $("#iva").val(monto);
    suma();
};
//edit
function calculaivaedit(monto){
    monto=parseFloat(monto*13/100).toFixed(2);
    $("#ivaedit").val(monto);
    suma();
};

function suma(){
    var gravada = $("#gravada").val();
    var iva = $("#iva").val();
    var exenta = $("#exenta").val();
    var otros = $("#others").val();
    var contrans = $("#contrans").val();
    var fovial = $("#fovial").val();
    var retencion_iva = $("#iretenido").val();

    gravada = parseFloat(gravada);
    iva = parseFloat(iva);
    exenta = parseFloat(exenta);
    otros = parseFloat(otros);
    contrans = parseFloat(contrans);
    fovial = parseFloat(fovial);
    retencion_iva = parseFloat(retencion_iva);
    $("#total").val(parseFloat(gravada+iva+exenta+otros+contrans+fovial+retencion_iva).toFixed(2));
};
//edit
function sumaedit(){
    var gravada = $("#gravadaedit").val();
    var iva = $("#ivaedit").val();
    var exenta = $("#exentaedit").val();
    var otros = $("#othersedit").val();
    var contrans = $("#contransedit").val();
    var fovial = $("#fovialedit").val();
    var retencion_iva = $("#iretenidoedit").val();

    gravada = parseFloat(gravada);
    iva = parseFloat(iva);
    exenta = parseFloat(exenta);
    otros = parseFloat(otros);
    contrans = parseFloat(contrans);
    fovial = parseFloat(fovial);
    retencion_iva = parseFloat(retencion_iva);
    $("#totaledit").val(parseFloat(gravada+iva+exenta+otros+contrans+fovial+retencion_iva).toFixed(2));
};
   function editpurchase(id){
    //Get data edit Products
    $.ajax({
        url: "getpurchaseid/"+btoa(id),
        method: "GET",
        success: function(response){
            console.log(response);
            $.each(response, function(index, value) {
                    if(value==null) {
                        value = "0.00";
                    }
                    $('#'+index+'edit').val(value);
                    if(index=='provider_id'){
                        $("#provideredit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='company_id'){
                        $("#companyedit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='periodo'){
                        $("#periodedit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='document_id'){
                        $("#documentedit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='provider_id'){
                        $("#provideredit option[value='"+ value  +"']").attr("selected", true);
                    }

              });
              $("#updatePurchaseModal").modal("show");
        }
    });
   }

   function deletepurchase(id){
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
                                'Algo sucedio y no pudo eliminar el cliente, favor comunicarse con el administrador.',
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

   (function () {
    // Flat Picker
    // --------------------------------------------------------------------
    const flatpickrDate = document.querySelector('date')
    const flatpickrDateedit = document.querySelector('#dateedit')

    // Date
    if (flatpickrDate) {
      flatpickrDate.flatpickr({
        //monthSelectorType: 'static',
        dateFormat: 'd-m-Y'
      });
    }

    //date edit
    if (flatpickrDateedit) {
        flatpickrDateedit.flatpickr({
          //monthSelectorType: 'static',
          dateFormat: 'd-m-Y'
        });
      }
  })();


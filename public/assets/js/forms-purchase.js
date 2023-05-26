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
            });
        },
    });
});

function calculaiva(monto){
    monto=parseFloat(monto*13/100).toFixed(2);
    $("#iva").val(monto);
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

   function editproduct(id){
    //Get data edit Products
    $.ajax({
        url: "getproductid/"+btoa(id),
        method: "GET",
        success: function(response){
            console.log(response);
            $.each(response[0], function(index, value) {
                    $('#'+index+'edit').val(value);
                    if(index=='image'){
                        $('#imageview').html("<img src='http://inetv4.test/assets/img/products/"+value+"' alt='image' width='180px'><input type='hidden' name='imageeditoriginal' id='imageeditoriginal'/>");
                        $('#imageeditoriginal').val(value);
                    }
                    if(index=='provider_id'){
                        $("#provideredit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='cfiscal'){
                        $("#cfiscaledit option[value='"+ value  +"']").attr("selected", true);
                    }
                    if(index=='type'){
                        $("#typeedit option[value='"+ value  +"']").attr("selected", true);
                    }

              });
              $("#updateProductModal").modal("show");
        }
    });
   }

   function deleteproduct(id){
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
    const flatpickrDate = document.querySelector('#datedoc')

    // Date
    if (flatpickrDate) {
      flatpickrDate.flatpickr({
        //monthSelectorType: 'static',
        dateFormat: 'd-m-Y'
      });
    }
  })();


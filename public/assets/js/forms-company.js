/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){
    //Get countrys avaibles
    getpaises();
});

(function () {
  // Flat Picker
  // --------------------------------------------------------------------
  const flatpickrDate = document.querySelector('#birthday')

  // Date
  if (flatpickrDate) {
    flatpickrDate.flatpickr({
      //monthSelectorType: 'static',
      dateFormat: 'd-m-yy'
    });
  }
})();

function getpaises(selected="",type=""){
    if(type=='edit'){
        $.ajax({
            url: "/getcountry",
            method: "GET",
            success: function(response){
                $.each(response, function(index, value) {
                    if(selected!="" && value.id==selected){
                        $('#countryedit').append('<option value="'+value.id+'" selected>'+value.name.toUpperCase()+'</option>');
                    }else{
                        $('#countryedit').append('<option value="'+value.id+'">'+value.name.toUpperCase()+'</option>');
                    }

                  });
            }
        });
    }else{
        $.ajax({
            url: "/getcountry",
            method: "GET",
            success: function(response){
                $.each(response, function(index, value) {
                    $('#country').append('<option value="'+value.id+'">'+value.name.toUpperCase()+'</option>');
                  });
            }
        });
    }

}

function getdepartamentos(pais, type="", selected, selectedact){
 //Get countrys avaibles
 if(type=='edit'){
    $.ajax({
        url: "/getdepartment/"+btoa(pais),
        method: "GET",
        success: function(response){
            $('#departamentedit').find('option:not(:first)').remove();
            $.each(response, function(index, value) {
                if(selected!="" && value.id==selected){
                    $('#departamentedit').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                }else{
                    $('#departamentedit').append('<option value="'+value.id+'">'+value.name+'</option>');
                }

              });
        }
    });

     //Get acteconomica
 $.ajax({
    url: "/geteconomicactivity/"+btoa(pais),
    method: "GET",
    success: function(response){
        $.each(response, function(index, value) {
            if(selectedact!=="" && value.id==selectedact){
                $('#acteconomicaedit').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
            }else{
                $('#acteconomicaedit').append('<option value="'+value.id+'">'+value.name+'</option>');
            }

          });
    }
});
 }else{
    $.ajax({
        url: "/getdepartment/"+btoa(pais),
        method: "GET",
        success: function(response){
            $('#departament').find('option:not(:first)').remove();
            $.each(response, function(index, value) {
                $('#departament').append('<option value="'+value.id+'">'+value.name+'</option>');
              });
        }
    });
     //Get acteconomica
 $.ajax({
    url: "/geteconomicactivity/"+btoa(pais),
    method: "GET",
    success: function(response){
        $.each(response, function(index, value) {
            $('#acteconomica').append('<option value="'+value.id+'">'+value.name+'</option>');
          });
    }
});
 }
}

function getmunicipio(dep, type="", selected){
    if(type=='edit'){
//Get countrys avaibles
$.ajax({
    url: "/getmunicipality/"+btoa(dep),
    method: "GET",
    success: function(response){
     $('#municipioedit').find('option:not(:first)').remove();
        $.each(response, function(index, value) {
            if(selected!=="" && value.id==selected){
                $('#municipioedit').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
            }else{
                $('#municipioedit').append('<option value="'+value.id+'">'+value.name+'</option>');
            }

          });
    }
});
    }else{
//Get countrys avaibles
$.ajax({
    url: "/getmunicipality/"+btoa(dep),
    method: "GET",
    success: function(response){
     $('#municipio').find('option:not(:first)').remove();
        $.each(response, function(index, value) {
            $('#municipio').append('<option value="'+value.id+'">'+value.name+'</option>');
          });
    }
});
    }
   }
function llamarselected(pais, departamento, municipio, acteconomica){
getpaises(pais,'edit');
getdepartamentos(pais,'edit', departamento, acteconomica);
getmunicipio(departamento, 'edit', municipio);
}


   function editCompany(id){
    //Get data edit companies
    //alert('entro');
    $.ajax({
        url: "getCompanyid/"+btoa(id),
        method: "GET",
        success: function(response){
            llamarselected(response[0]['country'],response[0]['departament'],response[0]['municipio'], response[0]['acteconomica']);
            $.each(response[0], function(index, value) {
                if(index=='logo'){
                    $('#logoview').html("<img src='http://inetv4.test/assets/img/logo/"+value+"' alt='logo' width='150px'><input type='hidden' name='logoeditoriginal' id='logoeditoriginal'/>");
                    $('#logoeditoriginal').val(value);
                }else{
                    if(index=='phone_id'){
                        $('#phoneeditid').val(value);
                    }
                    if(index=='address_id'){
                        $('#addresseditid').val(value);
                    }
                    if(index=='tipoContribuyente'){
                        var opciones = ['GRA', 'MED', 'PEQU', 'OTR'];
                        var labels = ['GRANDE', 'MEDIANO', 'PEQUEÑO', 'OTRO'];
                        $('#tipocontribuyenteedit').empty();
                        var y = 0;
                        opciones.forEach(function(opcion) {
                            $('#tipocontribuyenteedit').append('<option value="' + opcion + '">' + labels[y] + '</option>');
                            if (value === opcion) {
                                $('#tipocontribuyenteedit option[value="' + opcion + '"]').prop('selected', true);
                            }
                            y++;
                        });
                    }
                    if(index=='tipoEstablecimiento'){
                        var opciones = ['01', '02', '04', '07', '20'];
                        var labels = ['SUCURSAL/AGENCIA', 'CASA MATRIZ', 'BODEGA', 'PREDIO y/o PATIO', 'OTRO'];
                        $('#tipoEstablecimientoedit').empty();
                        var y = 0;
                        opciones.forEach(function(opcion) {
                            $('#tipoEstablecimientoedit').append('<option value="' + opcion + '">' + labels[y] + '</option>');
                            if (value === opcion) {
                                $('#tipoEstablecimientoedit option[value="' + opcion + '"]').prop('selected', true);
                            }
                            y++;
                        });
                    }

                    $('#'+index+'edit').val(value);
                }
              });
              const bsOffcanvas = new bootstrap.Offcanvas('#offcanvasUpdateCompany').show();
        }
    });
   }

   function deleteCompany(id){
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

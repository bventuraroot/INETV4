/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){
    //Get companies avaibles
    var iduser = $('#iduser').val();
    $.ajax({
        url: "/company/getCompanybyuser/"+iduser,
        method: "GET",
        success: function(response){
            $('#company').append('<option value="0">Seleccione</option>');
            $.each(response, function(index, value) {
                $('#company').append('<option value="'+value.id+'">'+value.name.toUpperCase()+'</option>');
                $('#companyupdate').append('<option value="'+value.id+'">'+value.name.toUpperCase()+'</option>');
              });
        }
    });
    getpaises();
});

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

function getdepartamentos(pais, type="", selected){
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

   function llamarselected(pais, departamento, municipio){
    getpaises(pais,'edit');
    getdepartamentos(pais,'edit', departamento);
    getmunicipio(departamento, 'edit', municipio);
    }

   function editProvider(id){
    //Get data edit providers
    $.ajax({
        url: "getproviderid/"+btoa(id),
        method: "GET",
        success: function(response){
            //console.log(response);
            llamarselected(response[0]['paisid'],response[0]['departamentoid'],response[0]['municipioid']);
            $.each(response[0], function(index, value) {
                    $('#'+index+'update').val(value);
                    if(index=='companyid'){
                        $("#companyupdate option[value='"+ value  +"']").attr("selected", true);
                    }

              });
              $("#updateProviderModal").modal("show");
        }
    });
   }

   function deleteProvider(id){
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


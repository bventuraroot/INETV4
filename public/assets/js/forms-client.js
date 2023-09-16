/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){

    $("#tel1").inputmask("9999-9999");
    $("#tel2").inputmask("9999-9999");

    $("#tel1edit").inputmask("9999-9999");
    $("#tel2edit").inputmask("9999-9999");

    //Get companies avaibles
    $.ajax({
        url: "/company/getCompany",
        method: "GET",
        success: function(response){
            let companyselected = $('#companyselected').val();
            $.each(response, function(index, value) {
                $('#selectcompany').append('<option value="'+value.id+'">'+value.name+'</option>');
              });
            $("#selectcompany option[value="+ companyselected +"]").attr("selected",true);
        }
    });

    if($('#companyselected').val()==0){
        $("button.add-new").attr("disabled", true);
    }
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

   function typeperson(type){

if(type=='N'){
    $('#fields_natural').css('display','');
    $('#fields_juridico').css('display','none');
    $('#contribuyentelabel').css('display','');
    $('#siescontri').css('display','none');
}else{
    $('#contribuyentelabel').css('display','none');
    $('#siescontri').css('display','');
}
if(type=='J'){
    $('#fields_juridico').css('display','');
    $('#fields_natural').css('display','none');
}
   }
   function typepersonedit(type){

if(type=='N'){
    $('#contribuyentelabeledit').css('display','');
    $('#siescontriedit').css('display','none');
    validarchecked();
}else{
    $('#contribuyentelabeledit').css('display','none');
    $('#siescontriedit').css('display','');
}
   }

   function escontri(){
    if ($('#contribuyente').is(':checked') ) {
        $('#siescontri').css('display','');
    }else{
        $('#siescontri').css('display','none');
    }
   }

   function escontriedit(){
    if ($('#contribuyenteedit').is(':checked') ) {
        $('#siescontriedit').css('display','');
    }else{
        $('#siescontriedit').css('display','none');
    }
    validarchecked();
   }

   function validarchecked(){
    if($('#contribuyenteedit').is(':checked')){
        $('#contribuyenteeditvalor').val('1');
    }

   }
   function llamarselected(pais, departamento, municipio, acteconomica){
    getpaises(pais,'edit');
    getdepartamentos(pais,'edit', departamento, acteconomica);
    getmunicipio(departamento, 'edit', municipio);
    }

   function editClient(id){
    //Get data edit companies
    //alert('entro');
    $.ajax({
        url: "/client/getClientid/"+btoa(id),
        method: "GET",
        success: function(response){
            llamarselected(response[0]['country'],response[0]['departament'],response[0]['municipio'], response[0]['acteconomica']);
            $.each(response[0], function(index, value) {
                    if(index=='phone'){
                    $('#tel1edit').val(value);
                    }else if(index=='phone_fijo'){
                    $('#tel2edit').val(value);
                    }
                    if(index=='phone_id'){
                        $('#phoneeditid').val(value);
                    }
                    if(index=='address_id'){
                        $('#addresseditid').val(value);
                    }

                    if(index=='contribuyente'){
                        if(value=='1'){
                            $('#contribuyenteedit').prop('checked', true);
                            $('#contribuyentelabeledit').css('display','');
                            validarchecked();

                        }else if(value=='0'){
                            $('.contribuyenteedit').prop('checked', false);
                            $('#contribuyentelabeledit').css('display','');
                        }
                        escontriedit();
                        if($('#tpersonaedit').val()=='J'){
                            $('#contribuyentelabeledit').css('display','none');
                            $('#siescontriedit').css('display','');
                        }
                    }
                    if(index=='tpersona'){
                        var selectedN="";
                        var selectedJ="";
                        if(value=='J'){
                            selectedJ='selected';
                        }else if(value=='N'){
                            selectedN='selected';
                            $('#contribuyentelabeledit').css('display','');
                        }
                        $('#tpersonaedit').empty();
                        $('#tpersonaedit').append('<option value="N" '+selectedN+'>NATURAL</option>');
                        $('#tpersonaedit').append('<option value="J" '+selectedJ+'>JURIDICO</option>');
                    }

                    if(index=='tipoContribuyente'){
                        $("#tipocontribuyenteedit option[value='"+ value +"']").attr("selected",true);
                    }
                    $('#'+index+'edit').val(value);
              });
              const bsOffcanvas = new bootstrap.Offcanvas('#offcanvasUpdateClient').show();
        }
    });
   }

   function deleteClient(id){
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
                url: "/client/destroy/"+btoa(id),
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


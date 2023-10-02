/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){
});

   function paycredit(id){
    //Get pay credit
    $('#idsale').val(id);
    $.ajax({
        url: "getinfocredit/"+btoa(id),
        method: "GET",
        success: function(response){
            $('#pendingamount').html(response);
            $("#PayCreditsModal").modal("show");
        }
    });

   }


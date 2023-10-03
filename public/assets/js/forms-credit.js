/**
 * Form Picker
 */

'use strict';
$(document).ready(function (){

   $("#amountpay").on("change", function () {
    var amountpay = parseFloat($(this).val());
    var current = parseFloat($("#currentamount").val());
        if (current >= amountpay) {
            // El monto es mayor que el valor actual
            $("#savepay").prop("disabled", false);
        } else {
            // El monto es menor o igual al valor actual
            alert('El monto ingresado no puede ser menor o igual al valor actual.');
            // Puedes desactivar el botón si es necesario
            $("#savepay").prop("disabled", true);
        }
});
});

   function paycredit(id){
    //Get pay credit
    $('#idsale').val(id);
    $.ajax({
        url: "getinfocredit/"+btoa(id),
        method: "GET",
        success: function(response){
            $('#pendingamount').html(response);
            $('#currentamount').val(response);
            $("#PayCreditsModal").modal("show");
        }
    });

   }



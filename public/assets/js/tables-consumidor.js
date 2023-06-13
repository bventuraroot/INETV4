/**
 * DataTables Advanced (jquery)
 */

'use strict';

$(function () {

//Get companies avaibles
var iduser = $("#iduser").val();
$.ajax({
    url: "/company/getCompanybyuser/" + iduser,
    method: "GET",
    success: function (response) {
        //$("#company").append('<option value="0">Seleccione</option>');
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

    $("#first-filter").click(function(){
        $('#sendfilters').submit();
});
});

function impFAC(nombreDiv) {
    var contenido = document.getElementById(nombreDiv).innerHTML;
    var contenidoOriginal = document.body.innerHTML;
    document.body.innerHTML = contenido;
    window.print();
    location.reload(true);
}

/**
 *  Form Wizard
 */

"use strict";

$(function () {
    var valcorrdoc = $('#valcorr').val();
    var valdraftdoc = $('#valdraft').val();
    if(isNumeric(valcorrdoc) && valdraftdoc==true){
        var draft = draftdocument(valcorrdoc, valdraftdoc);
    }
    const select2 = $(".select2"),
        selectPicker = $(".selectpicker");

    // Bootstrap select
    if (selectPicker.length) {
        selectPicker.selectpicker();
    }

    // select2
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                placeholder: "Select value",
                dropdownParent: $this.parent(),
            });
        });
    }
    //Get companies avaibles
    var iduser = $("#iduser").val();
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

    //Get products avaibles
    $.ajax({
        url: "/product/getproductall",
        method: "GET",
        success: function (response) {
            $("#psearch").append('<option value="0">Seleccione</option>');
            $.each(response, function (index, value) {
                $("#psearch").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name.toUpperCase() +
                        "</option>"
                );
            });
        },
    });

    var selectdcompany = $(".select2company");

    if (selectdcompany.length) {
        var $this = selectdcompany;
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Seleccionar empresa",
            dropdownParent: $this.parent(),
        });
    }

    var selectdclient = $(".select2client");

    if (selectdclient.length) {
        var $this = selectdclient;
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Seleccionar cliente",
            dropdownParent: $this.parent(),
        });
    }

    var selectdpsearch = $(".select2psearch");

    if (selectdpsearch.length) {
        var $this = selectdpsearch;
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Seleccionar Producto",
            dropdownParent: $this.parent(),
        });
    }
});

function agregarp() {
    var clientid = $("#client").val();
    var corrid = $("#corr").val();
    var productid = $("#productid").val();
    var acuenta = $("#acuenta").val();
    var fpago = $("#fpago").val();
    var productname = $("#productname").val();
    var price = parseFloat($("#precio").val());
    var ivarete13 = parseFloat($("#ivarete13").val());
    var ivarete = parseFloat($("#ivarete").val());
    var type = $("#typesale").val();
    var cantidad = $("#cantidad").val();
    var productdescription = $("#productdescription").val();
    var pricegravada = 0;
    var priceexenta = 0;
    var pricenosujeta = 0;
    var sumas = parseFloat($("#sumas").val());
    var iva13 = parseFloat($("#13iva").val());
    var ivaretenido = parseFloat($("#ivaretenido").val());
    var ventasnosujetas = parseFloat($("#ventasnosujetas").val());
    var ventasexentas = parseFloat($("#ventasexentas").val());
    var ventatotal = parseFloat($("#ventatotal").val());
    var sumasl = 0;
    var ivaretenidol = 0;
    var iva13l = 0;
    var ventasnosujetasl = 0;
    var ventasexentasl = 0;
    var ventatotall = 0;
    if (type == "gravada") {
        pricegravada = parseFloat(price * cantidad);
    } else if (type == "exenta") {
        priceexenta = parseFloat(price * cantidad);
    } else if (type == "nosujeta") {
        pricenosujeta = parseFloat(price * cantidad);
    }
    var totaltemp = parseFloat(pricegravada + priceexenta + pricenosujeta);
    var iva13temp = parseFloat(totaltemp * 0.13);
    var totaltemptotal = parseFloat(pricegravada + priceexenta + pricenosujeta + ivarete13 + ivarete);
    //enviar a temp factura
    $.ajax({
        url: 'savefactemp/'+ corrid +'/'+ clientid+'/'+ productid +'/'+ cantidad +'/'+ price +'/'+ pricenosujeta +'/'+ priceexenta +'/'+ pricegravada +'/' + ivarete13 + '/' + ivarete + '/' + acuenta + '/' + fpago,
        method: "GET",
        success: function (response) {
            if (response.res == 1) {
                var row =
                    '<tr id="pro' +
                    response.idsaledetail +
                    '"><td>' +
                    cantidad +
                    "</td><td>" +
                    productname +
                    "</td><td>" +
                    price.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    pricenosujeta.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    priceexenta.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    pricegravada.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    '</td><td class="text-center">' +
                    totaltemp.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    '</td><td><button class="btn rounded-pill btn-icon btn-danger" type="button" onclick="eliminarpro(' +
                    response.idsaledetail +
                    ')"><span class="ti ti-trash"></span></button></td></tr>';
                $("#tblproduct tbody").append(row);
                sumasl = sumas + totaltemp;
                $("#sumasl").html(
                    sumasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#sumas").val(sumasl);
                iva13l = parseFloat(iva13 + iva13temp);
                $("#13ival").html(
                    iva13l.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#13iva").val(iva13l);
                ivaretenidol = ivaretenido + ivarete;
                $("#ivaretenidol").html(
                    ivaretenidol.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ivaretenido").val(ivaretenidol);
                ventasnosujetasl = ventasnosujetas + pricenosujeta;
                $("#ventasnosujetasl").html(
                    ventasnosujetasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventasnosujetas").val(ventasnosujetasl);
                ventasexentasl = ventasexentas + priceexenta;
                $("#ventasexentasl").html(
                    ventasexentasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventasexentas").val(ventasexentasl);
                ventatotall = ventatotal + totaltemptotal;
                $("#ventatotall").html(
                    ventatotall.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventatotal").val(ventatotall);
            } else if (response == 0) {
            }
        },
    });
}

function searchproduct(idpro) {
    //Get products by id avaibles
    var typecontricompany = $("#typecontribuyente").val();
    var typecontriclient = $("#typecontribuyenteclient").val();
    var retencion;
    $.ajax({
        url: "/product/getproductid/" + btoa(idpro),
        method: "GET",
        success: function (response) {
            $.each(response, function (index, value) {
                $("#precio").val(value.price);
                $("#productname").val(value.name);
                $("#productid").val(value.id);
                $("#productdescription").val(value.description);
                $("#productunitario").val(value.id);
                //validar si es gran contribuyente el cliente vs la empresa
                if (typecontricompany == "GRA") {
                    if (typecontriclient == "GRA") {
                        retencion = 0.00;
                    } else if (
                        typecontriclient == "MED" ||
                        typecontriclient == "PEQ" ||
                        typecontriclient == "OTR"
                    ) {
                        retencion = 0.01;
                    }
                }
                $("#ivarete").val(
                    parseFloat(value.price * retencion).toFixed(2)
                );
                $("#ivarete13").val(parseFloat(value.price * 0.13).toFixed(2));
            });
        },
    });
}

function eliminarpro(id) {

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
                url: "destroysaledetail/"+btoa(id),
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
                                    $("#pro" + id).remove();
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

function aviablenext(idcompany) {
    $("#step1").prop("disabled", false);
}

function getclientbycompanyurl(idcompany){
    $.ajax({
        url: "/client/getclientbycompany/" + btoa(idcompany),
        method: "GET",
        success: function (response) {
            $("#client").append('<option value="0">Seleccione</option>');
            $.each(response, function (index, value) {
                $("#client").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.firstname.toUpperCase() +
                        " " +
                        value.secondname.toUpperCase() +
                        "</option>"
                );
            });
        },
    });

    //traer el tipo de contribuyente
    $.ajax({
        url: "/company/gettypecontri/" + btoa(idcompany),
        method: "GET",
        success: function (response) {
            $("#typecontribuyente").val(response.tipoContribuyente);
        },
    });
}

function valtrypecontri(idcliente) {
    //traer el tipo de contribuyente
    $.ajax({
        url: "/client/gettypecontri/" + btoa(idcliente),
        method: "GET",
        success: function (response) {
            $("#typecontribuyenteclient").val(response.tipoContribuyente);
        },
    });
}
function createcorrsale() {
    //crear correlativo temp de factura
    let salida = false;
    var valicorr = $("#corr").val();
    if (valicorr == "") {
        var idcompany = $("#company").val();
        var iduser = $("#iduser").val();
        var typedocument = $("#typedocument").val();

        $.ajax({
            url: "newcorrsale/" + idcompany + "/" + iduser + "/" + typedocument,
            method: "GET",
            async: false,
            success: function (response) {
                if ($.isNumeric(response)) {
                    //recargar la pagina para retomar si una factura quedo en modo borrador
                    //$("#corr").val(response);
                    //salida = true;
                    window.location.href = "create?corr=" + response + "&draft=true";
                } else {
                    Swal.fire("Hay un problema, favor verificar");
                }
            },
        });
    } else {
        salida = true;
    }

    return salida;
}

function valfpago(fpago) {
    //alert(fpago);
}

function draftdocument(corr, draft){
    if(draft){
        $.ajax({
            url: "getdatadocbycorr/" + btoa(corr),
            method: "GET",
            async: false,
            success: function (response) {
                $.each(response, function (index, value) {
                    //valor de company
                    $('#idcompany').val(value.idcompany);
                    $('#typeContribuyente').val(value.typeContribuyente);
                    $('#idcompany').val(value.idcompany);
                    $('#idcompany').val(value.idcompany);
                    $('#idcompany').val(value.idcompany);
                    $('#idcompany').val(value.idcompany);
                    $('#idcompany').val(value.idcompany);
                });
            },
            failure: function (response) {
                Swal.fire("Hay un problema: " + response.responseText);
            },
            error: function (response) {
                Swal.fire("Hay un problema: " + response.responseText);
            }
        });
    }
}

(function () {
    // Icons Wizard
    // --------------------------------------------------------------------
    const wizardIcons = document.querySelector(".wizard-icons-example");

    if (typeof wizardIcons !== undefined && wizardIcons !== null) {
        const wizardIconsBtnNextList = [].slice.call(
                wizardIcons.querySelectorAll(".btn-next")
            ),
            wizardIconsBtnPrevList = [].slice.call(
                wizardIcons.querySelectorAll(".btn-prev")
            ),
            wizardIconsBtnSubmit = wizardIcons.querySelector(".btn-submit");

        const iconsStepper = new Stepper(wizardIcons, {
            linear: false,
        });
        if (wizardIconsBtnNextList) {
            wizardIconsBtnNextList.forEach((wizardIconsBtnNext) => {
                wizardIconsBtnNext.addEventListener("click", (event) => {
                    var id = $(wizardIconsBtnNextList).attr("id");
                    switch (id) {
                        case "step1":
                            var create = createcorrsale();
                            if (create) {
                                iconsStepper.next();
                            }
                            break;
                        case "step2":
                            break;
                        case "step3":
                            break;
                    }
                });
            });
        }
        if (wizardIconsBtnPrevList) {
            wizardIconsBtnPrevList.forEach((wizardIconsBtnPrev) => {
                wizardIconsBtnPrev.addEventListener("click", (event) => {
                    iconsStepper.previous();
                });
            });
        }
        if (wizardIconsBtnSubmit) {
            wizardIconsBtnSubmit.addEventListener("click", (event) => {
                alert("Submitted..!!");
            });
        }
    }
})();

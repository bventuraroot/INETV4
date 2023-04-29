$( document ).ready(function() {
    var corr = $('#corr').val();
        var tbl = getinfodoc(corr);
        //var divToPrint=document.getElementById('imprimirdoc');
        //var newWin=window.open('','Print-Window');
        //newWin.document.open();
        //newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        //newWin.document.close();
        //setTimeout(function(){newWin.close();},10);
});

function getinfodoc(corr){
    //var corr = $('#valcorr').val();
    let salida = false;
    $.ajax({
        url: "/sale/getdatadocbycorr2/" + btoa(corr),
        method: "GET",
        async: false,
        success: function (response) {
            salida = true;
            $('#logodocfinal').attr('src', '../../assets/img/logo/' + response[0].logo);
            $('#addressdcfinal').empty();
            $('#addressdcfinal').html('' + response[0].country_name.toUpperCase() + ', ' + response[0].department_name + ', ' + response[0].municipality_name + '</br>' + response[0].address);
            $('#phonedocfinal').empty();
            $('#phonedocfinal').html('' + ((CheckNullUndefined(response[0].phone_fijo)==true) ? '' : 'PBX: +503 ' + response[0].phone_fijo) + ' ' + ((CheckNullUndefined(response[0].phone)==true) ? '' : 'Móvil: +503 ' + response[0].phone));
            $('#emaildocfinal').empty();
            $('#emaildocfinal').html(response[0].email);
            $('#name_client').empty();
            $('#name_client').html(response[0].client_firstname + ' ' + response[0].client_secondname);
            $('#date_doc').empty();
            var dateformat = response[0].date.split('-');
            $('#date_doc').html(dateformat[2] + '/' + dateformat[1] + '/' + dateformat[0]);
            $('#address_doc').empty();
            $('#address_doc').html(response[0].address);
            $('#duinit').empty();
            $('#duinit').html(response[0].nit);
            $('#municipio_name').empty();
            $('#municipio_name').html(response[0].municipality_name);
            $('#giro_name').empty();
            $('#giro_name').html(response[0].giro);
            $('#name_type_documents_details').empty();
            $('#name_type_documents_details').html(response[0].document_name);
            $('#corr_details').empty();
            $('#corr_details').html('USD' + response[0].corr + '00000');
            $('#NCR_details').empty();
            $('#NCR_details').html('NCR: ' + response[0].NCR);
            $('#NIT_details').empty();
            $('#NIT_details').html('NIT: ' + response[0].NIT);
            $('#departamento_name').empty();
            $('#departamento_name').html(response[0].department_name);
            $('#forma_pago_name').empty();
            var forma_name;
            switch(response[0].waytopay){
                case "1":
                    forma_name='CONTADO';
                    break;
                case "2":
                    forma_name='CREDITO';
                    break;
                case "3":
                    forma_name='OTRO';
                    break;
            }
            $('#forma_pago_name').html(forma_name);
            $('#acuenta_de').empty();
            $('#acuenta_de').html(response[0].acuenta);
            var tabledetails = agregarfacdetails(corr);
            var letters = numeroALetras(response[0].totalamount, {
                plural: 'DÓLARES ESTAUNIDENSES',
                singular: 'DÓLAR ESTAUNIDENSE',
                centPlural: 'CENTAVOS',
                centSingular: 'CENTAVO'
              });
              $('#numtoletters').html('SON: <b>' + letters + '</b>');
              $('#imprimirdoc').printThis({
                printDelay: 10
              });
              //setTimeout(function(){window.close();},10);
            //var div_copy = $('#tblproduct').clone();
                // div_copy.removeClass();
                // div_copy.addClass('table_details');
                // div_copy.find('.fadeIn').removeClass();
                // div_copy.children().val("");
                // div_copy.find('.quitar_documents').remove();
                // div_copy.find('.bg-secondary').removeClass();
                // div_copy.find('.text-white').removeClass();
                // div_copy.find('thead').addClass('head_details');
                // div_copy.find('tfoot').addClass('tfoot_details');
                // div_copy.find('th').addClass('th_details');
                // div_copy.find('td').addClass('td_details');
                // $('#details_products_documents').empty();
                // $('#details_products_documents').append(div_copy);
                //$(".quitar_documents").empty();
                //$("#quitar_documents").remove();
        },
    });
    return salida;
}

function savepdf(){
    var element = document.getElementById('imprimirdoc');
    html2pdf(element);
}

function agregarfacdetails(corr) {
    $('#tblproduct tbody').find('tr').remove();
    $.ajax({
        url: "/sale/getdetailsdoc/" + btoa(corr),
        method: "GET",
        async: false,
        success: function (response) {

            let totaltemptotal = 0;
            let totalsumas = 0;
            let ivarete13total = 0;
            let ivaretetotal = 0;
            let nosujetatotal = 0;
            let exempttotal = 0;
            let pricesaletotal = 0;
            $.each(response, function (index, value) {
                var totaltemp = (parseFloat(value.nosujeta) + parseFloat(value.exempt) + parseFloat(value.pricesale));
                totalsumas += totaltemp;
                ivarete13total += parseFloat(value.detained13);
                ivaretetotal += parseFloat(value.detained);
                nosujetatotal += parseFloat(value.nosujeta);
                exempttotal += parseFloat(value.exempt);
                pricesaletotal += parseFloat(value.pricesale);
                totaltemptotal += (parseFloat(value.nosujeta) + parseFloat(value.exempt) + parseFloat(value.pricesale))
                + (parseFloat(value.detained13) + parseFloat(value.detained));
                var sumasl = 0;
                var iva13l = 0;
                var ivaretenidol = 0;
                var ventasnosujetasl = 0;
                var ventasexentasl = 0;
                var ventatotall = 0;
                var row =
                    '<tr id="pro' +
                    value.id +
                    '"><td>' +
                    value.amountp +
                    "</td><td>" +
                    value.product_name +
                    "</td><td>" +
                    parseFloat(value.priceunit).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    parseFloat(value.nosujeta).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    parseFloat(value.exempt).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    "</td><td>" +
                    parseFloat(value.pricesale).toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    '</td><td class="text-center">' +
                    totaltemp.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    }) +
                    '</tr>';
                $("#tblproduct tbody").append(row);
                sumasl = totalsumas;
                $("#sumasl").html(
                    sumasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#sumas").val(sumasl);
                iva13l = ivarete13total;
                $("#13ival").html(
                    iva13l.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#13iva").val(iva13l);
                ivaretenidol =  ivaretetotal;
                $("#ivaretenidol").html(
                    ivaretenidol.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ivaretenido").val(ivaretenidol);
                ventasnosujetasl =  nosujetatotal;
                $("#ventasnosujetasl").html(
                    ventasnosujetasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventasnosujetas").val(ventasnosujetasl);
                ventasexentasl = exempttotal;
                $("#ventasexentasl").html(
                    ventasexentasl.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventasexentas").val(ventasexentasl);
                ventatotall = totaltemptotal;
                $("#ventatotall").html(
                    ventatotall.toLocaleString("en-US", {
                        style: "currency",
                        currency: "USD",
                    })
                );
                $("#ventatotal").val(ventatotall);
            });
        },
        failure: function (response) {
            Swal.fire("Hay un problema: " + response.responseText);
        },
        error: function (response) {
            Swal.fire("Hay un problema: " + response.responseText);
        },
    });
}

function CheckNullUndefined(value) {
    return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
  }

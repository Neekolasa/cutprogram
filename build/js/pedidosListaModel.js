$(document).ready(function(){

    //sessionStorage.setItem('userLogged',userLogged);
    var userLogged = localStorage.getItem('userLogged');

    // Ejecutar las funciones al principio para que se ejecuten inmediatamente
    ejecutarFunciones();

    setInterval(ejecutarFunciones, 60000);
    if (!userLogged) {
        $('#tableCompletados').addClass('blur');
        $('#modal_login').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modal_login').modal('show');
    }
    else{
         $("#username_logged").text(userLogged);
    }


    $("#ingresar_button").on('click', function(event) {
        event.preventDefault();
        var numEmpleado = $("#badge").val()
        numEmpleado = numEmpleado.replace("C8","");
        numEmpleado = numEmpleado.replace("c8","");

        $.ajax({
            url: 'cont/pedidosController.php',
            data: {request: 'validateAccess',
                      badge : numEmpleado
        },
        })
        .done(function(info) {
            var Data = JSON.parse(info);
            if (Data['response']=='success') {
                new PNotify({
                    title: 'Exito',
                    text: 'Usuario valido',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                localStorage.setItem('userLogged',numEmpleado);
                $("#username_logged").text(numEmpleado);
                $('#modal_login').modal('hide');
                $('#modal_login').on('hidden.bs.modal', function () {
                    $('#tableCompletados').removeClass('blur');
                });
            }
            else{
                new PNotify({
                    title: 'Error',
                    text: 'Usuario no valido',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
        })
        .fail(function() {
            console.log("error");
        })

    
        
    });
	//getListaPedidos();
    //getCompletePedidos();
    $("#exitTicketButton").on('click', function(event) {
        event.preventDefault();
        $("#exitTicketModal").modal('show');
    });

    $("#ticketNumber").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            $("#sendTicketExit").click();
        }
                /* Act on the event */
    });

    $("#sendTicketExit").on('click', function(event) {
        event.preventDefault();
        var ticketNumber = $("#ticketNumber").val();
        $.ajax({
            url: 'cont/pedidosController.php',
            data: {request: 'exitTicket',
                      folio: ticketNumber,
                      userLogged: localStorage.getItem('userLogged')

        },
        })
        .done(function(info) {
            var Data = JSON.parse(info);
            if (Data['response']=='success') {
                new PNotify({
                    title: 'Exito',
                    text: 'Pedido completado',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#ticketNumber").val("");
            }
        })
        .fail(function() {
            console.log("error");
        }) 
    });

    $('#exitTicketModal').on('hidden.bs.modal', function () {
      getListaPedidos();
    });

    $("#responsableBadge").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            $("#sendResponsable").click();
        }
                /* Act on the event */
    });
    $("#sendResponsable").on('click',function(){
        var folioNum = $("#folioNumber").val();
        var numResp = $("#responsableBadge").val();
        numResp = numResp.trim();
        if (numResp == "") {
            new PNotify({
                title: 'Error',
                text: 'Debe ingresar un numero de empleado',
                type: 'error',
                styling: 'bootstrap3'
            });
        }
        else{
            numResp = numResp.replace("C8","");
            numResp = numResp.replace("c8","");

            $.ajax({
                url: 'cont/pedidosController.php',
                data: {request: 'setResponsable',
                         folioNum : folioNum,
                         numResp : numResp
            },
            })
            .done(function(info) {
                var Data = JSON.parse(info);
                if (Data['response']=='success') {
                    $("#responsableModal").modal('hide');
                    printTicket(folioNum);

                    $("#responsableBadge").val("");
                }

            })
            .fail(function() {
                console.log("error");
            });
        }
        
        
    });

    $("#printCardButton").on('click',function(event){
        event.preventDefault();
        $("#modalPrint").modal('show');
    });

$("#printCardAction").on('click', function(event){
    event.preventDefault();
    var leadCode = $("#leadCode").val();
    leadCode = leadCode.trim();
    if (leadCode === "") {
        new PNotify({
            title: 'Error',
            text: 'Ingrese un Leadcode',
            type: 'error',
            styling: 'bootstrap3'
        });
    } else {
        var radioLinea = $(".radios:checked").val();
        $.ajax({
            url: 'cont/pedidosController.php',
            type: 'GET',
            data: {
                request : 'leadCodeInfo',
                datos: leadCode,
                radioLinea: radioLinea
            },
            
        })
        .done(function(info) {
            var Data = JSON.parse(info);
            console.log(Data[0]);

            var cardContent = generateCardContent(Data,radioLinea);
             setTimeout(function() {
                    sendToPrinter(cardContent);
                },2000)
            

            
            
        })
        .fail(function() {
            console.log("error");
        });
    }
});

});

function getCompletePedidos(){
    $.ajax({
        url: 'cont/pedidosController.php',
        data: {request: 'ticketsCompleted'},
    })
    .done(function(info) {
        var Data = JSON.parse(info);

       var tabla_au = $('#tableCompletados').DataTable({
                dom: 'frtlip',
                destroy: true,
                responsive: true,
                paging: true,
                order: [
                    [4, 'desc'], // Ri en ascendiente
                    //[3, 'asc'], // Rack en ascendiente
                    //[8, 'asc']  // Piso en ascendiente
                ],
                language: {
                    url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
                },
                columns: [
                    { data: "ID", className: "dt-center" },
                    { data: "Folio", className: "dt-center" },
                    { data: "Fecha_pedido", className: "dt-center" },
                    { data: "Badge_request", className: "dt-center" },
                    { data: "Fecha_atendido", className: "dt-center" },
                    { data: "Badge_atendido", className: "dt-center" },
                    { data: "responsable", className: "dt-center" },
                    { data: "Estatus", className: "dt-center" },
                    { data: "Actions", className: "dt-center" }
                ]
            });

            tabla_au.clear().rows.add(Data['info']).draw();

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function getListaPedidos(){
	$.ajax({
		url: 'cont/pedidosController.php',
		data: {request: 'getListaPedidos'},
	})
	.done(function(info) {
		var Data = JSON.parse(info);

		if (Data['response']=='success') {
			new PNotify({
		        title: 'Exito',
		        text: 'Se ha obtenido la lista de pedidos pendientes',
		        type: 'success',
		        styling: 'bootstrap3'
		    });

		    var tabla_au = $('#tablePedidos').DataTable({
                dom: 'frtli',
                destroy: true,
                responsive: true,
                paging: false,
                order: [
                    [2, 'asc'], // Ri en ascendiente
                    //[3, 'asc'], // Rack en ascendiente
                    //[8, 'asc']  // Piso en ascendiente
                ],
                language: {
                    url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
                },
                columns: [
                    { data: "ID", className: "dt-center" },
                    { data: "Folio", className: "dt-center" },
                    { data: "Fecha_pedido", className: "dt-center" },
                    { data: "Badge_request", className: "dt-center" },
                    { data: "Fecha_atendido", className: "dt-center" },
                    { data: "Badge_atendido", className: "dt-center" },
                    { data: "responsable", className: "dt-center" },
                    { data: "Estatus", className: "dt-center" },
                    { data: "Actions", className: "dt-center" }
                ]
            });

            tabla_au.clear().rows.add(Data['info']).draw();

		}
	});
	
}
function modalResponsable(folio){
    $("#responsableModal").modal('show');
    $("#folioNumber").val(folio);
    
}

function printTicket(folio) {
    $.ajax({
        url: 'cont/pedidosController.php',
        type: 'GET',
        data: { request: 'printTicket', folio: folio },
        success: function(response) {
            var Data = JSON.parse(response);

            if (Data['response'] == 'success') {

                var ticketContent = generateTicketContent(Data['info'], folio);
                
                setTimeout(function() {
                	sendToPrinter(ticketContent);
                },2000)

                getListaPedidos();
                
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'No se pudo obtener la información del ticket',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
        },
        error: function(xhr, status, error) {
            new PNotify({
                title: 'Error',
                text: 'Error en la solicitud AJAX: ' + error,
                type: 'error',
                styling: 'bootstrap3'
            });
            console.error("Error en la solicitud AJAX: ", error);
        }
    });
}

function getPedido(folio){
    $.ajax({
        url: 'cont/pedidosController.php',
        data: {request: 'getPedido',
                  folio:folio
    },
    })
    .done(function(info) {
        var Data = JSON.parse(info);
        if (Data['response']=='success') {
            new PNotify({
                title: 'Exito',
                text: 'Pedido en proceso',
                type: 'success',
                styling: 'bootstrap3'
            });
        }
    })
    .fail(function() {
        console.log("error");
    });
    
}

function generateTicketContent(data, folio) {
    var currentDateTime = new Date().toLocaleString();
    var ticketHtml = "<style>@font-face {font-family: '3of9Barcode'; src: url('3of9Barcode.TTF') format('truetype');} .barcode {font-family: '3of9Barcode', Arial, sans-serif; font-size: 36px;} body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; }</style>";
    ticketHtml += "<div style='margin: 10px auto; width: 95%; font-family: Helvetica Neue, Roboto, Arial, Droid Sans, sans-serif;'>";
    ticketHtml += "<table style='width: 100%; font-size: 12px;'><tr><td style='text-align: left;'><b>Ticket: </b>" + (data.length > 0 && data[0].Ticket ? data[0].Ticket : '') + "</td><td style='text-align: justify;'><b>Folio: </b>" + folio + "</td><td style='text-align: right;'><b>Fecha: </b>" + data[0].fecha_creacion + "</td></tr></table>";
    ticketHtml += '<table style="width:100%;font-size: 10px; border-collapse:collapse; border:1px solid #000;">';
    ticketHtml += '<tr><th>Leadcode</th><th>Color</th><th>Locación</th><th>Piso</th><th>Board</th><th>Atados</th><th>Faltante</th></tr>';

    data.forEach(function(item) {
        ticketHtml += '<tr>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.leadcode || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.color || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.locacion || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.piso || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.board || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000;">' + (item.Atados || '') + '</td>';
        ticketHtml += '<td style="border-collapse:collapse; border:1px solid #000; width:10px;"></td>';
        ticketHtml += '</tr>';
    });

    ticketHtml += '</table>';
    ticketHtml += "<span style='font-size:12px; text-align:center; width:100%; display:block;'><b>Solicitante: </b>" + (data[0].badge_request || '') + "</span>";
    ticketHtml += "<span style='font-size:12px; text-align:center; width:100%; display:block;'><b>Surtidor: </b>" + (data[0].responsable || '') + "</span>";
    
    ticketHtml += "<span style='font-size:12px; text-align:center; width:100%; display:block;'><b>Fecha de impresión: </b>" + (currentDateTime) + "</span>";

    // Generar el código de barras usando la fuente 3 of 9
    ticketHtml += '<p style="text-align:center;" class="barcode">*' + folio + '*</p>';

    ticketHtml += "<b style='font-size:12px; text-align:center; width:100%; display:block;'>Desarrollado por: Ing Joel Andrade Enriquez</b>";
    ticketHtml += "</div>";
    return ticketHtml;
}
function generateCardContent(Data, linea) {
        var ticketHtml = "<style type='text/css'>";
    ticketHtml +="@font-face {font-family: '3of9Barcode'; src: url('3of9Barcode.TTF') format('truetype');}";
    ticketHtml +=".barcode {font-family: '3of9Barcode'}";
    ticketHtml +=".tg  {border-collapse:collapse;border-spacing:0;}";
    ticketHtml +=".tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;";
    ticketHtml +="  overflow:hidden;padding: 3px 0px;word-break:normal;}";
    ticketHtml +=".tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;";
    ticketHtml +="  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}";
    ticketHtml +=".tg .tg-1wig{font-weight:bold;text-align:left;vertical-align:top}";
    ticketHtml +=".tg .tg-baqh{text-align:center;vertical-align:top}";
    ticketHtml +=".tg .tg-iks7{background-color:#ffffff;border-color:#000000;text-align:left;vertical-align:top}";
    ticketHtml +=".tg .tg-jbyd{background-color:#ffffff;border-color:#000000;text-align:center;vertical-align:top}";
    ticketHtml +=".tg .tg-0lax{text-align:left;vertical-align:top}";
    ticketHtml +=".tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}";
    ticketHtml +=".tg .tg-ir4y{font-size:12px;font-weight:bold;text-align:center;vertical-align:top}";
    ticketHtml +=".tg .tg-akbm{font-weight:bold;text-align:left;text-decoration:underline;vertical-align:top}";
    ticketHtml +=".tg .tg-qnqf{background-color:#c0c0c0;font-size:22px;font-weight:bold;text-align:center;vertical-align:top}";
    ticketHtml +=".tg .tg-t2cw{font-weight:bold;text-align:center;text-decoration:underline;vertical-align:top}";
    ticketHtml +=".no-border {border: none;}";
    ticketHtml +=".image{width: 40px; padding: 0 15px;}";
    ticketHtml +="</style>";
    ticketHtml +="<table class='tg'><thead>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <th class='tg-baqh' colspan='5' style='border-bottom: none; border-right-width: 2px;border-left-width: 2px;border-left-width: 2px;'><span style=' padding: 0; padding-top: 10px; font-weight:bold'>TARJETA VIAJERA</span><br><span style='font-weight:bold'>ORIGEN CORTE CA </span></th>";
    ticketHtml +="  </tr></thead>";
    ticketHtml +="<tbody>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-jbyd no-border' colspan='1' style='border-right: none;border-top: none;font-weight:bold;border-left-width: 2px;'>RACK</td>";
    ticketHtml +="    <td class='tg-jbyd no-border' colspan='3' style='border: none !important;font-weight:bold'>NIVEL</td>";
    ticketHtml +="    <td class='tg-jbyd no-border' colspan='1' style='border-left: none; border-top: none; border-right-width: 2px;font-weight:bold'>RIEL</td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-amwm' colspan='1' style='border-right: none; border-bottom-width: 2px; text-align: center; font-size: 18px;border-left-width: 2px;'>"+Data[0].Rack+"</td>";
    ticketHtml +="    <td class='tg-amwm' colspan='2' style='border-right: none; border-left: none; border-bottom-width: 2px; font-size: 18px;'>"+Data[0].Nivel+"</td>";
    ticketHtml +="    <td class='tg-amwm' colspan='2' style='border-left: none; border-bottom-width: 2px; border-right-width: 2px; text-align: center; font-size: 18px;'>"+Data[0].Riel+"</td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-amwm' style='border-right: none; border-bottom: none;border-left-width: 2px;'>LC:</td>";
       
    ticketHtml +="    <td class='tg-1wig' colspan='3' style='border-left: none; border-right: none; border-bottom: none; text-align: center; font-size: 18px;'>"+Data[0].Leadcode+"</td>";
    ticketHtml +="      <td class='tg-0lax' style='border-left: none; border-bottom: none; border-top: none; border-right-width: 2px;'></td>";
     
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-baqh' style='border-left-width: 2px; font-size: 18px; border-top: none;border-bottom: none; border-right-width: 2px; ' colspan='5'><p class='barcode' style='font-size: 25px !important;'>*"+Data[0].Leadcode+"*</p></td>";
     
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-0lax' style='border-right: none; border-bottom: none; border-top: none;border-left-width: 2px;'></td>";
    ticketHtml +="    <td class='tg-ir4y' style='border-right: none; border-left: none; border-bottom: none; border-top: none; font-size: 10px;'>CIRC</td>";
    ticketHtml +="    <td class='tg-amwm' style='border-right: none; border-left: none; border-bottom: none; border-top: none;font-size: 12px;'>"+Data[0].Wire+"</td>";
    ticketHtml +="    <td class='tg-0lax' style='border-right: none; border-left: none; border-bottom: none; border-top: none; '></td>";
    ticketHtml +="    <td class='tg-0lax' style='border-left: none; border-bottom: none; border-top: none; border-right-width: 2px;'></td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
        

    ticketHtml +="    <td class='tg-akbm' colspan='5' style='border-top: none; border-left-width: 2px;border-bottom-width: 2px; border-right-width: 2px; text-align: center;'>"+Data[0].Mspec_Color1+"</td>";

    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
        
    if (linea=="GM") {
        ticketHtml +="    <td class='tg-baqh' colspan='5' style='align-content: center; border-right-width: 2px; border-left-width: 2px; border-bottom: none; border-top: none;text-align: center;'><img class='image' style='text-align: center;' src='gm_logo.jpg'><img class='image' style='text-align: center;' src='gm_logo.jpg'><img class='image' src='gm_logo.jpg'></td>";
    }
    else if (linea=="Honda") {
          ticketHtml +="    <td class='tg-baqh' colspan='5' style='align-content: center; border-right-width: 2px; border-left-width: 2px; border-bottom: none; border-top: none;text-align: center;'><img class='image' style='text-align: center;' src='honda_logo.png'><img class='image' style='text-align: center;' src='honda_logo.png'><img class='image' src='honda_logo.png'></td>";
    
    }
    else{
          ticketHtml +="    <td class='tg-baqh' colspan='5' style='align-content: center; border-right-width: 2px; border-left-width: 2px; border-bottom: none; border-top: none;text-align: center;'><img class='image' style='text-align: center;' src='stellantis_logo.jpg'><img class='image' style='text-align: center;' src='stellantis_logo.jpg'><img class='image' src='stellantis_logo.jpg'></td>";
    
    }
    
       

    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-amwm' colspan='5' style='border-bottom: none; border-top: none; border-left-width: 2px; border-right-width: 2px; text-align: center;'>"+Data[0].Board+"</td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";

    ticketHtml +="    <td class='tg-baqh' colspan='5' style='border-bottom: none; border-top: none; border-left-width: 2px; border-right-width: 2px; text-align: center;'>KIT/EST</td>";

    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-0lax' style='border-right: none; border-bottom: none; border-top: none;border-left-width: 2px;'></td>";
    ticketHtml +="    <td class='tg-amwm' colspan='2'>"+Data[0].Estacion+"</td>";
    ticketHtml +="    <td class='tg-0lax' colspan='2' style='border-left: none; border-bottom: none; border-top: none; border-right-width: 2px;'></td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-qnqf' style='border-right-width: 2px; border-left-width: 2px;' colspan='5'>DIRECTO</td>";
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-amwm' style='border-right: none; border-bottom: none;border-left-width: 2px;'>MAQ:</td>";
    ticketHtml +="    <td class='tg-0lax' style='border-left: none; border-right: none; border-bottom: none;'></td>";
    ticketHtml +="    <td class='tg-baqh' style='border-left: none; border-right: none; border-bottom: none;'>LONG1:</td>";
    ticketHtml +="    <td class='tg-baqh' style='border-right: none; border-left: none; border-bottom: none;'>"+Data[0].Lnth+"</td>";
    ticketHtml +="    <td class='tg-t2cw' rowspan='2' style='border-left: none; border-right-width: 2px; border-bottom-width: 2px;transform: rotate(270deg); font-size: 10px;'>"+Data[0].formatted_date+"</td>";
        
    ticketHtml +="  </tr>";
    ticketHtml +="  <tr>";
    ticketHtml +="    <td class='tg-akbm' style='border-top: none; border-right: none; border-bottom-width: 2px; border-left-width: 2px;'>ATADOS:</td>";
    ticketHtml +="    <td class='tg-akbm' style='border-right: none; border-top: none; border-left: none; border-bottom-width: 2px;'>2</td>";
    ticketHtml +="    <td class='tg-t2cw' style='border-right: none; border-top: none; border-left: none; border-bottom-width: 2px;'>1</td>";
    ticketHtml +="    <td class='tg-0lax' style='border-right: none; border-top: none; border-left: none; border-bottom-width: 2px;'></td>";
    ticketHtml +="  </tr>";
    ticketHtml +="</tbody></table>";

    return ticketHtml;
}

function sendToPrinter(content) {
    var printWindow = window.open('', '_blank', 'width=600,height=1000');

    printWindow.document.open();
    printWindow.document.write('<html><head><title>Ticket</title>');
    // Incluir la hoja de estilo para la fuente aquí también
    printWindow.document.write('<style>@font-face {font-family: "3of9Barcode"; src: url("src/barcode.TTF") format("truetype");} .barcode {font-family: "3of9Barcode", Arial, sans-serif; font-size: 18px;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    // Agregar un retraso para asegurar que los recursos se carguen completamente antes de imprimir
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.print();
            // Verificar si el navegador soporta onafterprint
            if (typeof printWindow.onafterprint === 'function') {
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            } else {
                // Fallback para navegadores que no soportan onafterprint
                printWindow.close();
            }
        }, 1000); // Retraso de 1 segundo para asegurar la carga completa
    };
}

function generateCode(text, isQR) {
    if (isQR) {
        // Generar un elemento de imagen con un ID único para el código QR
        var qrElementId = 'qr-' + Math.random().toString(36).substr(2, 9);
        var qrImgTag = '<img id="' + qrElementId + '" />';
        // Agregar el elemento de imagen al documento
        $('body').append(qrImgTag);

        // Generar el código QR utilizando QRCode.js
        new QRCode(document.getElementById(qrElementId), {
            text: text,
            width: 100,
            height: 100
        });

        // Obtener el código QR como una cadena Base64
        var base64String = document.getElementById(qrElementId).src;
        // Remover el elemento de imagen del documento
        $('#' + qrElementId).remove();

        return '<img src="' + base64String + '" alt="QR Code" style="width: 170px; height: 90px;" />';
    } else {
        // Generar un elemento de imagen con un ID único para el código de barras
        var barcodeElementId = 'barcode-' + Math.random().toString(36).substr(2, 9);
        var barcodeImgTag = '<img id="' + barcodeElementId + '" />';
        // Agregar el elemento de imagen al documento
        $('body').append(barcodeImgTag);

        // Generar el código de barras utilizando JsBarcode
        JsBarcode('#' + barcodeElementId, text);

        // Obtener el código de barras como una cadena Base64
        var base64String = document.getElementById(barcodeElementId).src;
        // Remover el elemento de imagen del documento
        $('#' + barcodeElementId).remove();

        return '<img src="' + base64String + '" alt="Barcode" style="width: 160px; height: 80px;" />';
    }
}
function ejecutarFunciones() {
    getListaPedidos();
    getCompletePedidos();
}
function closeSession(){
    localStorage.removeItem('userLogged');
    window.location.reload();
}

$(document).ready(function(){
    var leadCodes = [];
    getTempData();
    $("#num_empleado").focus();
    $("#leadcodeButton").prop("disabled", true);
    $("#leadcode_number").prop("disabled", true);
    var lastInputTime = 0;
    var typingDelay = 50;
    scan = false;
    
    $("#num_empleado").on('input',function(e){
        var currentTime = new Date().getTime();
        if (currentTime - lastInputTime < typingDelay) {
            scan = true;

        } else {
            scan = false;

        }
        lastInputTime = currentTime;


    });
    $("#num_empleado").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
        	let numEmpleado = $("#num_empleado").val();

			// Reemplazar "C8" por una cadena vacía en el valor del input
			//numEmpleado = numEmpleado.replace("C8", "");

			// Establecer el valor modificado de nuevo en el input
			//$("#num_empleado").val(numEmpleado);
			
			if (scan) {
				$("#leadcode_number").prop("disabled", false);
				
				$("#leadcode_number").focus();
			}
			else{
				$("#leadcode_number").prop("disabled", true);
			}


            
        }


    });

    if ($("#num_empleado").val() == "") {
        $("#leadcode_number").prop("disabled", true);
    }

    // Ejecutar la verificación al cambiar el valor del input
    $("#num_empleado").on('keyup', function(event) {
        event.preventDefault();
        if ($("#num_empleado").val() == "" || $("#num_empleado").val().length<10) {
            $("#leadcode_number").prop("disabled", true);
        } else {
            //$("#leadcode_number").prop("disabled", false); // Habilitar si no está vacío
        }
    });


    

    $("#leadcodeButton").on('click', function(event) {
        event.preventDefault();
        if (!scan) {
            new PNotify({
                title: 'Error',
                text: 'Debe escanear el numero de empleado',
                type: 'error',
                styling: 'bootstrap3'
            });
        }
        else if($("#num_empleado").val() == "" || $("#num_empleado").val().length<10){
        	new PNotify({
                title: 'Error',
                text: 'Debe ingresar un numero de empleado valido',
                type: 'error',
                styling: 'bootstrap3'
            });
        }
        else{
        	var folioNumber = generateSerial();
        	var badge = $("#num_empleado").val();
        	badge = badge.replace("C8","");
            badge = badge.replace("c8","");
            

        	$.ajax({
        		url: 'cont/pedidosController.php',
        		data: {request: 'addPedido',
                          folioNumber: folioNumber,
                          badge: badge
        	},
        	})
        	.done(function(info) {
        		var Data = JSON.parse(info);
        		if (Data['response']=='success') {
        			new PNotify({
		                title: 'Exito',
		                text: 'Se ha enviado el pedido',
		                type: 'success',
		                styling: 'bootstrap3'
		            });
                    $("#leadcodeButton").prop("disabled", true);
                    //$("#leadcode_number").prop("disabled", true);

		            
        		}
                else if (Data['response']=='NoInfo') {
                    new PNotify({
                        title: 'Error',
                        text: 'Debe ingresar al menos un Leadcode',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                    $("#leadcodeButton").prop("disabled", true);
                    //$("#leadcode_number").prop("disabled", true);

                    
                }
        		
        	})
        	.always(function() {
        		getTempData();
        	});
        	
        }

        /*var leadCodeData = $("#leadcode_number").val();
        var leadCodeData = {
        	leadCode: $("#leadcode_number").val(),
        	numEmpleado: 
        }
        leadCodes.push(leadCodeData);*/

        //getLeadCodes(JSON.stringify(leadCodes));
    });

    


    $("#leadcode_number").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var leadCode = $("#leadcode_number").val();
            $.ajax({
                url: 'cont/pedidosController.php',
                type: 'GET',
                data: {
                    request: 'validateNumber',
                    leadCode: leadCode
                }
            })
            .done(function(info) {
            	$("#leadcodeButton").prop("disabled", false);
                var Data = JSON.parse(info);
                
                if (Data['response'] == 'success') {
                    new PNotify({
                        title: 'Leadcode valido',
                        text: 'Agregado al pedido',
                        type: 'success',
                        styling: 'bootstrap3'
                    });
                    getTempData();
                    // Agregar la información a la tabla
                    
                  
                    
                }
                else if (Data['response']=='NoData') {
                    new PNotify({
                        title: 'Leadcode no valido',
                        text: 'No se agrego al pedido',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                $("#leadcode_number").val("");
            });
        }
    });
})

function getTempData(){
	$.ajax({
		url: 'cont/pedidosController.php',
		type: 'GET',
		data: {request: 'getTempData'},
	})
	.done(function(info) {
		var Data = JSON.parse(info);
		//console.log(Data)
		var tabla_au = $('#table_criticos').DataTable({
                dom: 'frtli',
                destroy: true,
                responsive: true,
                paging: false,
                order: [
                    [5, 'asc'], // Ri en ascendiente
                    [3, 'asc'], // Rack en ascendiente
                    [8, 'asc']  // Piso en ascendiente
                ],
                language: {
                    url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
                },
                columns: [
                    { data: "leadcode" },
                    { data: "color" },
                    { data: "rack", visible:false},
                    { data: "nivel", visible:false },
                    { data: "ri", visible:false },
                    { data: "e", visible:false },
                    { data: "l" , visible: false},
                    { data: "piso", visible:false},
                    { data: null, // Agrega una columna para la locación completa
                        render: function(data, type, row) {
                            return row.rack + ' ' + row.nivel + ' ' + row.ri + ' ' + row.e + ' ' + row.l;
                        }
                    },

                    { data: "Board" }
                ]
            });
            tabla_au.clear().rows.add(Data['information']).draw();
		
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function getLeadCodes(dato){
    //console.log(dato);
    $.ajax({
        url: 'cont/pedidosController.php',
        type: 'POST', // Cambiar a POST
        data: {datos: dato}
    })
    .done(function(info) {
        try {
            var Data = JSON.parse(info);
            var tabla_au = $('#table_criticos').DataTable({
                dom: 'frtli',
                destroy: true,
                responsive: true,
                paging: false,
                order: [
                    [5, 'asc'], // Ri en ascendiente
                    [3, 'asc'], // Rack en ascendiente
                    [8, 'asc']  // Piso en ascendiente
                ],
                language: {
                    url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
                },
                buttons: [
                    { 
                        extend: 'print', 
                        text: 'Imprimir documento', 
                        className: "btn btn-primary text-light boton-margen",
                        exportOptions: {
                            columns: [0, 1, 8, 9] // Índices de las columnas Leadcode, Mspec_Color1 y Locación
                        },
                        customize: function (win) {
                            var currentDateTime = new Date().toLocaleString();
                            // Agregar la fecha y hora al inicio del documento
                            $(win.document.body).prepend('<p>Fecha: ' + currentDateTime + '</p>').css('font-size', '70px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', '70px').css('border-collapse', 'collapse').css('width', '100%').css('border', '5px solid #000000');
                            $(win.document.body).find('table th, table td').css('padding', '8px').css('border', '5px solid #000000');
                            $(win.document.body).css('margin', '20px');
                            $(win.document.body).find('h1').css('display', 'none');
                            $(win.document.body).find('.dataTables_info').css('display', 'none');
                            $(win.document.body).find('.dataTables_paginate').css('display', 'none');
                            $(win.document.body).append('<p>Desarrollado por: Ing. Joel Andrade Enriquez</p>').css('font-size', '70px').css('font-weight', 'bold').css('width', '100%').css('text-align', 'center').css('display','block');
                        }
                    }
                ],
                columns: [
                    { data: "Leadcode" },
                    { data: "Mspec_Color1" },
                    { data: "Rack", visible:false},
                    { data: "Nivel", visible:false },
                    { data: "Ri", visible:false },
                    { data: "e", visible:false },
                    { data: "l" , visible: false},
                    { data: "Piso", visible:false},
                    { data: null, // Agrega una columna para la locación completa
                        render: function(data, type, row) {
                            return row.Rack + ' ' + row.Nivel + ' ' + row.Ri + ' ' + row.e + ' ' + row.l;
                        }
                    },

                    { data: "Board" }
                ]
            });
            tabla_au.clear().rows.add(Data).draw();
        } catch (e) {
            console.error("Error parsing JSON data: ", e);
            console.error("Received data: ", info);
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Error in AJAX request: ", textStatus, errorThrown);
    });
}

function generateSerial() {
    'use strict';
    
    var chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
        serialLength = 10,
        randomSerial = "",
        i,
        randomNumber;

    for (i = 0; i < serialLength; i++) {
        randomNumber = Math.floor(Math.random() * chars.length);
        randomSerial += chars.substring(randomNumber, randomNumber + 1);
    }

    return randomSerial;
}


$(document).ready(function(){
	getData(getShift());
		setInterval(function() {
	    getData(getShift());
	}, 60 * 1000); // Ejecutar cada 60,000 ms (1 minuto)
 
	$("#uploadDaily").on('click',  function(event) {
		event.preventDefault();
		$("#updateCountsModal").modal('show');
	});

    $("#loginButton").on('click',  function(event) {
        event.preventDefault();
        $("#loginModal").modal('show');
    });



    $('#table_criticos thead input').on('keyup change', function() {
        var index = $(this).parent().index(); // Obtiene el índice de la columna
        var value = this.value; // Obtiene el valor del input

        tabla_au.column(index).search(value).draw(); // Aplica el filtro a la columna correspondiente
    });

     

    // Inicializar visibilidad de columnas basadas en el valor por defecto del menú desplegable
    updateColumnVisibility();

    // Manejar cambios en la selección del menú desplegable
    $('#column-select').on('change', function() {
        updateColumnVisibility();
    });
    

	$("#btnEnter").on('click', function(event) {
		var user = $("#numEmpleado").val();
		var pass = $("#passEmpleado").val();
		$.ajax({
			url: 'cont/checkUser.php',
			data: {request: 'checkUser',
			          numEmpleado: user,
			          passEmpleado: pass
		},
		})
		.done(function(info) {
			var data = JSON.parse(info)
			if (data["response"]=="success") {
				new PNotify({
	                title: 'Exito',
	                text: 'Usuario identificado',
	                type: 'success',
	                styling: 'bootstrap3'
	            });
				$("#loginModal").modal('hide');
	            $("#uploadInfoModal").modal('show');

			}
			else{
				new PNotify({
	                title: 'Error',
	                text: 'Usuario no encontrado o no administrador',
	                type: 'error',
	                styling: 'bootstrap3'
	            });
			}
		})
		.fail(function() {
			new PNotify({
                title: 'Error',
                text: 'Error interno',
                type: 'error',
                styling: 'bootstrap3'
            });
		})
		
		
	});

    $("#downloadTemplate").on('click',function(e){
        e.preventDefault();
        var ruta = "UploadTemplate.xlsx";
        downloadFile(ruta);
    });
     
    $("#btnEnterCount").on('click',function(event){
        event.preventDefault();
        var Leadcode = $("#LeadcodeUpdated").val();
        var qtyUpdated = $("#qtyUpdated").val();

        $.ajax({
            url: 'cont/criticosController.php',
            data: {request: 'individualQtyUpdate',
                       Leadcode : Leadcode,
                       qtyUpdated : qtyUpdated
        },
        })
        .done(function(info) {
            var data = JSON.parse(info);
            console.log(data)
            if (data['response']=='success') {
                getData(getShift());

                $("#qtyUpdateModal").modal('hide');
            }
            else{
                new PNotify({
                        title: 'Error',
                        text: 'Ha ocurrido un error',
                        type: 'error',
                        styling: 'bootstrap3'
                    });    
            }
            
        })
        .fail(function() {
            new PNotify({
                title: 'Error',
                text: 'Ha ocurrido un error',
                type: 'error',
                styling: 'bootstrap3'
            });        

        })
        .always(function() {
            console.log("complete");
        });
        
    
    });
        

	 $('#newUpload-info').on('click', function () {
        var fileInput = document.getElementById('file-upload');
        if (fileInput.files.length === 0) {
            new PNotify({
		        title: 'Error',
		        text: 'Seleccione un archivo',
		        type: 'error',
		        styling: 'bootstrap3'
		    });
            return;
        }

        var file = fileInput.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });
            var firstSheet = workbook.Sheets[workbook.SheetNames[0]];

            var jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

            // Convert JSON data to array of objects
            var dataArray = [];
            for (var i = 1; i < jsonData.length; i++) {
                var row = jsonData[i];
                dataArray.push({
                    LeadCode: row[0],
                    VolumenDiario: row[1],
                    Turno: row[2]
                });
            }
             console.log(dataArray);
            $.ajax({
            	url: 'cont/criticosController.php',
            	type: 'POST',
            	data: {request: 'updateData',
                          data: dataArray
            },
            })
            .done(function(info) {
            	var dato = JSON.parse(info);
               
            	if (dato['response']=='success') {
            		getData(getShift());
            	}
            	else{
            		new PNotify({
			            title: 'Error',
			            text: 'Ha ocurrido un error',
			            type: 'error',
			            styling: 'bootstrap3'
			        });
            	}
            })
            .fail(function() {
            	new PNotify({
			            title: 'Error',
			            text: 'Ha ocurrido un error',
			            type: 'error',
			            styling: 'bootstrap3'
			        });
            });
            

            // Send data to PHP using AJAX
            /*$.ajax({
                url: 'process_upload.php',
                type: 'POST',
                data: { data: JSON.stringify(dataArray) },
                success: function (response) {
                    console.log('Server response:', response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX error:', textStatus);
                }
            });*/
        };

        reader.readAsArrayBuffer(file);
    });

     $('#newUploadCount-info').on('click', function () {
        var fileInput = document.getElementById('file-uploadCount');
        if (fileInput.files.length === 0) {
            new PNotify({
                title: 'Error',
                text: 'Seleccione un archivo',
                type: 'error',
                styling: 'bootstrap3'
            });
            return;
        }

        var file = fileInput.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });
            var firstSheet = workbook.Sheets[workbook.SheetNames[0]];

            var jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

            // Convert JSON data to array of objects
            var dataArray = [];
            for (var i = 1; i < jsonData.length; i++) {
                var row = jsonData[i];
                dataArray.push({
                    LeadCode: row[0] !== undefined ? row[0] : 0, 
                    InventarioContado: row[3] !== undefined ? row[3] : 0 
                });
            }
            console.log(dataArray);

            $.ajax({
                url: 'cont/criticosController.php',
                type: 'POST',
                data: {request: 'updateCountData',
                          data: dataArray
            },
            })
            .done(function(info) {
                var data = JSON.parse(info);
                if (data['response']=='success') {
                    getData(getShift());
                }
                else{
                    new PNotify({
                        title: 'Error',
                        text: 'Ha ocurrido un error',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }
            })
            .fail(function() {
                new PNotify({
                        title: 'Error',
                        text: 'Ha ocurrido un error',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
            });
            

           
        };

        reader.readAsArrayBuffer(file);
    });
});
    var tabla_au;   
function getShift() {
        var fecha = new Date();
        var hora = fecha.getHours();
        var minutos = fecha.getMinutes();

        var horaInicio = 6;
        var minutosInicio = 0;
        var horaFin = 15;
        var minutosFin = 36;

        if ((hora > horaInicio || (hora === horaInicio && minutos >= minutosInicio)) &&
            (hora < horaFin || (hora === horaFin && minutos <= minutosFin))) {
            return 'A';
        } else {
            return 'A';
        }
    }
function updateColumnVisibility() {
    var selectedColumns = $('#column-select').val();
    for (var i = 0; i < tabla_au.columns().nodes().length; i++) {
        var column = tabla_au.column(i);
        column.visible(selectedColumns.includes(i.toString()));
    }
}    

function getData(turno){
	console.log("Refreshed")
	new PNotify({
        title: 'Criticos actualizados',
        text: 'Se ha actualizado el estatus de los criticos',
        type: 'success',
        styling: 'bootstrap3'
    });
    $.ajax({
        url: 'cont/criticosController.php',
        data: {
            request: 'getData',
            turno: turno
        }
    })
    .done(function(info) {
        var data = JSON.parse(info);
        if (data['response'] === 'success') {
            tabla_au = $('#table_criticos').DataTable({
                dom: 'frtlip',
                destroy: true,
                responsive: true,
                paging: true,
                iDisplayLength: 25,
                order: [
                    [12, 'asc']  // Ordenar por la columna 8 en ascendente
                ],
                language: {
                    url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
                },
                buttons: [
                    {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen"},
                    
                    { extend: 'excel', text: 'Imprimir documento', className: "btn btn-primary text-light boton-margen", 
                      exportOptions: {
                        columns: ':visible'
                      }
                    },
                    {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
                    {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
                ],
                columns: [
                    { data: "LeadCode" },
                    { data: "Loc" },
                    { data: "Maquina" },
                    { data: "Color" },
                    { data: "Board" },
                    { data: "Estacion" },
                    { data: "Turno" },
                    { data: "VolumenDiario" },
                    { data: "UsoHora" },
                    { data: "InventarioContado"},
                    { data: "HoraConteo" },
                    { data: "InventarioActual" },
                    {
                        data: "HorasInventario",
                        render: function(data, type, row) {
                            // Verificar el valor y aplicar el estilo
                            var style = data <= 2 ? 'background-color: red; color: white; font-size:20px' : '';
                            return `<div class='col-md-12' style="${style}">${data}</div>`;
                        }
                    },
                    { data: "Accion" }
                ]
            });
            tabla_au.clear().rows.add(data['data']).draw();
            tabla_au.rows({ filter: 'applied' }).data().toArray();

        }
    })
    .fail(function() {
        new PNotify({
            title: 'Error',
            text: 'Error interno',
            type: 'error',
            styling: 'bootstrap3'
        });
    });
}

function exportToXLSX() {
    if (!tabla_au) {
        alert("No hay datos disponibles para exportar.");
        return;
    }

    // Obtén los datos de la DataTable
    var data = tabla_au.rows().data().toArray();
    console.log(data); // Verifica los datos en la consola

    if (data.length === 0) {
        alert("No hay datos para exportar.");
        return;
    }

    // Filtra los datos para incluir solo aquellos con HorasInventario < 3
    var filteredData = data.filter(function(row) {
        return row.HorasInventario < 3;
    });

    // Define las cabeceras de las columnas
    var headers = ["LeadCode", "Loc", "Mspec_Color1", "InventarioContado"];

    // Mapea los datos filtrados a un formato adecuado para XLSX
    var ws_data = filteredData.map(function(row) {
        return [
            row.LeadCode,        // LeadCode
            row.Loc,             // Loc
            row.Color          // Color
             // VolumenDiario
            // InventarioContado
        ];
    });

    // Incluye las cabeceras en los datos
    var ws_data_with_headers = [headers].concat(ws_data);

    // Crea la hoja de cálculo
    var ws = XLSX.utils.aoa_to_sheet(ws_data_with_headers);

    // Ajusta el ancho de las columnas
    const colWidths = headers.map((header, i) => {
        const maxLength = Math.max(
            header.length,
            ...ws_data.map(row => row[i] ? row[i].toString().length : 0)
        );
        return { wpx: maxLength * 10 }; // Ajusta el ancho de la columna en píxeles
    });

    ws['!cols'] = colWidths;

    // Crea el libro de trabajo y agrega la hoja
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Datos");

    // Convierte el libro a un archivo Blob
    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
    var blob = new Blob([wbout], { type: 'application/octet-stream' });

    // Crea un enlace para descargar el archivo XLSX
    var link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "NumerosCriticos - "+moment().format("MM-DD HH_mm")+".xlsx";
    link.click();
}

function updateQty(LeadCode){
    $("#qtyUpdateModal").modal('show');
    $("#LeadcodeUpdated").val(LeadCode);
    console.log(LeadCode)
}
function downloadFile(url) {
        // Creamos un elemento <a> temporal
        var link = $("<a>");
        // Asignamos la URL y el nombre de descarga al elemento <a>
        link.attr("href", url)
            .attr("download", "UploadTemplate.xlsx")
            .appendTo("body");
        // "Clicamos" en el enlace para iniciar la descarga
        link[0].click();
        // Eliminamos el enlace después de la descarga
        link.remove();
}

var tabla = $('#date-mercado').DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    
   // order:[[4, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
     	{extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
      	attr:  {
                id: 'jkjk'
            }},
     	{extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
     	{extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
     	{extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_emp = $('#table_empleados').DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    order:[[3, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_rutas = $('#data-rutas').DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
   // order:[[3, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_tolvas = $('#data-tolvas').DataTable({
    dom: 'frt',
    destroy: true,
    autoWidth: true,
    responsive: true,

    //order:[[4, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [

        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_tolvas = $('#data-tolvas-hora').DataTable({
    dom: 'frt',
    destroy: true,
    responsive: true,
    //order:[[4, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_personas = $('#data-tolvas-personas').DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    //order:[[4, 'desc']],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});

var tabla_au = $('#table_auditoria').DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    className: "center-block"
});


var table_conversion = $(".table_conversion").DataTable({
    dom: '',
    destroy: true,
    responsive: true,
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});

var data_salidas = $("#data_salidas").DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});
var data_barriles = $("#table_barriles").DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});

var master = $("#master").DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});




var table_inventario = $("#table_inventario").DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});

/*var table_mover = $("#table_mover").DataTable({
    dom: 'rt',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});*/

/*var table_criticos = $("#table_criticos").DataTable({
    dom: 'frtlip',
    destroy: true,
    responsive: true,
    buttons: [
        {extend :'copy', text: 'Copiar al portapapeles',className:"btn btn-primary boton-margen",
        attr:  {
                id: 'jkjk'
            }},
        {extend :'excel', text: 'Generar excel',className:"btn btn-primary text-light boton-margen"},
        {extend :'print', text: 'Imprimir documento',className:"btn btn-primary text-light boton-margen"},
        {extend :'pdf', text: 'Generar PDF',className:"btn btn-primary text-light boton-margen"}
    ],
    language: {
        url: 'http://10.215.156.203/materiales/rutas/build/traduccion.json',
    },
    className: "center-block"
});
*/





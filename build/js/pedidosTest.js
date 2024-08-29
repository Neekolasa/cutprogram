$(document).ready(function(){
    var testData = [
    {
        Ticket: "123",
        leadcode: "LC001",
        color: "Rojo",
        locacion: "A1 B2 C3 D4 E5",
        piso: "1",
        board: "B001",
        Atados: "2",
        fecha_creacion: "2024-06-02 10:00:00",
        badge_request: "Juan Perez"
    },
    {
        Ticket: "123",
        leadcode: "LC002",
        color: "Azul",
        locacion: "A2 B3 C4 D5 E6",
        piso: "2",
        board: "B002",
        Atados: "3",
        fecha_creacion: "2024-06-02 10:00:00",
        badge_request: "Juan Perez"
    },
    {
        Ticket: "123",
        leadcode: "LC003",
        color: "Verde",
        locacion: "A3 B4 C5 D6 E7",
        piso: "3",
        board: "B003",
        Atados: "4",
        fecha_creacion: "2024-06-02 10:00:00",
        badge_request: "Juan Perez"
    }
];

// Folio de prueba
var testFolio = "F12345";

// Llamar a la función con los datos de prueba y folio

    $("#printTest").on('click', function(event) {
        event.preventDefault();
        var ticketContent = generateTicketContent(testData, testFolio);
        sendToPrinter(ticketContent);
    });
});

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
    ticketHtml += "<span style='font-size:12px; text-align:center; width:100%; display:block;'><b>Fecha de impresión: </b>" + (currentDateTime) + "</span>";

    // Generar el código de barras usando la fuente 3 of 9
    ticketHtml += '<p style="text-align:center;" class="barcode">*' + folio + '*</p>';

    ticketHtml += "<b style='font-size:12px; text-align:center; width:100%; display:block;'>Desarrollado por: Ing Joel Andrade Enriquez</b>";
    ticketHtml += "</div>";
    return ticketHtml;
}

function sendToPrinter(content) {
    var printWindow = window.open('', '_blank', 'width=600,height=400');

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
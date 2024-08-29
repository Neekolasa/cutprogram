<?php

	require 'PHPExcel/PHPExcel.php';
	include '../connection.php';
	$plantillaExcel = '../TarjetaViajera.xlsx';

	$leadCode = $_REQUEST['leadCode'];
	$radioLinea = $_REQUEST['radioLinea'];

	$sheetIndex = 0;
	switch ($radioLinea) {
	    case 'GM':
	        $sheetIndex = 0;
	        break;
	    case 'Honda':
	        $sheetIndex = 1;
	        break;
	    case 'Stellantis':
	        $sheetIndex = 2;
	        break;
	    default:
	        echo json_encode(['status' => 'error', 'message' => 'Línea no válida']);
	        exit;
	}

	$sqlStatement = "
	    SELECT
	        Board,
	        Leadcode,
	        Mspec_Color1,
	        Estacion,
	        '2' as Atados,
	        Lnth,
	        Wire,
	        DATE_FORMAT(NOW(), '%d-%b-%y') AS formatted_date,
	        SUBSTRING_INDEX(Loc, ' ', 1) AS Rack,
	        SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', 2), ' ', -1) AS Nivel,
	        CASE
	            WHEN LOCATE('-', SUBSTRING_INDEX(Loc, ' ', -1)) = 0 THEN SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), '-', 1)
	            ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), '-', 1), ' ', -1)
	        END AS Ri,
	        CASE
	            WHEN LOCATE('-', SUBSTRING_INDEX(Loc, ' ', -1)) = 0 THEN ''
	            ELSE '-'
	        END AS e,
	        CASE
	            WHEN LOCATE('-', SUBSTRING_INDEX(Loc, ' ', -1)) = 0 THEN ''
	            ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), '-', -1)
	        END AS `l`,
	        CASE
	            WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', 2), ' ', -1) IN ('A', 'B', 'C', 'D', 'E') THEN 1
	            WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', 2), ' ', -1) IN ('F', 'G', 'H', 'I', 'J') THEN 2
	            ELSE 2
	        END AS Piso
	    FROM
	        criticos.masterdetalle
	    WHERE
	        Leadcode IN ('$leadCode')
	    ORDER BY
	        CAST(SUBSTRING_INDEX(Loc, ' ', 1) AS UNSIGNED) ASC,
	        CAST(
	            CASE
	                WHEN LOCATE('-', SUBSTRING_INDEX(Loc, ' ', -1)) = 0 THEN
	                    CASE
	                        WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), ' ', -1) REGEXP '^[0-9]+$' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), ' ', -1)
	                        ELSE '0'
	                    END
	                ELSE
	                    CASE
	                        WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), '-', 1), ' ', -1) REGEXP '^[0-9]+$' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(Loc, ' ', -1), '-', 1), ' ', -1)
	                        ELSE '0'
	                    END
	            END AS UNSIGNED
	        ) ASC,
	        Piso ASC;
	";
	$sqlQuery = mysqli_query($conn, $sqlStatement);
	if ($sqlQuery) {
	    $data = mysqli_fetch_assoc($sqlQuery);
	    if ($data) {
	        $objPHPExcel = PHPExcel_IOFactory::load($plantillaExcel);
	        $worksheet = $objPHPExcel->getSheet($sheetIndex);

	        // Actualizar celdas con los datos
	        $worksheet->setCellValue('B4', $data['Rack']);
	        $worksheet->setCellValue('C4', $data['Nivel']);
	        $worksheet->setCellValue('D4', $data['Ri']. $data['e']. $data['l']);
	        $worksheet->setCellValue('C5', $data['Leadcode']);
	        $worksheet->setCellValue('C6', $data['Leadcode']);  // Fuente especial no se maneja aquí
	        $worksheet->setCellValue('C7', $data['Wire']);
	        $worksheet->setCellValue('C8', $data['Mspec_Color1']);
	        $worksheet->setCellValue('C12', $data['Board']);
	        $worksheet->setCellValue('C14', $data['Estacion']);
	        $worksheet->setCellValue('D18', $data['Lnth']);
	        $worksheet->setCellValue('B19', $data['Atados']);
	        $worksheet->setCellValue('C19', '3187');
	        $worksheet->setCellValue('E18', $data['formatted_date']);

	        // Eliminar las hojas que no se están utilizando
	        for ($i = $objPHPExcel->getSheetCount() - 1; $i >= 0; $i--) {
	            if ($i != $sheetIndex) {
	                $objPHPExcel->removeSheetByIndex($i);
	            }
	        }

	        // Guardar el archivo temporalmente
	        $tempFile = tempnam(sys_get_temp_dir(), 'TarjetaViajera') . '.xlsx';
	        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	        $writer->save($tempFile);

	        // Enviar el archivo a imprimir (ejemplo para Windows)
	        $printCommand = 'PRINT /D:Xerox AltaLink B8170 (9F:F0:97) PS ' . escapeshellarg($tempFile);
        	shell_exec($printCommand);

	        // Configurar las cabeceras para la descarga
	        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	        header('Content-Disposition: attachment;filename="TarjetaViajera_Actualizada.xlsx"');
	        header('Cache-Control: max-age=0');

	        $writer->save('php://output');
	    } else {
	        echo json_encode(['status' => 'error', 'message' => 'No se encontraron datos para el Leadcode']);
	    }
	} else {
	    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta SQL']);
	}
?>
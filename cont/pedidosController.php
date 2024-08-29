<?php
// Incluir el archivo de conexión
include '../connection.php';

if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	$ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else{
	$ip=$_SERVER['REMOTE_ADDR'];
}
$request = $_REQUEST['request'];

if ($request == 'validateNumber') {
	$leadCode = $_REQUEST['leadCode'];
	$sqlStatement = "
	    SELECT
	        Board,
	        Leadcode,
	        Mspec_Color1,
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

	// Ejecutar la consulta
	$sqlQuery = mysqli_query($conn, $sqlStatement);

	// Verificar si la consulta se ejecutó correctamente
	if ($sqlQuery) {
		if (mysqli_num_rows($sqlQuery) > 0) {
			$resultArray = array();
    
		    // Recorrer los resultados y almacenarlos en un array
		    
		    while ($row = mysqli_fetch_assoc($sqlQuery)) {
		        $resultArray[] = $row;

		        // Verificar si ya existe un registro para este leadcode en la última hora
		        $checkDuplicateQuery = "SELECT COUNT(*) AS count FROM temp_pedidos WHERE leadcode = '$leadCode' AND fecha_creacion >= NOW() - INTERVAL 1 HOUR";
		        $checkResult = mysqli_query($conn, $checkDuplicateQuery);
		        $checkRow = mysqli_fetch_assoc($checkResult);

		        // Si no hay duplicados, insertar el nuevo registro
		        if ($checkRow['count'] == 0) {
		            $sqlInsertStatement = "INSERT INTO temp_pedidos (leadcode, color, rack, nivel, ri, e, l, piso, Board, ip_equipo, fecha_creacion) 
		            VALUES ('" . $leadCode . "','" . $row['Mspec_Color1'] . "','" . $row['Rack'] . "','" . $row['Nivel'] . "','" . $row['Ri'] . "','" . $row['e'] . "','" . $row['l'] . "','" . $row['Piso'] . "','" . $row['Board'] . "','" . $ip . "', NOW())";
		            $sqlInsertQuery = mysqli_query($conn, $sqlInsertStatement);
		        }
		    }
		    
			    // Convertir el array a JSON y mostrarlo
			    echo json_encode(array("response"=>"success"));
		}
		else{
			echo json_encode(array("response"=>"NoData"));
		}
    
	} else {
	    echo json_encode(array("error" => mysqli_error($conn)));
	}
}
elseif ($request == 'getTempData') {

	$sqlStatement = "SELECT *
		FROM temp_pedidos
		WHERE ip_equipo = '$ip'
		AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
	$sqlQuery = mysqli_query($conn, $sqlStatement);

	// Verificar si la consulta se ejecutó correctamente
	if ($sqlQuery) {
	    $resultArray = array();
	    
	    // Recorrer los resultados y almacenarlos en un array
	    while ($row = mysqli_fetch_assoc($sqlQuery)) {
	        $resultArray[] = $row;
	    }
	    
	    
	    // Convertir el array a JSON y mostrarlo

	    echo json_encode(array("response"=>"success","information"=>$resultArray));
	} else {
	    echo json_encode(array("error" => mysqli_error($conn)));
	}
}
elseif ($request == 'addPedido') {
	$folioNumber = $_REQUEST['folioNumber'];
	$numEmpleado = $_REQUEST['badge'];

	$sqlStatement = "SELECT *
		FROM temp_pedidos
		WHERE ip_equipo = '$ip'
		AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
	$sqlQuery = mysqli_query($conn, $sqlStatement);
	if (mysqli_num_rows($sqlQuery) > 0) {
		$sqlInsertPedidoStatement = "INSERT INTO pedidos_lista(folio, fecha_creacion, badge_request, estatus) VALUES('$folioNumber',NOW(), '$numEmpleado', 'pendiente')";
		$sqlInsertPedidoQuery = mysqli_query($conn,$sqlInsertPedidoStatement);

		// Verificar si la consulta se ejecutó correctamente
		if ($sqlQuery) {
		    $resultArray = array();
		    
		    // Recorrer los resultados y almacenarlos en un array
		    while ($row = mysqli_fetch_assoc($sqlQuery)) {
		        $resultArray[] = $row;
		        $sqlInsertMaterialStatement = "INSERT INTO pedidos_material (leadcode, color, rack, nivel, ri, e, l, piso, board, folio_pedido) 
				VALUES ('" . $row['leadcode'] . "','" . $row['color'] . "','" . $row['rack'] . "','" . $row['nivel'] . "','" . $row['ri'] . "','" . $row['e'] . "','" . $row['l'] . "','" . $row['piso'] . "','" . $row['Board'] . "','" . $folioNumber . "')";


		        $sqlQueryInsertMaterial = mysqli_query($conn,$sqlInsertMaterialStatement);
		    }
		    $sqlDelStatement = "DELETE FROM temp_pedidos WHERE ip_equipo = '$ip'";
		    $sqlDelQuery = mysqli_query($conn,$sqlDelStatement);
		    // Convertir el array a JSON y mostrarlo

		    echo json_encode(array("response"=>"success"));
		} else {
		    echo json_encode(array("error" => mysqli_error($conn)));
		}
	}
	else{
		echo json_encode(array("response"=>"NoInfo"));
	}

	
}
elseif ($request == 'getListaPedidos') {
	$sqlStatement = "SELECT
		pedidos_lista.id,
	    pedidos_lista.folio,
	    pedidos_lista.fecha_creacion,
	    COALESCE(req.nombre, 'No User') AS badge_request,
	    pedidos_lista.fecha_atendido,
	    COALESCE(atnd.nombre, 'No User') AS badge_atendido,
	    pedidos_lista.estatus,
	    COALESCE(resp.nombre, 'No User') AS responsable
	FROM
	    pedidos_lista 
	JOIN 
	    empleados AS req ON pedidos_lista.badge_request = req.num_empleado 
	LEFT JOIN
	    empleados AS atnd ON pedidos_lista.badge_atendido = atnd.num_empleado
	LEFT JOIN 
	    empleados AS resp ON pedidos_lista.responsable = resp.num_empleado
	WHERE
	    pedidos_lista.estatus <> 'completado'";
	$sqlQueryGetListaPedidos = mysqli_query($conn, $sqlStatement);

	if ($sqlQueryGetListaPedidos) {
	    $datos = array();
	    while ($row = mysqli_fetch_assoc($sqlQueryGetListaPedidos)) {
	        $datos[] = array(
	            "ID" => isset($row['id']) ? $row['id'] : "",
	            "Folio" => isset($row['folio']) ? $row['folio'] : "",
	            "Badge_request" => isset($row['badge_request']) ? $row['badge_request'] : "",
	            "Fecha_pedido" => isset($row['fecha_creacion']) ? $row['fecha_creacion'] : "",
	            "Fecha_atendido" => isset($row['fecha_atendido']) ? $row['fecha_atendido'] : "",
	            "Badge_atendido" => isset($row['badge_atendido']) ? $row['badge_atendido'] : "",
	            "responsable" => isset($row['responsable']) ? $row['responsable'] : "",
	            "Estatus" => getEstatus($row['estatus']),
	            "Actions" => "<button class='btn btn-primary' onclick='getPedido(\"" . (isset($row['folio']) ? $row['folio'] : "") . "\"); modalResponsable(\"" . (isset($row['folio']) ? $row['folio'] : "") . "\")'><i class='fa fa-print'></i></button>"
	        );
	    }
	    echo json_encode(array("response" => "success", "info" => $datos));
	} else {
	    echo json_encode(array("response" => "error", "message" => mysqli_error($conn)));
	}
}
elseif ($request == 'printTicket') {
	
	$folio = $_REQUEST['folio'];
	//$sqlStatementResponsable = "UPDATE pedidos_lista SET badge_atendido = '80124238' WHERE folio = '$folio'";
	//$sqlQueryResp = mysqli_query($conn, $sqlStatementResponsable);

    $sqlStatement = "
    	SELECT
		    pedidos_lista.id AS Ticket,
		    pedidos_lista.fecha_creacion,
		    COALESCE(req.nombre, 'No User') AS badge_request,
		    COALESCE(resp.nombre, 'No User') AS responsable,
		    pedidos_material.leadcode,
		    pedidos_material.color,
		    pedidos_material.rack,
		    pedidos_material.nivel,
		    pedidos_material.ri,
		    pedidos_material.e,
		    pedidos_material.l,
		    pedidos_material.piso,
		    pedidos_material.board,
		    pedidos_material.locacion,
		    pedidos_material.folio_pedido,
		    '2' AS Atados
		FROM
		    pedidos_material
		JOIN
		    pedidos_lista ON pedidos_material.folio_pedido = pedidos_lista.folio
		LEFT JOIN
		    empleados AS req ON pedidos_lista.badge_request = req.num_empleado
		LEFT JOIN
		    empleados AS resp ON pedidos_lista.responsable = resp.num_empleado
		WHERE
		    pedidos_material.folio_pedido = '$folio'
		ORDER BY
		    pedidos_material.nivel ASC,
		    pedidos_material.ri ASC,
		    pedidos_material.rack ASC,
		    pedidos_material.piso ASC


    ";
    $sqlQuery = mysqli_query($conn, $sqlStatement);

    if ($sqlQuery) {
        $resultArray = array();
        while ($row = mysqli_fetch_assoc($sqlQuery)) {
            $resultArray[] = $row;
        }
        echo json_encode(array("response" => "success", "info" => $resultArray));
    } else {
        echo json_encode(array("response" => "error", "message" => mysqli_error($conn)));
    }
}
elseif ($request == 'getPedido') {
	$folioNumber = $_REQUEST['folio'];
	$sqlUpdateStatement = "UPDATE pedidos_lista SET estatus = 'en proceso' WHERE folio = '$folioNumber'";
	$sqlQueryUpdate = mysqli_query($conn, $sqlUpdateStatement);

	if ($sqlQueryUpdate) {
	    echo json_encode(["response" => "success"]);
	}
}
elseif ($request == 'exitTicket') {
	$folioNumber = $_REQUEST['folio'];
	$numEmpleado = $_REQUEST['userLogged'];

	$sqlUpdateStatement = "UPDATE pedidos_lista SET estatus = 'completado', fecha_atendido = NOW(), badge_atendido= '$numEmpleado' WHERE folio = '$folioNumber'";
	$sqlQueryUpdate = mysqli_query($conn, $sqlUpdateStatement);

	if ($sqlQueryUpdate) {
	    echo json_encode(["response" => "success"]);
	}
}
elseif ($request == 'validateAccess') {
	$numEmpleado = $_REQUEST['badge'];

	// Realizar la consulta SQL
	$sqlValidateAccessStatement = "SELECT COUNT(*) as Numero FROM empleados WHERE num_empleado = '$numEmpleado'";
	$sqlQueryAccess = mysqli_query($conn, $sqlValidateAccessStatement);

	// Verificar si se encontraron registros
	if ($sqlQueryAccess) {
	    $row = mysqli_fetch_assoc($sqlQueryAccess);
	    $numeroRegistros = $row['Numero'];
	    
	    // Verificar si se encontraron registros que coinciden
	    if ($numeroRegistros > 0) {
	        // Hay registros que coinciden, enviar respuesta de éxito
	        echo json_encode(["response" => "success"]);
	    } else {
	        // No se encontraron registros que coinciden, enviar respuesta de fallo
	        echo json_encode(["response" => "fail"]);
	    }
	} else {
	    // Error en la consulta SQL, enviar respuesta de fallo
	    echo json_encode(["response" => "fail"]);
	}

}
elseif ($request == 'ticketsCompleted') {
	$sqlCompletedTicketsStatement = "SELECT
		pedidos_lista.id,
	    pedidos_lista.folio,
	    pedidos_lista.fecha_creacion,
	    COALESCE(req.nombre, 'No User') AS badge_request,
	    pedidos_lista.fecha_atendido,
	    COALESCE(atnd.nombre, 'No User') AS badge_atendido,
	    pedidos_lista.estatus,
	    COALESCE(resp.nombre, 'No User') AS responsable
	FROM
	    pedidos_lista 
	JOIN 
	    empleados AS req ON pedidos_lista.badge_request = req.num_empleado 
	LEFT JOIN
	    empleados AS atnd ON pedidos_lista.badge_atendido = atnd.num_empleado
	LEFT JOIN 
	    empleados AS resp ON pedidos_lista.responsable = resp.num_empleado
	WHERE
	    pedidos_lista.estatus = 'completado' ORDER BY pedidos_lista.id DESC LIMIT 200";
	/*$sqlCompletedTicketsStatement = "SELECT
		    id, 
		    folio,
		    fecha_creacion,
		    badge_request, 
		    fecha_atendido, 
		    badge_atendido,
		    estatus,
		    TIMESTAMPDIFF(MINUTE, fecha_creacion, fecha_atendido) AS TiempoSurtido --OBTENER TIEMPO DE SURTIDO EN MINUTOS
		FROM
		    pedidos_lista
		WHERE 
		    estatus = 'completado';";*/
	$sqlCompletedTicketsQuery = mysqli_query($conn, $sqlCompletedTicketsStatement);

	if ($sqlCompletedTicketsQuery) {
	    if (mysqli_num_rows($sqlCompletedTicketsQuery) > 0) {
	        $datos = array();
	        while ($row = mysqli_fetch_assoc($sqlCompletedTicketsQuery)) {
	            $datos[] = array(
	                "ID" => isset($row['id']) ? $row['id'] : "",
	                "Folio" => isset($row['folio']) ? $row['folio'] : "",
	                "Badge_request" => isset($row['badge_request']) ? $row['badge_request'] : "",
	                "Fecha_pedido" => isset($row['fecha_creacion']) ? $row['fecha_creacion'] : "",
	                "Fecha_atendido" => isset($row['fecha_atendido']) ? $row['fecha_atendido'] : "",
	                "Badge_atendido" => isset($row['badge_atendido']) ? $row['badge_atendido'] : "",
	                "responsable" => isset($row['responsable']) ? $row['responsable'] : "",
	                "Estatus" => getEstatus($row['estatus']),
	                "Actions" => "<button class='btn btn-primary' onclick='printTicket(\"" . (isset($row['folio']) ? $row['folio'] : "") . "\");'><i class='fa fa-print'></i></button>"
	            );
	        }
	        echo json_encode(array("response" => "success", "info" => $datos));
	    } else {
	        echo json_encode(array("response" => "fail", "message" => "No se encontraron tickets completados."));
	    }
	} else {
	    echo json_encode(array("response" => "fail", "message" => "Error al ejecutar la consulta SQL."));
	}

}
elseif ($request == 'setResponsable') {
	$folio = $_REQUEST['folioNum'];
	$numResp = $_REQUEST['numResp'];

	$sqlStatementResponsable = "UPDATE pedidos_lista SET responsable = '$numResp' WHERE folio = '$folio'";
	$sqlQueryResp = mysqli_query($conn, $sqlStatementResponsable);

	echo json_encode(array("response"=>"success"));
}
elseif ($request == 'leadCodeInfo') {
	
	// Recibir los datos enviados por POST
	$leadCodes = $_REQUEST['datos'];

	// Construir la consulta SQL

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
	        Leadcode IN ('$leadCodes')
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

	// Ejecutar la consulta
	$sqlQuery = mysqli_query($conn, $sqlStatement);

	// Verificar si la consulta se ejecutó correctamente
	if ($sqlQuery) {
	    $resultArray = array();
	    
	    $datos = array();
	    while ($row = mysqli_fetch_assoc($sqlQuery)) {
	        array_push($datos, array(
	        	"Atados"=>$row["Atados"],
	        	"Board"=>$row["Board"],
	        	"Estacion"=>$row["Estacion"],
	        	"Leadcode"=>$row["Leadcode"],
	        	"Lnth"=>$row["Lnth"],
	        	"Mspec_Color1"=>$row["Mspec_Color1"],
	        	"Piso"=>$row["Piso"],
	        	"Rack"=>$row["Rack"],
	        	"Nivel"=>$row["Nivel"],
	        	"Riel"=>$row["Ri"].$row["e"].$row["l"],
	        	"Wire"=>$row["Wire"],
	        	"formatted_date"=>$row["formatted_date"]

	        ));
	    }
	    
	    // Convertir el array a JSON y mostrarlo
	    echo json_encode($datos);
	} else {
	    echo json_encode(array("error" => mysqli_error($conn)));
	}
}
elseif ($request == 'pedidoInfo') {
	$folio = $_REQUEST['folio'];
	$sqlInfoPedidoStatement = "
		SELECT
		    pedidos_material.leadcode,
		    pedidos_material.color,
		    pedidos_material.rack,
		    pedidos_material.nivel,
		    CONCAT(pedidos_material.ri, pedidos_material.e, pedidos_material.l) AS Riel,
		    pedidos_material.piso,
		    pedidos_material.board,
		    '2' AS Atados
		FROM
		    pedidos_material
		JOIN
		    pedidos_lista ON pedidos_material.folio_pedido = pedidos_lista.folio
		WHERE
		    pedidos_material.folio_pedido = '$folio'
		ORDER BY
		    pedidos_material.nivel ASC,
		    pedidos_material.ri ASC,
		    pedidos_material.rack ASC,
		    pedidos_material.piso ASC;
	";
	$sqlQueryInfo = mysqli_query($conn, $sqlInfoPedidoStatement);

    if ($sqlQueryInfo) {
        $resultArray = array();
        while ($row = mysqli_fetch_assoc($sqlQueryInfo)) {
            $resultArray[] = $row;
        }
        echo json_encode(array("response" => "success", "info" => $resultArray));
    } else {
        echo json_encode(array("response" => "error", "message" => mysqli_error($conn)));
    }

}
else{
	// Recibir los datos enviados por POST
	$datos = json_decode($_POST['datos'], true);

	// Construir la consulta SQL
	$leadCodes = implode("','", array_map('mysqli_real_escape_string', array_fill(0, count($datos), $conn), $datos));

	$sqlStatement = "
	    SELECT
	        Board,
	        Leadcode,
	        Mspec_Color1,
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
	        Leadcode IN ('$leadCodes')
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

	// Ejecutar la consulta
	$sqlQuery = mysqli_query($conn, $sqlStatement);

	// Verificar si la consulta se ejecutó correctamente
	if ($sqlQuery) {
	    $resultArray = array();
	    
	    // Recorrer los resultados y almacenarlos en un array
	    while ($row = mysqli_fetch_assoc($sqlQuery)) {
	        $resultArray[] = $row;
	    }
	    
	    // Convertir el array a JSON y mostrarlo
	    echo json_encode($resultArray);
	} else {
	    echo json_encode(array("error" => mysqli_error($conn)));
	}
}
function getEstatus($status){
	if ($status == "pendiente") {
		return "<b style='color:red'>Pendiente de surtir</b>";
	}
	elseif ($status == "en proceso") {
		return "<b style='color:#F59533;'>En proceso de surtido</b>";
	}
	elseif ($status == "completado") {
		return "<b style='color:green;'>Surtido completo</b>";
	}
}
?>

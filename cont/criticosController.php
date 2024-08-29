<?php 
	include '../connection.php';
	$request = $_REQUEST['request'];

	if ($request == 'getData') {
		$turno = $_REQUEST['turno'];

		if ($turno == "A") {

			/*$sqlFullStatement = "
				SELECT
				    id,
				    LeadCode,
				    Loc,
				    mnbr AS Maquina,
				    Mspec_Color1 as Color,
				    Board,
				    Estacion,
				    Turno,
				    VolumenDiario,
				    CASE
				        WHEN Turno = 'A' THEN (VolumenDiario / 9.5)
				        WHEN Turno = 'B' THEN (VolumenDiario / 8.5)
				        WHEN Turno = 'X' THEN (VolumenDiario / 18)
				        ELSE NULL
				    END AS UsoHora,
				    InventarioContado,
				    DATE_FORMAT(FechaCarga, '%l:%i %p') AS HoraConteo,  -- Hora en formato AM/PM
				    GREATEST(0, InventarioContado - (TIMESTAMPDIFF(HOUR, FechaCarga, NOW()) * CASE
				        WHEN Turno = 'A' AND HOUR(NOW()) BETWEEN 6 AND 15 THEN (VolumenDiario / 9.5)
				        WHEN Turno = 'B' AND (HOUR(NOW()) >= 15 OR HOUR(NOW()) < 6) THEN (VolumenDiario / 8.5)
				        WHEN Turno = 'X' THEN (VolumenDiario / 18)
				        ELSE 0
				    END)) AS InventarioActual,

				    CASE
				        WHEN Turno = 'A' AND HOUR(NOW()) BETWEEN 6 AND 15 THEN GREATEST(0, (InventarioContado - (TIMESTAMPDIFF(HOUR, FechaCarga, NOW()) * (VolumenDiario / 9))) / (VolumenDiario / 9.5))
				        WHEN Turno = 'B' AND (HOUR(NOW()) >= 15 OR HOUR(NOW()) < 6) THEN GREATEST(0, (InventarioContado - (TIMESTAMPDIFF(HOUR, FechaCarga, NOW()) * (VolumenDiario / 9))) / (VolumenDiario / 8.5))
				        WHEN Turno = 'X' THEN
				            GREATEST(0, (InventarioContado - (TIMESTAMPDIFF(HOUR, FechaCarga, NOW()) * (VolumenDiario / 9.5))) / (VolumenDiario / 9.5)) +
				            GREATEST(0, (InventarioContado - (TIMESTAMPDIFF(HOUR, FechaCarga, NOW()) * (VolumenDiario / 8.5))) / (VolumenDiario / 8.5))
				        ELSE 0
				    END AS HorasInventario
				FROM
				    `criticos`.`masterdetalle_mirror`
				WHERE
				    VolumenDiario > 0 AND VolumenDiario <= 100;*/

			/*$sqlStatement = "
				SELECT
				    LeadCode,
				    Loc,
				    mnbr as Maquina,
				    Mspec_Color1,
				    Board,
				    Turno,
				    VolumenDiario,
				    CASE
				        WHEN Turno = 'A' THEN (VolumenDiario / 9)
				        WHEN Turno = 'B' THEN (VolumenDiario / 9)
				        WHEN Turno = 'X' THEN (VolumenDiario / 18)
				        ELSE NULL
				    END AS UsoHora,
				    CASE
				        WHEN Turno = 'A' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 9))))
				        WHEN Turno = 'B' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 15) * (VolumenDiario / 9))))
				        WHEN Turno = 'X' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 18))))
				        ELSE 0
				    END AS InventarioActual,
				    COALESCE(
				        CASE
				            WHEN Turno = 'A' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 9))) / (VolumenDiario / 9)))
				            WHEN Turno = 'B' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 15) * (VolumenDiario / 9))) / (VolumenDiario / 9)))
				            WHEN Turno = 'X' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 18))) / (VolumenDiario / 18)))
				            ELSE 0
				        END, 0
				    ) AS HorasInventario
				FROM
				    masterdetalle_mirror
				WHERE VolumenDiario > 0 AND (Turno = 'A' OR Turno = 'X');
			";
			*/
			$sqlStatement = "
				SELECT *
				FROM `criticos`.`masterdetalle_mirror_vista`;
			";
			$datos = array();
			$sqlQuery = mysqli_query($conn, $sqlStatement);
  
			if ($sqlQuery) {
				if (mysqli_num_rows($sqlQuery)>0) {
					while ($row = mysqli_fetch_assoc($sqlQuery)) {
						array_push($datos, array(
							"LeadCode"=>$row["LeadCode"],
							"Loc"=>$row["Loc"],
							"Maquina"=>$row["Maquina"],
							"Color"=>$row["Color"],
							"Board"=>$row["Board"],
							"Estacion"=>$row['Estacion'],
							"Turno"=>turnoName($row['Turno']),
							"VolumenDiario"=>round($row["VolumenDiario"],2),
							"UsoHora"=>round($row["UsoHora"],2),
							"InventarioContado"=>round($row['InventarioContado'],2),
							"InventarioActual"=>round($row["InventarioActual"],2),
							"HoraConteo"=>$row['HoraConteo'],
							"HorasInventario"=>calInv($row["InventarioActual"],(round($row["HorasInventario"],2)),$row['Turno']),
							"Accion" => "<button class='btn btn-primary' onclick='updateQty(\"" . (isset($row['LeadCode']) ? $row['LeadCode'] : "") . "\");'><i class='fa fa-refresh fa-spin'></i></button>"

						));
					}

					echo json_encode(array("data"=>$datos,"response"=>"success"));
				}
				else{
					echo json_encode(array("response"=>"NoData"));
				}
			}
		}
		else {
			
			$sqlStatement = "
				SELECT
				    LeadCode,
				    Loc,
				    mnbr as Maquina,
				    Mspec_Color1,
				    Board,
				    Turno,
				    VolumenDiario,
				    CASE
				        WHEN Turno = 'A' THEN (VolumenDiario / 9)
				        WHEN Turno = 'B' THEN (VolumenDiario / 9)
				        WHEN Turno = 'X' THEN (VolumenDiario / 18)
				        ELSE NULL
				    END AS UsoHora,
				    CASE
				        WHEN Turno = 'A' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 9))))
				        WHEN Turno = 'B' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 15) * (VolumenDiario / 9))))
				        WHEN Turno = 'X' THEN GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 18))))
				        ELSE 0
				    END AS InventarioActual,
				    COALESCE(
				        CASE
				            WHEN Turno = 'A' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 9))) / (VolumenDiario / 9)))
				            WHEN Turno = 'B' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 15) * (VolumenDiario / 9))) / (VolumenDiario / 9)))
				            WHEN Turno = 'X' THEN (GREATEST(0, (VolumenDiario - ((HOUR(NOW()) - 6) * (VolumenDiario / 18))) / (VolumenDiario / 18)))
				            ELSE 0
				        END, 0
				    ) AS HorasInventario
				FROM
				    masterdetalle_mirror
				WHERE VolumenDiario > 0 AND (Turno = 'B' OR Turno = 'X');
			";
			$datos = array();
			$sqlQuery = mysqli_query($conn, $sqlStatement);

			if ($sqlQuery) {
				if (mysqli_num_rows($sqlQuery)>0) {
					while ($row = mysqli_fetch_assoc($sqlQuery)) {
						array_push($datos, array(
							"LeadCode"=>$row["LeadCode"],
							"Loc"=>$row["Loc"],
							"Maquina"=>$row["Maquina"],
							"Color"=>$row["Mspec_Color1"],
							"Board"=>$row["Board"],
							"VolumenDiario"=>$row["VolumenDiario"],
							"UsoHora"=>round($row["UsoHora"],2),
							"InventarioActual"=>round($row["InventarioActual"],2),
							"HorasInventario"=>(round($row["HorasInventario"],2))

						));
					}

					echo json_encode(array("data"=>$datos,"response"=>"success"));
				}
				else{
					echo json_encode(array("response"=>"NoData"));
				}
			}
		}

	}
	elseif($request =='updateData'){
		$data = ($_REQUEST['data']);
		foreach ($data as $row) {
	        $leadCode = $row['LeadCode'];
	        $volumenDiario = $row['VolumenDiario'];
	        $turno = $row['Turno'];

	        $sqlStatement = "UPDATE masterdetalle_mirror SET VolumenDiario = '$volumenDiario', Turno = '$turno' WHERE Leadcode = '$leadCode'";
	        $sqlQuery = mysqli_query($conn, $sqlStatement);

	    }

	    if ($sqlQuery) {
	    	echo json_encode(array("response"=>"success"));
	    }
	}
	elseif($request == 'updateCountData'){
		$data = ($_REQUEST['data']);
		foreach ($data as $row) {
	        $leadCode = $row['LeadCode'];
	        $conteoTotal = $row['InventarioContado'];

	        $sqlStatement = "UPDATE masterdetalle_mirror SET InventarioContado = '$conteoTotal', FechaCarga = NOW() WHERE Leadcode = '$leadCode'";
	        $sqlQuery = mysqli_query($conn, $sqlStatement);

	    }

	    if ($sqlQuery) {
	    	echo json_encode(array("response"=>"success"));
	    }
	}
	elseif($request == 'individualQtyUpdate'){
		$leadCode = $_REQUEST['Leadcode'];
		$qtyUpdated = $_REQUEST['qtyUpdated'];
		$sqlStatement = "UPDATE masterdetalle_mirror SET InventarioContado = '$qtyUpdated', FechaCarga = NOW() WHERE Leadcode = '$leadCode'";
	    $sqlQuery = mysqli_query($conn, $sqlStatement);

	    if ($sqlQuery) {
	    	echo json_encode(array("response"=>"success"));
	    }
	}

	function turnoName($turno){
		if ($turno == 'X') {
			return "A & B";
		}
		else{
			return $turno;
		}
	}

	function calInv($inv,$res,$turno){
		if ($inv>0 && $res<=0 && $turno=='B') {
			return "No esta corriendo este turno";
		}
		elseif ($inv>0 && $res<=0 && $turno=='A') {
			return "No esta corriendo este turno";
		}
		else{
			return $res;
		}
	}

?>
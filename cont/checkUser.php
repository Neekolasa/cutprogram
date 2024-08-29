<?php 
	include '../connection.php';

	$request = $_REQUEST['request'];
	if ($request == 'checkUser') {
		$numEmpleado = $_REQUEST['numEmpleado'];
		$passEmpleado = $_REQUEST['passEmpleado'];
		$passEmpleado = md5($passEmpleado);

		$sqlStatement = "SELECT * FROM empleados WHERE num_empleado = '$numEmpleado' AND pass='$passEmpleado' AND tipouser = 'Administrador'";
		$sqlQuery = mysqli_query($conn,$sqlStatement);
		if ($sqlQuery) {
			if (mysqli_num_rows($sqlQuery)>0) {
				$datos = array();

				while ($row = mysqli_fetch_assoc($sqlQuery)) {
					array_push($datos, array(
                    	"User"=>$row['num_empleado'],
                    	"Permiso"=>$row['tipouser']
					));
				}

				echo json_encode(array("datos"=>$datos,"response"=>"success"));
			}
			else{
				echo json_encode(array("response"=>"NoData"));
			}
		}

	}
?>
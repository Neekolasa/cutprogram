<?php 
	include '../connection.php';
	//$statement = "SELECT Count(*) as tolvas FROM ChkComp_MainMov"; 
	
	$statement = "SELECT        Route, COUNT(Route) AS COMPONENTE
					FROM [SMS].[dbo].[ChkComp_MainMov]
					WHERE scandate BETWEEN '20230719 15:36' AND '20230720 00:09:59.999'and sn <>'0FV559000000000' and badge <>'-1' 
					GROUP BY Route";

	$statement_two = "SELECT        Route, COUNT(Route) AS POLIDUCTO
						FROM [SMS].[dbo].[ChkComp_MainMov]
						WHERE scandate BETWEEN '20230719 15:36' AND '20230720 00:09:59.999'and sn ='0FV559000000000' 
						GROUP BY Route";
 
	$query = sqlsrv_query($conn,$statement);


	while ($result = sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC)) {
		$tolva = array('Ruta' => $result['Route'],
						'Componente' => $result['COMPONENTE'] );

		echo json_encode($tolva);
	}

	$query = sqlsrv_query($conn,$statement_two);

	echo "
		<html>
			<br><br>
		</html>";

	while ($result = sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC)) {
		$tolva = array('Ruta' => $result['Route'],
						'Poliducto' => $result['POLIDUCTO'] );

		echo json_encode($tolva);
	}

  																																							
 ?>


<?php
	date_default_timezone_set('America/Monterrey');
	$unaHoraMenos = strtotime('-1 hour');
	$dataCheck = array(	'scanDate' => date('Y-m-d'),
							'scanHour' => date('H:i:s',$unaHoraMenos));
	echo $dataCheck;

?>
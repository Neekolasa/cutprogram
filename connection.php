<?php
	// Datos de conexión
	$host = "dl7wfrwp3";
	$username = "corte";
	$password = "1234567";
	$database = "criticos";

	// Crear conexión
	$conn = new mysqli($host, $username, $password, $database);

	// Verificar conexión
	if ($conn->connect_error) {
	    die("Conexión fallida: " . $conn->connect_error);
	}

	//echo "Conexión exitosa a la base de datos MySQL";
?>
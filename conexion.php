<?php	
	$hostname = 'localhost';
	$database = 'dbsigav';
	$username = 'tarosoft';
	$password = 'armagedon';
	
	$enlace = mysqli_connect($hostname, $username, $password, $database);
	$enlace->set_charset("utf8");

	if (mysqli_connect_errno())
	{
    	printf("Falló la conexión: %s\n", mysqli_connect_error());
    	exit();
	}
	//$mysqli -> mysqli_close();
	//echo $mysqli->host_info . "\n";	

?>
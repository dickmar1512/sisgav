<?php
  include('funciones.php');
  
	if(isset($_POST['tipo']))
  {
    //$txtBusqueda = $_POST['txtBusqueda'];
    
    $numDocUsuario = $_POST['numDocUsuario'];
    $tipo = $_POST['tipo']; 

    $dato = datosbyDocumento($enlace, $numDocUsuario, $tipo);

    if ($tipo == 1)
    {
      include('datos_ruc.php');
    }
    else /*if($tipo == 3)*/
    {
      include('datos_dni.php');
    }

    /*else if($tipo == 4)
    {
      include('datos_dni.php');
    }*/
	}
 ?>
<?php
	if(count($_POST)>0)
	{	
		$user = new PersonData();

		$tipo = $_POST["optTipoPersona"];

		$user->tipo_persona = $tipo;

		if ($tipo == 3)
		{
			$user->numero_documento = $_POST["dni"];
			$user->name = $_POST["name"];
			$user->lastname = $_POST["lastname"];
		}
		else
		{
			$user->numero_documento = $_POST["ruc"];
			$user->name = $_POST["razon_social"];
			$user->lastname = "";
		}
		
		$user->address1 = $_POST["address1"];
		$user->email1 = $_POST["email1"];
		$user->phone1 = $_POST["phone1"];

		$user->add_provider();

		print "<script>window.location='index.php?view=providers';</script>";
	}
?>
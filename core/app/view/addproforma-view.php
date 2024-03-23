<?php
	$conexion = new mysqli('localhost','tarosoft','armagedon','dbsigav',3306);

	if (mysqli_connect_errno()) 
	{
    	printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
    	exit();
	}

	if(count($_POST) > 0)
	{
		//BOLETA / FACTURA
		$RUC = $_POST["RUC"];
		$TIPO = $_POST["TIPO"];

		$SERIE = $_POST["SERIE"];
		$COMPROBANTE = $_POST["COMPROBANTE"];
		$estado = $_POST["selEstado"];

        $fecEmision = $_POST["fecEmision"];
		$horEmision = $_POST["horEmision"];
        $numDocUsuario = $_POST["numDocUsuario"];
		$rznSocialUsuario = $_POST["rznSocialUsuario"];
        $Observacion = $_POST['txtObservacion'];
		//precio total
		$sumTotValVenta = 0;
		$sumPrecioVenta = 0;

		

		if(isset($_SESSION["cart"]))
		{
			$cart = $_SESSION["cart"];

			if(count($cart) > 0)
			{
				$num_succ = 0;
				$process = true;
				$errors = array();

				if($num_succ == count($cart))
				{
					$process = true;
				}

				if($process==false)
				{
					$_SESSION["errors"] = $errors;
					?>
						<script>
							window.location="index.php?view=sell";
						</script>
					<?php
				}

				if($process == true)
				{
					if(trim($numDocUsuario) != '')
					{
						$a = PersonData::verificar_persona($numDocUsuario, 1);

						if(is_null($a))
						{
							$person = new PersonData();

							$person->tipo_persona = 1;
							$person->numero_documento = $numDocUsuario;
							$person->name = $rznSocialUsuario;
							$person->lastname = "";
							$person->address1 = $desDireccionCliente;
							$person->ubigeo = $codUbigeoCliente;
							$person->email1 = "";

							$per = $person->add_client();

							$person_id = $per[1];
						}
						else
						{
							$person_id = $a->id;
						}
					}

					$sell = new SellData();
					$sell->user_id = $_SESSION["user_id"];
					$sell->tipo_comprobante = 1;
					$sell->serie = $SERIE;
					$sell->comprobante = $COMPROBANTE;
					$sell->total = $_POST["total"];
					$sell->discount = $_POST["discount"];
					$sell->cash = $_POST["money"];
					$sell->tipo_pago = $_POST["selTipoPago"];
					$sell->person_id = $person_id;
					$sell->estado = $estado;
					$sell->created_at = $fecEmision.' '.$horEmision;
					$sell->observacion = $Observacion;

					if(isset($_POST["client_id"]) && $_POST["client_id"]!="")
					{
						$sell->person_id=$_POST["client_id"];
						$s = $sell->add_with_client();
					}
					else
					{
						$s = $sell->add2();
					}
                     
					foreach($cart as  $c)
					{
						$op = new OperationData();
						$op->product_id = $c["product_id"];

						$product = ProductData::getById($c["product_id"]);

						$op->operation_type_id=OperationTypeData::getByName("salida")->id;
						$op->sell_id=$s[1];
						$op->descripcion = $c["descripcion"];						
						$op->cu = $product->price_in;
						$op->prec_alt=$c["precio_unitario"];						
						$op->descuento = $c["descuento"];												
					    $op->idpaquete = $c["idpaquete"];
					    $op->qp = $c["qp"];
						$op->q = round($c["q"]*$c["qp"],2);

						if(isset($_POST["is_oficial"]))
						{
							$op->is_oficial = 1;
						}

						$add = $op->add();

						if($product->is_stock == 1)
						{
							$product2 = new ProductData();
							$product2->stock = $c["q"];
							$product2->id = $c["product_id"];

							$product2->restar_stock();
						}

						unset($_SESSION["cart"]);
						setcookie("selled","selled");
					}
				}
			}
		}		
			//print "<script>window.location='index.php?view=proforma&id=$s[1]';</script>";		
	}	
 ?>
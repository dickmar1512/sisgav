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

		//ARCHIVO CAB
		$tipOperacion = $_POST["tipOperacion"];
		$fecEmision = $_POST["fecEmision"];
		$horEmision = $_POST["horEmision"];
		$fecVencimiento = $_POST["fecVencimiento"];
		$codLocalEmisor = $_POST["codLocalEmisor"];
		$tipDocUsuario = $_POST["tipDocUsuario"];
		$numDocUsuario = $_POST["numDocUsuario"];
		$rznSocialUsuario = $_POST["rznSocialUsuario"];
		$tipMoneda = $_POST["tipMoneda"];
		$sumTotTributos = $_POST["sumTotTributos"];
		//precio total
		$sumTotValVenta = 0;
		$sumPrecioVenta = 0;

		//ARCHIVO DET
		$codUnidadMedida = $_POST["codUnidadMedida"];
		$codProducto = $_POST["codProducto"];
		$codProductoSUNAT = $_POST["codProductoSUNAT"];
		$codTriIGV = $_POST["codTriIGV"];
		$mtoIgvItem = $_POST["mtoIgvItem"];
		//$mtoBaseIgvItem = $_POST["mtoBaseIgvItem"]; *
		if ($_POST['mtoIscItem'] == '')
		{
			$mtoBaseIgvItem = 0.00;
		}
		else
		{
			$mtoBaseIgvItem = $_POST["mtoIscItem"];
		}
		
		$nomTributoIgvItem    = $_POST["nomTributoIgvItem"];
		$codTipTributoIgvItem = $_POST["codTipTributoIgvItem"];
		$tipAfeIGV            = $_POST["tipAfeIGV"];
		$porIgvItem           = $_POST["porIgvItem"];
		$codTriISC            = "-";
		$mtoIscItem           = $_POST["mtoIscItem"];
		$mtoBaseIscItem       = 0;
		$nomTributoIscItem    = $_POST["nomTributoIscItem"];
		$codTipTributoIscItem = $_POST["codTipTributoIscItem"];
		$tipSisISC            = $_POST["tipSisISC"];
		$porIscItem           = $_POST["porIscItem"];
		$codTriOtroItem       = "-";
		$sumTotTributosItem   = $_POST["sumTotTributosItem"];

		/*Tributo ICBPER 7152*/
		$codTriIcbper            = "-";
		$mtoTriIcbperItem        = "";
		$ctdBolsasTriIcbperItem  = "";
		$nomTributoIcbperItem    = "";
		$codTipTributoIcbperItem = "";
		$mtoTriIcbperUnidad      = "";


		//datos por verificar
		$mtoTriOtroItem = '';
		$mtoBaseTriOtroItem = '';
		$nomTributoIOtroItem = '';
		$codTipTributoIOtroItem = '';
		$porTriOtroItem = '';
		// fin datos por verificar

		//$mtoPrecioVentaUnitario = $mtoValorUnitario;
		//$mtoValorVentaItem = $mtoValorUnitario*$ctdUnidadItem;//$_POST["mtoValorVentaItem"];
		$mtoValorReferencialUnitario = round($_POST["mtoValorReferencialUnitario"],2);

		//ARCHVIO TRI
		$ideTributo = $_POST["ideTributo"];
		$nomTributo = $_POST["nomTributo"];
		$codTipTributo = $_POST["codTipTributo"];
		$mtoBaseImponible = 0;//$_POST["mtoBaseImponible"];
		$mtoTributo = $_POST["mtoTributo"];

		//$sell_id = $_POST["id"];

		//ARCHVIO LEY
		$codLeyenda = "2001";		

		if ($codLeyenda == "2001")
		{
			$desLeyenda = "BIENES TRANSFERIDOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";//$_POST["desLeyenda"];
		}
		if ($codLeyenda == "2002")
		{
			$desLeyenda = "SERVICIOS PRESTADOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";//$_POST["desLeyenda"];
		}		

		//ARCHIVO ACA
		$ctaBancoNacionDetraccion = "-";
		$codBienDetraccion        = "-";
		$porDetraccion            = 0;
		$mtoDetraccion            = 0;
		$codMedioPago             = "";
		$codPaisCliente           = 'PE';
		$codUbigeoCliente         = $_POST['codUbigeoCliente'];
		$desDireccionCliente      = $_POST['desDireccionCliente'];
		$codPaisEntrega           = "-";
		$codUbigeoEntrega         = "-";
		$desDireccionEntrega      = "-";
	
		$id_boleta_impresa = $conexion->insert_id;
		$TIPO_DOC = $TIPO;
		// $ID_TIPO_DOC = $COMPROBANTE;
		$ID_TIPO_DOC = $id_boleta_impresa;
		//Se agrego campo de texto observacion solo para factura
		$Observacion = $_POST['txtObservacion'];

		//########## CONTENIDO ARCHIVO DET === 32 ITEMS #############################
		$downloadfile2 = "../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".det";

		$filecontent2 = "";

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
					//$sell->discount = 0;

					if(isset($_POST["client_id"]) && $_POST["client_id"]!="")
					{
						$sell->person_id=$_POST["client_id"];
						$s = $sell->add_with_client();
					}
					else
					{
						$s = $sell->add2();
					}
                     // printf($s);
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

					//print "<script>window.location='index.php?view=onesell&id=$s[1]';</script>";
				}
			}
		}

		//fin operaciones

		if($estado == 1)
		{
			#BASE DE DATOS TIPO DOCUMENTO - BOLETA/FACTURA
			#BASE DE DATOS TIPO DOCUMENTO - BOLETA/FACTURA
			$sql_DOC = "insert into factura (
				RUC,
				TIPO,
				SERIE,
				COMPROBANTE,
				EXTRA1
				) values (
				\"$RUC\",
				\"$TIPO\",
				\"$SERIE\",
				\"$COMPROBANTE\",
				\"$s[1]\"
			)";

			#CAPTAR TIPO DE DOCUMENTO: FACTURA y SU ID: OBTENIENDO SERIE Y NRO
			$conexion->query($sql_DOC);

			$id_factura_impresa = $conexion->insert_id;
			$TIPO_DOC = $TIPO;
			$ID_TIPO_DOC = $id_factura_impresa;

			$operations = OperationData::getAllProductsBySellId2($s[1]);

			foreach ($operations as $item)
			{
				//$product  = $item->getProduct();
                if($item->idpaquete=="X"):
				    $product  = $item->getProduct();
				    $cantidad = round($item->q,2);				    
				    $precio_unitario = $item->prec_alt;
				else:
					$product  =$item->getPaquete();
					$cantidad = round($item->q,2);
				    $precio_unitario = $item->prec_alt/$cantidad;
				endif;

				//$cantidad = round($item->q,2);
				//$precio_unitario = $item->prec_alt;
				$descripcion_producto = $product->name;

				$mtoValorVentaItem = round($cantidad * $precio_unitario,2);//$_POST["mtoValorVentaItem"];
				$mtoPrecioVentaUnitario = $precio_unitario;
				$mtoBaseIgvItem = $mtoValorVentaItem;

				$filecontent2 .=$codUnidadMedida.'|'.
								$cantidad.'|'.
								$codProducto."|".
								$codProductoSUNAT."|".
								$descripcion_producto.'|'.
								$precio_unitario.'|'.
								$sumTotTributosItem.'|'.
								$codTriIGV.'|'.
								$mtoIgvItem.'|'.
								$mtoBaseIgvItem.'|'.
								$nomTributoIgvItem.'|'.
								$codTipTributoIgvItem.'|'.
								$tipAfeIGV.'|'.
								$porIgvItem.'|'.
								$codTriISC.'|'.
								$mtoIscItem."|".
								$mtoBaseIscItem."|".
								$nomTributoIscItem."|".
								$codTipTributoIscItem."|".
								$tipSisISC."|".
								$porIscItem."|".
								$codTriOtroItem."|".
								$mtoTriOtroItem."|".
								$mtoBaseTriOtroItem."|".
								$nomTributoIOtroItem."|".
								$codTipTributoIOtroItem."|".
								$porTriOtroItem."|".
								$codTriIcbper."|".
								$mtoTriIcbperItem."|".
								$ctdBolsasTriIcbperItem."|".
								$nomTributoIcbperItem."|".
								$codTipTributoIcbperItem."|".
								$mtoTriIcbperUnidad."|".
								$mtoPrecioVentaUnitario."|".
								$mtoValorVentaItem."|".
								$mtoValorReferencialUnitario."|".PHP_EOL;

				#BASE DE DATOS ARCHIVO .DET
								
				$sql_DET = "insert into det (
				  TIPO_DOC,
				  ID_TIPO_DOC,
				  codUnidadMedida,
				  ctdUnidadItem,
				  codProducto,
				  codProductoSUNAT,
				  desItem,
				  mtoValorUnitario,
				  sumTotTributosItem,
				  codTriIGV,
				  mtoIgvItem,
				  mtoBaseIgvItem,
				  nomTributoIgvItem,
				  codTipTributoIgvItem,
				  tipAfeIGV,
				  porIgvItem,
				  codTriISC,
				  mtoIscItem,
				  mtoBaseIscItem,
				  nomTributoIscItem,
				  codTipTributoIscItem,
				  tipSisISC,
				  porIscItem,
				  codTriOtroItem,
				  mtoTriOtroItem,
				  mtoBaseTriOtroItem,
				  nomTributoIOtroItem,
				  codTipTributoIOtroItem,
				  porTriOtroItem,
				  codTriIcbper,
				  mtoTriIcbperItem,
				  ctdBolsasTriIcbperItem,
				  nomTributoIcbperItem, 
				  codTipTributoIcbperItem,
				  mtoTriIcbperUnidad,
				  mtoPrecioVentaUnitario,
				  mtoValorVentaItem,
				  mtoValorReferencialUnitario
				) values (
				  \"$TIPO_DOC\",
				  \"$ID_TIPO_DOC\",
				  '".$codUnidadMedida."',
				  '".$cantidad."',
				  \"$codProducto\",
				  \"$codProductoSUNAT\",
				  '".$descripcion_producto."',
				  '".$precio_unitario."',
				  \"$sumTotTributosItem\",
				  \"$codTriIGV\",
				  \"$mtoIgvItem\",
				  \"$mtoBaseIgvItem\",
				  \"$nomTributoIgvItem\",
				  \"$codTipTributoIgvItem\",
				  \"$tipAfeIGV\",
				  \"$porIgvItem\",
				  \"$codTriISC\",
				  \"$mtoIscItem\",
				  \"$mtoBaseIscItem\",
				  \"$nomTributoIscItem\",
				  \"$codTipTributoIscItem\",
				  \"$tipSisISC\",
				  \"$porIscItem\",
				  \"$codTriOtroItem\",
				  \"$mtoTriOtroItem\",
				  \"$mtoBaseTriOtroItem\",
				  \"$nomTributoIOtroItem\",
				  \"$codTipTributoIOtroItem\",
				  \"$porTriOtroItem\",
				  \"$codTriIcbper\",
				  \"$mtoTriIcbperItem\",
				  \"$ctdBolsasTriIcbperItem\",
				  \"$nomTributoIcbperItem\",
				  \"$codTipTributoIcbperItem\",
				  \"$mtoTriIcbperUnidad\",
				  \"$mtoPrecioVentaUnitario\",
				  \"$mtoValorVentaItem\",
				  \"$mtoValorReferencialUnitario\"
				)";

				$conexion->query($sql_DET);

				$sumTotValVenta = round($cantidad*$precio_unitario + $sumTotValVenta,2);
				$sumPrecioVenta = round($sumTotValVenta+$sumTotTributos,2);//$_POST["sumPrecioVenta"]; UNID * PREC. UNIT
			}

			$mtoBaseImponible = $sumTotValVenta;//$_POST["mtoBaseImponible"];
                        $dscGlobal = $_POST["discount"]; //ES EL DESCUENTO QUE SE LE HACE AL MONTO TOTAL DE LA FACTURA
			$sumDescTotal = $_POST["sumDescTotal"]+$dscGlobal;
			$sumOtrosCargos = $_POST["sumOtrosCargos"];
			$sumTotalAnticipos = $_POST["sumTotalAnticipos"];
			$sumImpVenta = $sumPrecioVenta-$sumDescTotal+$sumOtrosCargos-$sumTotalAnticipos;//$_POST["sumImpVenta"];  UNID * PREC. UNIT
			$ublVersionId = $_POST["ublVersionId"];
			$customizationId = $_POST["customizationId"];

			#BASE DE DATOS ARCHIVO .CAB
			$sql_CAB = "insert into cab (
				  TIPO_DOC,
				  ID_TIPO_DOC,
				  tipOperacion,
				  fecEmision,
				  horEmision,
				  fecVencimiento,
				  codLocalEmisor,
				  tipDocUsuario,
				  numDocUsuario,
				  rznSocialUsuario,
				  tipMoneda,
				  sumTotTributos,
				  sumTotValVenta,
				  sumPrecioVenta,
				  sumDescTotal,
				  sumOtrosCargos,
				  sumTotalAnticipos,
				  sumImpVenta,
				  ublVersionId,
				  customizationId
				) values (
				  \"$TIPO_DOC\",
				  \"$ID_TIPO_DOC\",
				  \"$tipOperacion\",
				  \"$fecEmision\",
				  \"$horEmision\",
				  \"$fecVencimiento\",
				  \"$codLocalEmisor\",
				  \"$tipDocUsuario\",
				  \"$numDocUsuario\",
				  \"$rznSocialUsuario\",
				  \"$tipMoneda\",
				  \"$sumTotTributos\",
				  \"$sumTotValVenta\",
				  \"$sumPrecioVenta\",
				  \"$sumDescTotal\",
				  \"$sumOtrosCargos\",
				  \"$sumTotalAnticipos\",
				  \"$sumImpVenta\",
				  \"$ublVersionId\",
				  \"$customizationId\"
				)";

			$conexion->query($sql_CAB);

			$ar = fopen($downloadfile2, "a") or die("Error al crear");
			fwrite($ar, $filecontent2);

			//FIN SI EXISTE UN METODO POST "SE ESTAN RECIBIENDO DATOS"

			//########## CONTENIDO ARCHIVO CAB === 21 ITEMS #############################
			$downloadfile="../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".cab";

			$filecontent=
				$tipOperacion."|".
				$fecEmision."|".
				$horEmision."|".
				$fecVencimiento."|".
				$codLocalEmisor."|".
				$tipDocUsuario."|".
				$numDocUsuario."|".
				$rznSocialUsuario."|".
				$tipMoneda."|".
				$sumTotTributos."|".
				$sumTotValVenta."|".
				$sumPrecioVenta."|".
				$sumDescTotal."|".
				$sumOtrosCargos."|".
				$sumTotalAnticipos."|".
				$sumImpVenta."|".
				$ublVersionId."|".
				$customizationId."|";

			$ar = fopen($downloadfile, "a") or die("Error al crear");
			fwrite($ar, $filecontent);
                        
                        //########## CONTENIDO ARCHIVO PAG === #############################
			$downloadfilepag="../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".pag";

			$filecontentpag=
				"CONTADO|0|-|";

			$arpag = fopen($downloadfilepag, "a") or die("Error al crear");
			fwrite($arpag, $filecontentpag);	

			//########## CONTENIDO ARCHIVO TRI === 6 ITEMS #############################
			$downloadfile3 = "../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".tri";

			$filecontent3=
				$ideTributo."|".
				$nomTributo."|".
				$codTipTributo."|".
				$mtoBaseImponible."|".
				$mtoTributo."|";

				$ar = fopen($downloadfile3, "a") or die("Error al crear");
				fwrite($ar, $filecontent3);


				#BASE DE DATOS ARCHIVO .TRI
			$sql_TRI = "insert into tri (
				TIPO_DOC,
			  	ID_TIPO_DOC,
				ideTributo,
				nomTributo,
				codTipTributo,
				mtoBaseImponible,
				mtoTributo
				) values (
				\"$TIPO_DOC\",
				\"$ID_TIPO_DOC\",
				\"$ideTributo\",
				\"$nomTributo\",
				\"$codTipTributo\",
				\"$mtoBaseImponible\",
				\"$mtoTributo\"
				)";

			$conexion->query($sql_TRI);

			//########## CONTENIDO ARCHIVO ACA === 10 ITEMS #############################
			$downloadfile10 = "../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".aca";

			$filecontent10 =
				$ctaBancoNacionDetraccion."|".
				$codBienDetraccion."|".
				$porDetraccion."|".
				$mtoDetraccion."|".
				$codMedioPago."|".
				$codPaisCliente."|".
				$codUbigeoCliente."|".
				$desDireccionCliente."|".
				$codPaisEntrega."|".
				$codUbigeoEntrega."|".
				$desDireccionEntrega."|";

				$ar = fopen($downloadfile10, "a") or die("Error al crear");
				fwrite($ar, $filecontent10);


			#BASE DE DATOS ARCHIVO .TRI
			$sql_CAB = "insert into aca (
				TIPO_DOC,
			  	ID_TIPO_DOC,
				ctaBancoNacionDetraccion,
				codBienDetraccion,
				porDetraccion,
				mtoDetraccion,
				codMedioPago,
				codPaisCliente,
				codUbigeoCliente,
				desDireccionCliente,
				codPaisEntrega,
				codUbigeoEntrega,
				desDireccionEntrega
				) values (
				\"$TIPO_DOC\",
				\"$ID_TIPO_DOC\",
				\"$ctaBancoNacionDetraccion\",
				\"$codBienDetraccion\",
				\"$porDetraccion\",
				\"$mtoDetraccion\",
				\"$codMedioPago\",
				\"$codPaisCliente\",
				\"$codUbigeoCliente\",
				\"$desDireccionCliente\",
				\"$codPaisEntrega\",
				\"$codUbigeoEntrega\",
				\"$desDireccionEntrega\"
				)";

			$conexion->query($sql_CAB);

			//########## CONTENIDO ARCHIVO LEY === 2 ITEMS #############################0

			$downloadfile4 = "../efsigav/sunat_archivos/sfs/DATA/".$RUC."-".$TIPO."-".$SERIE."-".$COMPROBANTE.".ley";

			$filecontent4=
				$codLeyenda."|".
				$desLeyenda."|";

			$ar=fopen($downloadfile4, "a") or die("Error al crear");
			fwrite($ar, $filecontent4);

			#BASE DE DATOS ARCHIVO .LEY
				$sql_LEY = "insert into ley (
				  TIPO_DOC,
				  ID_TIPO_DOC,
				  codLeyenda,
				  desLeyenda
				) values (
				  \"$TIPO_DOC\",
				  \"$ID_TIPO_DOC\",
		  		  \"$codLeyenda\",
		  		  \"$desLeyenda\"
				)";

			$conexion->query($sql_LEY);			

			echo "El archivo de texto se creo correctamente";

			print "<script>window.location='index.php?view=onesell2c&id=$s[1]';</script>";
		}
		else if($estado == 3)
		{
			print "<script>window.location='index.php?view=onesellf&id=$s[1]';</script>";
		}
		else
		{
			print "<script>window.location='index.php?view=proforma&id=$s[1]';</script>";
		}		
	}	
 ?>
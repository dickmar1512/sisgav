<?php 

header('Content-Type: application/vnd.ms-excel');

header('Expires: 0');

header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

header('content-disposition: attachment;filename=COMPROBANTES.xls');

?>
<section class="content">

		<h1>Reporte de Comprobantes</h1>
		
<!--- -->
<div class="row">	
	<div class="col-md-12">
		<?php if(isset($_GET["idcliente"])):?>
			<?php 
				$facturas = array();				
				$facturas = Factura2Data::get_facturas_x_fecha($_GET["ini"],$_GET["fin"], 0, "");
			?>
		<?php if(count($facturas) > 0):?>
		
		<table  border="1">
			<?php 
				$subtotal = 0;
				$descuento = 0;
				$total = 0;
				$nro = 0;
			?>
			<thead>
				<th>N°</th>
				<th>SERIE</th>
				<th>COMPROBANTE</th>
				<th>FECHA EMISIÓN</th>
				<th>N° DOC USUARIO</th>
				<th>RAZÓN SOCIAL</th>
				<th>DETALLE</th>
				<th>PRECIO VENTA</th>
				<th class="boton"></th>
			</thead>
			<?php
				foreach($facturas as $fac)
				{
					$nro++;
			$notacomprobar = $fac->SERIE."-".$fac->COMPROBANTE; 
			$probar = Not_1_2Data::getByIdComprobado($notacomprobar);
					?>
						<tr

						style="background: <?php if (isset($probar)) {
							if ($probar->TIPO_DOC==8) {	echo "#C2FCCF"; }
							if ($probar->TIPO_DOC==7) {	echo "#FFC4C4"; }
						} ?>
						"
						>
							<td><?php echo $nro ?></td>
							<td><?php echo $fac->SERIE ?></td>
							<td><?php echo $fac->COMPROBANTE ?></td>
							<td><?php echo $fac->fecEmision ?></td>
							<td><?php echo $fac->numDocUsuario ?></td>
							<td><?php echo $fac->rznSocialUsuario ?></td>
							<td>
							<?php 
							 if($fac->TIPO=='01'):
								$operations = OperationData::getAllProductsBySellId($fac->EXTRA1);

								foreach ($operations as $ope) 
								{
									$product = ProductData::getById($ope->product_id);
									echo $ope->q."-".$product->name."-".$ope->prec_alt;

									if($ope->descripcion != "")
									{
										echo "|".$ope->descripcion;
									}

									echo "<br>";
								}
							else: 						
								$operation2 = Not_1_2Data::getById($fac->id,'7');
								//print_r($operation2);
									echo $operation2->serieDocModifica." | ".$operation2->descMotivo;
							endif;	
							?>						
						    </td>
							<td>
								<?php 
						$valor = 0;
						 if (isset($probar)) {
									if ($probar->TIPO_DOC==8) {	
										$valor = $fac->sumPrecioVenta  + (float)$probar->sumPrecioVenta;

									}
									elseif ($probar->TIPO_DOC==7) {	$valor=0; }
									else {
											$valor = $fac->sumPrecioVenta;
									}
						$total += $valor;
						}else {
											$valor = $fac->sumPrecioVenta;
											$total += $valor;
									}

						echo 'S/ '.$valor ?></td>
						<td><?php if ($valor == 0) {
							echo "Con Nota de Crédito";
						}else{
							echo "";
						} ?></td>

				<!-- <td style="text-align: center;" class="<?php if($fac->ESTADO==1){ echo "btn-success"; } else{ echo "btn-danger"; } ?>">
					<?php if($fac->ESTADO==1){ echo "GENERADO"; } else{ echo "RECHAZADA"; } ?>
				</td> -->
							
				
				
						</tr>
					<?php
					//$total = $fac->sumPrecioVenta + $total;
				}
				
			?>
		
		</table>
		<h1>Total: S/ <?php echo number_format($total,2,'.',','); ?></h1>

		<?php else:
		// si no hay operaciones
		?>
	<script>
		$("#wellcome").hide();
	</script>
	<div class="jumbotron">
		<h2>No hay facturas</h2>
		<p>El rango de fechas seleccionado no proporciono ningun resultado de facturas.</p>
	</div>
	<?php endif; ?>

	<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>
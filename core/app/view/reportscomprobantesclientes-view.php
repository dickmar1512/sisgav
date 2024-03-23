<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Reporte de Comprobantes por Cliente</h1>
		<form>
			<input type="hidden" name="view" value="reportscomprobantesclientes">
			<div class="row">
				<?php
					$clientes = PersonData::getAll();
				?>
				<div class="col-md-6">
					<select name="idcliente" class="form-control">
						<option value="0">:: Seleccione ::</option>
						<?php
							foreach ($clientes as $cliente) {
								?>
									<option value="<?php echo $cliente->id?>"><?php echo $cliente->name?></option>
								<?php
							}
						?>
					</select>
				</div>
				<div class="col-md-2">
					<input type="submit" class="btn btn-primary btn-block" value="Procesar">
				</div>
			</div>
		</form>
	</div>
</div>
<br>
<!--- -->
<div class="row">	
	<div class="col-md-12">
		<?php if(isset($_GET["idcliente"]) ):?>
			<?php 
				$ventas = array();				
				$ventas = SellData::get_ventas_x_idCliente( $_GET["idcliente"]);
			?>
		<?php if(count($ventas) > 0):?>
			<p align="center">
			<!--a href="index.php?view=excel_comprobantes&idcliente=<?php echo $_GET['idcliente'] ?>"; class="btn btn-success"><i class="fa fa-file-excel-o"></i> Excel</a-->
			</p>
		<table class="table table-bordered">
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
				<th>CLIENTE</th>
				<th>DETALLE</th>
				<th>PRECIO VENTA</th>
			</thead>
			<?php
				foreach($ventas as $ven)
				{
					$nro++;
			$notacomprobar = $ven->SERIE."-".$ven->COMPROBANTE; 
			$probar = Not_1_2Data::getByIdComprobado($notacomprobar);
					?>
						<tr

						style="background: <?php if (isset($probar)) {
							if ($probar->TIPO_DOC==8) {	echo "#C2FCCF"; }
							if ($probar->TIPO_DOC==7) {	echo "#FFC4C4"; }
						} ?>
						"
						>
							<td><?php echo $nro; ?></td>
							<td><?php echo $ven->serie; ?></td>
							<td><?php echo $ven->comprobante; ?></td>
							<td><?php echo $ven->{"10"} ?></td>
							<td><?php echo $ven->numero_documento; ?></td>
							<td><?php echo $ven->name; ?></td>
							<td>
							<?php 
								$operations = OperationData::getAllProductsBySellId($ven->id);

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
							?>						
						</td>
							<td>
								<?php 			

						echo 'S/ '.$ven->total ?></td>
						</tr>
					<?php
					$total += $ven->total;
				}
				
			?>
			<tfoot>
				<td colspan="6"></td>
				<td><?php echo 'S/ '. number_format($total,2,'.',',');?></td>
			</tfoot>
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
	<?php else:?>
	<script>
		$("#wellcome").hide();
	</script>
	<div class="jumbotron">
		<h2>Fecha Incorrectas</h2>
		<p>Puede ser que no selecciono un rango de fechas, o el rango seleccionado es incorrecto.</p>
	</div>

	<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>
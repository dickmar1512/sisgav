<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1><i class="fa fa-file"></i> Reporte de Notas de crédito de Factura</h1>
		<form>
			<input type="hidden" name="view" value="reportsnotascredito">
			<div class="row">
				<?php
					$series = Factura2Data::get_series_notas_credito();
				?>
				<div class="col-md-2 hide">
					<select name="selSerie" class="form-control">
						<option class="hide" value="0">:: Seleccione ::</option>
						<?php
							foreach ($series as $serie) {
								?>
									<option value="<?php echo $serie->SERIE?>"><?php echo $serie->SERIE?></option>
								<?php
							}
						?>
					</select>
				</div>
				<div class="col-md-2">
					<input type="text" name="comprobante" placeholder="N° comprobante" class="form-control">
				</div>
				<div class="col-md-2">
					<input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; } else { echo date("Y-m-d"); }?>" class="form-control">
				</div>
				<div class="col-md-2">
					<input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; } else { echo date("Y-m-d"); } ?>" class="form-control">
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
		<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) && isset($_GET["selSerie"]) && isset($_GET["comprobante"])):?>
			<?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
			<?php 
				$facturas = array();				
				//$facturas = Not_1_2Data::get_notas_credito_factura_x_fecha($_GET["sd"],$_GET["ed"]);
				$facturas = Not_1_2Data::get_notas_credito_factura_x_fecha2($_GET["sd"],$_GET["ed"]);
			?>
		<?php if(count($facturas) > 0):?>
			<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
		<table class="table table-bordered">
			<?php 
				$subtotal = 0;
				$descuento = 0;
				$total = 0;
				$nro = 0;
			?>
			<thead>
				<th>N°</th>
				<th>NOTA</th>
				<th>FECHA EMISIÓN</th>
				<th>DOC MODIFICADO</th>
				<th>N° DOC USUARIO</th>
				<th>RAZÓN SOCIAL</th>
				<th>IMPORTE S/</th>
				<th>ESTADO</th>
				<th>XML</th>
				<th>CDR</th>
				<th class="text-center boton">OPCIONES</th>
			</thead>
			<?php
			 //print_r($facturas);
				foreach($facturas as $fac)
				{
					$nro++;
					?>
						<tr>
							<td><?php echo $nro ?></td>
							<td><?php echo $fac->SERIE."-".$fac->COMPROBANTE ?></td>
							<td><?php echo $fac->fecEmision ?></td>
							<td><?php echo $fac->serieDocModifica ?></td>
							<td><?php echo $fac->numDocUsuario ?></td>
							<td><?php echo $fac->rznSocialUsuario ?></td>
							<td style="text-align:right"><?php echo $fac->sumPrecioVenta ?></td>
							<?php $datosFacSunat = FacturadorData::getByNuemroDocumento($fac->SERIE."-".$fac->COMPROBANTE);  ?>
							<td><?=$datosFacSunat->DES_SITU?></td>
							<td>
								<?php
								if(file_exists($datosFacSunat->URL_XML)){
								?>								 
								<a href="<?=$datosFacSunat->URL_XML?>" target="_blank"><i class="fa fa-download"></i></a>
								<?php }?>
							</td>
							<td>
								<?php
								if(file_exists($datosFacSunat->URL_CDR)){
								?>								 
								<a href="<?=$datosFacSunat->URL_CDR?>" target="_blank"><i class="fa fa-download"></i></a>
								<?php }?>
							</td>
							<td class="text-center boton">
								<?php 
								$nota_credito = Factura2Data::getByID($fac->ID_TIPO_DOC);
								 ?>
								<a href="index.php?view=notacredito&num=<?php echo $nota_credito->SERIE . '-' . $nota_credito->COMPROBANTE ?>" class="btn btn-info btn-xs"><i class="fa fa-file-pdf">Ver</i></a>
							</td>
						</tr>
					<?php
					$total = $fac->sumPrecioVenta + $total;
				}
				
			?>
			<tfoot>
			    <td colspan="6"><h5 style="text-align: right"><b>Total S/:</b></h5></td>
				<td><h5 style="text-align: right"><b><?php echo number_format($total,2,'.',',');?></b></h5></td>
			</tfoot>
		</table>
		<!-- <h1>Total: S/ <?php //echo number_format($total,2,'.',','); ?></h1> -->

		<?php else:
		// si no hay operaciones
		?>
	<script>
		$("#wellcome").hide();
	</script>
	<div class="jumbotron">
		<h2>No hay notas de crédito</h2>
		<p>El rango de fechas seleccionado no proporciono ningun resultado de notas.</p>
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
	<?php endif;?>

	<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>
<script type="text/javascript">
	$('#imprimir').click(function() {
	    	imprimir();
	    });
	function imprimir()
    {
    	$('#imprimir').hide();
      	$('.boton').hide();
      	// $('.logo').hide();
      	window.print();

      	$('#imprimir').show();
      	$('.boton').show(); 
      	// $('.logo').show(); 
    }
</script>
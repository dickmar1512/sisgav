<script type="text/javascript">
	function imprimir()
    {
    	$('#imprimir').hide();
      	$('#div_opciones').hide();
      	// $('.logo').hide();
      	window.print();

      	$('#imprimir').show();
      	$('#div_opciones').show(); 
      	// $('.logo').show(); 
    }
</script>
<?php
	if (isset($_GET['im'])) 
	{
		?>
			<script type="text/javascript">
				imprimir();
			</script>
		<?php
	}

	$product = Boleta2Data::getByNumDoc($_GET["num"]);
	$comp_cab = Not_1_2Data::getById($product->id, 7);
	// $comp_aca = Aca_1_2Data::getById($product->id, 1);
	$detalles = Det_1_2Data::getById($product->id, 7);
	$comp_tri = Tri_1_2Data::getById($product->id, 7);
	$comp_ley = Ley_1_2Data::getById($product->id, 7);

     //print_r($product);
    //print_r($comp_cab );
	// print_r($detalles );
	// print_r($comp_tri );
	// print_r($comp_ley );

	$sell = SellData::getByNroDoc($comp_cab->serieDocModifica);
	//print_r($sell);
    //$mesero = UserData::getById($sell->mesero_id);
    //$cajero= null;
    //$cajero = UserData::getById($sell->user_id);
    $empresa = EmpresaData::getDatos();
?>
<div class="row" style="background: #FFF; margin-top: 0px; padding-top: 0px">
	<div class="col-md-8 col-md-offset-2">
		<div class="row pull-right">
			<br>
			<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
		</div>
		<div class="row" id="ticketera">
			<div class="col-md-8 col-md-offset-2" style="border-right: 1px solid #ccc; border-left: 1px solid #cccc; border: 1px solid #cccc;">
				<table class="table">
					<tr style="text-align: center;">
							<td> 
							<center>
								<img src="plugins/dist/img/logo2.jpg" style="height: 65px; width: 90px" class="img-circle"/>
							</center>
							</td>
					</tr>
                    <tr>
						<td style="font-family: courier new; font-size: 0.7em">
							<center>
							<strong><?php echo $empresa->Emp_RazonSocial ?><br>
							<?php echo $empresa->Emp_Direccion ?><br>
							Tel. <?php echo $empresa->Emp_Telefono ?><br>
							RUC <?php echo $empresa->Emp_Ruc ?></strong><br>
							</center>
							
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center; font-family: courier new; font-size: 0.9em">
							<span class="label-comprobante" style=" font-weight: bold;">NOTA DE CRÉDITO ELECTRÓNICA</span>
							<br>
							<span style=" font-weight: bold;"><?php echo $product->SERIE."-".$product->COMPROBANTE; ?></span>
							<div style=" color: red"><h5><b><?php if($comp_cab->codTipoNota==1){ echo("Anulación en la Operación");} else if($comp_cab->codTipoNota==2){ echo("Anulación por error en el RUC");}  else if($comp_cab->codTipoNota==3){ echo("Correción por error en la descripción");} else if($comp_cab->codTipoNota==4){ echo("Descuento global");} else if($comp_cab->codTipoNota==5){ echo("Descuento por item");} else if($comp_cab->codTipoNota==6){ echo("Devolución total");} else if($comp_cab->codTipoNota==7){ echo("Devolución por item");} ?></b></h5></div>
						</td>
					</tr>
					<tr>
						<td style="font-family: courier new; font-size: 0.7em">	
							<b>Fecha Emision <?=": ".$comp_cab->fecEmision?></b><br>
							<b>DOCUMENTO QUE MODIFICA</b><br>
							<b>Boleta Electrónica: <?php echo $comp_cab->serieDocModifica; ?></b>
							<br>
							<b>Fecha Vencimiento: <?php echo $sell->created_at; ?></b>
							<br>
							<b>DNI: <?php echo ": ".$comp_cab->numDocUsuario; ?>
							<br>
							NOMBRE: <?php echo ": ".$comp_cab->rznSocialUsuario; ?></b>
							<!--MOTIVO <?php echo ": ".$comp_cab->descMotivo; ?>	</b>-->						
						</td>
					</tr>
				</table>				
				<div id="detalle_venta" <?php if(isset($_GET['con'])){ ?> style="display: none" <?php } ?>>
					<table class="table" style="font-family: courier new; font-size: 0.7em">
						<thead class="thead-dark">
							<th>CANT.</th>							
							<th>DESCRIPCION</th>
							<th>P. UNIT.</th>
							<th>P. TOTAL</th>
						</thead>
						<tbody>
							<?php
								$total = 0;
								foreach ($detalles as $det) {
									?>
										<tr>
											<td><b><?php echo $det->ctdUnidadItem; ?></b></td>
											<td><b><?php echo $det->desItem; ?></b></td>
											<td><b><?php echo $det->mtoValorUnitario; ?></b></td>
											<td><b><?php echo $det->mtoValorVentaItem; ?></b></td>
										</tr>
									<?php
									$total = $det->mtoValorVentaItem + $total;
								}
							?>
						</tbody>
					</table>
				</div>
				<div style="text-align: right;">
					<table style="width: 40%">
						<tr></tr>
					</table>
					<table style="font-family: courier new; font-size: 0.7em; float: right;font-weight: bold;">
						<tbody style="text-align: left;">
							<tr>
								<td>OP. GRATUITA</td>
								<td>S/ 0.00</td>
							</tr>
							<tr>
								<td>OP. EXONERADA</td>
								<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td>
							</tr>
							<tr>
								<td>OP. INAFECTA</td>
								<td>S/ 0.00</td>
							</tr>
							<tr>
								<td>OP. GRAVADA</td>
								<td>S/ 0.00</td>
							</tr>
							<tr>
								<td>IGV</td>
								<td>S/ 0.00</td>
							</tr>
							<tr>
								<td>MONTO TOTAL</td>
								<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td>
							</tr>							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div><!--  fin col-md-6 -->

<script>
  $(document).ready(function(){
	    $("#product_code").keydown(function(e){
	        if(e.which==17 || e.which==74 ){
	            e.preventDefault();
	        }else{
	            console.log(e.which);
	        }
	    })

	    $('#imprimir').click(function() {
	    	imprimir();
	    });	    
	});  
</script>
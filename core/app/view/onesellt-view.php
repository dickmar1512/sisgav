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

	$product = Boleta2Data::getByExtra($_GET["id"]);
	$comp_cab = Cab_1_2Data::getById($product->id, 3);
	$comp_aca = Aca_1_2Data::getById($product->id, 3);
	$detalles = Det_1_2Data::getById($product->id, 3);
	$comp_tri = Tri_1_2Data::getById($product->id, 3);
	$comp_ley = Ley_1_2Data::getById($product->id, 3);

	$sell = SellData::getById($product->EXTRA1);
    //$mesero = UserData::getById($sell->mesero_id);

    $operations = OperationData::getAllProductsBySellId($_GET["id"]);

    $empresa = EmpresaData::getDatos();
?>
<div class="row" style="background: #FFF; margin-top: 0px; padding-top: 0px">
	<div class="col-md-8 col-md-offset-2">
		<div class="row pull-right">
			<br>
			<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
		</div>
		<div class="row" id="ticketera">
			<div class="col-md-8 col-md-offset-1" style="border-left: 1px solid #ccc; border-left: 1px solid #cccc; border: 1px solid #cccc;">
				<table class="table"  >
					<tr style="text-align: center;">
						<td class="logo">
							<img src="plugins/dist/img/logo2.jpg" style="height: 65px; width: 90px" class="img-circle">
						</td>
					</tr>
					<tr style="font-family: courier new; font-size: 0.7em">	
						<td>
							<center><b>
							<?php echo $empresa->Emp_RazonSocial;?><br>
							<?php echo $empresa->Emp_Direccion; ?><br>
							Tel. <?php echo $empresa->Emp_Telefono; ?><br>
							Iquitos - Loreto - Perú
							</b>
						  </center>
						  </th>
					</tr>
					<tr style="font-family: courier new; font-size: 0.9em; border:1">
						<td class="text-center">							
							<b>RUC <?php echo $empresa->Emp_Ruc ?><br>
							<img src="img/bol.png" style="width: 150px;"><br>
							<?php echo $product->SERIE."-".$product->COMPROBANTE; ?></b>
						</td>
					</tr>
					<tr>
						<td style="font-family: courier new; font-size: 0.7em">
							<b>DNI: <?php echo ": ".$comp_cab->numDocUsuario; ?>
							<br>
							NOMBRE: <?php echo ": ".$comp_cab->rznSocialUsuario; ?>
							<br>
							<?php $person = PersonData::getById($sell->person_id); ?>
							DIRECCIÓN <?php echo ": ".$comp_aca->desDireccionCliente; ?>
							<br>
							FEC. EMIS. <?php echo ": ".$comp_cab->fecEmision." | ".$comp_cab->horEmision; ?>
							</b>
						</td>
					</tr>
				</table>
				<div id="detalle_venta" <?php if(isset($_GET['con'])){ ?> style="display: none" <?php } ?>>
					<table class="table" style="max-width: 900px; font-family: courier new; font-size: 0.7em">
						<thead class="thead-dark">
						<tr>
							<th style="padding: 0px; text-align: center">CANT.</th>							
							<th style="padding: 0px;  text-align: center">DESCRIP.</th>
							<th style="padding: 0px;  text-align: center">P. UNIT.</th>
							<th style="padding: 0px;  text-align: center">P. TOTAL</th>
						</tr>	
						</thead>
						<tbody>
							<?php
								$total = 0;
								foreach ($operations as $ope) 
								{
									$product = ProductData::getById($ope->product_id);
									$subtotal = $ope->q*$ope->prec_alt;

									?>
										<tr>
											<?php
												$unidad = UnidadMedidaData::getById($product->unit);
											 ?>
											<td  style="padding: 0px; text-align: center;font-family: courier new; font-size: 0.7em" align="center">
											<?php echo $ope->q." ".$unidad->sigla; ?></td>
											<td  style="padding: 0px; text-align: left; font-family: courier new; font-size: 0.7em">
												&nbsp;&nbsp;<?php echo $product->name; ?>
												<?php
													if($ope->descripcion != "")
													{
														echo " | ".$ope->descripcion;
													}
												?>
											</td>
											<td  style="padding: 0px; text-align: center; font-family: courier new; font-size: 0.7em"><?php echo $ope->prec_alt; ?></td>
											<td  style="padding: 0px; text-align: center, font-family: courier new; font-size: 0.7em"><?php echo number_format($subtotal, 2, '.', ','); ?></td>
										</tr>
									<?php
									$total = $subtotal + $total;
								}
							?>
						</tbody>
					</table>
				</div>
				<div style="text-align: right;">
					<table style="width: 40%">
						<tr></tr>
					</table>
					<table style="width: 60%; float: right;">
						<tbody style="text-align: left;">
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>Op. gratuita</td>
								<td>S/ 0.00</td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>Op. exonerada</td>
								<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>Op. inafecta</td>
								<td>S/ 0.00</td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>OP. gravada</td>
								<td>S/ 0.00</td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>IGV</td>
								<td>S/ 0.00</td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td><b>MONTO TOTAL</b></td>
								<td><b>S/ <?php echo number_format($total, 2, '.', ','); ?></b></td>
							</tr>
						    <tr style="font-family: courier new; font-size: 0.7em">
								<td>EFECTIVO</td>
								<td>S/ <?php echo number_format($sell2->cash,2,'.',','); ?></td>
							</tr>
							<tr style="font-family: courier new; font-size: 0.7em">
								<td>VUELTO</td>
								<td>S/ <?php echo number_format($sell2->cash - $total,2,'.',','); ?></td>
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
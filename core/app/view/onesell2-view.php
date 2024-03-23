<style type="text/css">
#doc{
   border:3px solid black;
   border-radius:22px;
}
</style>
<?php
	$product = Factura2Data::getByExtra($_GET["id"]);

	$comp_cab = Cab_1_2Data::getById($product->id, 1);
	$comp_aca = Aca_1_2Data::getById($product->id, 1);

	$detalles = Det_1_2Data::getById($product->id, 1);
	$comp_tri = Tri_1_2Data::getById($product->id, 1);
	$comp_ley = Ley_1_2Data::getById($product->id, 1);

	$sell = SellData::getById($product->EXTRA1);
    
	$operations = OperationData::getAllProductsBySellId($_GET["id"]);

    $cajero= null;
	$cajero = UserData::getById($sell->user_id)->username;
    $empresa = EmpresaData::getDatos();
?>
<div class="row" style="margin-top: 0px; padding-top: 0px; background: #fff">
	<div class="col-md-10 col-md-offset-1">
		<div class="row ">
			<div class="row pull-right">
				<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
			</div>
		</div>
		<div>
			<div class="para_imprimir" style="font-family: courier new; font-size: 0.9em;">
				<table class="table" style="margin-top: 0px; padding-top: 0px; max-width: 700px">
					<tr style="margin-top: 0px; padding-top: 0px">
						<td style="text-align: center; width: 200px; margin-top: -5px; padding-top: 0px">
							<img src="./plugins/dist/img/logo.png" style="height: 100px; width: 100%"><br>
						</td>
						<td style="text-align: center; color: green; margin-top: 0px; padding-top: 0px; width: 420px">
							<h5 style="margin-top: 0px; padding-top: 0px"><b><?php echo $empresa->Emp_RazonSocial ?></b></h5>
							<h6><b><?php echo $empresa->Emp_Descripcion ?></b></h6>
							<p style="margin: 2px;"><?php echo $empresa->Emp_Direccion ?></p>
							<p style="margin: 2px;">Telf.: <?php echo $empresa->Emp_Telefono?></p>
							<p style="margin: 2px;">Correo.: <?php echo $empresa->Emp_Celular?></p>
						</td>
						<td style="text-align: center; width: 200px; margin-top: 0px; padding-top: 0px">
							<div class="row" id="doc" >
							<h6><b>RUC: <?php echo $empresa->Emp_Ruc ?></b></h6>
							<div style="font-weight: bold; font-size: 12px">
								<img src="img/fac.png" style="width: 90%; height: 70%">
							</div>
							<h6><?php echo $sell->serie."-".$sell->comprobante; ?></h6>
							</div>
				  		</td>
					</tr>
				</table>
				<table class="table-bordered" style="margin-top: 0px; padding-top: 0px; max-width: 700px">
					<tr>
					    <td>
							<b>RUC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b>
						</td>	 
						<td colspan="4">
							<?php echo $comp_cab->numDocUsuario; ?>
				    	</td>
					</tr>
					<tr>	
				    	<td>
							<b>Razon Social :</b> 
						</td>
						<td colspan="4">
							<?php echo $comp_cab->rznSocialUsuario; ?>
				    	</td>
				    </tr>
				    <tr>
				        <td>
							<b>Dirección&nbsp;&nbsp;&nbsp;&nbsp;:</b>
						</td>
						<td colspan="4">
							<?php echo $comp_aca->desDireccionCliente; ?>
				    	</td>
					</tr>
					<tr>	
				        <td>
				        	<b>Fecha Emision:</b>
						</td>
						<td colspan="4">
							<?php echo $comp_cab->fecEmision." | ".$comp_cab->horEmision; ?>
				        </td>
					</tr>
				</table>
				<br>
				<table class="table-bordered" style="max-width: 700px">
					<thead class="thead-dark">
						<tr>
							<th style="width: 50px">CANTIDAD</th>
							<th style="width: 50px">&nbsp;&nbsp;CODIGO</th>
							<th style="width: 450px">&nbsp;&nbsp;DESCRIPCION</th>
							<th style="width: 200px">PRECIO UNIT.</th>
							<th style="width: 200px">IMPORTE</th>
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
										<td><?php echo $ope->q; ?></td>
										<td>&nbsp;&nbsp;<?php echo $product->barcode; ?></td>
										<td>&nbsp;&nbsp;<?php echo $product->name; ?></td>
										<td><?php echo $ope->prec_alt; ?></td>
										<td><?php echo number_format($subtotal, 2, '.', ','); ?></td>
									</tr>
								<?php
								$total = $subtotal + $total;
								$numLetra = NumeroLetras::convertir($total);
							}
						?>
					</tbody>
				</table>
				<br>
				<table class="table table-bordered"  style="max-width: 700px; margin-bottom: 0px; padding-bottom:  0px ">
					<thead style="align-content: center; text-align: center; border-style: none">
						<th style="align-content: center; text-align: left; width:70%">
						<label for="inputEmail1" class="col-lg-2 control-label">Observacion</label>
						<textarea name="txtObservacion" id="txtObservacion" style="width:100%" rows="4"><?=$sell->observacion?></textarea>												
						&nbsp;<br>
							<?php echo "Usuario:".$cajero;?>
						</th>	
						<th>
							<table>
								<tr>
									<td style="min-width: 240px;">OPER. GRATUITA</td>
									<td style="min-width: 80px; text-align: right;">0.00</td></tr>
								<tr>
									<td>OPER. EXONERADA</td>
									<td style="text-align: right;"><?php echo number_format($total, 2, '.', ','); ?></td></tr>
								<tr>
									<td>OPER. INAFECTA</td>
									<td style="text-align: right;">0.00</td></tr>
								<tr>
									<td>OPER. GRAVADA</td>
									<td style="text-align: right;">0.00</td></tr>
								<tr>
									<td>IGV</td>
									<td style="text-align: right;">0.00</td></tr>
								<tr>
									<td>MONTO TOTAL</td>
									<td style="text-align: right;"><?php echo number_format($total, 2, '.', ','); ?></td>
								</tr>
								<tr>
									<td colspan="2"><b><?php echo "Son: ".$numLetra; ?></b></td>
								</tr>
							</table>
						</th>
					</thead>
				</table>
				<br>
				<table class="table" style="max-width: 700px; margin-bottom: 0px; padding-bottom:  0px ">
					<tr>
						<th><div id="qrcodigo"></div></th>
						<th style="vertical-align: bottom;">
							<table>
								<tr scope="col">
									<td style="font-size: 10px; text-align: center;">
										<b><?php echo $comp_ley->desLeyenda; ?></b>
									</td>
								</tr>
								<tr>
									<td style="font-size: 10px; text-align: center;">Consulte y/o descargue su comprobante electronico en www.sunat.gob.pe, utilizando su clave SOL</td>
								</tr>
								<tr>
									<td style="font-size: 10px; text-align: center;"><p>Autorizado para ser emisor electrónico mediante la Resolución de Superintendencia N° 155-2017</p></td>
								</tr>
							</table>
						</th>
					</tr>
				</table>
				<br>				
				<table class="table-bordered" style="max-width: 700px; margin-bottom: 0px; padding-bottom:  0px ">
					<tr>
						<th style="font-size: 10px; text-align: left;" colspan="5">
						BBVA: Número de cuenta: 0011-0057-0277275849 | 	CCI: 011-057-000277275849-72
						</th>
					</tr>
					<tr>
						<th style="font-size: 10px; text-align: left;">
						BCP SOLES: Número de cuenta: 39005904170079 | CCI: 00239010590417007935
						</th>
					</tr>
					<tr>
						<th style="font-size: 10px; text-align: left;">
						INTERBANK: Número de cuenta: 7403343301426 | 00374001334330142682
						</th>
					</tr>
				</table>		
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
	    	$('#imprimir').hide();
	      	$('#div_opciones').hide();
	      	$('.logo').hide();
			$('.main-footer').hide();
	      	window.print();

	      	$('#imprimir').show();
	      	$('#div_opciones').show(); 
	      	$('.logo').show();
			$('.main-footer').show();
	    });
	});
</script>
<script>
	$("#qrcodigo").qrcode({
		render:'canvas',
		size:80,
		color:'#3A3',
		ecLevel: 'L',
		text:'<?php echo $empresa->Emp_Ruc."|01|".$product->SERIE."-".$product->COMPROBANTE."|0.00|".$totalConDesc."|".$comp_cab->fecEmision."|"?>'
	});
</script>
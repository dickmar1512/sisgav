<?php
	$product = Boleta2Data::getByExtra($_GET["id"]);

	$comp_cab = Cab_1_2Data::getById($product->id, 3);
	$comp_aca = Aca_1_2Data::getById($product->id, 3);

	$detalles = Det_1_2Data::getById($product->id, 3);
	$comp_tri = Tri_1_2Data::getById($product->id, 3);
	$comp_ley = Ley_1_2Data::getById($product->id, 3);

	$sell = SellData::getById($product->EXTRA1);

	$operations = OperationData::getAllProductsBySellId($_GET["id"]);

    //$mesero = UserData::getById($sell->mesero_id);
    $cajero= null;    
    $cajero = UserData::getById($sell->user_id)->username;
    $empresa = EmpresaData::getDatos();
?>
<center>
<div class="row" style="margin-top: 0px; padding-top: 0px; background: #fff">
	<div class="col-md-10 col-md-offset-1">
		<div class="row ">
			<div class="row pull-right">
				<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
			</div>
		</div>
		<div class="row">
			<div class="para_imprimir">
				<br>
				<table>
					<tr>
						<td style="text-align: center;">
				      		<img src="plugins/dist/img/logo2.jpg" style="height: 78px; width: 108px"/>
						</td>
					</tr>
					<tr>	
						<td style="text-align: center; font-family: courier new; font-size: 0.7em">
								<center><b><?php echo $empresa->Emp_RazonSocial ?><br>
								   <?php echo $empresa->Emp_Direccion ?><br>
								   Telf.: <?php echo $empresa->Emp_Telefono?><br>
								   Correo.: <?php echo $empresa->Emp_Celular?><br>
								   Iquitos - Loreto - Perú</b></center>
						</td>
					</tr>
					<tr>	
						<td style="text-align: center; font-family: courier new; font-size: 0.9em">
					      	<b>RUC:<?php echo $empresa->Emp_Ruc ?></b>
					      	<div style=" color: #FFF">
					      		<img src="img/bol.png" style="width: 50%; height: 20%">
					      	</div>
					      	<b><?php echo $product->SERIE."-".$product->COMPROBANTE; ?></b>
				  		</td>
					</tr>
				</table>
				<table style="font-family: courier new; font-size: 0.7em">
					<tr>
					    <td>
							<b>DNI</b> <?php echo ": ".$comp_cab->numDocUsuario; ?>
				    	</td>
				    </tr>
				    <tr>	
				    	<td>
							<b>NOMBRE</b> <?php echo ": ".$comp_cab->rznSocialUsuario; ?>
				    	</td>
				    </tr>
				    <tr>
				        <td>
							<b>Dirección</b> <?php echo ": ".$comp_aca->desDireccionCliente; ?>
				    	</td>
				    </tr>
				    <tr>	
				        <td>
				        	<b> FEC. EMIS.</b> <?php echo ": ".$comp_cab->fecEmision." | ".$comp_cab->horEmision; ?>
				        </td>
					</tr>
				</table>
				<table class="table-bordered" style="margin-top: 0px; padding-top: 0px;">
					<thead class="thead-dark">
						<th style="font-family: courier new; font-size: 0.7em">CANT.</th>
						<th style="font-family: courier new; font-size: 0.7em">UMD</th>
						<th style="font-family: courier new; font-size: 0.7em">&nbsp;&nbsp;DESCRIPCION</th>
						<th style="font-family: courier new; font-size: 0.7em">P. UNIT.</th>
						<th style="font-family: courier new; font-size: 0.7em">IMP. S/</th>
					</thead>
					<tbody>
						<?php
							$total = 0;
							foreach ($detalles as $det) 
							{   
								if(substr($det->desItem,0,3)!="KIT"):
									$unid = ProductData::getByName($det->desItem);
									$sigla = UnidadMedidaData::getById($unid->unit)->sigla;

							    else:
                                    $sigla="UND";
							    endif;	
								?>
									<tr>
											<td class="text-center" style="font-family: courier new; font-size: 0.7em"><b><?php echo $det->ctdUnidadItem;?></b></td>
											<td class="text-center" style="font-family: courier new; font-size: 0.7em"><b><?php echo $sigla;?></b></td>
											<td style="font-family: courier new; font-size: 0.7em"><b><?php echo $det->desItem; ?></b></td>
											<td style="font-family: courier new; font-size: 0.7em"><b><?php echo $det->mtoValorUnitario; ?></b></td>
											<td style="font-family: courier new; font-size: 0.7em"><b><?php echo $det->mtoValorVentaItem; ?></b></td>
										</tr>

								<?php
								$total = $det->mtoValorVentaItem + $total;
									$totalConDesc= $total-$comp_cab->sumDescTotal;
									//$numLetra = NumLetras::convertirNumeroLetra($totalConDesc);
									$numLetra = NumeroLetras::convertir($totalConDesc);
							}
						?>
					</tbody>
				</table>
				<table class="table-bordered"  style="margin-bottom: 0px; padding-bottom:  0px;font-family: courier new; font-size: 0.7em ">				       				
					<thead>	
					    <tr>
							<td  colspan="2" style="text-align:left;">
							<b><?php echo $numLetra;?></b>
							</td>
						</tr>						
						<tr>
						<th>
							&nbsp;<br><br><br>
							<?php echo "Usuario:".$cajero;?>
						</th>	
						<th>
							<table>
								<tr>
									<td>SUB TOTAL</td>
									<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td></tr>
								<tr>
									<td>DESCUENTO</td>
									<td><?=$comp_cab->sumDescTotal?></td></tr>
								<tr>
									<td>MONTO TOTAL</td>
									<td>S/ <?php echo number_format($totalConDesc, 2, '.', ','); ?></td>
								</tr>				
							</table>
						</th>
						</tr>
						<tr cellspacing="0" cellpading="0">
							<th colspan="2">
								<table style=" margin-bottom: 0px; padding-bottom:  0px; text-align: center; ">
									<tr scope="col">
										<td>
											<div id="qrcodigo"></div>
										</td>
										<td>
											<table>
											<tr><td><b><?php echo $comp_ley->desLeyenda; ?></b></td></tr>
											<tr><td>Consulte y/o descargue su comprobante electronico en www.sunat.gob.pe, utilizando su clave SOL</td></tr>
											<tr><td><p>Autorizado para ser emisor electrónico mediante la Resolución de Superintendencia N° 155-2017</p></td></tr>
										    </table>
									    </td>
							        </tr>
							    </table>
						    </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div><!--  fin col-md-6 -->
</center>
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
	      	window.print();

	      	$('#imprimir').show();
	      	$('#div_opciones').show(); 
	      	$('.logo').show(); 
	    });
	});
</script>
<script>
	$("#qrcodigo").qrcode({
		render:'canvas',
		size:80,
		color:'#3A3',
		ecLevel: 'L',
		text:'<?php echo $empresa->Emp_Ruc."|03|".$product->SERIE."-".$product->COMPROBANTE."|0.00|".$total."|".$comp_cab->fecEmision."|"?>'
	});
</script>
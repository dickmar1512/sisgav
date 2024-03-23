<?php
	

	$product = Factura2Data::getByExtra($_GET["id"]);
	$comp_cab = Cab_1_2Data::getById($product->id, 1);
	$comp_aca = Aca_1_2Data::getById($product->id, 1);
	$detalles = Det_1_2Data::getById($product->id, 1);
	$comp_tri = Tri_1_2Data::getById($product->id, 1);
	$comp_ley = Ley_1_2Data::getById($product->id, 1);

	$sell = SellData::getById($product->EXTRA1);
    //$mesero = UserData::getById($sell->mesero_id);

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
			<div class="para_imprimir">
				<table style="margin-top: 0px; padding-top: 0px">
					    <tr>
							<td> 
							<center>
								<img src="plugins/dist/img/logo2.jpg" style="height: 100px; width: 100px"/>
							</center>
							</td>
						</tr>
						<tr>
						 <td style="font-family: courier new; font-size: 0.7em">
						 	<center>
							<b><?php echo $empresa->Emp_RazonSocial ?></b>
                            </center>
                        </td>
						</tr>
						<tr>  
						   <td style="font-family: courier new; font-size: 0.7em"> 	
					      	<center><b><?php echo $empresa->Emp_Direccion ?></b></center>
					       </td>
					    </tr>
					    <tr>  	
					      	<td style="font-family: courier new; font-size: 0.7em">
							  <center>
							  <b>Telf.: <?php echo $empresa->Emp_Telefono?></b>
							  </center>
							</td>
					    </tr>
					    <tr>
					      	<td style="font-family: courier new; font-size: 0.7em">
							  <center><b>Correo.: <?php echo $empresa->Emp_Celular?></b></center>
							</td>
					     </tr>
					     <tr>
					      	<td style="font-family: courier new; font-size: 0.9em">
							  <center><b>Iquitos-Loreto-Perú</b></center>
							</td>
					     </tr> 
						<tr>
						   <td style="font-family: courier new; font-size: 0.7em">     
							<center>
							<b>-----------------------------------------</b>
							</center>
						   </td>
						</tr>	
					    <tr> 
							<td style="font-family: courier new; font-size: 0.9em;">
						      	<center><b>RUC:<?=$empresa->Emp_Ruc?></b>	
								  <div style=" color: #FFF">				      		
								  <img src="img/fac.png" style="width: 46%; height: 28%">
								  </div>
						      	 <b><?php echo $product->SERIE." - ".$product->COMPROBANTE; ?></b>
								  </center>
					  		</td>
					    </tr>
					    <tr>
						  <td style="font-family: courier new; font-size: 0.7em">
						  <center>
						    <b>----------------------------------------</b>
						  </center>
						  </td>
						</tr>
				</table>
				<table style="margin-top: 0px; padding-top: 0px">
					<tr>
					    <td style="font-family: courier new; font-size: 0.7em">
							<b>RUC<?php echo ": ".$comp_cab->numDocUsuario; ?><br>
							Razón Social<?php echo ": ".$comp_cab->rznSocialUsuario; ?><br>
							Dirección <?php echo ": ".$comp_aca->desDireccionCliente; ?><br>
				        	FEC. EMIS. <?php echo ": ".$comp_cab->fecEmision." | ".$comp_cab->horEmision; ?></b>
				        </td>
					</tr>
					<tr>
						<td style="font-family: courier new; font-size: 0.7em">
						  <center>
						  <b>-----------------------------------------</b>
						  </center>
						</td>
					</tr>
				</table>
				<table style="margin-top: 0px; padding-top: 0px">
					<thead class="thead-dark">
					<tr>	
						<th style="font-family: courier new; font-size: 0.7em">CANT.</th>
						<th style="font-family: courier new; font-size: 0.7em">UMD</th>
						<th style="font-family: courier new; font-size: 0.7em">&nbsp;&nbsp;DESCRIPCION
						</th>
						<th style="font-family: courier new; font-size: 0.7em">P. UNIT.</th>
						<th style="font-family: courier new; font-size: 0.7em">IMP. S/</th>
					</tr>	
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
				<table style="margin-top: 0px; padding-top: 0px">
					<thead style="align-content: center; text-align: center; border-style: none">
					<tr>
						  <td style="font-family: courier new; font-size: 0.7em" colspan="2">
						  <center>
						  <b>-----------------------------------------</b>
						  </center>
						  </td>
					</tr>
					<tr>
						<td  colspan="2" style="font-family: courier new; font-size: 0.7em; text-align: left;">
						<b><?php echo $numLetra;?></b>
						</td>
					</tr>
					<tr>
						  <td style="font-family: courier new; font-size: 0.7em" colspan="2">
						  <center>
						  <b>-----------------------------------------</b>
						  </center>
						  </td>
					</tr>
					<tr>
						<th style="width:50%;font-family: courier new; font-size: 0.7em">
						   	<br><br><br> <br><br>
							<?php echo "Usuario:".$cajero;?>
						</th> 	
						<th>
							<table>								
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">SUB TOTAL
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/<?=number_format($total, 2, '.', ',')?>						
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">DESCUENTO
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/<?=number_format($comp_cab->sumDescTotal, 2, '.', ','); ?>	
									</td>
								</tr>								
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">TOTAL
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/<?=number_format($totalConDesc, 2, '.', ','); ?>				
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">OP. GRATUITA
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/0.00</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">OP. EXONERADA
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/<?=number_format($total, 2, '.', ',')?>						
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">OP. INAFECTA
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/0.00
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">OP. GRAVADA
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/0.00
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">ICBPER
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/0.00
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">I.G.V
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/0.00
									</td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">IMP. TOTAL
									</td>
									<td style="font-family: courier new; font-size: 0.7em">S/<?=number_format($totalConDesc, 2, '.', ','); ?>				
									</td>
								</tr>				
							</table>
					    </th>
					</tr>
					<tr>
						  <td style="font-family: courier new; font-size: 0.7em" colspan="2">
							  <center>
							  <b>-----------------------------------------</b>
							  </center>
						  </td>
					</tr>
					</thead>
				</table>
				<table style="margin-top: 0px; padding-top: 0px">
					<thead>
					<tr>
					    <th>
						   <div id="qrcodigo"></div>
						</th>
						<th>
						    <table style="margin-top: 0px; padding-top: 0px">
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">
								    <center><b><?php echo $comp_ley->desLeyenda; ?></b></center>
								    </td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">
								    <center><b>Consulte y/o descargue su comprobante electronico en www.sunat.gob.pe, utilizando su clave SOL</b></center>
								    </td>
								</tr>
								<tr>
									<td style="font-family: courier new; font-size: 0.7em">
								      <center><b>Autorizado para ser emisor electrónico mediante la Resolución de Superintendencia N° 155-2017</b></center>
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

<!--<script>
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
</script>-->
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
		text:'<?php echo $empresa->Emp_Ruc."|01|".$product->SERIE."-".$product->COMPROBANTE."|0.00|".$totalConDesc."|".$comp_cab->fecEmision."|"?>'
	});
</script>
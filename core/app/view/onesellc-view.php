<style type="text/css">
#doc{
   border:3px solid black;
   border-radius:22px;
}
</style>
<?php
	$product = Boleta2Data::getByExtra($_GET["id"]);

	$comp_cab = Cab_1_2Data::getById($product->id, 3);
	$comp_aca = Aca_1_2Data::getById($product->id, 3);

	$detalles = Det_1_2Data::getById($product->id, 3);
	$comp_tri = Tri_1_2Data::getById($product->id, 3);
	$comp_ley = Ley_1_2Data::getById($product->id, 3);

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
		<div class="row">
			<div class="para_imprimir" style="font-family: courier new; font-size: 0.9em;">
				<br>
						<table class="table" style="margin-top: 0px; padding-top: 0px; max-width: 700px">
							<tr style="margin-top: 0px; padding-top: 0px">
								<td style="text-align: center; width: 200px; margin-top: -5px; padding-top: 0px">
								<img src="./plugins/dist/img/logo.png" style="height: 100px; width: 100%">
								</td>
								<td style="text-align: center; margin-top: 0px; padding-top: 0px; width: 420px">
									<h4 style="margin-top: 0px; padding-top: 0px"><b><?php echo $empresa->Emp_RazonSocial ?></b></h4>
									<h6><b><?php echo $empresa->Emp_Descripcion ?></b></h6>
									<p style="margin: 2px;"><?php echo $empresa->Emp_Direccion ?></p>
									<p style="margin: 2px;">Telf.: <?php echo $empresa->Emp_Telefono?></p>
									<p style="margin: 2px;">Correo.: <?php echo $empresa->Emp_Celular?></p>
								</td>
								<td style="text-align: center; width: 200px; margin-top: 0px; padding-top: 0px">
									<div class="row" id="doc" >
										<h6><b>RUC: <?php echo $empresa->Emp_Ruc ?></b></h6>
										<div style="font-weight: bold; font-size: 12px">
										<img src="img/bol.png" style="width: 90%; height: 70%">
										</div>
										<h6><?php echo $sell->serie."-".$sell->comprobante; ?></h6>
									</div>
								</td>
							</tr>
						</table>
						<table class="table table-bordered" style="max-width: 700px">
							<tr>
								<td>
									<b>DNI</b> <?php echo ": ".$comp_cab->numDocUsuario; ?>
								</td>
								<td>
									<b>Nombre</b> <?php echo ": ".$comp_cab->rznSocialUsuario; ?>
								</td>
							</tr>
							<tr>
								<td>
									<b>Dirección</b> <?php echo ": ".$comp_aca->desDireccionCliente; ?>
								</td>
								<td>
									<b> FEC. EMIS.</b> <?php echo ": ".$comp_cab->fecEmision." | ".$comp_cab->horEmision; ?>
								</td>
							</tr>
						</table>
						<table class="table-bordered" style="max-width: 700px">
							<thead class="thead-dark">
								<th style="width: 50px">CANTIDAD</th>
								<th style="width: 50px">&nbsp;&nbsp;CODIGO</th>
								<th style="width: 450px">&nbsp;&nbsp;DESCRIPCION</th>
								<th style="width: 200px">PRECIO UNIT.</th>
								<th style="width: 200px">IMPORTE</th>
							</thead>
							<tbody>
								<?php
								foreach ($detalles as $det) 
								{		
								    // if(substr($det->desItem,0,3)!="KIT"):
									// 	$unid = ProductData::getByName($det->desItem);
									// 	$sigla = UnidadMedidaData::getById($unid->unit)->sigla;
								    // else:
	                                    $sigla="UND";
								    //endif;						
									?>
										<tr>
											<td class="text-center"><b><?php echo $det->ctdUnidadItem;?></b></td>
											<td class="text-center"><b><?php echo $sigla;?></b></td>
											<td><b><?php echo $det->desItem; ?></b></td>
											<td><b><?php echo $det->mtoValorUnitario; ?></b></td>
											<td><b><?php echo $det->mtoValorVentaItem; ?></b></td>
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
						<br>
						<table class="table table-bordered"  style="max-width: 700px; margin-bottom: 0px; padding-bottom:  0px ">
							<thead style="align-content: center; text-align: center; border-style: none">
								<th style="align-content: center; text-align: center; width:70%">
								<div id="qrcodigo"></div>
								&nbsp;<br>
									<?php echo "Usuario:".$cajero;?>
								</th>	
								<th>
									<table>
										<tr>
											<td style="min-width: 240px;">OPER. GRATUITA</td>
											<td style="min-width: 80px">S/ 0.00</td></tr>
										<tr>
											<td>OPER. EXONERADA</td>
											<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td></tr>
										<tr>
											<td>OPER. INAFECTA</td>
											<td>S/ 0.00</td></tr>
										<tr>
											<td>OPER. GRAVADA</td>
											<td>S/ 0.00</td></tr>
										<tr>
											<td>IGV</td>
											<td>S/ 0.00</td></tr>
										<tr>
											<td>MONTO TOTAL</td>
											<td>S/ <?php echo number_format($total, 2, '.', ','); ?></td>
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
							    <th style="font-size: 10px; text-align: left;">
						          INTERBANK: Número de cuenta Soles: 740-3005786280 | CCI : 003-740-003005786280-84
								</th>
							</tr>
						</table>	
					<!-- </div>	 -->
				<!-- </div> -->
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
		text:'<?php echo $empresa->Emp_Ruc."|03|".$product->SERIE."-".$product->COMPROBANTE."|0.00|".$total."|".$comp_cab->fecEmision."|"?>'
	});
</script>
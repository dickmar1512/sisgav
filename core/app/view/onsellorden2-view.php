<?php
	$sell = SellData::getById($_GET["id"]);
	$cliente = PersonData::getById($sell->person_id);	
	$product = OperationData::getAllProductsBySellId($sell->id);
    $cajero= null;    
    $cajero = UserData::getById($sell->user_id)->username;
    $empresa = EmpresaData::getDatos();
?>
<center>
<div class="row" style="margin-top: 0px; padding-top: 0px; background: #fff;">
	<div class="col-md-10 col-md-offset-1">
		<div class="row ">
			<div class="row pull-right">
				<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
			</div>
		</div>
		<div>
			<div class="para_imprimir">
				<div>
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
										  NOTA DE VENTA
										</div>
										<h6><?php echo $sell->serie."-".$sell->comprobante; ?></h6>
									</div>
								</td>
							</tr>
					</table>
					<table class="table table-bordered" style="max-width: 700px">
						<tr>
							<td>
									<b>DNI</b>
					    	</td>
							<td>
								<b><?php echo ": ".$cliente->numero_documento; ?></b>
							</td>
						</tr>
						<tr>	
					        <td>
					        		<b>NOMBRE</b>
					    	</td>
					        <td>
					        	<b><?php echo ":".$cliente->name." ".$cliente->lastname; ?></b>
					        </td>
					    </tr> 
					    <tr>
					        <td>
							<b>FEC. EMIS.</b>
					        </td>
					        <td>
					        	<b><?php
								$fecha=date("d/m/Y");									
								 echo ": ".$sell->created_at; ?></b>
					        </td>			        
						</tr>
					</table>
					<table class="table table-bordered" style="max-width: 700px">
						<thead class="thead-dark">
							<th>CANT.</th>
							<th>UMD</th>
							<td>&nbsp;&nbsp;<b>DESCRIPCION</b></td>
							<th>P. UNIT.</th>
							<th>IMP. S/</th>
						</thead>
						<tbody>
							<?php
								$total = 0;
								//print_r($product);
								foreach ($product as $det) 
								{		
								    if(substr($det->desItem,0,3)!="KIT"):
										$unid = ProductData::getByName($det->desItem);
										$sigla = UnidadMedidaData::getById($unid->unit)->sigla;
								    else:
	                                    $sigla="UND";
								    endif;						
									?>
										<tr>
											<td class="text-center"><b><?php echo $det->q;?></b></td>
											<td class="text-center"><b><?php echo $sigla;?></b></td>
											<?php
                                                  $prod = ProductData::getById($det->product_id);
											?>
											<td><b><?php echo $prod->name; ?></b></td>
											<td><b><?php echo $det->prec_alt; ?></b></td>
											<td><b><?php echo $det->q*$det->prec_alt; ?></b></td>
										</tr>
									<?php
									$total = $det->q*$det->prec_alt + $total;
									$totalConDesc= $total;
									$numLetra = NumeroLetras::convertir($totalConDesc);
								}
							?>
						</tbody>
					</table>
					<table class="table table-bordered" style="max-width: 700px">
						<thead style="border-style: none">
						<tr>
						<td  colspan="2" style="align-content:left;">
						<b><?php echo $numLetra;?></b>
						</td>
						</tr>
						<tr cellspacing="0" cellpading="0">
						   <th>
						   	<br><br><br>
							<?php echo "Usuario:".$cajero;?>
						    </th>	
							<th>
								<table>
									<tr>
										<td>SUB TOTAL</td>
										<td>S/<?=number_format($total, 2, '.', ',')?></td>
									</tr>
									<tr>
										<td>DESCUENTO</td>
										<td>S/<?=number_format(0, 2, '.', ',')?></td>
									</tr>
									<tr>
										<td>TOTAL</td>
										<td>S/ <?php echo number_format($totalConDesc, 2, '.', ','); ?></td>
									</tr>				
								</table>
							</th>
                        </tr>
						</thead>
					</table>
				</div>
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
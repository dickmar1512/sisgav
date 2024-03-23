<style type="text/css">
#doc{
   border:3px solid black;
   border-radius:22px;
   max-width:200px;
}
</style>
<?php
	
	$product = Factura2Data::getByNumDoc($_GET["num"]);
	$comp_cab = Not_1_2Data::getById($product->id, 7);
	// $comp_aca = Aca_1_2Data::getById($product->id, 1);
	$detalles = Det_1_2Data::getById($product->id, 7);
	$comp_tri = Tri_1_2Data::getById($product->id, 7);
	$comp_ley = Ley_1_2Data::getById($product->id, 7);

	// print_r($comp_cab );
	// print_r($detalles );
	// print_r($comp_tri );
	// print_r($comp_ley );

	$sell = SellData::getByNroDoc($comp_cab->serieDocModifica);
    //$mesero = UserData::getById($sell->mesero_id);
    //$cajero= null;
    //$cajero = UserData::getById($sell->user_id);
    $empresa = EmpresaData::getDatos();
?>
<div class="row" style="margin-top: 0px; padding-top: 0px; background: #fff">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="row pull-right">
				<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button>
			</div>
		</div>
		<div class="row">
			<div class="para_imprimir">
				<br>
				<table style="margin-top: 0px; padding-top: 0px">
					<tr style="margin-top: 0px; padding-top: 0px">
						<td style="text-align: center; width: 180px; margin-top: -5px; padding-top: 0px">
				      		<img src="img/logo.jpg" style="height: 150px; width: 100%"><br>
						</td>
						<td style="text-align: center; color: green; margin-top: 0px; padding-top: 0px; width: 470px">
							<h5 style="margin-top: 0px; padding-top: 0px"><b><?php echo $empresa->Emp_RazonSocial ?></b></h5>
					      	<h6><b><?php echo $empresa->Emp_Descripcion ?></b></h6>
					      	<p style="margin: 2px;"><?php echo $empresa->Emp_Direccion ?></p>
					      	<p style="margin: 2px;">Cel.: <?php echo $empresa->Emp_Celular?></p>
					      	<h6 style="">FEC. EMIS.: <?php echo $comp_cab->fecEmision." | ".$comp_cab->horEmision; ?></h6>
						</td>
						<td style="text-align: center; width: 200px; border-color: #222; border-width: 20px; margin-top: 0px; padding-top: 0px">
							<div class="row" id="doc">
					      	<h5><b>RUC: <?php echo $empresa->Emp_Ruc ?></b></h5>
					      	<div>
					      		<b>NOTA DE CRÉDITO</b>
					      	</div>
					      	<div><h5><?php echo $product->SERIE."-".$product->COMPROBANTE; ?></h5></div>
					      	</div>
					      	<div style=" color: red"><h5><b><?php if($comp_cab->codTipoNota==1){ echo("Anulación en la Operación");} else if($comp_cab->codTipoNota==2){ echo("Anulación por error en el RUC");}  else if($comp_cab->codTipoNota==3){ echo("Correción por error en la descripción");} else if($comp_cab->codTipoNota==4){ echo("Descuento global");} else if($comp_cab->codTipoNota==5){ echo("Descuento por item");} else if($comp_cab->codTipoNota==6){ echo("Devolución total");} else if($comp_cab->codTipoNota==7){ echo("Devolución por item");} ?></b></h5></div>
					      	</div>
				  		</td>
					</tr>
				</table>
				<br>
				<label>Documento que modifica:</label>
				<table class="table" style="margin-top: 0px; padding-top: 0px; max-width:900px">
					<tr>
					    <td>
							<p><b>Factura Electrónica:</b></p>
						</td>
						<td>
							<p><b><?php echo $comp_cab->serieDocModifica; ?></b></p>
						</td>
					</tr>
					<tr>	
						<td>
								<p><b>RUC:</b></p>
				    	</td>
						<td>
							<p><?php echo $comp_cab->numDocUsuario; ?></p>
						</td>
					</tr>
					<tr>	
				        <td>
				        		<p><b>Razón Social:</b></p>
				    	</td>
				        <td>
				        	<p><?php echo $comp_cab->rznSocialUsuario; ?></p>
				        </td>	        
					</tr>
				</table>
				<table class="table" style="margin-top: 0px; padding-top: 0px; max-width:900px">
					<tr>
						<td>
								<p><b>Motivo:</b></p>
						</td>
						 <td>
				        	<p><?php echo $comp_cab->descMotivo; ?></p>
				        </td>	        
					</tr>
				</table>
				<table class="table-bordered" style="margin-top: 0px; padding-top: 0px; max-width: 900px">
					<thead class="thead-dark">
						<th style="width: 50px">CANTIDAD</th>
						<th style="width: 610px">DESCRIPCION</th>
						<th style="width: 120px">IMPORTE UNI.</th>
						<th style="width: 120px">IMPORTE TOT.</th>
					</thead>
					<tbody>
						<?php
							$total = 0;
							foreach ($detalles as $det) {
								?>
									<tr>
										<td><?php echo $det->ctdUnidadItem; ?></td>
										<td><?php echo $det->desItem; ?></td>
										<td style="text-align:right"><?php echo number_format($det->mtoValorUnitario,2); ?></td>
										<td style="text-align:right"><?php echo $det->mtoValorVentaItem; ?></td>
									</tr>
								<?php
								$total = $det->mtoValorVentaItem + $total;
							}
						?>
					</tbody>
				</table>
				<table class="table table-bordered"  style="max-width: 900px; margin-bottom: 0px; padding-bottom:  0px ">
					<thead style="align-content: center; text-align: center; border-style: none">
						<th style="align-content: center; text-align: center;">
							<table>
								<tr scope="col"><td><b><?php echo $comp_ley->desLeyenda; ?></b></td></tr>
								<tr><td style="font-size: 11px">Consulte y/o descargue su comprobante electronico en www.sunat.gob.pe, utilizando su clave SOL</td></tr>
								<tr><td style="font-size: 11px"><p>Autorizado para ser emisor electrónico mediante la Resolución de Superintendencia N° 155-2017</p></td></tr>
							</table>
						</th>	
						<th>
							<table>
								<tr>
									<td style="width: 270px;">OP. GRATUITA S/:</td>
									<td style="min-width: 190px; text-align:right">0.00</td></tr>
								<tr>
									<td>OP. EXONERADA S/:</td>
									<td style="text-align:right"><?php if($comp_cab->codTipoNota==1 || $comp_cab->codTipoNota==2 || $comp_cab->codTipoNota==4 || $comp_cab->codTipoNota==5 || $comp_cab->codTipoNota==6|| $comp_cab->codTipoNota==7){  echo number_format($total, 2, '.', ',');} else if($comp_cab->codTipoNota==3){ echo("0.00");}?></td></tr>
								<tr>
									<td>OP. INAFECTA S/:</td>
									<td style="text-align:right">0.00</td></tr>
								<tr>
									<td>OP. GRAVADA S/:</td>
									<td style="text-align:right">0.00</td></tr>
								<tr>
									<td>IGV S/:</td>
									<td style="text-align:right">0.00</td></tr>
								<tr>
									<td>MONTO TOTAL S/:</td>
									<td style="text-align:right"><?php if($comp_cab->codTipoNota==1 || $comp_cab->codTipoNota==2 || $comp_cab->codTipoNota==4 || $comp_cab->codTipoNota==5 || $comp_cab->codTipoNota==6 || $comp_cab->codTipoNota==7){  echo number_format($total, 2, '.', ',');} else if($comp_cab->codTipoNota==3){ echo("0.00");}?></td>
								</tr>				
							</table>
						</th>
					</thead>
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
	      	window.print();

	      	$('#imprimir').show();
	      	$('#div_opciones').show(); 
	      	$('.logo').show(); 
	    });
	});
</script>
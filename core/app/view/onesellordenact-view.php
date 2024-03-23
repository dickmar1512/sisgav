<?php
	$orden_id = $_GET["id"];
	$orden = SellData::getById($orden_id);
	$detalles = OperationData::getAllProductsBySellId($orden_id);
	$empresa = EmpresaData::getDatos();
?>
<div class="row" >
	<div class="col-md-10 col-md-offset-1">
		<div class="row ">
			<div class="row pull-right">
				<!-- <a href="" class="btn btn-success">Generar Comprobante</a>
				<button id="imprimir" class="btn btn-md btn-info"><i class="fa fa-print"></i> IMPRIMIR</button> -->
				<form method="post" action="index.php?view=addfacturao" class="form-inline" onsubmit="return enviado2()">
					<input type="hidden" value="<?php echo $_GET["id"] ?>" name="orden_id">					
					<?php
						//if($orden->estado == 0)
						{
							?>
							<div class="input-group">
								<select name="selTipoComprobante" class="form-control" id="selTipoComprobante">
									<option value="1">Factura</option>
									<option value="3">Boleta</option>
								</select>						
							</div>
								<button class="btn btn-md btn-danger" type="submit" id="btnComprobante"><i class="fa fa-file-text"></i> COMPROBANTE</button>
							<?php
						}
					?>					
					<a id="imprimir" class="btn btn-md btn-info" href="#"><i class="fa fa-print"></i> IMPRIMIR</a>
				</form>
			</div>
		</div>
		<div class="row" style="background: #fff; margin-top: 5px">
			<br>
			<div class="para_imprimir">
				<!--<div class="col-md-12 row">-->
				<div class="col-md-4 col-md-offset-2" style="border-right: 1px solid #ccc; border-left: 1px solid #cccc; border: 1px solid #cccc;font-size: 0.7em">
					<div style="width: 100%; text-align: center;">
						<img src="plugins/dist/img/logo2.jpg" style="height: 78px; width: 108px">
						<p style="margin: 2px;"><b><?php echo $empresa->Emp_RazonSocial ?></b></h3>
						<p style="margin: 2px;"><b><?php echo $empresa->Emp_Descripcion ?></b></p>
						<p style="margin: 2px;"><?php echo $empresa->Emp_Direccion ?></p>
						<p style="margin: 2px;">Tel.: <?php echo $empresa->Emp_Telefono?></p>
						<p style="margin: 2px;">Cel.: <?php echo $empresa->Emp_Celular?></p>					
						<p style="margin: 2px;">LORETO - IQUITOS - PERÚ</p>
					</div>
					<div style="width: 100%;">
						<div style="margin: 15px; text-align: center;">
							<div style="border: 2px solid black;">
								ORDEN DE VENTA:<br>
								<span style="color: #dd4b39; font-weight: bold;">
									<?php echo $orden->serie."-".$orden->comprobante; ?></span>
								<br>
								F. EMISIÓN <span style=""><?php echo convertir_fecha($orden->created_at); ?></span>
							</div>
						</div>
					</div>
					<div style="width: 100%;">
					<?php
						$cliente = PersonData::getById($orden->person_id);
					?>
					<div style="border: 1px solid black; padding: 10px">
						<table style="width: 100%;">
							<tbody>
								<tr>									
									<td>CLIENTE:</td>
									<td><?php echo $cliente->name.' '.$cliente->lastname ?></td>
								</tr>
								<tr>
									<td>RUC:</td>
									<td><?php echo $cliente->numero_documento ?></td>
								</tr>
							</tbody>
						</table>
					</div>

				<div style="width: 100%;">
					<table class="table-bordered" style="margin-top: 0px; padding-top: 0px;">
						<thead class="thead-dark">
							<th>CANT.</th>
							<th>UMD</th>
							<th>&nbsp;&nbsp;DESCRIPCION</th>
							<th>P. UNIT.</th>
							<th>IMP. S/</th>
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
												<td class="text-center"><b><?php echo $det->q;?></b></td>
												<td class="text-center"><b><?php echo $sigla;?></b></td>
												<td><b><?php echo $det->product_id; ?></b></td>
												<td><b><?php echo $det->prec_alt; ?></b></td>
												<td><b><?php echo $det->q*$det->prec_alt; ?></b></td>
											</tr>

									<?php
									$total = $det->q*$det->prec_alt + $total;
									$totalConDesc= $total;
										//$numLetra = NumLetras::convertirNumeroLetra($totalConDesc);
									$numLetra = NumeroLetras::convertir($totalConDesc);
								}
							?>
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
						</tbody>
				    </table>
				</div>

				</div>
				</div>
			</div>
		</div>
	</div>
</div><!--  fin col-md-6 -->
<?php
	function generaCerosComprobante($numero)
	{
	    $largo_numero = strlen($numero);
	    $largo_maximo = 6;
	    $agregar = $largo_maximo - $largo_numero;
	    for($i = 0; $i < $agregar; $i++)
	    {
	    	$numero = "0".$numero;
	    }
	    return $numero;
	}

	function convertir_fecha($fecha)
	{
	    $date = date_create($fecha);
	    return date_format($date, 'd-m-Y');
	}


?>
<script>
	function enviado2() 
	{
	    go = confirm("¿Seguro que desea emitir el comprobante, la accion no podrá ser revertida ?");
	     
	    if(go == true)
	    {
	        if (cuenta == 0)
	        {
	          cuenta++;
	          return true;
	        }
	        else
	        {
	          alert("El formulario ya está siendo enviado, por favor aguarde un instante.");
	          return false;
	        }
	    }
	    else
	    {
	        return false;
	    }	    
	}

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
	    	$('#btnComprobante').hide();
	    	$('#selTipoComprobante').hide();
	      	$('#div_opciones').hide();
	      	$('.logo').hide();
	      	$('.eliminar_repuesto').hide();
	      	window.print();

	      	$('#imprimir').show();
	      	$('#div_opciones').show(); 
	      	$('#btnComprobante').show();
	      	$('#selTipoComprobante').show();
	      	$('.eliminar_repuesto').show();
	      	$('.logo').show(); 
	    });
	});
</script>
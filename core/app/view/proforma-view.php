<style type="text/css">
#doc{
   border:3px solid black;
   border-radius:22px;
}
</style>
<?php
	$sell = SellData::get_ventas_x_id($_GET["id"]);

	$operations = OperationData::getAllProductsBySellId($sell->id);

	$empresa = EmpresaData::getDatos();

	function generaCerosComprobante($numero)
	{
	    $largo_numero = strlen($numero); //OBTENGO EL LARGO DEL NUMERO
	    $largo_maximo = 8; //ESPECIFICO EL LARGO MAXIMO DE LA CADENA
	    $agregar = $largo_maximo - $largo_numero; //TOMO LA CANTIDAD DE 0 AGREGAR
	    for($i =0; $i<$agregar; $i++)
	    {
	      $numero = "0".$numero;
	    } //AGREGA LOS CEROS
	    return $numero; //RETORNA EL NUMERO CON LOS CEROS
	}
?>
<div class="row" style="margin-top: 0px; padding-top: 0px; background: #fff">
	<div class="col-md-10 col-md-offset-1">
		<div class="row ">
			<div class="row pull-right">
				<?php 
					if($sell->tipo_comprobante == 1)
					{
						$pagina = "addfacturap";
					}
					else
					{
						$pagina = "addboletap";
					}					 
				?>
				<form method="post" action="index.php?view=<?php echo $pagina?>">
					<input type="hidden" value="<?php echo $_GET["id"] ?>" name="sell_id">
					<button class="btn btn-md btn-danger" type="submit" id="venta"><i class="fa fa-shopping-cart"></i> VENTA</button>
					<a id="imprimir" class="btn btn-md btn-info" href="#"><i class="fa fa-print"></i> IMPRIMIR</a>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="para_imprimir" style="font-family: courier new; font-size: 0.9em;">
					<table class="table" style="margin-top: 0px; padding-top: 0px; max-width: 700px">
						<tr style="margin-top: 0px; padding-top: 0px">
							<td style="text-align: center; width: 200px; margin-top: -5px; padding-top: 0px">
					      		<img src="./plugins/dist/img/logo.png" style="height: 100px; width: 100%"><br>
							</td>
							<td style="text-align: center; color: green; margin-top: 0px; padding-top: 0px; width: 420px">
								<h2 style="margin-top: 0px; padding-top: 0px"><b><?php echo $empresa->Emp_RazonSocial ?></b></h2>
						      	<h5><b><?php echo $empresa->Emp_Descripcion ?></b></h5>
						      	<p style="margin: 2px;"><?php echo $empresa->Emp_Direccion ?></p>
						      	<p style="margin: 2px;">Cel.: <?php echo $empresa->Emp_Celular?></p>
							</td>
							<td style="text-align: center; width: 200px; margin-top: 0px; padding-top: 0px">
								<div class="row" id="doc">
						      	<h6><b>RUC: <?php echo $empresa->Emp_Ruc ?></b></h6>
						      	<h3 style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 3px 0px"><b>PROFORMA</b></h3>
						      	<div><h6><?php echo "P001-".generaCerosComprobante($sell->id) ?></h6></div>
						      	</div>
					  		</td>
						</tr>
					</table>
					<table class="table-bordered" style="margin-top: 0px; padding-top: 0px; max-width: 700px">
						<tr>
							<td>
								<div>
									<p><b>RUC</b></p>
								</div>
					    	</td>
							<td colspan="3">
								<p>:
									<?php 
									if ($sell->numero_documento == '') 
									{
										echo "00000000";
									}
									else
									{
										echo $sell->numero_documento; 
									}
									?>
								</p> 
							</td>
						</tr>
						<tr>
					        <td>
					        	<div>
					        		<p><b>RAZON SOCIAL</b></p>
					        	</div>
					    	</td>
					        <td colspan="3">
					        	<p>: <?php echo $sell->name; ?></p>
					        </td>
						</tr>
						<tr>
					        <td style="width:150px">
					        	<div><p><b>FECHA EMISION</b></p></div>
					        </td>
					        <td style="width: 550px">
					        	<p><?php echo ": ".$sell->created_at; ?></p>
					        </td>
						</tr>
					</table>
					<br>
					<table class=" table-bordered" style="max-width: 700px">
						<thead class="thead-dark">
							<th style="width: 50px; text-align: center;">CANTIDAD</th>
							<th style="width: 350px; text-align: center;">DESCRIPCION</th>
							<th style="width: 150px; text-align: center;">PRECIO UNIT.</th>
							<th style="width: 150px; text-align: center;">IMPORTE</th>
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
											<td><?php echo $product->name; ?></td>
											<td><?php echo $ope->prec_alt; ?></td>
											<td><?php echo number_format($subtotal, 2, '.', ',') ?></td>
										</tr>
									<?php
									$total = $subtotal + $total;
								}
							?>
						</tbody>						
						<tfoot>
							<tr>
								<td colspan="3"><i>SON <?php echo NumeroLetras::convertir($total, 'soles', 'centimos'); ?></i></td>
								<td><?php echo number_format($total, 2, '.', ','); ?></td></tr>
							</tr>
						</tfoot>
					</table>
					<br>
					<table class="table-bordered" style="max-width: 700px; margin-bottom: 0px; padding-bottom:  0px ">
					<tr>
						<th style="font-size: 10px; text-align: left;">
						INTERBANK: Número de cuenta Soles: 740-3005786280 | CCI : 003-740-003005786280-84
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
	      	$('#venta').hide();
			$('.main-footer').hide();
	      	window.print();

	      	$('#imprimir').show();
	      	$('#div_opciones').show(); 
	      	$('#venta').show(); 
	      	$('.logo').show();
			$('.main-footer').show(); 
	    });
	});
</script>
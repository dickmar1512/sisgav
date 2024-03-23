
<?php
	$categories = CategoryData::getAll();
	$productos = ProductData::getAll();
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1><i class='fa fa-file-excel-o'></i>&nbsp;&nbsp;Kardex Productos</h1>
			<form>
				<!--input type="hidden" name="view" value="sellreports"-->
				<input type="hidden" name="view" value="kardexbyproducto">
				<!--div class="row">
					<div class="col-md-3">
						<select name="selCategory" class="form-control">
							<option value="">--  TODOS --</option>
							<?php 
								foreach($categories as $c)
								{
									?>
										<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
									<?php
								}
							?>
						</select>
					</div-->
					<div id="productsbycategory">
						<div class="col-md-3">
							<select name="selProduct" class="form-control">
								<option value="T">..::Todos::..</option>
								<?php foreach($productos as $p):?>
								<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>					
					<div class="col-md-3">
						<input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; }?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; }?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-success btn-block" value="Procesar">
					</div>
				</div>
			</form>
			<div class="clearfix"></div>
			<br>
		<!--/div>
	</div>
	<div class="row">
		<div class="col-md-12"-->
		<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) ):?>
		<?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
			<?php 
				$operations = array();
				$i = 0;
				/*if($_GET["selProduct"]=="T")
				{
					$operations = SellData::getAllByKardex($_GET["sd"],$_GET["ed"]);
				}
				else
				{
				$operations = SellData::getAllByKardexProd($_GET["selProduct"],$_GET["sd"],$_GET["ed"],2);
				}*/
				if($_GET["selProduct"]=="T"):
				  $operations = ProductData::getAll();
				  $url = "sd=".$_GET['sd']."&ed=".$_GET['ed']."&selProduct=T";
				else:  
				  $operations = ProductData::getById($_GET["selProduct"]);
				  $url = "sd=".$_GET['sd']."&ed=".$_GET['ed']."&selProduct=".$_GET["selProduct"];
				endif;  
			?>

			<?php if(count($operations)>0):?>
				<?php 
				        $superQEntrada     = 0;
						$superCuEntrada    = 0;
						$superTotalEntrada = 0;
						$superQSalida      = 0;
						$superCuSalida     = 0;
						$superTotalSalida  = 0;
						$superQSaldo       = 0;
						$superCuSaldo      = 0;
						$superTotalSaldo   = 0;					  
					  ?>
				<table class="table table-bordered">
					<thead>
					    <tr>
							<th rowspan="2">#</th>
							<th>
							  <center>
							    <h3>
								  <p align="center">
								  <a href="index.php?view=excel_kardex_fecha&<?=$url?>"; class="btn btn-success" target="_self">
								  <i class="fa fa-file-excel-o"></i>Exportar Excel
								  </a>
								  </p>
				                </h3>
							  </center>
							</th>
							<th colspan="3"><center><h3>Entradas</h3></center></th>
							<th colspan="3"><center><h3>Salidas</h3></center></th>
							<th colspan="3"><center><h3>Saldos</h3></center></th>
						</tr>
						<tr>
						   <th><center><h3>Producto</h3></center></th>
						   <th>Cantidad</th>
						   <th>Costo Unit.</th>
						   <th>Total</th>
						   <th>Cantidad</th>
						   <th>Costo Unit.</th>
						   <th>Total</th>
						   <th>Cantidad</th>
						   <th>Costo Unit.</th>
						   <th>Total</th>
						</tr>
					</thead>
				<?php foreach($operations as $ope):
				   $i++;
				?>
					<tr>
						<td><?=$i?></td>
						<td><?=$ope->name?></td>
						<?php
						   $entrada=OperationData::obtenerCantidadProductoEntrada($_GET["sd"],$_GET["ed"],$ope->id)
						?>
						<td><?php if(isset($entrada->cant)){echo $entrada->cant;} else{ echo '0';} ?></td>
						<td><?=number_format($entrada->cu,2,'.',',')?></td>
						<td><?=number_format($entrada->total,2,'.',',')?></td>
						<?php
						   $salida=OperationData::obtenerCantidadProductoSalida($_GET["sd"],$_GET["ed"],$ope->id)
						?>
						<td><?php if(isset($salida->cant)){echo $salida->cant;} else{ echo '0';} ?></td>
						<td><?=number_format($salida->cu,2,'.',',')?></td>
						<td><?=number_format($salida->total,2,'.',',')?></td>
						<?php
						   $saldo=ProductData::getById($ope->id)
						?>
						<td><?php if(isset($saldo->stock)){echo $saldo->stock;} else{ echo '0';} ?></td>
						<td><?=number_format($saldo->price_in,2,'.',',')?></td>
						<td><?=number_format($saldo->stock*$saldo->price_in,2,'.',',')?></td>
					</tr>
				<?php
				        $superQEntrada     += $entrada->cant;
						$superCuEntrada    += $entrada->cu;
						$superTotalEntrada += $entrada->total;
						$superQSalida      += $salida->cant;
						$superCuSalida     += $salida->cu;
						$superTotalSalida  += $salida->total;
						$superQSaldo       += $saldo->stock;
						$superCuSaldo      += $saldo->price_in;
						$superTotalSaldo   += $saldo->stock*$saldo->price_in;	
				 endforeach; ?>
                 <tr>
				    <th colspan="2"><h3>Total</h3></th>
					<th><?=$superQEntrada?></th>
					<th><?=number_format($superCuEntrada,2,'.',',')?></th>
					<th><?=number_format($superTotalEntrada,2,'.',',')?></th>
					<th><?=$superQSalida?></th>
					<th><?=number_format($superCuSalida,2,'.',',')?></th>
					<th><?=number_format($superTotalSalida,2,'.',',')?></th>
					<th><?=$superQSaldo?></th>
					<th><?=number_format($superCuSaldo,2,'.',',')?></th>
					<th><?=number_format($superTotalSaldo,2,'.',',')?></th>
				 </tr>
				</table>


			<?php else:
			 
			?>
			<script>
				$("#wellcome").hide();
			</script>
			<div class="jumbotron">
				<h2>No hay operaciones</h2>
				<p>El rango de fechas seleccionado no proporciono ningun resultado de operaciones.</p>
			</div>

			<?php endif; ?>
		<?php else:?>
	<script>
		$("#wellcome").hide();
	</script>
	<div class="jumbotron">
		<h2>Fecha Incorrectas</h2>
		<p>Puede ser que no selecciono un rango de fechas, o el rango seleccionado es incorrecto.</p>
	</div>
<?php endif;?>

		<?php endif; ?>
	</div>
</div>
</section>
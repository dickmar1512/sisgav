<div class="row">
	<div class="col-md-12">
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Registro de Ventas</h1>
		<form>
				<input type="hidden" name="view" value="sells">
				<div class="row">
					<div class="col-md-3">
					<input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; } else { echo date("Y-m-d"); }?>" class="form-control">
					</div>
					<div class="col-md-3">
					   <input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; } else { echo date("Y-m-d"); } ?>" class="form-control">
					</div>
					<div class="col-md-3">
					   <input type="submit" class="btn btn-success btn-block" value="Procesar">
					</div>

				</div>
	        </form>
		<div class="clearfix"></div>

	<?php
	   $sd = "";
	   $ed = "";
	  if(isset($_GET["sd"]) && isset($_GET["ed"]) ):
		$products = SellData::getSellsXfecha($_GET["sd"],$_GET["ed"]);
		$sd = $_GET["sd"];
		$ed = $_GET["ed"];
	  else:
	  	$products = SellData::getSells();
	  endif;	  
	  
	  $tc=0;
	  $tg=0;
	  $tv = 0;
	  $total_capital = 0;
	  $total_ganancia = 0;

		if(count($products)>0)
		{   
			?> 
            <br>
			 <p align="center"><a href="index.php?view=excel_ventas&sd=<?php echo $sd."&ed=".$ed;?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i>Exportar Excel</a></p>
				<br>
				<table class="table table-bordered table-hover">
					<thead class="table table-primary">
					<tr>	
						<th></th>
						<th>Comprobante</th>
						<th>Num. doc</th>
						<th>Cliente</th>
						<th>Total S/</th>
						<th>Fecha</th>
						<th>Estado</th>
						<th>XML</th>
						<th>CDR</th>
						<th></th>
					</tr>	
					</thead>
					<?php 				 
					foreach($products as $sell):
					     $notacomprobar = $sell->serie."-".$sell->comprobante;
						 $probar = Not_1_2Data::getByNumComp($notacomprobar);
	                     $total_nc = 0;
	                     $total_anulado=0;
                         $color = "";
	                     if(isset($probar)) 
	                     {
							if ($probar->TIPO_DOC==8) {$col="#C2FCCF"; }
							if ($probar->TIPO_DOC==7) {$col="#FFC4C4"; }
							$color = $col;
						}
						 
						if($sell->estado==0)
						 {
							$color="#FFC4C4";
						 }
					?>
					<tr style="background:<?php echo $color;?>">
						<td style="width:30px;">

							<?php
								if($sell->tipo_comprobante == 3)
								{
									if($sell->estado == 1)
									{
										?>
											<a href="index.php?view=onesellc&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
											<a href="index.php?view=guia&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default" title="Guia Remitente"><i class="fa fa-file"></i></a>
										<?php
									} 
									else
									{
										?>
											<a href="index.php?view=onesellf&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
										<?php
									}
								}
								else
								{
									if($sell->tipo_comprobante == 1)
									{
										if($sell->estado == 1)
										{
											?>
												<a href="index.php?view=onesell2c&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default" title="Ver"><i class="glyphicon glyphicon-eye-open"></i></a>
												<a href="index.php?view=guia&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default" title="Guia Remitente"><i class="fa fa-file"></i></a>
											<?php
										} 
										else
										{
											?>
												<a href="index.php?view=onesell2f&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
											<?php
										}
									}
									else
									{ ?>
                                       <a href="index.php?view=onsellorden2&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
							  <?php }
								}
								
							?>
						</td>	
						<td>
							<?=$sell->serie."-".$sell->comprobante?>
						</td>
						<?php
					         $datoscli = PersonData::nombre_cliente($sell->person_id);         
						?>						
						<td><?php echo $datoscli->numero_documento;?></td>
						<td>
							<?php echo $datoscli->name." ".$datoscli->lastname;
							     if (isset($probar)) 
							     	{ 
							     		echo "(".$probar->SERIE."-".$probar->COMPROBANTE.")";
					                    $total_nc = $probar->sumTotValVenta;
							         }
							     if($sell->estado==0)
						           {
						           	echo "(Comprobante Anulado)";
					                    $total_anulado = $sell->total;
						           }    
							 ?>
						</td>
						<?php ?>
						<td style="text-align: right;">

				<?php
				$total = $sell->total;
						echo "<b>".number_format($total,2)."</b>";
				//$tv += $total-($total_nc+$total_anulado);	
				if($sell->estado==0 || isset($probar))
				{}	 
				else
				{
				$tv += $total-($total_nc+$total_anulado);	
                $operacion = OperationData::getAllProductsBySellId($sell->id);
				foreach($operacion as $ope):
				$total_capital+=$ope->cu*$ope->q;
				$total_ganancia+=($ope->prec_alt-$ope->cu)*$ope->q;
				endforeach;
				
				$tc=$total_capital;
				$tg=$total_ganancia;
						 }
				?>			

						</td>
						<td><?php echo $sell->created_at; ?></td>
						<?php $datosFacSunat = FacturadorData::getByNuemroDocumento($sell->serie."-".$sell->comprobante);  ?>
						<td><?=$datosFacSunat->DES_SITU?></td>
						<td>
							<?php
							if(file_exists($datosFacSunat->URL_XML)){
							 ?>								 
							<a href="<?=$datosFacSunat->URL_XML?>" target="_blank" download><i class="fa fa-download"></i></a>
							<?php }?>
						</td>
						<td>
							<?php
							if(file_exists($datosFacSunat->URL_CDR)){
							 ?>								 
							<a href="<?=$datosFacSunat->URL_CDR?>" target="_blank"><i class="fa fa-download"></i></a>
							<?php }?>
						</td>
						<td style="width:30px;">
						<?php if($sell->estado==0 || isset($probar))
				              {}	 
				              else
				               { 
								if(!file_exists($datosFacSunat->URL_CDR)){
								?>
				               	<a href="index.php?view=delsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
				               <?php } 
							   }?>	
				          </td>
					</tr>

				<?php endforeach; ?>
                 <tr>
                 	<th></th>
                 	<th colspan="3" style="text-align: right;"> <b>Total de ventas S/:</b></th>
                 	<th style="text-align: right;"><?=number_format($tv,2)?></th>
                 	<th> Soles.</th>
                 </tr>
				 <tr>
				 	<th></th>
                 	<th colspan="3" style="text-align: right;">Total de capital S/:</th>
                 	<th style="text-align: right;"><?=number_format($tc,2)?></th>
                 	<th> Soles.</th>
                 </tr>
				 <tr>
				 	<th></th>
                 	<th colspan="3" style="text-align: right;">Total de Ganancia S/:</th>
                 	<th style="text-align: right;"><?=number_format($tg,2)?></th>
                 	<th> Soles.</th>
                 </tr>
				</table>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>
	<?php
}

?>
<br>
	</div>
</div>
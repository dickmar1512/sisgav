<?php
header("Pragma: public");
header("Expires: 0");
$filename = "Kardex_".$_GET["sd"]."_".$_GET["ed"]."_".$_GET["selProduct"].".xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>
<div class="row">
	<div class="col-md-12">
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Registro de Ventas</h1>

	<?php
	  if(isset($_GET["sd"]) && isset($_GET["ed"]) ):
		$products = SellData::getSellsXfecha($_GET["sd"],$_GET["ed"]);
	  else:
	  	$products = SellData::getSells();
	  endif;	

		if(count($products)>0)
		{
			?> 
            <br>
			 <p align="center"><a href="index.php?view=excel_asiento&ini=<?php echo $_GET['sd'] ?>&fin=<?php echo $_GET['ed']?>"; class="btn btn-success"><i class="fa fa-file-excel-o"></i>Exportar Excel</a></p>
				<br>
				<table class="table table-bordered table-hover">
					<thead class="table table-primary">
					<tr>	
						<th></th>
						<th>Comprobante</th>
						<th>Cliente</th>
						<th>Total S/</th>
						<th>Fecha</th>
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
						 //if($sell->comprobante=='00000015'||$sell->comprobante=='00000018'||$sell->comprobante=='00000019'||$sell->comprobante=='00000021')
						if($sell->estado==0)
						 {
							$color="#FFC4C4";
						 }	 
						 /*else
						 {
							//$color="";
							$totalItem=$sell->total; 
						 }*/
					?>
					<tr style="background:<?php echo $color;?>">
						<td style="width:30px;">

							<?php
								if($sell->tipo_comprobante == 3)
								{
									if($sell->estado == 1)
									{
										?>
											<a href="index.php?view=onesellc&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
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
												<a href="index.php?view=onesell2c&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
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
						<td>

				<?php
				$total =$sell->total;
						echo "<b>".number_format($total,2)."</b>";
				$tv +=	$total-($total_nc+$total_anulado);	
				if($sell->estado==0 || isset($probar))
				{}	 
				else
				{
                $operacion = OperationData::getAllProductsBySellId($sell->id);
				foreach($operacion as $ope):
				$total_capital+=$ope->cu*$ope->q;
				$total_ganancia+=($ope->prec_alt-$ope->cu)*$ope->q;
				endforeach;
				//echo $total_capital."+".$total_ganancia;
				$tc=$total_capital;
				$tg=$total_ganancia;
						 }
				?>			

						</td>
						<td><?php echo $sell->created_at; ?></td>
						<td style="width:30px;">
						<?php if($sell->estado==0 || isset($probar))
				              {}	 
				              else
				               { ?>
				               	<a href="index.php?view=delsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
				               <?php }?>	
				          </td>
					</tr>

				<?php endforeach; ?>
                 <tr>
                 	<th></th>
                 	<th colspan="2" style="text-align: right;"> <b>Total de ventas S/:</b></th>
                 	<th><?=$tv?></th>
                 	<th> Soles.</th>
                 </tr>
				 <tr>
				 	<th></th>
                 	<th colspan="2" style="text-align: right;">Total de capital S/:</th>
                 	<th><?=$tc?></th>
                 	<th> Soles.</th>
                 </tr>
				 <tr>
				 	<th></th>
                 	<th colspan="2" style="text-align: right;">Total de Ganancia S/:</th>
                 	<th><?=$tg?></th>
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
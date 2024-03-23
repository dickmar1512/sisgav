<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-primary "><i class="fa fa-clock-o"></i> Historial</a>
<a href="./index.php?view=processbox" class="btn btn-primary ">Procesar Ventas <i class="fa fa-arrow-right"></i></a>
</div>
		<h1><i class='fa fa-archive'></i> Caja</h1>
		<div class="clearfix"></div>


<?php
$products = SellData::getSellsUnBoxed();
if(count($products)>0)
{
$total_total = 0;
?>
<br>
<table class="table table-bordered table-hover	">
	<thead>
		<th>#</th>
		<th>Comprobante</th>
		<th>Cant. Prod.</th>
		<th>Total</th>
		<th>Fecha</th>
		<th>Usuario</th>
	</thead>
	<tbody>
	<?php foreach($products as $sell):
        $i++;
		?>
	<tr>
		<td style="width:30px;"><?=$i?></td>
		<td><?=$sell->serie."-".$sell->comprobante?></td>
		<td>	
		<?php
			$operations = OperationData::getAllProductsBySellId2($sell->id);
			echo count($operations);
		?>
	    </td>
		<td>
			<?php
			$total=0;
				foreach($operations as $operation)
				{
					$product  = $operation->getProduct();
					$total += $operation->q*($operation->prec_alt-$operation->descuento);
				}
					$total_total += $total;
					echo "<b>S/ ".number_format($total,2,".",",")."</b>";
			?>	
		</td>
		<td><?php echo $sell->created_at; ?></td>
		<?php
          $datosusu = UserData::nombre_usuario($sell->user_id);         
		?>
		<td><?=$datosusu->name?></td>
	</tr>
    <?php endforeach; ?>
   </tbody>
</table>
<h1>Total: <?php echo "S/ ".number_format($total_total,2,".",","); ?></h1>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
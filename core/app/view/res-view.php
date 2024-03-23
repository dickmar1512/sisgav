<div class="row">
	<div class="col-md-12">
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Compras/Reabastecimientos</h1>
		<div class="clearfix"></div>


<?php
$products = SellData::getRes();

if(count($products)>0){
	?>
<br>
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Comprobante</th>
		<th>Proveedor</th>
		<th>Total</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php
    $color="";
	foreach($products as $sell):
	   if($sell->estado==0)
		{
		  $color="#FFC4C4";
		}	
	    else{$color="";}	
	?>
	<tr style="background:<?=$color?>">
		<td style="width:30px;">
		<?php
		if($sell->estado==0)
		{}
	   else{
	?>
		<a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default">
		<i class="glyphicon glyphicon-eye-open"></i>
		</a>
	   <?php }?>
		</td>
        <td><?=$sell->serie."-".$sell->comprobante?></td>		
		<?php
			$proveedor = PersonData::getById($sell->person_id);			
		?>
		<td>
		 <?=$proveedor->name?>
		<td>
<?php
 $operations = OperationData::getAllProductsBySellId($sell->id);
$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$operation->cu;
	}
		echo "<b>S/ ".number_format($total,2)."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
		<td style="width:30px;">
		  <?php
		if($sell->estado==0)
		{ echo "Anulado";}
		else { ?>
		<a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
	<?php }?>
		</td>
	</tr>

<?php endforeach; ?>

</table>


	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>
<br><br>
	</div>
</div>
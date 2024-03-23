<div class="row">
	<div class="col-md-12">

<!-- Single button -->
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-default"><i class="fa fa-clock-o"></i> Historial</a>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="report/box-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
</div>
		<h1><i class='fa fa-archive'></i> Corte de Caja #<?php echo $_GET["id"]; ?></h1>
		<div class="clearfix"></div>


<?php
$products = SellData::getByBoxId($_GET["id"]);
if(count($products)>0)
{
$total_total = 0;
?>
<br>
<table class="table table-bordered table-hover	">
	<thead>
		<tr>
			<th></th>
			<th>Comprobante</th>
			<th>Cliente</th>
			<th>Total S/</th>
			<th>Fecha</th>
			<th>Usuario</th>
	  </tr>	
	</thead>
	<?php foreach($products as $sell):
		if($sell->estado==0)
		{
		   $color="#FFC4C4";
		}
		?>
	<tr style="background:<?php echo $color;?>">
		<td style="width:30px;">
     <!--<a href="./index.php?view=onesell&id=<?php echo $sell->id; ?>" class="btn btn-default btn-xs">
     	<i class="glyphicon glyphicon-eye-open"></i>
     </a>	-->
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
                                       <a href="index.php?view=onesellorden&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a>
							  <?php }
								}
								
							?>
     </td>
     <td><?=$sell->serie."-".$sell->comprobante;?></td>	
     <td>
     <?php	
     	$datoscli = PersonData::nombre_cliente($sell->person_id);
		  echo $datoscli->name;
		   $operations = OperationData::getAllProductsBySellId($sell->id);
		?>
		</td>
		<td style="text-align:right;">

<?php
$total=0;
if($sell->estado!=0)
{
	foreach($operations as $operation){
		//$product  = $operation->getProduct();
		$total += $operation->q*$operation->prec_alt;
	}
}
		$total_total += $total;
		echo "<b>".number_format($total,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
		<?php
          $datosusu = UserData::nombre_usuario($sell->user_id);         
		?>
		<td><?=$datosusu->name?></td>
	</tr>

<?php endforeach; ?>
<tr><td colspan="3" style="text-align:right;"><h4>Total S/:</h4></td><td style="text-align:right;"><h4><?php echo number_format($total_total,2,".",","); ?></h4></td></tr>
</table>
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
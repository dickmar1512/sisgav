<?php
$clients = PersonData::getClients();
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	   <h1><i class='fa fa-cart-plus'></i> Reportes de Ventas</h1>
		<form>
			<input type="hidden" name="view" value="sellreports">
			<div class="row">
				<div class="col-md-3">
					<select name="client_id" class="form-control">
						<option value="">--  TODOS --</option>
						<?php foreach($clients as $p):?>
						<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
						<?php endforeach; ?>
					</select>
				</div>
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
	</div>
</div>
<br><!--- -->
<div class="row">	
	<div class="col-md-12">
		<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) ):?>
        <?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
			<?php 
			$operations = array();

			if($_GET["client_id"]=="")
			{
			$operations = SellData::getAllByDateOp($_GET["sd"],$_GET["ed"],2);
			}
			else
			{
			$operations = SellData::getAllByDateBCOp($_GET["client_id"],$_GET["sd"],$_GET["ed"],2);
			} 
			 ?>

			 <?php if(count($operations)>0):?>
			 	<?php $supertotal = 0; ?>
<table class="table table-bordered">
	<thead>
	 <tr>	
		<th>Comprobante</th>
		<th>Cliente</th>
		<th>Total</th>
		<th>Fecha</th>
	 </tr>	
	</thead>
	<tbody>
<?php foreach($operations as $operation):
     $notacomprobar = $operation->serie."-".$operation->comprobante; 
	 $probar = Not_1_2Data::getByNumComp($notacomprobar);
	 $total_nc = 0;
	?>
	<tr style="background: <?php if (isset($probar)) {
							if ($probar->TIPO_DOC==8) {	echo "#C2FCCF"; }
							if ($probar->TIPO_DOC==7) {	echo "#FFC4C4"; }
						} ?>">
		<td><?php echo $operation->serie."-".$operation->comprobante; ?></td>
		<?php
          $datoscli = PersonData::nombre_cliente($operation->person_id);         
		?>
		<td><?php echo $datoscli->name." ".$datoscli->lastname;
		     if (isset($probar)) 
		     	{ 
		     		echo "(".$probar->SERIE."-".$probar->COMPROBANTE.")";
                    $total_nc = $probar->sumTotValVenta;
		         }
		 ?></td>
		<td>S/ <?php echo number_format($operation->total-$operation->discount,2,'.',','); ?></td>
		<td><?php echo $operation->created_at; ?></td>
	</tr>
<?php
$supertotal+= ($operation->total-$operation->discount-$total_nc);
 endforeach; ?>
    </tbody>
</table>
<h1>Total de ventas: S/ <?php echo number_format($supertotal,2,'.',','); ?></h1>
<?php else:
 // si no hay operaciones
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

<br><br>
</section>
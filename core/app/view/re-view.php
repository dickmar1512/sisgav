<?php
 $mysqli = new mysqli('localhost','tarosoft','armagedon','dbsigav',3306); //BASE DE DATOS
 $query = $mysqli->prepare("SELECT * FROM sell WHERE tipo_comprobante = '60' ");
 $query->execute();
 $query->store_result();
 $registros_orden = $query->num_rows;

  $id_comprobante_actual_o = $registros_orden + 1;

   function generaCerosComprobante($numero)
  {
  	$empresa = EmpresaData::getDatos();
    $largo_numero = strlen($numero); //OBTENGO EL LARGO DEL NUMERO

    $largo_maximo = 8; //ESPECIFICO EL LARGO MAXIMO DE LA CADENA
    if($empresa->Emp_Sucursal!=0){ $largo_maximo = 8; } //PARCHE OTRA SUCURSAL
    $agregar = $largo_maximo - $largo_numero; //TOMO LA CANTIDAD DE 0 AGREGAR
    for($i =0; $i<$agregar; $i++)
    {
      $numero = "0".$numero;
    } //AGREGA LOS CEROS
    return $numero; //RETORNA EL NUMERO CON LOS CEROS
  }

  $ORDEN = generaCerosComprobante($id_comprobante_actual_o);
?>
<div class="row">
	<div class="col-md-12">
		<h1>Reabastecer Inventario</h1>
		<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form>
			<div class="row">
				<div class="col-md-6">
					<input type="hidden" name="view" value="re">
					<input type="text" id="product_code2" autofocus name="product" class="form-control">
				</div>
				<div class="col-md-3">
					<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
				</div>
			</div>
		</form>
	</div>

	<?php if(isset($_GET["product"])):?>
	<?php
		$products = ProductData::getLikeSinStock($_GET["product"]);

		if(count($products)>0)
		{
			?>
				<h3>Resultados de la Busqueda</h3>
				<table class="table table-bordered table-hover">
					<thead>
						<th>Codigo</th>
						<th>Nombre</th>
						<th>Unidad de Medida</th>
						<th>Precio unitario</th>
						<th>En inventario</th>
						<th>Cantidad</th>
						<th style="width:100px;"></th>
					</thead>
					<?php
						$products_in_cero=0;
					 	foreach($products as $product):
							// $q = OperationData::getQYesF($product->id);
							$q = $product->stock;							
							?>
							<form method="post" action="index.php?view=addtore">
								<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
									<td style="width:80px;"><?php echo $product->barcode; ?></td>
									<td><?php echo $product->name; ?></td>
									<td>
										<?php 
											$unidad = UnidadMedidaData::getById($product->unit);
											echo $unidad->sigla; 
										?>
									</td>
									<td>
									<b>S/&nbsp;
									<input type="text" name="f_price_in" value="<?php echo number_format($product->price_in,5,'.',','); ?>" style="width: 60px">
								    </b>
								    </td>
									<td>
										<?php
										if ($product->is_stock == 1) {
										 	echo $q;
										 } else{
										 	echo "Sin stock";
										 }
											 
										?>
										 
									</td>
									<td>
									<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
									<input type="number" class="form-control" required name="q" placeholder="Cantidad de producto ..." step="any"></td>
									<td style="width:100px;">
									<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Agregar</button>
									</td>
								</tr>
							</form>
					<?php endforeach;?>
				</table>
			<?php
		}
	?>
	<br><hr>
	<hr><br>
	<?php else:
?>

<?php endif; ?>

<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
	<tr class="danger">
		<th>Codigo</th>
		<th>Producto</th>
		<th>Mensaje</th>
	</tr>
	<?php foreach ($_SESSION["errors"]  as $error):
	$product = ProductData::getById($error["product_id"]);
	?>
	<tr class="danger">
		<td><?php echo $product->id; ?></td>
		<td><?php echo $product->name; ?></td>
		<td><b><?php echo $error["message"]; ?></b></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php
	unset($_SESSION["errors"]);
 endif; ?>

<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["reabastecer"])):
$total = 0;
?>
<h2>Lista de Reabastecimiento</h2>
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">Codigo</th>
	<th style="width:30px;">Cantidad</th>
	<th style="width:30px;">Unidad</th>
	<th>Producto</th>
	<th style="width:30px;">Precio Unitario</th>
	<th style="width:30px;">Precio Total</th>
	<th ></th>
</thead>
<?php foreach($_SESSION["reabastecer"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td><?php echo $product->barcode; ?></td>
	<td ><?php echo $p["q"]; ?></td>
	<td>
	<?php 
		$unidad = UnidadMedidaData::getById($product->unit);
		echo $unidad->sigla; 
	?>
	</td>
	<td><?php echo $product->name; ?></td>
	<td><b>S/ <?php echo number_format($p["price_in"], 5, '.', ','); ?></b></td>
	<td>
		<b>S/ <?php  $pt = $p["price_in"]*$p["q"]; 
		$total +=$pt; 
		echo number_format($pt, 2, '.', ','); ?>
		</b>
	</td>
	<td style="width:30px;">
		<a href="index.php?view=clearre&product_id=<?php echo $product->id; ?>" class="btn btn-danger">
			<i class="glyphicon glyphicon-remove"></i> Cancelar
		</a>
	</td>
</tr>
<?php endforeach; ?>
</table>
<form method="post" class="form-horizontal" id="processsell" action="index.php?view=processre">
	<h2>Resumen</h2>
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">Comprobante</label>
		<div class="col-lg-10">
			<label class="radio-inline"><input type="radio" name="optTipoComprobante" value="3">Boleta</label>
    	    <label class="radio-inline"><input type="radio" name="optTipoComprobante" value="1" checked>Factura</label>    	    
            <label class="radio-inline"><input type="radio" name="optTipoComprobante" value="60">Ingreso Diverso</label>
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">N° Comprobante</label>
		<div class="col-lg-4">
			<input type="text" name="serie" required class="form-control" id="serie" placeholder="Serie">
		</div>
		<div class="col-lg-5">
			<input type="text" name="comprobante" required class="form-control" id="comprobante" placeholder="Comprobante">
		</div>
	</div>
	<div class="form-group">
    	<label for="inputEmail1" class="col-lg-2 control-label">Proveedor</label>
    	<div class="col-lg-10">
    	<?php 
			$clients = PersonData::getProviders();
    	?>
    	<select name="client_id" class="form-control">
    		<option value="">-- NINGUNO --</option>
    		<?php foreach($clients as $client):?>
    		<option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    		<?php endforeach;?>
    	</select>
    </div>
  	</div>
	<div class="form-group">
    	<label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
    	<div class="col-lg-10">
      		<input type="text" name="money" required class="form-control" id="money"
      		value="<?php echo $total; ?>" 
      		 placeholder="Efectivo">
      	    <input type="hidden" name="total" required class="form-control" id="total"
      		value="<?php echo $total; ?>">	 
    	</div>
  	</div>
  	<div class="row">
		<div class="col-md-6 col-md-offset-6">
			<table class="table table-bordered">
				<!-- <tr>
					<td><p>Subtotal</p></td>
					<td><p><b>$ <?php //echo number_format($total*.84); ?></b></p></td>
				</tr>
				<tr>
					<td><p>IVA</p></td>
					<td><p><b>$ <?php //echo number_format($total*.16); ?></b></p></td>
				</tr> -->
				<tr>
					<td><p>Total</p></td>
					<td><p><b>S/ <?php echo number_format($total, 2, '.', ','); ?></b></p></td>
				</tr>
			</table>
		  	<div class="form-group">
		    	<div class="col-lg-offset-2 col-lg-10">
		      		<div class="checkbox">
		        		<label>
		          			<input name="is_oficial" type="hidden" value="1">
		        		</label>
		      		</div>
		    	</div>
		  	</div>
			<div class="form-group">
			    <div class="col-lg-offset-2 col-lg-10">
			    	<div class="checkbox">
			        	<label>
							<a href="index.php?view=clearre" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
			        		<button class="btn btn-lg btn-primary"><i class="fa fa-refresh"></i> Procesar Reabastecimiento</button>
			        	</label>
			      	</div>
			    </div>
			</div>
</form>
<script>
	/*$("#processsell").submit(function(e){
		money = $("#money").val();
		if(money<<?php echo $total;?>){
			alert("No se puede efectuar la operacion");
			e.preventDefault();
		}else{
			go = confirm("Cambio: S/ "+(money-<?php echo $total;?>));
			if(go){}
				else{e.preventDefault();}
		}
	});*/

	$("input[name=optTipoComprobante]").click(function () {
      var optTipoComprobante =  $('input:radio[name=optTipoComprobante]:checked').val();
     
      if(optTipoComprobante == 60)
      {
        /*$("#comprobante_boleta").show("slow");
        $("#comprobante_factura").hide("slow");*/
        $("#serie").val('0001');
        $("#comprobante").val(<?=$ORDEN?>)

        
      }
     /* else if (optTipoComprobante == 2)
      {
        $("#comprobante_boleta").hide("slow");
        $("#comprobante_factura").show("slow");
      }*/
    });
</script>
</div>
</div>

<br><br><br><br><br>
<?php endif; ?>

</div>
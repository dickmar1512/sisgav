<?php 
$is_desc = UserData::getById($_SESSION["user_id"])->is_desc;
if(isset($_GET["product"]) && $_GET["product"]!=""):?>
<?php
$products = ProductData::getLike(htmlspecialchars($_GET["product"],ENT_NOQUOTES,"UTF-8"));
if(count($products)>0){
	?>
		<h3>Resultados de la Busqueda</h3>
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>En inventario</th>
		<th style="width:700px;">
		<div class="label-group">
			<label style="width: 190px;"  >Descripción Adicional  </label>
			<label style="width: 100px;" >P.Uni. S/  </label>
			<label style="width: 100px;" >Desc S/  </label>
			<label style="width: 100px;" >Cantidad  </label>
			<label style="width: 100px;" >&nbsp;</label>
		</div>
		</th>
		<th class="hide"></th>
	</thead>
	<?php
	$products_in_cero=0;
	foreach($products as $product):
	 	$q = $product->stock;
	?>
	<?php 
	if($q>0 or $product->is_stock == 0):?>
		
	<style type="text/css">
		.seleccion::selection {
		    background: yellow;
   			border: 1px solid #39c !important;
		}

		/* Firefox */
		.seleccion::-moz-selection {
		    background: yellow;
   			border: 1px solid #39c !important;
		}
	</style>

	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;  font-size: 18px;"><?php echo $product->barcode; ?></td>
		<td style=" font-size: 18px;"><?php echo $product->name; ?><b>(<?php if($product->description!=''){echo $product->description; }else{ echo "Ninguno";}?>-<?=$product->anaquel?>)</b></td>
		<td><?php if($product->is_stock==0){ echo "Ilimitado";}else {echo $q;} ?></td>
		<td style="width:600px;">
			<form method="post" action="index.php?view=addtocart">
				<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
				<input type="hidden" name="idpaquete" value="X">
				<div class="input-group">
				<input type="descripcion" step="any" class="form-control seleccion" name="descripcion" placeholder="Descripcion" style="width: 200px; font-size: 18px;" min="0">
				<input type="number" step="any" class="form-control" required name="precio_unitario" placeholder="Precio Unitario" value="<?php echo $product->price_out?>" 
				style="width: 100px; font-size: 20px;" min="0">	
				<?php if($is_desc==1):?>
				<input type="number" step="any" class="form-control" required name="descuento" placeholder="Descuento" value="0.00" style="width: 100px; font-size: 20px;" min="0">
				<?php else:?>
					<label class="form-control" style="width: 100px; font-size: 20px;">0.00<input type="hidden" name="descuento" value="0"></label>
				<?php endif?>			
				<?php $permiso = PermisoData::get_permiso_x_key('decimales');
			  		if($permiso->Pee_Valor == 1){ ?>
			  			<input type="number" class="form-control" required name="q" placeholder="Cantidad ..." step='any' value="1" style="width: 90px; font-size: 21px; float: left;">

						<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
			  	<?php }
			  		else	{
				?>

					<input type="number" class="form-control" required name="q" placeholder="Cantidad ..." value="1" style="width: 90px; float: left; font-size: 21px;">
						<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
				<?php }	?>
      			</div>
      			<?php  ?>
			</form>
			</td>
		<td class="hide">
			<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		</td>
	</tr>
	
<?php else:$products_in_cero++;
?>
<?php  endif; ?>
	<?php endforeach;?>
</table>
<?php if($products_in_cero>0){ echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary'>Ir al Inventario</a></p>"; }?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
}
?>
<hr><br>
<?php else:
?>
<?php endif; ?>
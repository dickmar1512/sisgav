<section class="content-header">
	<a href="index.php?view=newproduct" class="btn btn-default pull-right">Agregar Producto/Servicio</a>
	<?php
				$permiso = PermisoData::get_permiso_x_key('descargar');
				if($permiso->Pee_Valor == 1)
				{
					?>
						<div class="btn-group pull-right">
			  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			    				<i class="fa fa-download"></i> Descargar <span class="caret"></span>
			  				</button>
			  				<ul class="dropdown-menu" role="menu">
			    				<li><a href="report/products-word.php">Word 2007 (.docx)</a></li>
			    			</ul>
						</div>
					<?php
				}
			?>
	<h1>Lista de Productos/Servicios(<a href="index.php?view=actstock" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>)</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- <div class="btn-group  pull-right">
				<a href="index.php?view=newproduct" class="btn btn-default">Agregar Producto/Servicio</a>
				<?php
					//$permiso = PermisoData::get_permiso_x_key('descargar');
					//if($permiso->Pee_Valor == 1)
					//{
						?>
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-download"></i> Descargar <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="report/products-word.php">Word 2007 (.docx)</a></li>
								</ul>
							</div>
						<?php
					//}
				?>
			</div>
			<h1>Lista de Productos/Servicios(<a href="index.php?view=actstock" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>)</h1>
			<div class="clearfix"></div> -->
			<!-- <div class="row">
			<div class="col-md-3">
				<span class="btn btn-primary" id="basic-addon1">Buscar</span>
			</div>
			<div class="col-md-8">
			<input id="FiltrarContenido" type="text" class="form-control" placeholder="Ingrese Nombre de Producto" aria-label="Producto" aria-describedby="basic-addon1">
			</div>
			</div> -->
	<?php
	$page = 1;
	if(isset($_GET["page"])){
		$page=$_GET["page"];
	}
	$limit=1000;
	if(isset($_GET["limit"]) && $_GET["limit"]!="" && $_GET["limit"]!=$limit){
		$limit=$_GET["limit"];
	}

	$products = ProductData::getAll2();
	if(count($products)>0)
	{

	if($page==1)
	{
	$curr_products = ProductData::getAllByPage(intval($products[0]->barcode),$limit);
	}
	else
	{
	$curr_products = ProductData::getAllByPage(intval($products[($page-1)*$limit]->barcode),$limit);
	}
	$npaginas = floor(count($products)/$limit);
	$spaginas = count($products)%$limit;

	if($spaginas>0){ $npaginas++;}
		?>
		<!-- <h3>Pagina <?php echo $page." de ".$npaginas; ?></h3> -->
		<div class="btn-group pull-right">
		<?php
			$px=$page-1;
			if($px>0):
			?>
				<!-- <a class="btn btn-sm btn-default" href="<?php echo "index.php?view=products&limit=$limit&page=".($px); ?>"><i class="glyphicon glyphicon-chevron-left"></i> Atras </a> -->
			<?php endif; ?>

			<?php 
			$px=$page+1;
			if($px<=$npaginas):
				?>
				<!-- <a class="btn btn-sm btn-default" href="<?php echo "index.php?view=products&limit=$limit&page=".($px); ?>">Adelante <i class="glyphicon glyphicon-chevron-right"></i></a> -->
			<?php endif; ?>
		</div>
	<div class="clearfix"></div>
	<!-- <br> -->
	<div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title"></h3>
        </div>
        <div class="box-body table-responsive">
			<table class="table table-bordered table-hover datatable">
				<thead>
					<th>Codigo</th>
					<th>Imagen</th>
					<th>Nombre</th>
					<th>Precio Entrada</th>
					<th>Precio Por Mayor</th>
					<th>Precio Salida</th>
					<th>Anaquel</th>
					<th>Stock</th>
					<th>Minima</th>
					<th>Activo</th>
					<th></th>
				</thead>
				<tbody class="BusquedaRapida">
				<?php foreach($curr_products as $product):?>
				<tr>
					<td><?php echo $product->barcode; ?></td>
					<td>
						<?php if($product->image!=""):?>
							<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
						<?php endif;?>
					</td>
					<td><?php echo $product->name; ?></td>
					<td>S/ <?php echo number_format($product->price_in,2,'.',','); ?></td>
					<td>S/ <?php echo number_format($product->price_may,2,'.',','); ?></td>
					<td>S/ <?php echo number_format($product->price_out,2,'.',','); ?></td>
					<td class="text-center">
						<?php 
							/*$unidad = UnidadMedidaData::getById($product->unit);
							echo $unidad->sigla; */
							echo $product->anaquel;
						?>	
					</td>
					<td>
						<?php //if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; } 
						echo $product->stock; ?></td>
					<td><?php echo $product->inventary_min; ?></td>
					<td><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
					

					<td style="width:70px;">
					<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
					<a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>		
	<div class="btn-group pull-right">
	<?php

	for($i=0;$i<$npaginas;$i++){
		echo "<a href='index.php?view=products&limit=$limit&page=".($i+1)."' class='btn btn-default btn-sm'>".($i+1)."</a> ";
	}
	?>
	</div>
	<!-- <form class="form-inline">
		<label for="limit">Limite</label>
		<input type="hidden" name="view" value="products">
		<input type="number" value=<?php echo $limit?> name="limit" style="width:60px;" class="form-control">
	</form> -->

	<div class="clearfix"></div>

		<?php
	}else{
		?>
		<div class="jumbotron">
			<h2>No hay productos/servicios</h2>
			<p>No se han agregado productos/servicios a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
		</div>
		<?php
	}

	?>
	<br><br><br><br><br><br><br><br><br><br>
		</div>
	</div>
</section>	
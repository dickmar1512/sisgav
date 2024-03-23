<section class="content-header">
<a href="index.php?view=newcategory" class='btn btn-default pull-right'><i class='fa fa-th-list'></i> Nueva Categoria</a>
			<h1><i class="fa fa-th-list"></i> Categorias</h1>
</section>
<section class="content">
<div class="row">
	<div class="col-md-12">
<!-- <div class="btn-group pull-right">
	<a href="index.php?view=newcategory" class="btn btn-default"><i class='fa fa-th-list'></i> Nueva Categoria</a>
</div>
		<h1>Categorias</h1>
<br> -->
		<?php

		$users = CategoryData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
	    <div class="box box-solid box-primary">	
		   	<div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <div class="box-body table-responsive">	
			<table class="table table-bordered table-hover datatable">
			<thead>
			<th>Nombre</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo $user->name." ".$user->lastname; ?></td>
				<td style="width:130px;"><a href="index.php?view=editcategory&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a> <a href="index.php?view=delcategory&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a></td>
				</tr>
				<?php

			}
			?>
		 </table>
		 </div>
	    </div>	
			<?php
		}else{
			echo "<p class='alert alert-danger'>No hay Categorias</p>";
		}
		?>


	</div>
</div>
	</section>
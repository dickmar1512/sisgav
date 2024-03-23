<section class="content-header">
<a href="index.php?view=newuser" class="btn btn-default pull-right"><i class='glyphicon glyphicon-user'></i> Nuevo Usuario</a>
		<h1><i class="fa fa-th-list"></i> Lista de Usuarios</h1>
</section>
<section class="content">
<div class="row">
	<div class="col-md-12">
	 	<div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <div class="box-body table-responsive">	
				<?php
				$users = UserData::getAll();
				if(count($users)>0){
					// si hay usuarios
					?>
					<table class="table table-bordered table-hover datatable">
					<thead>
					<th>Nombre completo</th>
					<th>Nombre de usuario</th>
					<th>Email</th>
					<th>Activo</th>
					<th>Admin</th>
					<th>Descuentos</th>
					<th></th>
					</thead>
					<?php
					foreach($users as $user){
						?>
						<tr>
						<td><?php echo $user->name." ".$user->lastname; ?></td>
						<td><?php echo $user->username; ?></td>
						<td><?php echo $user->email; ?></td>
						<td>
							<?php if($user->is_active):?>
								<i class="glyphicon glyphicon-ok"></i>
								<?php else :?>
								<i class="glyphicon glyphicon-ban-circle"></i>
							<?php endif; ?>
						</td>
						<td>
							<?php if($user->is_admin):?>
								<i class="glyphicon glyphicon-ok"></i>
								<?php else :?>
								<i class="glyphicon glyphicon-ban-circle"></i>
							<?php endif; ?>
						</td>
						<td>
							<?php if($user->is_desc):?>
								<i class="glyphicon glyphicon-ok"></i>
								<?php else :?>
								<i class="glyphicon glyphicon-ban-circle"></i>
							<?php endif; ?>
						</td>
						<td style="width:30px;"><a href="index.php?view=edituser&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a></td>
						</tr>
						<?php

					}
		          	echo "</table>";
				}
				else{
					// no hay usuarios
				}?>

			</div>
        </div>
	</div>
</div>
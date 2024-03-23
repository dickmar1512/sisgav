<section class="content-header">
	<a href="index.php?view=newclient" class="btn btn-default pull-right"><i class='fa fa-smile-o'></i> Nuevo Cliente</a>
	<div class="btn-group pull-right">				
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
					<li><a href="report/clients-word.php">Word 2007 (.docx)</a></li>
				</ul>
			</div>
		<?php
			}
		?>
	</div>
	<h1><i class="fa fa-th-list"></i> Directorio de Clientes</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">	
		<div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <div class="box-body table-responsive">		
			<!-- <br>
			<div class="row">
			<div class="col-md-3">
				<span class="btn btn-primary" id="basic-addon1">Buscar</span>
			</div>
			<div class="col-md-8">
			<input id="FiltrarContenido" type="text" class="form-control" placeholder="Ingrese Nombre de Cliente" aria-label="Cliente" aria-describedby="basic-addon1">
			</div>
			</div> -->
			<?php
				$users = PersonData::getClients();

				if(count($users) > 0)
				{
					?>
						<table class="table table-bordered table-hover datatable">
							<thead>
								<th>N° Documento</th>
								<th>Nombre completo</th>
								<?php
								$permiso = PermisoData::get_permiso_x_key('institucion');

								if($permiso->Pee_Valor == 1)
								{
								?>
									<th>Grado</th>
								<?php
								} 
								?>
								<th>Direccion</th>
								<th>Email</th>
								<th>Telefono</th>
								<td align='center'><i class='fa fa-gears'></i></td>
							</thead>
							<tbody class="BusquedaRapida">
							<?php
								foreach($users as $user)
								{
									?>
										<tr>
											<td><?php echo $user->numero_documento ?></td>
											<td>
												<?php echo $user->lastname." ".$user->name; ?>
											</td>
											<td><?php echo $user->address1; ?></td>
											<?php
												if($permiso->Pee_Valor == 1)
												{
												?>
													<td><?php echo $user->company; ?></td>
												<?php
												} 
											?>
											<td><?php echo $user->email1; ?></td>
											<td><?php echo $user->phone1; ?></td>
											<td style="width:130px;">
											<a href="index.php?view=editclient&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
											<a href="index.php?view=delclient&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
											</td>
										</tr>
									<?php
								}
							?>
							</tbody>
						</table>
					<?php
				}
				else
				{
					echo "<p class='alert alert-danger'>No hay clientes</p>";
				}
			?>
			</div>
			</div>
		</div>
	</div>
</section>	
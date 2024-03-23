<section class="content-header">
	<a href="index.php?view=newprovider" class="btn btn-default pull-right"><i class='fa fa-truck'></i> Nuevo Proveedor</a>
	<div class="btn-group pull-right">
		<button type="button" class="btn btn-default dropdown-toggle hide" data-toggle="dropdown">
		   	<i class="fa fa-download"></i> Descargar <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
		   	<li><a href="report/providers-word.php">Word 2007 (.docx)</a></li>
		</ul>
	</div>
			<h1><i class="fa fa-th-list"></i> Directorio de Proveedores</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body table-responsive">
				<!-- <div class="btn-group pull-right">
					<a href="index.php?view=newprovider" class="btn btn-default"><i class='fa fa-truck'></i> Nuevo Proveedor</a>
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-default dropdown-toggle hide" data-toggle="dropdown">
							<i class="fa fa-download"></i> Descargar <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="report/providers-word.php">Word 2007 (.docx)</a></li>
						</ul>
					</div>
				</div>
				<h1>Directorio de Proveedores</h1>
				<br> -->
				<?php
					$users = PersonData::getProviders();

					if(count($users)>0)
					{
						?>
							<table class="table table-bordered table-hover datatable">
								<thead>
									<th>N° Documento</th>
									<th>Nombre completo</th>
									<th>Direccion</th>
									<th>Email</th>
									<th>Telefono</th>
									<th></th>
								</thead>
								<?php
									foreach($users as $user)
									{
										?>
											<tr>
												<td><?php echo $user->numero_documento ?></td>
												<td>
													<?php echo $user->name." ".$user->lastname; ?>
														
												</td>
												<td><?php echo $user->address1; ?></td>
												<td><?php echo $user->email1; ?></td>
												<td><?php echo $user->phone1; ?></td>
												<td style="width:130px;">
													<a href="index.php?view=editprovider&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
													<a href="index.php?view=delprovider&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
												</td>
											</tr>
										<?php
									}

								?>
							</table>
						<?php
					}
					else
					{
						echo "<p class='alert alert-danger'>No hay proveedores</p>";
					}
				?>
				</div>
			</div>	
		</div>
	</div>
</section>	
<section class="content-header"> 
	<h1><i class="fa fa-tachometer"></i> Configuracion</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-10">
			<div class="box box-solid box-primary">
				<div class="box-body table-responsive">
					<?php
						$empresa = EmpresaData::getDatos();
					?>
					<form action="index.php?view=updateempresa" method="post" class="form-horizontal">
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">RUC:</label>
								<input type="number" name="ruc" value="<?php echo $empresa->Emp_Ruc ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Razón Social:</label>
								<input type="text" name="razon_social" value="<?php echo $empresa->Emp_RazonSocial ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Descripción:</label>
								<textarea name="descripcion" class="form-control"><?php echo $empresa->Emp_Descripcion ?></textarea>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Dirección:</label>
								<input type="text" name="direccion" value="<?php echo $empresa->Emp_Direccion ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Teléfono/Celular:</label>
								<input type="text" name="telefono" value="<?php echo $empresa->Emp_Telefono ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label">Correo:</label>
								<input type="text" name="celular" value="<?php echo $empresa->Emp_Celular ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-8 text-center">
							<button type="submit" class="btn btn-success">Actualizar</button>
						</div>
					</form>
				</div>	
			</div>	
		</div>
	</div>
</section>	


    <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
<?php 
  ####################### NUMERO DE REGISTRO DE COMPROBANTES ###############################
  $mysqli = new mysqli('localhost','tarosoft','armagedon','dbsigav',3306); //BASE DE DATOS

  $queryb = $mysqli->prepare("SELECT MAX(CAST(comprobante AS UNSIGNED)) as numb FROM boleta WHERE tipo='03'");//TABLA
  $queryb->execute();
  $resultado=$queryb->get_result();
  $fila = $resultado->fetch_assoc();
  $registros = $fila["numb"];

  $queryf = $mysqli->prepare("SELECT MAX(CAST(comprobante AS UNSIGNED)) as numf FROM factura WHERE tipo='01'");//TABLA
  $queryf->execute();
  $resultado2=$queryf->get_result();
  $fila2 = $resultado2->fetch_assoc();  
  $registros_factura = $fila2["numf"];
 
  $queryg = $mysqli->prepare("SELECT MAX(CAST(comprobante AS UNSIGNED)) as numf FROM guia WHERE tipo='09'");
  $queryg->execute();
  $resultado3=$queryg->get_result();
  $fila3 = $resultado3->fetch_assoc();  
  $registros_guia = $fila3["numf"];

  $queryp = $mysqli->prepare("SELECT * FROM sell WHERE tipo_comprobante = '11' ");
  $queryp->execute();
  $queryp->store_result();
  $registros_proforma = $queryp->num_rows;

  $query = $mysqli->prepare("SELECT * FROM sell WHERE tipo_comprobante = '70' ");
  $query->execute();
  $query->store_result();
  $registros_orden = $query->num_rows;

  $id_comprobante_actual = $registros + 1;
  $id_comprobante_actual_f = $registros_factura + 1;
  $id_comprobante_actual_g = $registros_guia + 1;
  $id_comprobante_actual_p = $registros_proforma + 1;
  $id_comprobante_actual_o = $registros_orden + 1;

  $empresa = EmpresaData::getDatos();
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

  function generaCerosSerie($numero2)
  {
  	$empresa = EmpresaData::getDatos();
    $largo_numero = strlen($numero2);//OBTENGO EL LARGO DEL NUMERO
    $largo_maximo = 3;//ESPECIFICO EL LARGO MAXIMO DE LA CADENA
    //if($empresa->Emp_Sucursal!=0){ $largo_maximo = 2; } //PARCHE OTRA SUCURSAL
    $agregar = $largo_maximo - $largo_numero;   //TOMO LA CANTIDAD DE 0 AGREGAR
    for($i =0; $i<$agregar; $i++)
    {
    	$numero2 = "0".$numero2;
    } //AGREGA LOS CEROS
    return $numero2; //RETORNA EL NUMERO CON LOS CEROS
  }

  //COMPROBANTE PRIMERA SERIE B001
  $NUMERO_COMPROBANTE=generaCerosComprobante($id_comprobante_actual);
  //CAPTAMOS SERIE DE B001 AL B999
  $NUMERO_SERIE = (int)( ( $id_comprobante_actual / 99999999 ) + 1 );
  if ($empresa->Emp_Sucursal==0) { 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==1){ 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==2){ 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==3){ 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==4){ 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==5){ 	$SERIE = "B".generaCerosSerie($NUMERO_SERIE+$empresa->Emp_Sucursal); }

  
  //COMPROBANTE SERIE B001 AL SUPERIOR
  if ($NUMERO_SERIE>1) {  $NUMERO_COMPROBANTE = $id_comprobante_actual % 99999999; }
  $COMPROBANTE=generaCerosComprobante($NUMERO_COMPROBANTE);

  //COMPROBANTE PRIMERA SERIE F001
  $NUMERO_COMPROBANTE_F = generaCerosComprobante($id_comprobante_actual_f);
  //CAPTAMOS SERIE DE F001 AL F999
  $NUMERO_SERIE_F = (int)( ( $id_comprobante_actual_f / 99999999 ) + 1 );
  
  if ($empresa->Emp_Sucursal==0) { 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==1){ 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==2){ 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==3){ 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==4){ 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  else if($empresa->Emp_Sucursal==5){ 	$SERIE_F = "F".generaCerosSerie($NUMERO_SERIE_F+$empresa->Emp_Sucursal); }
  //COMPROBANTE SERIE B001 AL SUPERIOR
  if ($NUMERO_SERIE_F>1) {  $NUMERO_COMPROBANTE_F = $id_comprobante_actual_f % 99999999; }
  $COMPROBANTE_F=generaCerosComprobante($NUMERO_COMPROBANTE_F);

   //COMPROBANTE PRIMERA SERIE T001
   $NUMERO_COMPROBANTE_G = generaCerosComprobante($id_comprobante_actual_g);
   //CAPTAMOS SERIE DE F001 AL F999
   $NUMERO_SERIE_G = (int)( ( $id_comprobante_actual_g / 99999999 ) + 1 );
   
   if ($empresa->Emp_Sucursal==0) { 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   else if($empresa->Emp_Sucursal==1){ 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   else if($empresa->Emp_Sucursal==2){ 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   else if($empresa->Emp_Sucursal==3){ 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   else if($empresa->Emp_Sucursal==4){ 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   else if($empresa->Emp_Sucursal==5){ 	$SERIE_G = "T".generaCerosSerie($NUMERO_SERIE_G+$empresa->Emp_Sucursal); }
   //COMPROBANTE SERIE B001 AL SUPERIOR
   if ($NUMERO_SERIE_G>1) {  $NUMERO_COMPROBANTE_G = $id_comprobante_actual_f % 99999999; }
   $COMPROBANTE_G=generaCerosComprobante($NUMERO_COMPROBANTE_G);
   
   $PROFORMA = generaCerosComprobante($id_comprobante_actual_p);
   $ORDEN = generaCerosComprobante($id_comprobante_actual_o);
  #####################################################################################
  $empresa = EmpresaData::getDatos();
?>
<div class="row">
	<div class="col-md-8">
	<h1>Venta</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form id="searchp">
			<div class="row">
				<div class="col-md-8">
					<input type="hidden" name="view" value="sell">
					<input type="text" id="product_code2" autofocus name="product" class="form-control">
				</div>
				<div class="col-md-3">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
				</div>
			</div>
		</form>
		<p><b>Buscar Kit por nombre o por codigo:</b></p>
		<form id="searchk">
			<div class="row">
				<div class="col-md-8">
					<input type="hidden" name="view" value="sell">
					<input type="text" id="kit_code2" autofocus name="kit" class="form-control">
				</div>
				<div class="col-md-3">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4">
		<?php 
		$supertotal= 0;
		$subt =0;

				if (isset($_SESSION["cart"]))
				 {
					foreach($_SESSION["cart"] as $p):

					$subt = round($p["precio_unitario"]-$p["descuento"], 7)*round($p["q"]*$p["qp"], 2); 
					$supertotal +=$subt; //echo round($pt, 2); 
				endforeach;
				}
				
		 ?>
		<table class="table table-bordered">
		<tr><td style="font-size: 35px; background: green; color: #fff"><b>TOTAL: S/ <?php echo number_format($supertotal,2) ?></b></td></tr>
		<tr class="hide"><td><h4><b><?php echo $supertotal ?></b></h4></td></tr>
		</table>
	</div>
<div id="show_search_results"></div>

<script>
$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();
		
		$.get("./?action=searchproduct",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_code2").val("");
	});
});

$(document).ready(function(){
	$("#searchk").on("submit",function(e){
		e.preventDefault();
		
		$.get("./?action=searchkit",$("#searchk").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#kit_code2").val("");
	});
});

$(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});
</script>

<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>ID</th>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
$product = ProductData::getById($error["product_id"]);

?>
<tr class="danger">
	<td><?php echo $product->id; ?></td>
	<td><?php echo $product->barcode; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo $error["message"]; ?></b></td>
</tr>

<?php endforeach; ?>
</table>
<?php
unset($_SESSION["errors"]);
 endif; ?>

<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cart"])):
$total = 0;
//print_r($_SESSION["cart"]);
?>
<div class="col-md-12">
	<hr>
	<h2>Lista de venta</h2>
	<table class="table table-bordered table-hover">
		<thead>
			<th style="width: 6px">Nº</th>
			<th style="width:20px;">Codigo</th>
			<th style="width:20px;">Cantidad</th>
			<th>Producto</th>
			<th>Anaquel</th>
			<th style="width:100px;">Precio Unitario</th>
			<th style="width:25px;">Descuento</th>
			<th style="width:70px;">Precio Total</th>
			<th ></th>
		</thead>
		<?php $contador=0; 
		?>
		<?php foreach($_SESSION["cart"] as $p):
			$contador++;
			$product = ProductData::getById($p["product_id"]);
			if($product->is_may==1)
			{$precio=$product->price_may;}
			if($product->is_may==0)
			{$precio=$p["precio_unitario"];}
			?>
			<tr >
				<td style="background-color: #444; color: #FFF"><?php echo $contador; ?></td>
				<td><?php echo $product->barcode; ?></td>
				<td ><?php echo round($p["q"]*$p["qp"],3); ?></td>
				<td>
					<?php echo $product->name; ?>
					<?php
						if($product->description != "")
						{
							echo "<b>(".$product->description.")</b>";
						}
						$dsctotal =0;
					?>	
				</td>
				<td><?=$product->anaquel?></td>
				<td><b>S/ <?php echo number_format($p["precio_unitario"], 4, '.', ','); ?></b></td>
				<td><b>S/ <?php echo number_format($p["descuento"], 2, '.', ','); ?></b></td>
				<td><b>S/ <?php  
				$pt = number_format($precio-$p["descuento"], 5)*round($p["q"]*$p["qp"], 3); 
				$total +=$pt; 
				$dsctotal+=$p["descuento"]*$p["q"]*$p["qp"];
				echo number_format($pt, 2); 
				?>
					
				</b></td>
				<td style="width:30px;"><a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
	
<?php
		//COMPROBAMOS QUE LOS DATOS SEAN VALIDOS
		$total = round($total, 2);
 ?>

<div class="col-md-12">
	<?php
		$permiso = PermisoData::get_permiso_x_key('proforma');
		$permiso2 = PermisoData::get_permiso_x_key('comprobantes_fisicos');
		$permiso3 = PermisoData::get_permiso_x_key('otro_documento_no_dni');
		//print_r($_SESSION["cart"]);
	?>
	<h2 align="left">Resumen</h2>
	<?php 
	
	 ?>
	<h1 class="hide" align="right" style="font-size: 50px">Total: S/ <?php echo number_format($total,2,'.',','); ?></h1>

    <div class="row text-center">		
		<label class="radio-inline"><input type="radio" name="optTipoComprobante" value="1" checked>FACTURA</label>
        <label class="radio-inline"><input type="radio" name="optTipoComprobante" value="3" >BOLETA</label>		
		<label class="radio-inline"><input type="radio" name="optTipoComprobante" value="11">PROFORMA</label>
		<label class="radio-inline"><input type="radio" name="optTipoComprobante" value="9">GUIA DE REMISION REMITENTE</label>
        <label class="radio-inline"><input type="radio" name="optTipoComprobante" value="0">ORDEN VENTA</label>
    </div>

    <hr>
    <div id="comprobante_boleta" style="display: none">
      	<form action="index.php?view=addboleta" class="form-horizontal" method="post" onsubmit="return enviado2()">
      		<input type="hidden" name="person_id" value="">
			
			<div class="row">
	        	<div class="col-md-12">
          			<input type="hidden" name="total" value="<?php echo number_format($total,2,'.',''); ?>">
	                <input type="hidden" name="RUC" value="<?php echo $empresa->Emp_Ruc; ?>">
	                <input type="hidden" name="TIPO" value="03">
	                <input type="hidden" name="tipOperacion" value="0101">
	                <!-- input type="hidden" name="fecEmision" value="<?php echo date("Y-m-d");?>">
	                <input type="hidden" name="horEmision" value="<?php echo date('H:i:s');?>"> -->
	                <input type="hidden" name="fecVencimiento" value="-">
	                <input type="hidden" name="codLocalEmisor" value="<?php echo $empresa->Emp_Sucursal; ?>">
	                <input type="hidden" name="tipMoneda" value="PEN">
	                <input type="hidden" name="porDescGlobal" value="-">
	                <input type="hidden" name="mtoDescGlobal" value="0">
	                <input type="hidden" name="mtoBasImpDescGlobal" value="0">
	                <input type="hidden" name="sumTotTributos" value="0">
	                <input type="hidden" name="sumDescTotal" value="<?=$dsctotal?>">
	                <input type="hidden" name="sumOtrosCargos" value="0">
	                <input type="hidden" name="sumTotalAnticipos" value="0">
	                <input type="hidden" name="ublVersionId" value="2.1">
	                <input type="hidden" name="customizationId" value="2.0">
	                <div class="form-group hide">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha:</label>
	                    <div class="col-md-6">
	                    	
	                      <input type="" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
	                    </div>	                   
	                </div>
	                <div class="form-group">
	                	<label for="inputEmail1" class="col-lg-2 control-label">Comprobante:</label>
			                <div class="col-md-3">
		                	<select name="selEstado" class="form-control">
			  					<option value="1">Electrónico</option>
			  					<?php 
			  					if($permiso->Pee_Valor == 1)
			  					{
			  						?>
			  							<option value="2">Proforma</option>
			  						<?php
			  					}
			  					if($permiso2->Pee_Valor == 1)
			  					{
			  						?>
			  							<option value="3">Físico</option>
			  						<?php
			  					}
			  					?>
		  					</select>
		                </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">TIPO DE PAGO:</label>
	                    <div class="col-md-3">
	                    	<select name="selTipoPago" class="form-control">
	                      		<option value="1">Efectivo</option>
	                      		<option value="2">Tarjeta</option>
	                      	</select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha:</label>
	                    <div class="col-md-3">
	                      <input type="date" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">Hora:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="horEmision" id="horEmision" class="form-control" value="<?php echo date('H:i:s');?>">
	                    </div>
	                </div>
		    		<div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">SERIE:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="SERIE" id="SERIE" class="form-control" value="<?php echo $SERIE; ?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">COMPROBANTE:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="COMPROBANTE" id="COMPROBANTE" class="form-control" value="<?php echo $COMPROBANTE; ?>">
	                    </div>
	                </div>
		    		<div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">
	                    	<?php 
	                      		if($permiso3->Pee_Valor == 1) 
	                      			{echo 'Documento: ';} 
	                      			else{echo 'DNI';}
	                      	?>
	                      				
	                      			</label>
	                    <div class="col-md-8">
	                      <input type="number" name="numDocUsuario" id="numDocUsuario" class="form-control" placeholder="DNI" onblur="
	                      <?php 
						  echo 'fnBuscarDNI()';
	                      /*if($permiso3->Pee_Valor == 0) 
	                      	{echo 'validar_no_dni()';} 
	                      else{echo 'validar_dni()';}*/ ?>" 
	                      required="" value="00000000">
	                    </div>
	                </div>
	                <div id="datos3">
	                	<div class="form-group">
	                    	<label for="inputEmail1" class="col-lg-2 control-label">Nombres y Apellidos:</label>
		                    <div class="col-md-8">
		                    	<input type="text" name="rznSocialUsuario" class="form-control" id="rznSocialUsuario" value="Cliente General" placeholder="Nombres y apellidos" required="">
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Distrito</label>
		                    <div class="col-md-8">
		                    	<select name="codUbigeoCliente" id="codUbigeoCliente" class="form-control">
		                        	<option value="">::Seleccione::</option>
									<?php $objUbigeo = UbigeoData::getAllTipo('D');
									  foreach($objUbigeo as $ubigeo) :
									?>
		                        	<option value="<?=$ubigeo->codigo?>"><?=$ubigeo->descripcion?></option>
									<?php endforeach; ?>
		                      	</select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Dirección:</label>
		                    <div class="col-md-8">
		                    	<input type="text" name="desDireccionCliente" class="form-control" id="desDireccionCliente" value="" placeholder="Dirección">
		                    </div>
		                </div>
	                </div>
					<div class="form-group">
					    <label for="inputEmail1" class="col-lg-2 control-label">Descuento</label>
					    <div class="col-lg-2">
					      <input type="number" name="discount" class="form-control" required value="<?=number_format($dsctotal,2)?>" id="discount" placeholder="Descuento" step="any">
					    </div>
						
				    	<label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
				    	<div class="col-lg-2">
				      		<input type="number" name="money" required class="form-control" id="money" placeholder="Efectivo" step="any" value="<?php echo $total; ?>">
				    	</div>
					 </div>
				 	<!-- <div class="form-group">
				    	<label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
				    	<div class="col-lg-2">
				      		<input type="number" name="money" required class="form-control" id="money" placeholder="Efectivo" step="any" value="<?php echo $total; ?>">
				    	</div>
				  	</div> -->
				  	<input type="hidden" name="tipDocUsuario" value="1">
					<input type="hidden" name="codUnidadMedida" value="NIU">
					<input type="hidden" name="codProducto" value="0">
					<input type="hidden" name="codProductoSUNAT" value="-">
					<input type="hidden" name="sumTotTributosItem" value="0">
					<input type="hidden" name="codTriIGV" value="9997">
					<input type="hidden" name="mtoIgvItem" value="0">
					<input type="hidden" name="nomTributoIgvItem" value="EXO">
					<input type="hidden" name="codTipTributoIgvItem" value="VAT">
					<input type="hidden" name="codCatTributoIgvItem" value="E">
					<input type="hidden" name="tipAfeIGV" value="20">
					<input type="hidden" name="mtoIscItem" value="0">
					<input type="hidden" name="tipSisISC" value="">
					<input type="hidden" name="porIgvItem" value="0">
					<input type="hidden" name="codTriISC" value="-">
					<input type="hidden" name="mtoIscItem" value="">
					<input type="hidden" name="nomTributoIscItem" value="">
					<input type="hidden" name="codTipTributoIscItem" value="-">
					<input type="hidden" name="codCatTributoIscItem" value="-">
					<input type="hidden" name="tipSisISC" value="">
					<input type="hidden" name="porIscItem" value="">
					<input type="hidden" name="mtoValorReferencialUnitario" value="0">
					<input type="hidden" name="codTipDescuentoItem" value="-">
					<input type="hidden" name="porDescuentoItem" value="0">
					<input type="hidden" name="mtoDescuentoItem" value="0">
					<input type="hidden" name="mtoBasImpDescuentoItem" value="0">
					<input type="hidden" name="codTipCargoItem" value="-">
					<input type="hidden" name="porCargoItem" value="0">
					<input type="hidden" name="mtoCargoItem" value="0">
					<input type="hidden" name="mtoBasImpCargoItem" value="0">
					<input type="hidden" name="ideTributo" value="9997">
					<input type="hidden" name="nomTributo" value="EXO">
					<input type="hidden" name="codTipTributo" value="VAT">
					<input type="hidden" name="codCatTributo" value="E">
					<input type="hidden" name="mtoTributo" value="0">
				    <div class="row">
						<div class="col-md-6 col-md-offset-6">
							<table class="table table-bordered">
								<tr>
									<td><p>Total</p></td>
									<td><p><b>S/ <?php echo number_format($total,2,'.',','); ?></b></p></td>
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
											<a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
							        		<button class="btn btn-lg btn-primary">S/. Finalizar Venta</button>
							        	</label>
							      	</div>
							    </div>
							</div>
						</div>
					</div>
	              </div>
	            </div>
	          </div>
	        </div>
      	</form>
    </div>
    <div id="comprobante_factura">
        <form action="index.php?view=addfactura" class="form-horizontal" method="post" onsubmit="return enviado2()">
        	<div class="row">
	            <div class="col-md-12">
	            	<input type="hidden" name="total" 
	            	value="<?php echo number_format($total,2,'.',''); ?>">
	                <input type="hidden" name="RUC" value="<?php echo $empresa->Emp_Ruc; ?>">
	                <input type="hidden" name="TIPO" value="01">
	                <input type="hidden" name="tipOperacion" value="0101">	               
	                <input type="hidden" name="fecVencimiento" value="-">
	                <input type="hidden" name="codLocalEmisor" value="0000">
	                <input type="hidden" name="tipMoneda" value="PEN">
	                <input type="hidden" name="porDescGlobal" value="-">
	                <input type="hidden" name="mtoDescGlobal" value="0">
	                <input type="hidden" name="mtoBasImpDescGlobal" value="0">
	                <input type="hidden" name="sumTotTributos" value="0">
	                <input type="hidden" name="sumDescTotal" value="<?=$dsctotal?>">
	                <input type="hidden" name="sumOtrosCargos" value="0">
	                <input type="hidden" name="sumTotalAnticipos" value="0">
	                <input type="hidden" name="ublVersionId" value="2.1">
	                <input type="hidden" name="customizationId" value="2.0">
	               
	                <div class="form-group">
	                	<label for="inputEmail1" class="col-lg-2 control-label">Comprobante:</label>
			                <div class="col-md-3">
		                	<select name="selEstado" class="form-control">
			  					<option value="1">Electrónico</option>
			  					<?php 
			  					if($permiso->Pee_Valor == 1)
			  					{
			  						?>
			  							<option value="2">Proforma</option>
			  						<?php
			  					}
			  					if($permiso2->Pee_Valor == 1)
			  					{
			  						?>
			  							<option value="3">Físico</option>
			  						<?php
			  					}
			  					?>
		  					</select>
		                </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">TIPO DE PAGO:</label>
	                    <div class="col-md-3">
	                    	<select name="selTipoPago" class="form-control">
	                      		<option value="1">Efectivo</option>
	                      		<option value="2">Tarjeta</option>
	                      	</select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha:</label>
	                    <div class="col-md-3">
	                      <input type="date" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">Hora:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="horEmision" id="horEmision" class="form-control" value="<?php echo date('H:i:s');?>">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">SERIE:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="SERIE" id="SERIE" class="form-control" value="<?php echo $SERIE_F; ?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">COMPROBANTE:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="COMPROBANTE" id="COMPROBANTE" class="form-control" value="<?php echo $COMPROBANTE_F; ?>">
	                    </div>
		            </div>
		            <div class="form-group">
	                	<label for="inputEmail1" class="col-lg-2 control-label">RUC:</label>
	                  	<div class="col-md-3">
	                    	<input type="number" name="numDocUsuario" class="form-control" id="ruc" placeholder="Nº de documento" onblur="fnBuscarRUC()" required="required">
	                  	</div>
						<label for="inputEmail1" class="col-lg-2 control-label">FORMA DE PAGO:</label>
	                    <div class="col-md-3">
	                    	<select name="selFormaPago" class="form-control">
	                      		<option value="CONTADO">CONTADO</option>
	                      		<option value="CREDITO">CREDITO</option>
	                      	</select>
	                    </div>
	                </div>
	                <div id="datos">
	                	<div class="form-group">
		              		<label for="inputEmail1" class="col-lg-2 control-label">Razón Social:</label>
		              		<div class="col-md-8">
		                		<input type="text" name="rznSocialUsuario" class="form-control" id="rznSocialUsuario" value="" placeholder="Nombres y apellidos" required="required">
		              		</div>
		            	</div>
		            	<div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Distrito</label>
		                    <div class="col-md-8">
		                    	<select name="codUbigeoCliente" id="codUbigeoCliente" class="form-control">
		                        	<option value="">::Seleccione::</option>
		                        	<?php $objUbigeo = UbigeoData::getAllTipo('D');
									  foreach($objUbigeo as $ubigeo) :
									?>
		                        	<option value="<?=$ubigeo->codigo?>"><?=$ubigeo->descripcion?></option>
									<?php endforeach; ?>
		                      	</select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Dirección:</label>
		                    <div class="col-md-8">
		                    	<input type="text" name="desDireccionCliente" class="form-control" id="desDireccionCliente" value="" placeholder="Dirección">
		                    </div>
		                </div>
	                </div>
					<div class="form-group">
					    <label for="inputEmail1" class="col-lg-2 control-label">Descuento</label>
					    <div class="col-lg-3">
					      <input type="text" name="discount" class="form-control" required value="<?=$dsctotal?>" id="discount2" placeholder="Descuento">
					    </div>
						
				    	<label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
				    	<div class="col-lg-3">
				      		<input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo" value="<?php echo $total; ?>">
				    	</div>
					 </div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Observacion</label>
						<div class="col-lg-8">
				      		<textarea id="txtObservacion" name="txtObservacion" class="form-control" placeholder="Observacion" rows="3" value="<?php echo ""; ?>"></textarea>
				    	</div>
					</div>
				  	<input type="hidden" name="tipDocUsuario" value="6">
					<input type="hidden" name="codUnidadMedida" value="NIU">
					<input type="hidden" name="codProducto" value="0">
					<input type="hidden" name="codProductoSUNAT" value="-">
					<input type="hidden" name="sumTotTributosItem" value="0">
					<input type="hidden" name="codTriIGV" value="9997">
					<input type="hidden" name="mtoIgvItem" value="0">
					<input type="hidden" name="nomTributoIgvItem" value="EXO">
					<input type="hidden" name="codTipTributoIgvItem" value="VAT">
					<input type="hidden" name="codCatTributoIgvItem" value="E">
					<input type="hidden" name="tipAfeIGV" value="20">
					<input type="hidden" name="mtoIscItem" value="0">
					<input type="hidden" name="tipSisISC" value="">
					<input type="hidden" name="porIgvItem" value="0">
					<input type="hidden" name="codTriISC" value="-">
					<input type="hidden" name="mtoIscItem" value="">
					<input type="hidden" name="nomTributoIscItem" value="">
					<input type="hidden" name="codTipTributoIscItem" value="-">
					<input type="hidden" name="codCatTributoIscItem" value="-">
					<input type="hidden" name="porIscItem" value="">
					<input type="hidden" name="mtoValorReferencialUnitario" value="0">
					<input type="hidden" name="codTipDescuentoItem" value="-">
					<input type="hidden" name="porDescuentoItem" value="0">
					<input type="hidden" name="mtoDescuentoItem" value="0">
					<input type="hidden" name="mtoBasImpDescuentoItem" value="0">
					<input type="hidden" name="codTipCargoItem" value="-">
					<input type="hidden" name="porCargoItem" value="0">
					<input type="hidden" name="mtoCargoItem" value="0">
					<input type="hidden" name="mtoBasImpCargoItem" value="0">
					<input type="hidden" name="ideTributo" value="9997">
					<input type="hidden" name="nomTributo" value="EXO">
					<input type="hidden" name="codTipTributo" value="VAT">
					<input type="hidden" name="codCatTributo" value="E">
					<input type="hidden" name="mtoTributo" value="0">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<table class="table table-bordered">
								<tr>
									<td><p>Total</p></td>
									<td><p><b>S/ <?php echo number_format($total,2,'.',','); ?></b></p></td>
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
											<a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
							        		<button class="btn btn-lg btn-primary">S/. Finalizar Venta</button>
							        	</label>
							      	</div>
							    </div>
							</div>
						</div>
					</div>
	            </div>
          	</div>
        </form>
    </div>
	<div id="comprobante_guiarem" style="display:none;">
        <form action="index.php?view=addguia" class="form-horizontal" method="post">
        	<div class="row">
	            <div class="col-md-12">
	            	<input type="hidden" name="total" 
	            	value="<?php echo number_format($total,2,'.',''); ?>">
	                <input type="hidden" name="RUC" value="<?php echo $empresa->Emp_Ruc; ?>">
	                <input type="hidden" name="TIPO" value="01">
	                <input type="hidden" name="tipOperacion" value="0101">	               
	                <input type="hidden" name="fecVencimiento" value="-">
	                <input type="hidden" name="codLocalEmisor" value="0000">
	                <input type="hidden" name="tipMoneda" value="PEN">
	                <input type="hidden" name="porDescGlobal" value="-">
	                <input type="hidden" name="mtoDescGlobal" value="0">
	                <input type="hidden" name="mtoBasImpDescGlobal" value="0">
	                <input type="hidden" name="sumTotTributos" value="0">
	                <input type="hidden" name="sumDescTotal" value="<?=$dsctotal?>">
	                <input type="hidden" name="sumOtrosCargos" value="0">
	                <input type="hidden" name="sumTotalAnticipos" value="0">
	                <input type="hidden" name="ublVersionId" value="2.1">
	                <input type="hidden" name="customizationId" value="2.0">
	               
	                <div class="form-group">
						<input type="hidden" name="selEstado" id="selEstado" value="1">
						<input type="hidden" name="selTipoPago" id="selTipoPago" value="1">
	                </div>
					
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Serie:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="SERIE" id="SERIE" class="form-control" value="<?php echo $SERIE_G; ?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">Comprobante:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="COMPROBANTE" id="COMPROBANTE" class="form-control" value="<?php echo $COMPROBANTE_G; ?>">
	                    </div>
		            </div>

	                <div id="datosgr">
	                	<div class="form-group">
							<label for="inputEmail1" class="col-lg-2 control-label">RUC:</label>
							<div class="col-md-2">
								<input type="number" name="numDocUsuario" class="form-control" id="ruc" placeholder="Nº de documento" onblur="fnBuscarDocguiaremision()" required="required">
							</div>
		              		<label for="inputEmail1" class="col-lg-2 control-label">Razón Social:</label>
		              		<div class="col-md-4">
		                		<input type="text" name="rznSocialUsuario" class="form-control" id="rznSocialUsuario" value="" placeholder="Nombres y apellidos" required="required">
		              		</div>
		            	</div>

						<div class="form-group">
							<label for="inputEmail1" class="col-lg-2 control-label">Fecha de Emision:</label>
							<div class="col-md-3">
							<input type="date" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
							</div>
							<label for="inputEmail1" class="col-lg-2 control-label">Inicio del traslado:</label>
							<div class="col-md-3">
							<input type="date" name="fecInicioTraslado" id="fecInicioTraslado" class="form-control" value="<?php echo date('Y-m-d');?>">
							</div>
						</div>

						<div class="form-group">
							<label for="selMotivoTraslado"class="col-lg-2 control-label">Motivo Traslado:</label>
							<div class="col-md-3">
								<select id="selMotivoTraslado" name="selMotivoTraslado" class="form-control">
									<option value="01" selected>Traslado por Venta</option>
									<option value="02">Traslado por Compra</option>
									<option value="04">Traslado entre establecimientos de la misma empresa</option>
									<option value="14">Traslado por Venta sujeta a confirmación del comprador </option>
									<option value="13">Traslado por otros Motivos</option>
								</select>
							</div>
							<label for="selModalidadTraslado"class="col-lg-2 control-label">Modalidad Traslado:</label>
							<div class="col-md-3">
								<select id="selModalidadTraslado" name="selModalidadTraslado" class="form-control">
									<option value="01">Transporte público</option>
									<option value="02" selected>Transporte privado</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="inputEmail1" class="col-lg-2 control-label">DNI Conductor:</label>
							<div class="col-md-1">
								<input type="number" name="numDocConductor" class="form-control" id="numDocConductor" placeholder="Nº DNI" onblur="validar_dniConductor()" required="required">
							</div>
		              		<label for="inputEmail1" class="col-lg-1 control-label">Nombres:</label>
		              		<div class="col-md-2">
		                		<input type="text" name="nombres" class="form-control" id="nombres" value="" placeholder="Nombres Conductor" required="required">
		              		</div>
							<label for="inputEmail1" class="col-lg-1 control-label">Apellidos:</label>
		              		<div class="col-md-3">
		                		<input type="text" name="apellidos" class="form-control" id="apellidos" value="" placeholder="Apellidos Conductor" required="required">
		              		</div>
		            	</div>

						<div class="form-group">
							<label for="inputEmail1" class="col-lg-2 control-label">Licencia:</label>
							<div class="col-md-3">
								<input type="text" name="licencia" class="form-control" id="licencia" placeholder="Licencia de conducir" required="required">
							</div>
		              		<label for="inputEmail1" class="col-lg-1 control-label">Placa:</label>
		              		<div class="col-md-3">
		                		<input type="text" name="placa" class="form-control" id="placa" value="" placeholder="XXXXXX" required="required">
		              		</div>
		            	</div>
		            	<div class="form-group">
						    <label for="inputEmail1" class="col-lg-2 control-label">Punto de partida:</label>
		                    <label for="inputEmail1" class="col-lg-1 control-label">Distrito</label>
		                    <div class="col-md-2">
		                    	<select name="codUbigeoPartida" class="form-control">
		                        	<option value="">::Seleccione::</option>
		                        	<?php $objUbigeo = UbigeoData::getAllTipo('D');
									  foreach($objUbigeo as $ubigeo) :
									?>
		                        	<option value="<?=$ubigeo->codigo?>"><?=$ubigeo->descripcion?></option>
									<?php endforeach; ?>
		                      	</select>
		                    </div>
							<label for="inputEmail1" class="col-lg-1 control-label">Dirección:</label>
		                    <div class="col-md-3">
		                    	<input type="text" name="desDireccionPartida" class="form-control" id="desDireccionPartida" value="" placeholder="Dirección Partida">
		                    </div>
		                </div>

						<div class="form-group">
							<label for="inputEmail1" class="col-lg-2 control-label">Punto de llegada</label>
		                    <label for="inputEmail1" class="col-lg-1 control-label">Distrito</label>
		                    <div class="col-md-2">
		                    	<select name="codUbigeoLlegada" id="codUbigeoLlegada" class="form-control">
		                        	<option value="">::Seleccione::</option>
		                        	<?php $objUbigeo = UbigeoData::getAllTipo('D');
									  foreach($objUbigeo as $ubigeo) :
									?>
		                        	<option value="<?=$ubigeo->codigo?>"><?=$ubigeo->descripcion?></option>
									<?php endforeach; ?>
		                      	</select>
		                    </div>
							<label for="inputEmail1" class="col-lg-1 control-label">Dirección:</label>
		                    <div class="col-md-3">
		                    	<input type="text" name="desDireccionLlegada" class="form-control" id="desDireccionLlegada" value="" placeholder="Dirección Llegada">
		                    </div>
		                </div>
	                </div>
					<div class="form-group">
					    <label for="inputEmail1" class="col-lg-2 control-label">Peso bruto total:</label>
					    <div class="col-lg-3">
							<select name="unidadMedida" class="form-control">
		                       	<option value="">::Seleccione::</option>
		                       	<option value="KGM">kilogramos</option>
		                       	<option value="TNE">Toneladas</option>
		                    </select>
					    </div>
				    	<div class="col-lg-3">
				      		<input type="number" name="pesoBruto" required class="form-control" id="pesoBruto" placeholder="Peso bruto">
				    	</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Observacion</label>
						<div class="col-lg-8">
				      		<textarea id="txtObservacion" name="txtObservacion" class="form-control" placeholder="Observacion" rows="3" value="<?php echo ""; ?>"></textarea>
				    	</div>
					</div>
				  	<input type="hidden" name="tipDocUsuario" value="6">
					<input type="hidden" name="codUnidadMedida" value="NIU">
					<input type="hidden" name="codProducto" value="0">
					<input type="hidden" name="codProductoSUNAT" value="-">
					<input type="hidden" name="sumTotTributosItem" value="0">
					<input type="hidden" name="codTriIGV" value="9997">
					<input type="hidden" name="mtoIgvItem" value="0">
					<input type="hidden" name="nomTributoIgvItem" value="EXO">
					<input type="hidden" name="codTipTributoIgvItem" value="VAT">
					<input type="hidden" name="codCatTributoIgvItem" value="E">
					<input type="hidden" name="tipAfeIGV" value="20">
					<input type="hidden" name="mtoIscItem" value="0">
					<input type="hidden" name="tipSisISC" value="">
					<input type="hidden" name="porIgvItem" value="0">
					<input type="hidden" name="codTriISC" value="-">
					<input type="hidden" name="mtoIscItem" value="">
					<input type="hidden" name="nomTributoIscItem" value="">
					<input type="hidden" name="codTipTributoIscItem" value="-">
					<input type="hidden" name="codCatTributoIscItem" value="-">
					<input type="hidden" name="porIscItem" value="">
					<input type="hidden" name="mtoValorReferencialUnitario" value="0">
					<input type="hidden" name="codTipDescuentoItem" value="-">
					<input type="hidden" name="porDescuentoItem" value="0">
					<input type="hidden" name="mtoDescuentoItem" value="0">
					<input type="hidden" name="mtoBasImpDescuentoItem" value="0">
					<input type="hidden" name="codTipCargoItem" value="-">
					<input type="hidden" name="porCargoItem" value="0">
					<input type="hidden" name="mtoCargoItem" value="0">
					<input type="hidden" name="mtoBasImpCargoItem" value="0">
					<input type="hidden" name="ideTributo" value="9997">
					<input type="hidden" name="nomTributo" value="EXO">
					<input type="hidden" name="codTipTributo" value="VAT">
					<input type="hidden" name="codCatTributo" value="E">
					<input type="hidden" name="mtoTributo" value="0">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<table class="table table-bordered">
								<tr>
									<td><p>Total</p></td>
									<td><p><b>S/ <?php echo number_format($total,2,'.',','); ?></b></p></td>
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
											<a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
							        		<button class="btn btn-lg btn-primary">S/. Finalizar Venta</button>
							        	</label>
							      	</div>
							    </div>
							</div>
						</div>
					</div>
	            </div>
          	</div>
        </form>
    </div>
	<div id="comprobante_proforma" style="display:none;">
        <form action="index.php?view=addproforma" class="form-horizontal" method="post" onsubmit="return true">
        	<div class="row">
	            <div class="col-md-12">
	            	<input type="hidden" name="total" 
	            	value="<?php echo number_format($total,2,'.',''); ?>">
	                <input type="hidden" name="RUC" value="<?php echo $empresa->Emp_Ruc; ?>">
	                <input type="hidden" name="TIPO" value="01">
	                <input type="hidden" name="tipOperacion" value="0101">	               
	                <input type="hidden" name="fecVencimiento" value="-">
	                <input type="hidden" name="codLocalEmisor" value="0000">
	                <input type="hidden" name="tipMoneda" value="PEN">
	                <input type="hidden" name="porDescGlobal" value="-">
	                <input type="hidden" name="mtoDescGlobal" value="0">
	                <input type="hidden" name="mtoBasImpDescGlobal" value="0">
	                <input type="hidden" name="sumTotTributos" value="0">
	                <input type="hidden" name="sumDescTotal" value="<?=$dsctotal?>">
	                <input type="hidden" name="sumOtrosCargos" value="0">
	                <input type="hidden" name="sumTotalAnticipos" value="0">
	                <input type="hidden" name="ublVersionId" value="2.1">
	                <input type="hidden" name="customizationId" value="2.0">
	               
	                <div class="form-group">
	                	<label for="inputEmail1" class="col-lg-2 control-label">Comprobante:</label>
			                <div class="col-md-3">
		                	<select name="selEstado" class="form-control">
			  					<option value="2">Proforma</option>
		  					</select>
		                </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">TIPO DE PAGO:</label>
	                    <div class="col-md-3">
	                    	<select name="selTipoPago" class="form-control">
	                      		<option value="1">Efectivo</option>
	                      		<option value="2">Tarjeta</option>
	                      	</select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha:</label>
	                    <div class="col-md-3">
	                      <input type="date" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">Hora:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="horEmision" id="horEmision" class="form-control" value="<?php echo date('H:i:s');?>">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">SERIE:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="SERIE" id="SERIE" class="form-control" value="<?php echo 'P001'; ?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">COMPROBANTE:</label>
	                    <div class="col-md-3">
	                    	<input type="text" name="COMPROBANTE" id="COMPROBANTE" class="form-control" value="<?php echo $PROFORMA; ?>">
	                    </div>
		            </div>
		            <div class="form-group">
	                	<label for="inputEmail1" class="col-lg-2 control-label">RUC:</label>
	                  	<div class="col-md-3">
	                    	<input type="number" name="numDocUsuario" class="form-control" id="ruc" placeholder="Nº de documento" onblur="fnBuscarDocproforma()" required="required">
	                  	</div>
	                </div>
	                <div id="datosp">
	                	<div class="form-group">
		              		<label for="inputEmail1" class="col-lg-2 control-label">Razón Social:</label>
		              		<div class="col-md-8">
		                		<input type="text" name="rznSocialUsuario" class="form-control" id="rznSocialUsuario" value="" placeholder="Nombres y apellidos" required="required">
		              		</div>
		            	</div>
		            	<div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Distrito</label>
		                    <div class="col-md-8">
								<select name="codUbigeoCliente" class="form-control">
									<option value="">::Seleccione::</option>
								<?php $objUbigeo = UbigeoData::getAllTipo('D');
									foreach($objUbigeo as $ubigeo) :
									?>
		                        	<option value="<?=$ubigeo->codigo?>"><?=$ubigeo->descripcion?></option>
									<?php endforeach; ?>
		                      	</select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail1" class="col-lg-2 control-label">Dirección:</label>
		                    <div class="col-md-8">
		                    	<input type="text" name="desDireccionCliente" class="form-control" id="desDireccionCliente" value="" placeholder="Dirección">
		                    </div>
		                </div>
	                </div>
					<div class="form-group">
					    <label for="inputEmail1" class="col-lg-2 control-label">Descuento</label>
					    <div class="col-lg-3">
					      <input type="text" name="discount" class="form-control" required value="<?=$dsctotal?>" id="discount2" placeholder="Descuento">
					    </div>
						
				    	<label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
				    	<div class="col-lg-3">
				      		<input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo" value="<?php echo $total; ?>">
				    	</div>
					 </div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Observacion</label>
						<div class="col-lg-8">
				      		<textarea id="txtObservacion" name="txtObservacion" class="form-control" placeholder="Observacion" rows="3" value="<?php echo ""; ?>"></textarea>
				    	</div>
					</div>
				  	<input type="hidden" name="tipDocUsuario" value="6">
					<input type="hidden" name="codUnidadMedida" value="NIU">
					<input type="hidden" name="codProducto" value="0">
					<input type="hidden" name="codProductoSUNAT" value="-">
					<input type="hidden" name="sumTotTributosItem" value="0">
					<input type="hidden" name="codTriIGV" value="9997">
					<input type="hidden" name="mtoIgvItem" value="0">
					<input type="hidden" name="nomTributoIgvItem" value="EXO">
					<input type="hidden" name="codTipTributoIgvItem" value="VAT">
					<input type="hidden" name="codCatTributoIgvItem" value="E">
					<input type="hidden" name="tipAfeIGV" value="20">
					<input type="hidden" name="mtoIscItem" value="0">
					<input type="hidden" name="tipSisISC" value="">
					<input type="hidden" name="porIgvItem" value="0">
					<input type="hidden" name="codTriISC" value="-">
					<input type="hidden" name="mtoIscItem" value="">
					<input type="hidden" name="nomTributoIscItem" value="">
					<input type="hidden" name="codTipTributoIscItem" value="-">
					<input type="hidden" name="codCatTributoIscItem" value="-">
					<input type="hidden" name="porIscItem" value="">
					<input type="hidden" name="mtoValorReferencialUnitario" value="0">
					<input type="hidden" name="codTipDescuentoItem" value="-">
					<input type="hidden" name="porDescuentoItem" value="0">
					<input type="hidden" name="mtoDescuentoItem" value="0">
					<input type="hidden" name="mtoBasImpDescuentoItem" value="0">
					<input type="hidden" name="codTipCargoItem" value="-">
					<input type="hidden" name="porCargoItem" value="0">
					<input type="hidden" name="mtoCargoItem" value="0">
					<input type="hidden" name="mtoBasImpCargoItem" value="0">
					<input type="hidden" name="ideTributo" value="9997">
					<input type="hidden" name="nomTributo" value="EXO">
					<input type="hidden" name="codTipTributo" value="VAT">
					<input type="hidden" name="codCatTributo" value="E">
					<input type="hidden" name="mtoTributo" value="0">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<table class="table table-bordered">
								<tr>
									<td><p>Total</p></td>
									<td><p><b>S/ <?php echo number_format($total,2,'.',','); ?></b></p></td>
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
											<a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
							        		<button class="btn btn-lg btn-primary">S/. Finalizar Venta</button>
							        	</label>
							      	</div>
							    </div>
							</div>
						</div>
					</div>
	            </div>
          	</div>
        </form>
    </div>
    <div id="comprobante_orden" style="display: none;">
    	<form action="index.php?view=processsell" class="form-horizontal" method="post" onsubmit="return true" >
                    <input type="hidden" name="TIPO" value="70">
	                <div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha:</label>
	                    <div class="col-md-3">
	                      <input type="date" name="fecEmision" id="fecEmision" class="form-control" value="<?php echo date("Y-m-d");?>">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">Hora:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="horEmision" id="horEmision" class="form-control" value="<?php echo date('H:i:s');?>">
	                    </div>
	                </div>
		    		<div class="form-group">
	                    <label for="inputEmail1" class="col-lg-2 control-label">SERIE:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="SERIE" id="SERIE" class="form-control" value="P001">
	                    </div>
	                    <label for="inputEmail1" class="col-lg-2 control-label">NÚMERO ORDEN:</label>
	                    <div class="col-md-3">
	                      <input type="text" name="COMPROBANTE" id="COMPROBANTE" class="form-control" value="<?php echo $PROFORMA; ?>">
	                    </div>
	                </div>
			<div class="form-group">
			    <label for="inputEmail1" class="col-lg-2 control-label">Cliente</label>
			    <div class="col-lg-10">
			    <?php 
			$clients = PersonData::getClients();
			    ?>
			    <select name="client_id" class="form-control">
			    <?php foreach($clients as $client):?>
			    	<option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
			    <?php endforeach;?>
			    	</select>
			    </div>
			  </div>
			<!--div class="form-group">
			    <label for="inputEmail1" class="col-lg-2 control-label">Descuento</label>
			    <div class="col-lg-10"-->
			      <input type="hidden" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
			    <!--/div>
			  </div-->
			 <div class="form-group">
			    <label for="inputEmail1" class="col-lg-2 control-label">Efectivo</label>
			    <div class="col-lg-10">
			      <input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo" value="<?php echo $total; ?>">
			    </div>
			  </div>
			      <input type="hidden" name="total" id="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">

			  <div class="row">
			<div class="col-md-6 col-md-offset-6">
			<table class="table table-bordered">
			<tr>
				<td><p>Total</p></td>
				<td><p><b>S/ <?php echo number_format($total,2); ?></b></p></td>
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
					<a href="index.php?view=clearcart" class="btn btn-lg btn-danger">
						<i class="glyphicon glyphicon-remove"></i> Cancelar
					</a>
			        <button class="btn btn-lg btn-primary">
			        	<i class="glyphicon glyphicon-ok"></i>
			        	<i class="glyphicon glyphicon-ok"></i> Finalizar Venta
			        </button>
			        </label>
			      </div>
			    </div>
			  </div>
        </form>
    </div>
</div>

<script>

	$("input[name=optTipoComprobante]").click(function () {
      var optTipoComprobante =  $('input:radio[name=optTipoComprobante]:checked').val();
     
      if(optTipoComprobante == 3)
      {
        $("#comprobante_boleta").show("slow");
        $("#comprobante_factura").hide("slow");
		$("#comprobante_guiarem").hide("slow");
        $("#comprobante_proforma").hide("slow")		
        $("#comprobante_orden").hide("slow");
        
      }
      else if (optTipoComprobante == 1)
      {
        $("#comprobante_boleta").hide("slow");
        $("#comprobante_factura").show("slow");
		$("#comprobante_guiarem").hide("slow");
        $("#comprobante_proforma").hide("slow")
		$("#comprobante_orden").hide("slow");
      }
	  else if (optTipoComprobante == 9)
      {
		$("#comprobante_factura").hide("slow");
        $("#comprobante_boleta").hide("slow");
        $("#comprobante_guiarem").show("slow");
        $("#comprobante_proforma").hide("slow")
		$("#comprobante_orden").hide("slow");
      }
	  else if (optTipoComprobante == 11)
      {
		$("#comprobante_factura").hide("slow");
        $("#comprobante_boleta").hide("slow");
		$("#comprobante_guiarem").hide("slow");
        $("#comprobante_proforma").show("slow");
		$("#comprobante_orden").hide("slow");
      }
       else if (optTipoComprobante == 0)
      {
        $("#comprobante_boleta").hide("slow");
        $("#comprobante_factura").hide("slow");
		$("#comprobante_guiarem").hide("slow");
        $("#comprobante_proforma").hide("slow")
        $("#comprobante_orden").show("slow");
      }
    });

    function enviado2() 
	{
	    discount = 0;

	    var optTipoComprobante =  $('input:radio[name=optTipoComprobante]:checked').val();

	    if (optTipoComprobante == 3) 
	    {
	      money = $("#money").val();
	      discount = $("#discount").val();
	    }
	    else if(optTipoComprobante == 1)
	    {
	      money = $("#money").val();
	      discount = $("#discount2").val();
	    }
	    else if(optTipoComprobante == 0)
	    {
	      money = $("#money").val();
	      discount = 0;
	    }
	    
	    if(money<(<?php echo $total;?>-discount))
	    {
	      alert("No se puede efectuar la operacion");
	      return false;
	      //e.preventDefault();
	    }
	    else
	    {
	      if(discount=="")
	      {
	        discount=0;
	      }
	        
	     // go = confirm("Cambio: S/ "+(money-(<?php echo $total;?>-discount ) ) );
	      go = confirm("Cambio: S/ "+(money-(<?php echo $total;?>) ) );
	      
	      if(go == true)
	      {
	        if (cuenta == 0)
	        {
	          cuenta++;
	          return true;
	        }
	        else
	        {
	          alert("El formulario ya está siendo enviado, por favor aguarde un instante.");
	          return false;
	        }
	      }
	      else
	      {
	        return false;
	      }
	    }
	}
</script>
</div>
</div>
<br><br><br><br><br>
<?php endif; ?>
</div>
<?php
$product = ProductData::getById($_GET["id"]);
$categories = CategoryData::getAll();

if($product!=null):
?>
<div class="row">
	<div class="col-md-8">
	<h1><?php echo $product->name ?> <small>Editar Producto/Servicio</small></h1>
  <?php if(isset($_COOKIE["prdupd"])):?>
    <p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
  <?php setcookie("prdupd","",time()-18600); endif; ?>
	<br><br>
		<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen*</label>
    <div class="col-md-8">
      <input type="file" name="image" id="name" placeholder="">
<?php if($product->image!=""):?>
  <br>
        <img src="storage/products/<?php echo $product->image;?>" class="img-responsive">

<?php endif;?>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de barras*</label>
    <div class="col-md-8">
      <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Producto">
    </div>
  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Nombre*</label>
    <div class="col-md-8">
      <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Producto">
    </div>
  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Categoria</label>
    <div class="col-md-8">
    <select name="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->category_id!=null&& $product->category_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
    <div class="col-md-8">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"><?php echo $product->description;?></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Entrada*</label>
    <div class="col-md-8">
      <input type="text" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="Precio de entrada">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio Por Mayor*</label>
    <div class="col-md-8">
      <input type="text" name="price_may" class="form-control" id="price_may" value="<?php echo $product->price_may; ?>" placeholder="Precio de salida">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida*</label>
    <div class="col-md-8">
      <input type="text" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>" placeholder="Precio de salida" autofocus>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Anaquel*</label>
    <div class="col-md-8">
      <input type="text" name="anaquel" required class="form-control" id="anaquel" value="<?php echo $product->anaquel; ?>" placeholder="anaquel">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Unidad de Medida*</label>
    <div class="col-md-8">
      <select class="form-control" name="selUnidadMedida" required="">
        <option value="">::Seleccione::</option>
        <?php
          $unidades = UnidadMedidaData::getAll();
          foreach ($unidades as $uni)
          {
            ?>
              <option value="<?php echo $uni->id ?>" <?php if($uni->id == $product->unit){ ?> selected <?php } ?>><?php echo $uni->name ?></option>
            <?php
          }
        ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Presentacion</label>
    <div class="col-md-8">
      <input type="text" name="presentation" class="form-control" id="inputEmail1" value="<?php echo $product->presentation; ?>" placeholder="Presentacion del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Minima en inventario:</label>
    <div class="col-md-8">
      <input type="text" name="inventary_min" class="form-control" value="<?php echo $product->inventary_min;?>" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>

  <?php
    $operaciones = OperationData::getAllByProductId($product->id);

    if(count($operaciones) <= 0)
    {
      ?>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Inventario inicial:</label>
          <div class="col-md-4">
            <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial" value="<?php echo $product->stock ?>">
          </div>
        </div>
      <?php
    }
    else
    {
      ?>
        <input type="hidden" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial" value="<?php echo $product->stock ?>">
      <?php
    }
  ?>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label" >Esta activo</label>
    <div class="col-md-8">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="is_active" <?php if($product->is_active){ echo "checked";}?>> 
        </label>
      </div>
    </div>
  </div>

  
  <div class="form-group">    
    <label for="inputEmail1" class="col-lg-3 control-label"> Precio por Mayor</label>
    <div class="col-md-8">
      <div class="checkbox">
        <label>
        <input type="checkbox" name="is_may" <?php if($product->is_may){ echo "checked";}?>> 
        </label>        
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Con Stock:</label>
    <div class="col-md-8">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="is_stock" id="is_stock" <?php if($product->is_may){ echo "checked";}?>>
        </label>
      </div>  
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-8">
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
      <button type="submit" class="btn btn-success">Actualizar Producto</button>
    </div>
  </div>
</form>

<br><br><br><br><br><br><br><br><br>
	</div>
</div>
<?php endif; ?>
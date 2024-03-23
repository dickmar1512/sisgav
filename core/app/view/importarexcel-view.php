<div class="row">
	<div class="col-md-12">
  	<h1 align="center">Importar Excel</h1>
  	<hr>
  	<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addproductxls" role="form">
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Seleccione Archivo Excel</label>
        <div class="col-md-6">
          <input type="file" name="image" id="image" placeholder="">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Agregar Producto</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74 ){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});

</script>
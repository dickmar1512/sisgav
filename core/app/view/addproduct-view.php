<?php
if(count($_POST)>0)
{
  $product = new ProductData();
  $product->barcode = $_POST["barcode"];
  $product->name = addslashes($_POST["name"]);
  $product->price_in = $_POST["price_in"];
  $product->price_may = $_POST["price_may"];
  $product->price_out = $_POST["price_out"];
  $product->anaquel = $_POST["anaquel"];
  $product->stock = $_POST["q"];
  $product->unit = $_POST["selUnidadMedida"];
  $product->description = $_POST["description"];
  $product->presentation = $_POST["presentation"];
  //$product->inventary_min = $_POST["inventary_min"];
  $category_id="NULL";
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}
  $inventary_min="\"\"";
  if($_POST["inventary_min"]!=""){ $inventary_min=$_POST["inventary_min"];}

  $product->category_id=$category_id;
  $product->inventary_min=$inventary_min;
  $product->user_id = $_SESSION["user_id"];

  if(isset($_POST['is_stock']))
  {
    $product->is_stock = 1;
  }
  else
  {
    $product->is_stock = 0;
  }

  if(isset($_FILES["image"]))
  {
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
      $image->Process("storage/products/");
      if($image->processed)
      {
        $product->image = $image->file_dst_name;
        $prod = $product->add_with_image();
      }
      }else
      {
       $prod= $product->add();
      }
  }
  else
  {
   $prod= $product->add();
  }


print "<script>window.location='index.php?view=products';</script>";


}


?>
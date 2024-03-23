<?php

if(count($_POST)>0)
{	
	$product = ProductData::getById($_POST["product_id"]);

	$product->barcode = $_POST["barcode"];
	$product->name = $_POST["name"];
	$product->price_in = $_POST["price_in"];
	$product->price_may = $_POST["price_may"];
	$product->price_out = $_POST["price_out"];
	$product->anaquel = $_POST["anaquel"];
	$product->unit = $_POST["selUnidadMedida"];

  	$product->description = $_POST["description"];
  	$product->presentation = $_POST["presentation"];
  	$product->inventary_min = $_POST["inventary_min"];

	  if(isset($_POST['is_stock']))
	  {
		$product->is_stock = 1;
	  }
	  else
	  {
		$product->is_stock = 0;
	  }

  	$category_id="NULL";
  	if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}

  	$is_active = 0;

  	if(isset($_POST["is_active"])){ $is_active=1;}

  	$product->is_active = $is_active;

  	$is_may = 0;
    if(isset($_POST["is_may"])){$is_may=1;}
    $product->is_may = $is_may;

  	$product->category_id = $category_id;
  	$product->stock = $_POST["q"];

	$product->user_id = $_SESSION["user_id"];
	$product->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded)
		{
			$image->Process("storage/products/");
			if($image->processed){
				$product->image = $image->file_dst_name;
				$product->update_image();
			}
		}
	}

	// if(isset($_POST["q"]))
	// {
	// 	$product2 = new ProductData();

	// 	$product2->id = $_POST["product_id"];

	// 	$product2->stock = $_POST["q"];
	// 	$product2->update_stock();
	// }

	setcookie("prdupd","true");
	print "<script>window.location='index.php?view=editproduct&id=$_POST[product_id]';</script>";
}

?>
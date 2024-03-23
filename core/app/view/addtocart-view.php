<?php
$qp=1;
if(!isset($_SESSION["cart"]))
{
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"], "precio_unitario"=>$_POST["precio_unitario"], "descripcion"=>$_POST["descripcion"],"descuento"=>$_POST["descuento"],"idpaquete"=>$_POST["idpaquete"],"qp"=>$qp);
	$_SESSION["cart"] = array($product);
	$cart = $_SESSION["cart"];

	$num_succ = 0;
	$process=false;
	$errors = array();
	
	foreach($cart as $c)
	{
	  $product2 = ProductData::getById($c["product_id"]);
	  $q = $product2->stock;			

	  if($c["q"]<=$q or $product2->is_stock == 0)
	  {
		$num_succ++;
	  }
	  else
	  {
		$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
		$errors[count($errors)] = $error;
	  }

	}
	if($num_succ==count($cart))
	{
		$process = true;
	}
	if($process==false)
	{
		unset($_SESSION["cart"]);
	$_SESSION["errors"] = $errors;
		?>	
	<script>
		window.location="index.php?view=sell";
	</script>
<?php
	}
}
else
{
	$found = false;
	$cart = $_SESSION["cart"];
	$index=0;

	//$product2 = ProductData::getById($cart[$index]["product_id"]);
	$product2 = ProductData::getById($_POST["product_id"]);
	$q = $product2->stock;
	$can = true;

	if($_POST["q"]<=$q or $product2->is_stock == 0)
	{
	}
	else
	{
		$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
		$errors[count($errors)] = $error;
		$can=false;
	}

	if($can==false)
	{
	$_SESSION["errors"] = $errors;
		?>	
	<script>
		window.location="index.php?view=sell";
	</script>
	<?php
	}

if($can==true)
{
foreach($cart as $c)
{
	if($c["product_id"]==$_POST["product_id"])
	{
		echo "found";
		$found=true;
		break;
	}
	$index++;
}

if($found==true)
{
	$q1 = $cart[$index]["q"];
	$q2 = $_POST["q"];
	$p1 = $cart[$index]["precio_unitario"];
	$p2 = $_POST["precio_unitario"];
	$p3 = (($cart[$index]["q"]*$cart[$index]["precio_unitario"])+($_POST["q"]*$_POST["precio_unitario"]));
    $q3 = $q1+$q2;
    $cart[$index]["precio_unitario"]=round($p3/$q3,7);
	$cart[$index]["q"]=$q1+$q2;

	$_SESSION["cart"] = $cart;
}

if($found==false)
{
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"], "precio_unitario"=>$_POST["precio_unitario"], "descripcion"=>$_POST["descripcion"],"descuento"=>$_POST["descuento"],"idpaquete"=>$_POST["idpaquete"],"qp"=>$qp);
	$cart[$nc] = $product;
	//print_r($cart);
	$_SESSION["cart"] = $cart;
}

}
}
 print "<script>window.location='index.php?view=sell';</script>";
// unset($_SESSION["cart"]);

?>
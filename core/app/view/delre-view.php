<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $ope) 
{

    $op = new OperationData();
	$product = ProductData::getById($ope->product_id);	
	if($product->is_stock == 1)
	 {
		$product2 = new ProductData();
		$product2->stock = $ope->q;
		$product2->id = $ope->product_id;
		$product2->restar_stock();
	 }

	$op->delById($ope->id);
}

$sell->del();
Core::redir("./index.php?view=res");

?>
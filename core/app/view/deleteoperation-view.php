<?php

//print_r($_GET);

$operation = OperationData::getById($_GET["opid"]);
//print_r($operation);
$operation->del();
print "<script>window.location='index.php?view=$_GET[ref]&product_id=$_GET[pid]';</script>";
?>
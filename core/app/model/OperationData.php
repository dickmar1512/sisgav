<?php
class OperationData {
	public static $tablename = "operation";
	public $created_at = "SYSDATE()";

	public function OperationData(){
		$this->name              = "";
		$this->product_id        = "";
		$this->q                 = "";
		$this->cu                = "";
		$this->cut_id            = "";
		$this->operation_type_id = "";
		$this->descripcion       = "";
		$this->idpaquete         = "";
		$this->qp                = "";
		$this->created_at        = "SYSDATE()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (product_id,q,cu, prec_alt,descuento, operation_type_id,sell_id,created_at, descripcion,idpaquete,qp) ";
		$sql .= "value (\"$this->product_id\",\"$this->q\",\"$this->cu\", $this->prec_alt,$this->descuento, $this->operation_type_id,$this->sell_id,$this->created_at, \"$this->descripcion\",\"$this->idpaquete\",\"$this->qp\")";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	/*public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}*/

	public function del(){
		$sql = "update ".self::$tablename." set estado=0 where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}
    
	public static function getByIdSell($id){
		$sql = "select * from ".self::$tablename." where sell_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());

	}



	public static function getAllByDateOfficial($start,$end)
	{
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" order by created_at desc";
		
		if($start == $end)
		{
			$sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}

		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}




	/*REVISA ID DE LAS SOLICITUDADES MAS VENDIDAS*/
		public static function getAllByDateVendido($start,$end)
	{
 		$sql = "select product_id,COUNT(product_id)*q AS VENTAS   from ".self::$tablename." 
 			where 
 			date(created_at) >= \"$start\" and 
 			date(created_at) <= \"$end\" and
 			operation_type_id = 2
 			group by product_id 
 			order by VENTAS desc  LIMIT 5";
		
		if($start == $end)
		{
			$sql = "select  product_id,COUNT(product_id)*q AS VENTAS from ".self::$tablename." 
				where date(created_at) = \"$start\" and
 				operation_type_id = 2
 				group by product_id 
 				order by VENTAS desc  LIMIT 5";
		}

		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	/*CANTIDAD EXCATA VENDIDOS POR PRODUCTO*/
		public static function getAllByDateVendidoProducto($start,$end,$product_id)
	{
 		$sql = "select product_id, q   from ".self::$tablename." 
 			where 
 			date(created_at) >= \"$start\" and 
 			date(created_at) <= \"$end\" and
 			operation_type_id = 2
 			and product_id = $product_id";
		
		if($start == $end)
		{
			$sql = "select  product_id, q   from ".self::$tablename." 
				where date(created_at) = \"$start\" and
 				operation_type_id = 2 and 
 				product_id = $product_id";
		}

		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByDateOfficialBP($product, $start,$end)
	{
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and product_id=$product order by created_at desc";
		
		if($start == $end)
		{
			$sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}
		
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public function getProduct(){ return ProductData::getById($this->product_id);}
	public function getOperationtype(){ return OperationTypeData::getById($this->operation_type_id);}
    public function getPaquete(){
    	$sql = "select idpaquete id,barcode,nombre name,descripcion description  from paquete where idpaquete=".$this->idpaquete;
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

    }

	public static function getQYesF($product_id){
		$q=0;
		$operations = self::getAllByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
				else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getCantidadbyOperation($product_id, $operation_type_id)
	{
		$sql = "SELECT sum(q) as Cantidad FROM ".self::$tablename." WHERE product_id = $product_id AND operation_type_id = $operation_type_id";

		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());	
	}



	public static function getAllByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getAllByProductIdCutIdOficial($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
	}


	public static function getAllProductsBySellId($sell_id){
		$sql = "select * from ".self::$tablename." where sell_id=$sell_id order by created_at desc";
		// $sql = "select name from operation, product where operation.sell_id=$sell_id AND product.id=operation.product_id order by operation.created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllProductsBySellId2($sell_id)
	{
	  $sql="SELECT id,product_id,q,prec_alt,descuento,operation_type_id,sell_id,created_at,idpaquete
			FROM operation 
			WHERE sell_id=$sell_id
			AND idpaquete= 'X' 
			UNION
			SELECT id,'0' as product_id,q,SUM(prec_alt*q) prec_alt,SUM(descuento*q) descuento,operation_type_id,sell_id,created_at,idpaquete
			FROM operation
			WHERE sell_id=$sell_id
			AND idpaquete <> 'X'
			GROUP BY idpaquete";
	  $query = Executor::doit($sql);
	  return Model::many($query[0],new OperationData());
	}


	public static function getAllByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
		return $array;
	}

////////////////////////////////////////////////////////////////////
	public static function getOutputQ($product_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutId($product_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($product_id){
		$q=0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQYesF($product_id){
		$q=0;
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	}



	public static function getOutputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getOutputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

////////////////////////////////////////////////////////////////////
	public static function getInputQ($product_id,$cut_id){
		$q=0;
		return Model::many($query[0],new OperationData());
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getInputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}
	
	public function update_created_at(){
		$sql = "update ".self::$tablename." set created_at=$this->created_at where id=$this->id";
		Executor::doit($sql);
	}

/*********************************** querys para entrada y salida de kardex********************************************/
	public static function obtenerCantidadProductoSalida($start,$end,$product_id)
	{
 		$sql = "select product_id, SUM(q) cant,cu,SUM(q*cu) total from ".self::$tablename." 
 			where 
 			date(created_at) >= \"$start\" and 
 			date(created_at) <= \"$end\" and
 			operation_type_id = 2
 			and product_id = $product_id
			GROUP BY product_id,cu";
		
		if($start == $end)
		{
			$sql = "select product_id, SUM(q) cant,cu,SUM(q*cu) total from ".self::$tablename." 
				where date(created_at) = \"$start\" and
 				operation_type_id = 2 and 
 				product_id = $product_id
				GROUP BY product_id,cu";
		}

		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}
	
	public static function obtenerCantidadProductoEntrada($start,$end,$product_id)
	{
 		$sql = "select product_id, SUM(q) cant,cu,SUM(q*cu) total from ".self::$tablename." 
 			where 
 			date(created_at) >= \"$start\" and 
 			date(created_at) <= \"$end\" and
 			operation_type_id = 1
 			and product_id = $product_id
			GROUP BY product_id,cu";
		
		if($start == $end)
		{
			$sql = "select product_id, SUM(q) cant,cu,SUM(q*cu) total from ".self::$tablename." 
				where date(created_at) = \"$start\" and
 				operation_type_id = 1 and 
 				product_id = $product_id
				GROUP BY product_id,cu";
		}

		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}


}

?>
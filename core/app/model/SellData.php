<?php
class SellData {
	public static $tablename = "sell";

	public function SellData(){
		$this->created_at = "NOW()";
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (total, cash, discount,user_id,created_at) ";
		$sql .= "value ($this->total, $this->cash, $this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add2(){
		$sql = "insert into ".self::$tablename." (user_id, tipo_comprobante, serie, comprobante, total, cash, discount,created_at, estado, person_id, tipo_pago, observacion) ";
		$sql .= "value ($this->user_id, $this->tipo_comprobante, '".$this->serie."', '".$this->comprobante."', $this->total, $this->cash, $this->discount, '".$this->created_at."', $this->estado, $this->person_id, $this->tipo_pago,'".$this->observacion."')";
		return Executor::doit($sql);
	}

	public function add_re(){
		$sql = "insert into ".self::$tablename." (user_id,operation_type_id,created_at) ";
		$sql .= "value ($this->user_id,1,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_re2(){
		$sql = "insert into ".self::$tablename." (person_id,tipo_comprobante, serie, comprobante, user_id,operation_type_id,created_at,total,cash,discount) ";
		$sql .= "value ($this->person_id,$this->tipo_comprobante, '".$this->serie."', lpad($this->comprobante,8,0), $this->user_id,1,$this->created_at,$this->total,$this->cash,$this->discount)";
		return Executor::doit($sql);
	}


	public function add_with_client(){
		$sql = "insert into ".self::$tablename." (tipo_comprobante, serie, comprobante, total, discount, person_id, user_id, created_at, estado,tipo_pago) ";
		$sql .= "value ($this->tipo_comprobante, '".$this->serie."', '".$this->comprobante."',$this->total,$this->discount,$this->person_id,$this->user_id,'".$this->created_at."','1','1')";
		return Executor::doit($sql);
	}

	public function add_re_with_client(){
		$sql = "insert into ".self::$tablename." (person_id,operation_type_id,user_id,created_at,tipo_comprobante,serie,comprobante,total,cash,discount) ";
		$sql .= "value ($this->person_id,1,$this->user_id,$this->created_at,$this->tipo_comprobante,'".$this->serie."',lpad($this->comprobante,8,0),$this->total,$this->cash,0)";
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

	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_proforma_venta(){
		$sql = "update ".self::$tablename." set estado = 1 where id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public static function getByNroDoc($num){
		 $sql = "select * from ".self::$tablename." where CONCAT(SERIE,'-',COMPROBANTE)='$num'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public static function getSells(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 AND estado != 2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsXfecha($inicio,$fin){
		$sql = "select * from ".self::$tablename.
		       " where  date(created_at) >= '$inicio' ".
               "and date(created_at) <= '$fin' ".
               "and operation_type_id=2 AND estado != 2 order by created_at asc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getProformas(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 AND estado = 2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function get_ventas_x_id($id)
	{
		$sql = "SELECT sel.id, tipo_comprobante, serie, person_id, comprobante, total, per.numero_documento, CONCAT(per.name, ' ', per.lastname) as name, per.address1, sel.created_at
				FROM sell sel
				LEFT JOIN person per ON per.id = sel.person_id
				WHERE sel.id = $id LIMIT 1";

				// echo $sql; exit;

		$query = Executor::doit($sql);

		return Model::one($query[0],new SellData());
	}
	
	public static function get_ventas_x_idCliente($idperson)
	{
		$sql = "select sel.id, tipo_comprobante, serie, person_id, comprobante, total, per.numero_documento, CONCAT(per.name, ' ', per.lastname) as name, per.address1,sel.total, sel.created_at as fecemi
				FROM sell sel
				LEFT JOIN person per ON per.id = sel.person_id
				WHERE sel.person_id = $idperson ";
		$query = Executor::doit($sql);

		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOp($start,$end,$op){
  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}
	
	public static function getAllByDateOp2($start,$end,$op){
  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and tipo_comprobante in('1','3') order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}
	public static function getAllByDateOp3($start,$end,$op){
  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and tipo_comprobante ='60' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}
	
	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	/*****Agregue esto para kardex*****/
	public static function getAllByKardexProd($idprod,$start,$end){
 $sql = "select producto(a.product_id) prod, nombre(b.person_id) nombre, ".
        "CONCAT(b.serie,'-',b.comprobante) comp, ".
        "(CASE a.operation_type_id WHEN 2 THEN 'VENTA' ELSE 'COMPRA' END) tipo, ".
        "a.q,a.prec_alt,a.descuento,a.cu,a.created_at ".
        "from operation a, ".self::$tablename." b ".
        "where a.sell_id = b.id ".
        "and date(a.created_at) >= '$start' ".
        "and date(a.created_at) <= '$end' ".
        "and a.product_id='$idprod'  ".
        "order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByKardex($start,$end){
 $sql = "select a.product_id , b.person_id, ".
        "CONCAT(b.serie,'-',b.comprobante) comp, ".
        "(CASE a.operation_type_id WHEN 2 THEN 'VENTA' ELSE 'COMPRA' END) tipo, ".
        "a.q,a.prec_alt,a.descuento,a.cu,a.created_at ".
        "from operation a, ".self::$tablename." b ".
        "where a.sell_id = b.id ".
        "and date(a.created_at) >= '$start' ".
        "and date(a.created_at) <= '$end' ".
        "order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	/**********************************/

	public function update_created_at(){
		$sql = "update ".self::$tablename." set created_at=$this->created_at where id=$this->id";
		Executor::doit($sql);
	}


}

?>
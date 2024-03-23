<?php
class ProductData {
	public static $tablename = "product";

	public function ProductData(){
		$this->id = "";
		$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->price_may = "";
		$this->anaquel = "";
		$this->is_may = "";
		$this->unit = "";
		$this->user_id = "";
		$this->is_stock = "0";
		$this->stock = 0;
		$this->presentation = "0";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (barcode,name,description,price_in,price_may,price_out, stock, user_id,presentation,unit,category_id,inventary_min,created_at, is_stock,anaquel) ";
		$sql .= "value (\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price_in\",\"$this->ptice_may\",\"$this->price_out\", \"$this->stock\", $this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->inventary_min,NOW(), $this->is_stock,\"$this->anaquel\")";
		return Executor::doit($sql);
	}
	public function add_with_image(){
		$sql = "insert into ".self::$tablename." (barcode,image,name,description,price_in,price_may,price_out,user_id,presentation,unit,category_id,inventary_min,anaquel) ";
		$sql .= "value (\"$this->barcode\",\"$this->image\",\"$this->name\",\"$this->description\",\"$this->price_in\",\"$this->price_may\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->inventary_min,\"$this->anaquel\")";
		return Executor::doit($sql);
	}


	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",price_in=\"$this->price_in\",price_may=\"$this->price_may\",price_out=\"$this->price_out\",unit=\"$this->unit\",presentation=\"$this->presentation\",category_id=$this->category_id,inventary_min=\"$this->inventary_min\",description=\"$this->description\",is_active=\"$this->is_active\", stock=\"$this->stock\",anaquel=\"$this->anaquel\",is_may=\"$this->is_may\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_stock(){
		$sql = "update ".self::$tablename." set stock = stock + $this->stock where id=$this->id";
		Executor::doit($sql);
	}

	public function update_stock2($idprod){
		$sql = "call insertar_stock($idprod) ";
		return Executor::doit($sql);
	}

	public function sumar_stock(){
		$sql = "UPDATE ".self::$tablename." set stock = stock + $this->stock WHERE id=$this->id";

		Executor::doit($sql);
	}

	public function restar_stock(){
		$sql = "UPDATE ".self::$tablename." set stock = stock - $this->stock WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function sumar_stock_id(){
		$sql = "UPDATE ".self::$tablename." set stock = $this->stock + stock WHERE name=$this->id";

		Executor::doit($sql);
	}

	public function restar_stock_name(){
		$sql = "UPDATE ".self::$tablename." set stock =  stock - $this->stock WHERE name= '".$this->name."'";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_cu(){
		$sql = "update ".self::$tablename." set price_in =\"$this->price_in\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select id, image,barcode, name, description,stock,is_stock,inventary_min,price_in,price_out,unit,presentation,price_may,anaquel,is_may,is_active from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getByName($name){
		$sql = "select id, image,barcode, name, description,stock,is_stock,inventary_min,price_in,price_out,unit,presentation,price_may,anaquel,is_may,is_active from ".self::$tablename." where name like '%$name%' ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getAlertasInventario(){
		$sql = "SELECT * 
				FROM ".self::$tablename."
				WHERE stock <= inventary_min and is_stock =1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by name asc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public  static function getBarcode(){
		$sql = "select lpad(count(barcode)+1,4,0) barcode from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAll2(){
		$sql = "select * from ".self::$tablename." order by name asc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		//$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$sql = "select * from ".self::$tablename." order by name asc ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
	


	public static function getLikeSinStock($p){
		$sql = "select * from ".self::$tablename." where  is_active=1 and (
		barcode like '%$p%' or 
		name like '%$p%' or 
		id like '%$p%' )

		";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($category_id){
		$sql = "select * from ".self::$tablename." where category_id=$category_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
}

?>
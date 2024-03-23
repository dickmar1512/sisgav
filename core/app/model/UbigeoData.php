<?php
class UbigeoData {
	public static $tablename = "ubigeo";

	public function UbigeoData(){
		$this->codigo = "";
		$this->descripcion = "";
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." ";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array())
        {
			$array[$cnt] = new UbigeoData();
			$array[$cnt]->codigo = $r['codigo'];
			$array[$cnt]->descripcion = $r['descripcion'];
			$cnt++;
		}
		return $array;
	}

    public static function getAllTipo($tipo){
		$sql = "select * from ".self::$tablename." where tipo ='".$tipo."' ";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array())
        {
			$array[$cnt] = new UbigeoData();
			$array[$cnt]->codigo = $r['codigo'];
			$array[$cnt]->descripcion = $r['descripcion'];
			$cnt++;
		}
		return $array;
	}
}

?>
<?php
class Factura2Data {
	public static $tablename = "factura";

	public function Factura2Data(){
		$this->RUC = "";
		$this->TIPO = "";
		$this->SERIE = "";
		$this->COMPROBANTE = "";
		$this->fecEmision = "";
		$this->numDocUsuario = "";
		$this->rznSocialUsuario = "";
		$this->sumPrecioVenta = "";
	}

    public static function getFacturasSerie($serie){
		$sql = "select * from ".self::$tablename." where SERIE='$serie'";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Boleta2Data();
			$array[$cnt]->id = $r['id'];
			$cnt++;
		}
		return $array;
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Factura2Data();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->RUC = $r['RUC'];
			$data->TIPO = $r['TIPO'];
			$data->SERIE = $r['SERIE'];
			$data->COMPROBANTE = $r['COMPROBANTE'];
			$data->EXTRA1 = $r['EXTRA1'];
			$found = $data;
			break;
		}
		return $found;
	}

	public static function getByExtra($id){
		$sql = "select * from ".self::$tablename." where EXTRA1=$id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Boleta2Data();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->RUC = $r['RUC'];
			$data->TIPO = $r['TIPO'];
			$data->SERIE = $r['SERIE'];
			$data->COMPROBANTE = $r['COMPROBANTE'];
			$data->EXTRA1 = $r['EXTRA1'];
			$found = $data;
			break;
		}
		return $found;
	}

	public static function get_facturas_x_fecha($fecha_inicio, $fecha_fin, $selSerie, $comprobante){

		$condicion = "";

		if ($selSerie != "0") 
		{
			$condicion .= "AND SERIE = '".$selSerie."'";
		}

		if ($comprobante != "")
		{
			$condicion .= "AND COMPROBANTE like '%".$comprobante."%'";
		}

		$sql = "SELECT fac.*, cab.fecEmision, cab.numDocUsuario, cab.rznSocialUsuario, cab.sumPrecioVenta
				FROM cab cab
				INNER JOIN factura fac ON cab.ID_TIPO_DOC = fac.id
				WHERE cab.fecEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND cab.TIPO_DOC = 1 $condicion order by fecEmision";

				// echo $sql; exit();

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_facturas_x_fecha2($fecha_inicio, $fecha_fin){

		$condicion = "";

		$sql = "SELECT fac.*, cab.fecEmision, cab.numDocUsuario, cab.rznSocialUsuario, cab.sumPrecioVenta
				FROM cab cab
				INNER JOIN factura fac ON cab.ID_TIPO_DOC = fac.id
				WHERE cab.fecEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND cab.TIPO_DOC = 1 $condicion";

				// echo $sql; exit();

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_notas_credito_x_fecha($fecha_inicio, $fecha_fin, $selSerie, $comprobante){

		$condicion = "";

		if ($selSerie != "0") 
		{
			$condicion .= "AND SERIE = '".$selSerie."'";
		}

		if ($comprobante != "")
		{
			$condicion .= "AND COMPROBANTE like '%".$comprobante."%'";
		}

		$sql = "SELECT nota.*, cab.fecEmision, cab.numDocUsuario, cab.rznSocialUsuario, cab.sumPrecioVenta, cab.serieDocModifica
				FROM nota cab
				INNER JOIN factura nota ON cab.ID_TIPO_DOC = nota.id
				WHERE cab.fecEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND cab.TIPO_DOC = 7 $condicion";

				// echo $sql; exit();

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_notas_debito_x_fecha($fecha_inicio, $fecha_fin, $selSerie, $comprobante){

		$condicion = "";

		if ($selSerie != "0") 
		{
			$condicion .= "AND SERIE = '".$selSerie."'";
		}

		if ($comprobante != "")
		{
			$condicion .= "AND COMPROBANTE like '%".$comprobante."%'";
		}

		$sql = "SELECT nota.*, cab.fecEmision, cab.numDocUsuario, cab.rznSocialUsuario, cab.sumPrecioVenta, cab.serieDocModifica
				FROM nota cab
				INNER JOIN factura nota ON cab.ID_TIPO_DOC = nota.id
				WHERE cab.fecEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND cab.TIPO_DOC = 8 $condicion";

				// echo $sql; exit();

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_series(){

		$sql = "SELECT SERIE FROM ".self::$tablename." GROUP BY SERIE AND TIPO = '01'";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_series_notas_credito(){

		$sql = "SELECT SERIE FROM ".self::$tablename." GROUP BY SERIE AND TIPO = '07'";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function get_series_notas_debito(){

		$sql = "SELECT SERIE FROM ".self::$tablename." GROUP BY SERIE AND TIPO = '08'";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Factura2Data());
	}

	public static function getByNumDoc($num){
		$sql = "select * from ".self::$tablename." where CONCAT(SERIE,'-',COMPROBANTE)='$num'";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Factura2Data();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->RUC = $r['RUC'];
			$data->TIPO = $r['TIPO'];
			$data->SERIE = $r['SERIE'];
			$data->COMPROBANTE = $r['COMPROBANTE'];
			$data->EXTRA1 = $r['EXTRA1'];
			$found = $data;
			break;
		}
		return $found;
	}
}



?>
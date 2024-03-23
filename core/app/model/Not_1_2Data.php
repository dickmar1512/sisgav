<?php
class Not_1_2Data {
	public static $tablename = "nota";

	public function Not_1_2Data(){
		//CABECERA CAB
		$this->RUC = "";
		$this->TIPO = "";
		$this->SERIE = "";
		$this->COMPROBANTE = "";
	}

	public static function get_notas_credito_factura_x_fecha($start, $end){
		$sql = "SELECT * FROM  nota  
		WHERE date(fecEmision) >= \"$start\" and date(fecEmision) <= \"$end\"
		 AND TIPO_DOC = 7 AND tipDocModifica = 1 ";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Not_1_2Data());
	}	

	public static function get_notas_credito_boleta_x_fecha($start, $end){
		$sql = "SELECT * FROM  nota  
		WHERE date(fecEmision) >= \"$start\" and date(fecEmision) <= \"$end\"
		 AND TIPO_DOC = 7 AND tipDocModifica = 3 ";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Not_1_2Data());
	}
	public static function get_notas_credito_factura_x_fecha2($start, $end){
		$sql = "SELECT f.SERIE,f.COMPROBANTE,n.ID_TIPO_DOC,n.fecEmision,n.horEmision,n.numDocUsuario,
                n.rznSocialUsuario,n.serieDocModifica,n.sumImpVenta,n.sumPrecioVenta
				FROM  nota n, factura f  
				WHERE n.ID_TIPO_DOC= f.id
				AND DATE(n.fecEmision) >= \"$start\" and date(n.fecEmision) <= \"$end\"
		        AND TIPO_DOC = 7 AND tipDocModifica = 1 ";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Not_1_2Data());
	}
	/*
	SELECT f.SERIE,f.COMPROBANTE,n.fecEmision,n.horEmision,n.numDocUsuario,
       n.rznSocialUsuario,n.serieDocModifica,n.sumImpVenta
FROM  nota n, factura f  
WHERE n.ID_TIPO_DOC= f.id
AND DATE(n.fecEmision) >= "2021-10-01" 
AND DATE(n.fecEmision) <= "2021-10-29"
AND n.TIPO_DOC = 7 
AND n.tipDocModifica = 1 */


	public static function get_notas_debito_factura_x_fecha($start, $end){
		$sql = "SELECT * FROM  nota  
		WHERE date(fecEmision) >= \"$start\" and date(fecEmision) <= \"$end\"
		 AND TIPO_DOC = 8 AND tipDocModifica = 1 ";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Not_1_2Data());
	}	

	public static function get_notas_debito_boleta_x_fecha($start, $end){
		$sql = "SELECT * FROM  nota  
		WHERE date(fecEmision) >= \"$start\" and date(fecEmision) <= \"$end\"
		 AND TIPO_DOC = 8 AND tipDocModifica = 3 ";

		$query = Executor::doit($sql);

		return Model::many($query[0],new Not_1_2Data());
	}

    public static function getExisteNota($comprobante)
	{
		$sql ="select count(*) from nota = serieDocModifica='$comprobante'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new Not_1_2Data());
	}


	public static function getByIdComprobado($m){
		$sql = "select * from ".self::$tablename." where serieDocModifica='$m'";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Boleta2Data();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->TIPO_DOC = $r['TIPO_DOC'];
			$data->ID_TIPO_DOC = $r['ID_TIPO_DOC'];
			$data->ESTADO = $r['ESTADO'];
			$data->tipOperacion = $r['tipOperacion'];
			$data->fecEmision = $r['fecEmision'];
			$data->horEmision = $r['horEmision'];
			$data->codLocalEmisor = $r['codLocalEmisor'];
			$data->tipDocUsuario = $r['tipDocUsuario'];
			$data->numDocUsuario = $r['numDocUsuario'];
			$data->rznSocialUsuario = $r['rznSocialUsuario'];
			$data->tipMoneda = $r['tipMoneda'];
			$data->codTipoNota = $r['codTipoNota'];
			$data->descMotivo = $r['descMotivo'];
			$data->tipDocModifica = $r['tipDocModifica'];
			$data->serieDocModifica = $r['serieDocModifica'];
			$data->sumTotTributos = $r['sumTotTributos'];
			$data->sumTotValVenta = $r['sumTotValVenta'];
			$data->sumPrecioVenta = $r['sumPrecioVenta'];
			$data->sumDescTotal = $r['sumDescTotal'];
			$data->sumOtrosCargos = $r['sumOtrosCargos'];
			$data->sumTotalAnticipos = $r['sumTotalAnticipos'];
			$data->sumImpVenta = $r['sumImpVenta'];
			$data->ublVersionId = $r['ublVersionId'];
			$data->customizationId = $r['customizationId'];

			$found = $data;
			break;
		}
		return $found;
	}


	public static function getByNumComp($numcomp){
		$sql = "select a.id,a.TIPO_DOC,a.ID_TIPO_DOC,a.ESTADO,a.tipOperacion,a.fecEmision,a.horEmision,
		       '' fecVencimiento,a.codLocalEmisor,a.tipDocUsuario,a.numDocUsuario,a.rznSocialUsuario,
		       a.tipMoneda,a.codTipoNota,a.descMotivo,a.tipDocModifica,a.serieDocModifica,
		       a.sumTotTributos,a.sumTotValVenta,a.sumPrecioVenta,a.sumDescTotal,a.sumOtrosCargos,
		       a.sumTotalAnticipos,a.sumImpVenta,b.id,b.RUC,b.TIPO,b.SERIE,b.COMPROBANTE,b.ESTADO,b.EXTRA1
			    FROM ".self::$tablename." a
				INNER JOIN 
				factura b ON a.ID_TIPO_DOC = b.id
				WHERE serieDocModifica = '".$numcomp."'
				AND b.tipo='07'
				UNION
				select a.id,a.TIPO_DOC,a.ID_TIPO_DOC,a.ESTADO,a.tipOperacion,a.fecEmision,a.horEmision,
				       '' fecVencimiento,a.codLocalEmisor,a.tipDocUsuario,a.numDocUsuario,a.rznSocialUsuario,
				       a.tipMoneda,a.codTipoNota,a.descMotivo,a.tipDocModifica,a.serieDocModifica,
				       a.sumTotTributos,a.sumTotValVenta,a.sumPrecioVenta,a.sumDescTotal,a.sumOtrosCargos,
				       a.sumTotalAnticipos,a.sumImpVenta,b.id,b.RUC,b.TIPO,b.SERIE,b.COMPROBANTE,b.ESTADO,b.EXTRA1
				FROM ".self::$tablename." a
				INNER JOIN 
				boleta b ON a.ID_TIPO_DOC = b.id
				WHERE serieDocModifica = '".$numcomp."'
				AND b.TIPO='07' LIMIT 1";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Boleta2Data();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->TIPO_DOC = $r['TIPO_DOC'];
			$data->ID_TIPO_DOC = $r['ID_TIPO_DOC'];
			$data->ESTADO = $r['ESTADO'];
			$data->tipOperacion = $r['tipOperacion'];
			$data->fecEmision = $r['fecEmision'];
			$data->horEmision = $r['horEmision'];
			$data->codLocalEmisor = $r['codLocalEmisor'];
			$data->tipDocUsuario = $r['tipDocUsuario'];
			$data->numDocUsuario = $r['numDocUsuario'];
			$data->rznSocialUsuario = $r['rznSocialUsuario'];
			$data->tipMoneda = $r['tipMoneda'];
			$data->codTipoNota = $r['codTipoNota'];
			$data->descMotivo = $r['descMotivo'];
			$data->tipDocModifica = $r['tipDocModifica'];
			$data->serieDocModifica = $r['serieDocModifica'];
			$data->sumTotTributos = $r['sumTotTributos'];
			$data->sumTotValVenta = $r['sumTotValVenta'];
			$data->sumPrecioVenta = $r['sumPrecioVenta'];
			$data->sumDescTotal = $r['sumDescTotal'];
			$data->sumOtrosCargos = $r['sumOtrosCargos'];
			$data->sumTotalAnticipos = $r['sumTotalAnticipos'];
			$data->sumImpVenta = $r['sumImpVenta'];
			$data->SERIE = $r['SERIE'];
			$data->COMPROBANTE = $r['COMPROBANTE'];

			$found = $data;
			break;
		}
		return $found;
	}



	public static function getById($id, $tipo){

		$sql = "select * from ".self::$tablename." where ID_TIPO_DOC=$id and TIPO_DOC=$tipo";

		// echo $sql; exit();

		$query = Executor::doit($sql);
		$found = null;
		$data = new Not_1_2Data();

		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->TIPO_DOC = $r['TIPO_DOC'];
			$data->ID_TIPO_DOC = $r['ID_TIPO_DOC'];
			$data->ESTADO = $r['ESTADO'];
			$data->tipOperacion = $r['tipOperacion'];
			$data->fecEmision = $r['fecEmision'];
			$data->horEmision = $r['horEmision'];
			$data->codLocalEmisor = $r['codLocalEmisor'];
			$data->tipDocUsuario = $r['tipDocUsuario'];
			$data->numDocUsuario = $r['numDocUsuario'];
			$data->rznSocialUsuario = $r['rznSocialUsuario'];
			$data->tipMoneda = $r['tipMoneda'];
			$data->codTipoNota = $r['codTipoNota'];
			$data->descMotivo = $r['descMotivo'];
			$data->tipDocModifica = $r['tipDocModifica'];
			$data->serieDocModifica = $r['serieDocModifica'];
			$data->sumTotTributos = $r['sumTotTributos'];
			$data->sumTotValVenta = $r['sumTotValVenta'];
			$data->sumPrecioVenta = $r['sumPrecioVenta'];
			$data->sumDescTotal = $r['sumDescTotal'];
			$data->sumOtrosCargos = $r['sumOtrosCargos'];
			$data->sumTotalAnticipos = $r['sumTotalAnticipos'];
			$data->sumImpVenta = $r['sumImpVenta'];
			$data->ublVersionId = $r['ublVersionId'];
			$data->customizationId = $r['customizationId'];

			$found = $data;
			break;
		}

		return $found;
	}
}

?>
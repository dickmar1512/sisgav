<?php
class FacturadorData
{    
    public function __construct()
    {
        $this->NUM_RUC  = "";
        $this->TIP_DOCU = "";
        $this->NUM_DOCU = "";
        $this->FEC_CARG = "";
        $this->FEC_GENE = "";
        $this->FEC_ENVI = "";
        $this->NOM_ARCH = "";
        $this->IND_SITU = "";
        $this->URL_XML   = "";
        $this->URL_CDR   = "";
        $this->DES_SITU = "";
    }

    public static function getByNuemroDocumento($numeroDoc)
    {
        //Conexion a la base de datos del facturador
        try {
            $db = new PDO('sqlite:../efsigav/bd/BDFacturador.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage();
            exit();
        }

        $arXml = '../efsigav/sunat_archivos/sfs/FIRMA/';
		$arCdr = '../efsigav/sunat_archivos/sfs/RPTA/R'; 
							 

        // Consulta a la base de datos
        $query = "SELECT * FROM DOCUMENTO WHERE NUM_DOCU = '".$numeroDoc."'";
        try 
        {
            $statement = $db->query($query);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $found = null;
		    $data = new FacturadorData();

            // Procesar los datos obtenidos
            foreach ($result as $row) 
            {
                // Acceder a cada fila y columna de datos
                $data->NUM_RUC  = $row["NUM_RUC"];
                $data->TIP_DOCU = $row["TIP_DOCU"];
                $data->NUM_DOCU = $row["NUM_DOCU"];
                $data->FEC_CARG = $row["FEC_CARG"];
                $data->FEC_GENE = $row["FEC_GENE"];
                $data->FEC_ENVI = $row["FEC_ENVI"];
                $data->NOM_ARCH = $row["NOM_ARCH"];
                $data->IND_SITU = $row["IND_SITU"];
                $data->URL_XML   = $arXml.$row["NOM_ARCH"].".xml";
                $data->URL_CDR   = $arCdr.$row["NOM_ARCH"].".zip";
                /*  {"id":"01","nombre":"Por Generar XML"},
                    {"id":"02","nombre":"XML Generado"},
                    {"id":"03","nombre":"Enviado y Aceptado SUNAT"},
                    {"id":"04","nombre":"Enviado y Aceptado SUNAT con Obs."},
                    {"id":"05","nombre":"Rechazado por SUNAT"},
                    {"id":"06","nombre":"Con Errores"},
                    {"id":"07","nombre":"Por Validar XML"},
                    {"id":"08","nombre":"Enviado a SUNAT Por Procesar"},
                    {"id":"09","nombre":"Enviado a SUNAT Procesando"},
                    {"id":"10","nombre":"Rechazado por SUNAT"},
                    {"id":"11","nombre":"Enviado y Aceptado SUNAT"},
                    {"id":"12","nombre":"Enviado y Aceptado SUNAT con Obs."}
                */
                switch ($row["IND_SITU"]) {
                    case '01':
                        $data->DES_SITU = "Por Generar XML";
                        break;
                    case '02':
                        $data->DES_SITU = "XML Generado";
                        break;
                    case '03':
                        $data->DES_SITU = "Enviado y Aceptado SUNAT";
                        break;
                    case '04':
                        $data->DES_SITU = "Enviado y Aceptado SUNAT con Obs.";
                        break;
                    case '05':
                        $data->DES_SITU = "Rechazado por SUNAT";
                        break;
                    case '06':
                        $data->DES_SITU = "Con Errores";
                        break;
                    case '07':
                        $data->DES_SITU = "Por Validar XML";
                        break;
                    case '08':
                        $data->DES_SITU = "Enviado a SUNAT Por Procesar";
                        break;
                    case '09':
                        $data->DES_SITU = "Enviado a SUNAT Procesando";
                        break;
                    case '10':
                        $data->DES_SITU = "Rechazado por SUNAT";
                        break;
                    case '11':
                        $data->DES_SITU = "Enviado y Aceptado SUNAT";
                        break;
                    case '12':
                        $data->DES_SITU = "Enviado y Aceptado SUNAT con Obs.";
                        break;                                        
                }
                $found = $data;
			    break;
            }
            return $found;
        }
         catch(PDOException $e) 
         {
            echo "Error en la consulta: " . $e->getMessage();
         }
        
    }
}
?>
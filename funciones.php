<?php
  include_once('conexion.php');
  session_start();

  function datosbyDocumento($enlace, $numDocUsuario, $tipo)
  {
    $consulta = "SELECT id, CONCAT(name,' ', lastname) as name, address1, ubigeo
                FROM person
				WHERE numero_documento = '".$numDocUsuario."' AND kind = 1 AND tipo_persona in('1','3','4') LIMIT 1";
               /* WHERE numero_documento = '".$numDocUsuario."' AND kind = 1 AND tipo_persona = $tipo LIMIT 1";*/

    $resultado = mysqli_query($enlace, $consulta);

    $row = mysqli_fetch_array($resultado);

    return $row;
  }

  function get_producto($enlace, $product_id)
  {
    $consulta = "SELECT name, price_out
                FROM product
                WHERE id = $product_id LIMIT 1";

    $resultado = mysqli_query($enlace, $consulta);

    $row = mysqli_fetch_array($resultado);

    return $row;
  }

  function inventario_inicial($enlace, $product_id, $condicion="")
  {
    $consulta = "SELECT SUM(q) suma, operation_type_id
            FROM operation
            WHERE product_id = $product_id $condicion
            GROUP BY operation_type_id";

    $rows = [];

    if ($resultado = mysqli_query($enlace, $consulta))
    {
      while($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC))
      {
        $rows[] = $fila;
      }

      return $rows;
    }
  }

  function get_operaciones($enlace, $product_id, $condicion="")
  {
    $consulta = "SELECT ope.*, sel.tipo_comprobante, sel.serie, sel.comprobante
            FROM operation ope
            INNER JOIN sell sel ON sel.id = ope.sell_id
            WHERE product_id = $product_id $condicion";
            
    $rows = [];

    if ($resultado = mysqli_query($enlace, $consulta))
    {
      while($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC))
      {
        $rows[] = $fila;
      }

      return $rows;
    }
  }

  function get_busqueda($enlace, $busqueda="")
  {
    $consulta = "SELECT *
                FROM product ope
                WHERE barcode like '%".$busqueda."%' OR name like '%".$busqueda."%' OR id like '%".$busqueda."%'" ;
            
    $rows = [];

    if ($resultado = mysqli_query($enlace, $consulta))
    {
      while($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC))
      {
        $rows[] = $fila;
      }
 
      return $rows;
    }
  }

  function convertir_fecha($fecha)
  {
    if ($fecha == '0000-00-00')
    {
      return 'Sin fecha';
    }

    $date = date_create($fecha);
    return date_format($date, 'd-m-Y');
  }

  function datosbyNroDocFactura($enlace, $numdoc)
  {
    $sqlGet = "SELECT d.* FROM det AS d
              STRAIGHT_JOIN factura AS f ON (f.id=d.ID_TIPO_DOC AND d.TIPO_DOC=1)
              WHERE concat(f.SERIE,'-',f.COMPROBANTE) = '".$numdoc."'";
    $rows = [];

    if ($resultado = mysqli_query($enlace, $sqlGet))
    {
      while($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC))
      {
        $rows[] = $fila;
      }
 
      return $rows;
    }
  }
?>
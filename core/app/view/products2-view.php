        <script src="plugins/jquery/jquery-2.1.4.min.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="plugins/jquery-ui-1.8.16/themes/base/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="plugins/jquery.jqGrid-4.4.3/css/ui.jqgrid.css" />
        <!-- Archivos javascript -->
        <script src="plugins/jquery.jqGrid-4.4.3/js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-ui-1.8.16/ui/minified/jquery-ui.min.js" type="text/javascript"></script>
        <script src="plugins/jquery.jqGrid-4.4.3/js/i18n/grid.locale-es.js" type="text/javascript"></script>
        <script src="plugins/jquery.jqGrid-4.4.3/js/jquery.jqGrid.min.js" type="text/javascript"></script>
		  <script type="text/javascript">
		  /*<th>Codigo</th>
		<th>Imagen</th>
		<th>Nombre</th>
		<th>Precio Entrada</th>
		<th>Precio Por Mayor</th>
		<th>Precio Salida</th>
		<th>Anaquel</th>
		<th>Stock</th>
		<th>Minima</th>
		<th>Activo</th>*/
            $(document).ready(function(){
           jQuery("#tblproductos").jqGrid({
                    url:'./?view=lista_prod',
                    datatype: 'json',
                    mtype: 'POST',
                    colNames:['Codigo','Imagen', 'Nombre','Precio Entrada','Precio Salida','Stock','Activo'],
                    colModel:[
                        {name:'idCliente', index:'idCliente', width:50, resizable:false, align:"center"},
                        {name:'nombre', index:'nombre', width:160,resizable:false, sortable:true},
                        {name:'direccion', index:'direccion', width:150},
                        {name:'telefono', index:'telefono', width:70},
                        {name:'email', index:'email', width:120}
                    ],
                    pager: '#paginacion',
                    rowNum:10,
                    rowList:[15,30],
                    sortname: 'idCliente',
                    sortorder: 'asc',
                    viewrecords: true,
                    caption: 'Lista de productos'
                });
            });
        </script>
<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right">
			<a href="index.php?view=newproduct" class="btn btn-default">Agregar Producto/Servicio</a>
		</div>
		<h1>Lista de Productos/Servicios(<a href="index.php?view=actstock" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>)</h1>
		<div class="clearfix"></div>
		<table id="tblproductos"></table>
        <div id="paginacion" ></div>
	</div>
</div>
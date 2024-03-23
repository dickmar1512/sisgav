<!DOCTYPE html>
<html>
  <head>
    <!--meta charset="UTF-8"-->
    <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
    <title>..::SIGAV::..</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="plugins/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="plugins/dist/css/skins/skin-blue-light.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="plugins/jquery/jquery-2.1.4.min.js"></script>
    <script src="plugins/jquery/jquery-qrcode-0.17.0.min.js"></script>      
    <script src="plugins/morris/raphael-min.js"></script>
    <script src="plugins/morris/morris.js"></script>
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <link rel="stylesheet" href="plugins/morris/example.css">
    <script src="plugins/jspdf/jspdf.min.js"></script>
    <script src="plugins/jspdf/jspdf.plugin.autotable.js"></script>
    <?php if(isset($_GET["view"]) && $_GET["view"]=="sell"):?>
      <script type="text/javascript" src="plugins/jsqrcode/llqrcode.js"></script>
      <script type="text/javascript" src="plugins/jsqrcode/webqr.js"></script>
    <?php endif;?>
	<!--Esto agregue para busqueda dinamica 23/10/2021-->
	<link rel="stylesheet" href="plugins/jquery/jquery-ui.1.10.1.css" />
	<script type="text/javascript">
		$(document).ready(function () {
		   (function($) {
			   $('#FiltrarContenido').keyup(function () {
					var ValorBusqueda = new RegExp($(this).val(), 'i');
					$('.BusquedaRapida tr').hide();
					 $('.BusquedaRapida tr').filter(function () {
						return ValorBusqueda.test($(this).text());
					  }).show();
						})
			  }(jQuery));
		});
	</script> 
    <!--hasta aqui-->
<!-- PARA LOS GRAFICOS -->
 
  <script src="lib/morris.js-0.5.1/morris.js"></script>
  <link rel="stylesheet" href="lib/morris.js-0.5.1/morris.css">
  <script src="lib/raphael-min.js"></script>  
  <script src="lib/prettify.min.js"></script>
  <script src="lib/morris.js-0.5.1/examples/lib/example.js"></script>
  <!-- <link rel="stylesheet" href="lib/example.css"> -->
<!--   <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css"> -->

  </head>

  <body   
  style="<?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>   <?php else:?>background-image: url('img/fondo2.jpg') !important; background-size: 95% !important;<?php endif; ?>"
  >
    <div class="wrapper">
      <!-- Main Header -->
      <?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>
      <header class="main-header" >
        <!-- Logo -->
        <a href="./" class="logo" style="background: linear-gradient(180deg, #000, #031239);">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>T</b>S</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SI</b>GAV</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation" style="background: linear-gradient(180deg, #000, #031239);">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="">
                    <?php if(isset($_SESSION["user_id"]) ){ echo UserData::getById($_SESSION["user_id"])->name; 

                  }?> <b class="caret"></b> </span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="">
                    <!--**** -->
                  </li>                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="./logout.php" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->

        <style type="text/css">
        .sidebar{
            background: #4B5E8F !important;
          }
          
        .main-sidebar{
            background: #4B5E8F !important;
          }

          .sidebar a{
            color:#FFF !important;
          }
          .sidebar a:hover{
            color:#000 !important;
          }

          .treeview-menu a{
            color:#000 !important;
          }
          .treeview-menu span{
            color:#000 !important;
          }
          .header{
            background:#000 !important;
            color:#FFF !important;
          }

        </style>

      <aside class="main-sidebar" 
        >
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <ul class="sidebar-menu" style="color:">
            <li class="header">SOFTWARE v2.0</li>
            <?php              
             $admin = UserData::getById($_SESSION["user_id"])->is_admin;
             if(isset($_SESSION["user_id"])):?>
              <li><a href="./index.php?view=home" ><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=sell"><strong>S/</strong> <span>Vender</span></a></li>
              <li><a href="./?view=sells"><i class='fa fa-file-excel-o'></i> <span>Ventas</span></a></li>
              <?php if($admin == 1){?>
              <li><a href="./?view=res"><i class='fa fa-shopping-cart'></i><span>Compras</span></a></li>
              <?php }?>  
              <?php
                $permiso = PermisoData::get_permiso_x_key('proforma');

                if($permiso->Pee_Valor == 1)
                {
                  ?>
                    <li><a href="./?view=proformas"><i class='fa fa-shopping-cart'></i> <span>Proforma</span></a></li>
                  <?php
                }

                $permiso = PermisoData::get_permiso_x_key('orden_trabajo');

                if($permiso->Pee_Valor == 1)
                {
                  ?>
                    <li><a href="./?view=ordentrabajo"><i class='fa fa-briefcase'></i> <span>Orden de Trabajo</span></a></li>
                  <?php
                }

                $permiso = PermisoData::get_permiso_x_key('importar_excel');

                if($permiso->Pee_Valor == 1)
                {
                  ?>
                    <!-- <li><a href="./?view=importarexcel"><i class='fa fa-file-excel-o'></i> <span>Importar Excel</span></a></li> -->
                  <?php
                }
              ?>              
              <li><a href="./?view=box"><i class='fa fa-cube'></i> <span>Caja</span></a></li>
              <?php if($admin == 1){?>
              <li><a href="./?view=products"><i class='fa fa-glass'></i> <span>Productos/Servicios</span></a></li>
			  <!-- <li><a href="./?view=products2"><i class='fa fa-glass'></i> <span>Productos/Servicios2</span></a></li> -->
              <?php }?>
              <li><a href="./?view=clients"><i class='fa fa-user'></i> <span>Clientes</span></a></li>
              <?php if($admin == 1){?>
              <li><a href="./?view=providers"><i class='fa fa-truck'></i> <span>Proveedores</span></a></li>
              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Catalogos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=unidades"></i> <span>Unidades</span></a></li>
                  <li><a href="./?view=categories">Categorias</a></li>
                  <li><a href="./?view=paquetes">Paquetes</a></li>
                </ul>
              </li>
            <li class="treeview">
              <a href="#"><i class='fa fa-area-chart'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=inventary">Inventario</a></li>
                <li><a href="./?view=re">Abastecer</a></li>
              </ul>
            </li>
          <?php }?>
            <li class="treeview">
              <a href="#"><i class='fa fa-file-text-o'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=reports">Inventario</a></li>
                <li><a href="./?view=kardexbyproducto">Kardex</a></li>
                <li><a href="./?view=sellreports">Ventas</a></li>
                <li><a href="./?view=reportsboleta">Boletas</a></li>
                <li><a href="./?view=reportsfactura">Factura</a></li>
                <li><a href="./?view=reportsnotascredito">Notas de Crédito de Factura</a></li>
                <li><a href="./?view=reportsnotascreditoboleta">Notas de Crédito de Boleta</a></li>
				<li><a href="./?view=reportscomprobantesclientes">Comprobantes por Clientes</a></li>
                <!--<li><a href="./?view=reportsnotasdebito">Notas de Débito de Factura</a></li>
                <li><a href="./?view=reportsnotasdebitoboleta">Notas de Débito de Boleta</a></li>-->
              </ul>
            </li>
            <?php if($admin == 1){?>
            <li class="treeview">
              <a href="#"><i class='fa fa-cog'></i> <span>Administracion</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=users">Usuarios</a></li>
                <li><a href="./?view=settings">Configuracion</a></li> 
                <li><a href="./?view=permisos">Permisos</a></li>
              </ul>
            </li>
             <?php }?>
          <?php endif;?>

          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
    <?php endif;?>

      <!-- Content Wrapper. Contains page content -->
      <?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>
      <div class="content-wrapper">
      <div class="content">
        <?php View::load("index");?>
        </div>
      </div><!-- /.content-wrapper -->

        <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2019 TAROSOFT</strong>
      </footer>
      <?php else:?>
<div class="login-box">
       <div class="login-logo">
         <a href="./">
        <!-- TARO<b>SOTF</b> -->
        <img src="./plugins/dist/img/login.png" alt="" style="max-width: 350px; max-height: 200px;">
        </a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <form action="./?action=processlogin" method="post">
          <div class="form-group has-feedback">
            <input autofocus type="text" name="username" required class="form-control" placeholder="Usuario"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" required class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">

            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat"><b>ACCEDER</b></button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->  
      <?php endif;?>


    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <!-- Bootstrap 3.3.2 JS -->
    <script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="plugins/dist/js/app.min.js" type="text/javascript"></script>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".datatable").DataTable({
          "language": {
        "sProcessing":    "Procesando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "No se encontraron resultados",
        "sEmptyTable":    "Ningún dato disponible en esta tabla",
        "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Buscar:",
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":    "Último",
            "sNext":    "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
        });
      });
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience. Slimscroll is required when using the
          fixed layout. -->
  </body>
</html>


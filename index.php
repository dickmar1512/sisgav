<?php
define("ROOT", dirname(__FILE__));

$debug= false;
if($debug){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}

include "core/autoload.php";

date_default_timezone_set("America/Lima");

ob_start();
session_start();
Core::$root="";

// si quieres que se muestre las consultas SQL debes decomentar la siguiente linea
// Core::$debug_sql = true;

$lb = new Lb();
$lb->start();

?>

<script type="text/javascript">
	var cuenta = 0;
	function enviado() {
	  if (cuenta == 0)
	  {
	    cuenta++;
	    return true;
	  }
	  else
	  {
	    alert("El formulario ya está siendo enviado, por favor aguarde un instante.");
	    return false;
	  }
	}

	function validar_dni()
	{
	  valor = document.getElementById("numDocUsuario").value;
	  cantidad_digitos = valor.trim().length;

	  if(valor != '')
	  {
	  	if(cantidad_digitos == 8)
	  	{
	  		if (!/^([0-9])*$/.test(valor))
		    {
		      alert('Dni inválido');
		      document.getElementById("numDocUsuario").value = "";
		    }
		    else
		    {
		    	generar_nombre(valor, 3);
		    }
	  	}
	  	else
	  	{
	  		alert('Dni inválido');
	  	}	    
	  }
	}



	function validar_no_dni()
	{
	  valor = document.getElementById("numDocUsuario").value;
	  cantidad_digitos = valor.trim().length;

	  if(valor != '')
	  {
	  	if(cantidad_digitos >= 3)
	  	{
	  		if (!/^([0-9])*$/.test(valor))
		    {
		      alert('Dni inválido');
		      document.getElementById("numDocUsuario").value = "";
		    }
		    else
		    {
		    	generar_nombre(valor, 4);
		    }
	  	}
	  	else
	  	{
	  		alert('Dni inválido');
	  	}	    
	  }
	}

	function validar_ruc()
	{
		valor = document.getElementById("ruc").value;
	  	cantidad_digitos = valor.trim().length;

		if(valor != '')
	  	{
	  		if(cantidad_digitos == 11)
	  		{
	  			if (!/^([0-9])*$/.test(valor))
		    	{
		      		alert('RUC inválido');
		      		document.getElementById("ruc").value = "";
		    	}
		    	else
		    	{
		    		generar_nombre(valor, 1);
		    	}
	  		}
	  	else
	  	{
	  		alert('RUC inválido');
	  	}
	  }
	}

	function validar_dniproveedor()
	{
		var personaIdd = '659f1802e2aa5200148092df';		
		var token ='PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq';
       
	   $.ajax({
		   url:'https://back.apisunat.com/personas/' + personaIdd + '/getDNI',            
		   type:'GET',
		   dataType:'json',
		   data:{
			   dni:$('#dni').val(),
			   personaToken:token
		   }
	   })
	   .done(function(rpta){
			$('#name').val(rpta.data['nombre']);
			$('#lastname').val(rpta.data['apellido_paterno']+' '+rpta.data['apellido_materno']);
			$('#address1').val(rpta.data['domicilio'].direccion);		
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			$("#errorProvJ").html('');
			if (jqXHR.status == 400) {
			   $("#errorProvJ").html(jqXHR.responseJSON['error'].message);
		   	} 
			else 
			{
				console.log('Error:', jqXHR.status, errorThrown);
			}
	   });
	}

	function validar_dniConductor()
	{
		var personaIdd = '659f1802e2aa5200148092df';		
		var token ='PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq';
       
	   $.ajax({
		   url:'https://back.apisunat.com/personas/' + personaIdd + '/getDNI',            
		   type:'GET',
		   dataType:'json',
		   data:{
			   dni:$('#numDocConductor').val(),
			   personaToken:token
		   }
	   })
	   .done(function(rpta){
			$('#datosgr #nombres').val(rpta.data['nombre']);
			$('#datosgr #apellidos').val(rpta.data['apellido_paterno']+' '+rpta.data['apellido_materno']);
			$('#address1').val(rpta.data['domicilio'].direccion);		
		});
	// 	.fail(function(jqXHR, textStatus, errorThrown) {
	// 		$("#errorProvJ").html('');
	// 		if (jqXHR.status == 400) {
	// 		   $("#errorProvJ").html(jqXHR.responseJSON['error'].message);
	// 	   	} 
	// 		else 
	// 		{
	// 			console.log('Error:', jqXHR.status, errorThrown);
	// 		}
	//    });
	}

	function validar_rucproveedor()
	{
		var personaIdd = '659f1802e2aa5200148092df';		
		var token ='PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq';
       
       $.ajax({
           url:'https://back.apisunat.com/personas/' + personaIdd + '/getRUC',            
           type:'GET',
           dataType:'json',
           data:{
               ruc:$('#ruc').val(),
               personaToken:token
           }
       })
		.done(function(rpta){
			$('#razon_social').val(rpta.data['nombre']);
			$('#address1').val(rpta.data['domicilio'].direccion);
               })
		.fail(function(jqXHR, textStatus, errorThrown){
            $("#errorProvJ").html('');
            if (jqXHR.status == 400) 
				{
                    $("#errorProvJ").html(jqXHR.responseJSON['error'].message);
                } 
			else
				{
                   	console.log('Error:', jqXHR.status, errorThrown);
                }
       });
	}

	function generar_nombre(numDocUsuario, tipo)
	{
	    $.ajax({
		    type : "POST",
		    data: {
		    		"numDocUsuario": numDocUsuario,
		    		"tipo": tipo
		    	},
		    url: 'generar_nombre_ajax.php',

	    	success : function(data){

	    		if (tipo == 1) 
	    		{
	    			$("#datos").html('');
            		$("#datos").html(data);
	    		}
	    		else if(tipo == 4)
	    		{
	    			$("#datos3").html('');
            		$("#datos3").html(data);
	    		}
	    		
	    	},
	  	});
	}

	function fnBuscarRUC()
    {
        var personaIdd = '659f1802e2aa5200148092df';
       
       $.ajax({
           url:'https://back.apisunat.com/personas/' + personaIdd + '/getRUC',            
           type:'GET',
           dataType:'json',
           data:{
               ruc:$('#comprobante_factura #ruc').val(),
               personaToken:'PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq'
           }
       }).done(function(rpta){
				$('#datos #rznSocialUsuario').val(rpta.data['nombre']);
				$('#datos #desDireccionCliente').val(rpta.data['domicilio'].direccion);
				$('#datos #codUbigeoCliente').val(rpta.data['domicilio'].ubigeo);           
               }).fail(function(jqXHR, textStatus, errorThrown) {
                           if (jqXHR.status == 400) {
                               $("#datos #msjError").html(jqXHR.responseJSON['error'].message);
                           } else {
                               // Otro código de error
                               console.log('Error:', jqXHR.status, errorThrown);
                           }
       });
    }

	function fnBuscarDNI()
    {
        var personaIdd = '659f1802e2aa5200148092df';
       
        $.ajax({
            url:'https://back.apisunat.com/personas/' + personaIdd + '/getDNI',            
            type:'GET',
            dataType:'json',
            data:{
                dni:$('#comprobante_boleta #numDocUsuario').val(),
                personaToken:'PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq'
            }
        })
		.done(function(rpta){
			$('#datos3 #rznSocialUsuario').val(rpta.data['apellido_paterno']+' '+rpta.data['apellido_materno']+' '+rpta.data['nombre']);
			$('#datos3 #desDireccionCliente').val(rpta.data['domicilio'].direccion);
			$('#datos3 #codUbigeoCliente').val(rpta.data['domicilio'].ubigeo);
                })
		.fail(function(jqXHR, textStatus, errorThrown) {
        	$("#datos").html('');
            if (jqXHR.status == 400) 
			{
                $("#datos #msjError").html(jqXHR.responseJSON['error'].message);
            } 
			else 
			{
                console.log('Error:', jqXHR.status, errorThrown);
            }
        });
    }

	function fnBuscarDocproforma()
    {
        var personaIdd = '659f1802e2aa5200148092df';
       
       $.ajax({
           url:'https://back.apisunat.com/personas/' + personaIdd + '/getRUC',            
           type:'GET',
           dataType:'json',
           data:{
               ruc:$('#comprobante_proforma #ruc').val(),
               personaToken:'PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq'
           }
       }).done(function(rpta){
		console.log("Ubigeo=>",rpta.data['domicilio'].ubigeo);
		var ubigeo = rpta.data['domicilio'].ubigeo;

				$('#datosp #rznSocialUsuario').val(rpta.data['nombre']);
				$('#datosp #desDireccionCliente').val(rpta.data['domicilio'].direccion);
				$('#datosp #codUbigeoCliente').val(ubigeo);           
               }).fail(function(jqXHR, textStatus, errorThrown) {
                           if (jqXHR.status == 400) {
                               $("#datosp #msjError").html(jqXHR.responseJSON['error'].message);
                           } else {
                               // Otro código de error
                               console.log('Error:', jqXHR.status, errorThrown);
                           }
       });
    }

	function fnBuscarDocguiaremision()
    {
        var personaIdd = '659f1802e2aa5200148092df';
       
       $.ajax({
           url:'https://back.apisunat.com/personas/' + personaIdd + '/getRUC',            
           type:'GET',
           dataType:'json',
           data:{
               ruc:$('#comprobante_guiarem #ruc').val(),
               personaToken:'PRD_k3BCLo2s3GQH4CQgByHa9uqZgdGFqP0wydhGnJVmHHZxTBvWjpFq7G4bcJ3LWwCq'
           }
       }).done(function(rpta){
		var ubigeo = rpta.data['domicilio'].ubigeo;

				$('#datosgr #rznSocialUsuario').val(rpta.data['nombre']);
				$('#datosgr #desDireccionLlegada').val(rpta.data['domicilio'].direccion);
				$('#datosgr #codUbigeoLlegada').val(ubigeo);           
               }).fail(function(jqXHR, textStatus, errorThrown) {
                           if (jqXHR.status == 400) {
                               $("#datosgr #msjError").html(jqXHR.responseJSON['error'].message);
                           } else {
                               // Otro código de error
                               console.log('Error:', jqXHR.status, errorThrown);
                           }
       });
    }
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado De Benefeciarios</title>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
 function validar_vacios(opcion)
  {
	var form = document.listado_beneficiarios;
	if(opcion==1)
	{
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		//VALIDAMOS EL CAMPO NOMBRE
		if(form.aso_documento.value == "")
		{ CamposVacios += "* Documento\n"; form.aso_documento.focus(); }
		//SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
		if (CamposVacios != "")
		{
			alert(Mensaje + CamposVacios);
			return true;
		}
			form.action = '../reportes_PDF/listado_de_beneficiarios.php?opc='+opcion;
			form.submit();
     }
	 else
	 {
		 if(opcion==2)
		 {
		 	form.action = '../reportes_PDF/listado_de_beneficiarios.php?opc='+opcion;
		 	form.submit();
		 }
	 }
  }
  </script>
</head>
<body>
<?php
include_once('../clases/estado_nits.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$ins_est_nit=new estado_nits();
$con_estados=$ins_est_nit->con_est_nits();
$res_anios=$ins_presupuesto->obtener_lista_anios();
?>
<form name="listado_beneficiarios" id="listado_beneficiarios" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">Listado De Benefeciarios</th>
        </tr>
        <tr>
        	<th>Documento</th><td><input type="text" name="aso_documento" id="aso_documento" onkeypress="return permite(event,'num')"/></td>
            <td><input type="button" name="consultar" value="Consultar" onclick="validar_vacios(1);"/></td>
        </tr>
        <tr>
            <th colspan="4"><a href="Javascript:void(0)" onclick="validar_vacios(2);">Ver Todos</a></th>
        </tr>
    </table>
</form>
</body>
</html>
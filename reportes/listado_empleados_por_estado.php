<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Empleados Por Estado</title>
<script>
 function validar_vacios(opcion)
  {
	var form = document.lis_empleados;
	if(opcion==1)
	{
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		//VALIDAMOS EL CAMPO NOMBRE
		if(form.estados.selectedIndex == 0)
		{ CamposVacios += "* Estado\n"; form.estados.focus(); }
		//SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
		if (CamposVacios != "")
		{
			alert(Mensaje + CamposVacios);
			return true;
		}
			form.action = '../reportes_PDF/listado_empleados_por_estado.php?opc='+opcion;
			form.submit();
     }
	 else
	 {
		 if(opcion==2)
		 {
		 	form.action = '../reportes_PDF/listado_empleados_por_estado.php?opc='+opcion;
		 	form.submit();
		 }
	 }
  }
  </script>
</head>
<body>
<?php

include_once('../clases/estado_nits.class.php');
$ins_est_nit=new estado_nits();
$con_estados=$ins_est_nit->con_est_nits();
?>
<form name="lis_empleados" id="lis_empleados" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">Empleados Por Estado</th>
        </tr>
        <tr>
        	<th>Estado</th><td><select name="estados" id="estados"><option value="0">Seleccione</option>
            <?php
            while($res_estados=mssql_fetch_array($con_estados))
			{
			?>
            	<option value="<?php echo $res_estados['nit_est_id']; ?>"><?php echo $res_estados['nit_est_nombre']; ?></option>
            <?php
			}
			?>
            </select></td>
            <td><input type="button" name="consultar" value="Consultar" onclick="validar_vacios(1);"/></td>
        </tr>
        <tr>
			<th colspan="3"><a href="Javascript:void(0)" onclick="validar_vacios(2);">Ver Todos</a></th>
        </tr>
    </table>
</form>
</body>
</html>
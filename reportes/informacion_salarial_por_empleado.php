<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informacion Salarial Empleados</title>
<script>
 function validar_vacios(opcion)
  {
	var form = document.puc;
	if(opcion==1)
	{
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		//VALIDAMOS EL CAMPO NOMBRE
		if(form.documento.value == "")
		{ CamposVacios += "* Documento\n"; form.documento.focus(); }
		//SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
		if (CamposVacios != "")
		{
			alert(Mensaje + CamposVacios);
			return true;
		}
			form.action = '../reportes_PDF/informacion_salarial_por_empleado.php?opc='+opcion;
			form.submit();
     }
	 else
	 {
		 if(opcion==2)
		 {
		 	form.action = '../reportes_PDF/informacion_salarial_por_empleado.php?opc='+opcion;
		 	form.submit();
		 }
	 }
  }
  </script>
</head>
<body>
<form name="puc" id="puc" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">Informacion Salarial</th>
        </tr>
        <tr>
        	<th>Documento</th><td><input type="text" name="documento" id="documento"/></td>
            <td><input type="button" name="consultar" value="Consultar" onclick="validar_vacios(1);"/></td>
        </tr>
        <tr>
			<th colspan="3"><a href="Javascript:void(0)" onclick="validar_vacios(2);">Ver Todos</a></th>
        </tr>
    </table>
</form>
</body>
</html>
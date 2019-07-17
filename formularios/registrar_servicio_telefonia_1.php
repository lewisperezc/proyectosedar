<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>
<script>
function seleccion()
{
	if(document.form_sel_nit.tipo_nit.selectedIndex == 0)
	{
		alert('Seleccione Una Opcion Valida!!!');
	}
	else
	{
		document.form_sel_nit.submit();
	}
}
</script>
<center>
<form name="form_sel_nit" action="registrar_servicio_telefonia_2.php" target="frame2" method="post">
	<table>
    	<tr>
        	<th>Tipo Nit</th>
        	<td>
            	<select name="tipo_nit">
            		<option value="0" onclick="seleccion();">Seleccione</option>
                    <option value="1" onclick="seleccion();">Afiliado</option>
                    <option value="2" onclick="seleccion();">Empleado</option>
                </select>
            </td>
        </tr>
    </table>
</form>
</center>
</body>
</html>
<?php 
session_start();
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
		document.form_sel_tip_nit.submit();
}
</script>
<form name="form_sel_tip_nit" method="post" action="consultar_registro_servicio_telefonia_2.php" target="frame2">
<center>
	<table bordercolor="#0099CC" border="1">
    	<tr>
        	<th>Tipo Nit</th>
            <td>
            	<select name="tipo_nit" required x-moz-errormessage="Seleccione Una Opcion Valida">
            		<option value="">Seleccione</option>
                    <option value="1" onclick="seleccion();">Asociado</option>
                    <option value="2" onclick="seleccion();">Empleado</option>
                </select>
            </td>
        </tr>
    </table>
</center>
</form>
</body>
</html>
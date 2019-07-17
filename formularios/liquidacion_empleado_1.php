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
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
session_start();
$user = $_SESSION['usuario'];
$perfil = $_SESSION['perfil'];
$ins_nits = new nits();
$con_nits = $ins_nits->con_aso_por_id_estado(2,1);
?>
<script>
	function enviar()
	{
		if(document.reg_liq_empleado.sel_nit.selectedIndex == 0)
		{
			alert('Seleccione un afiliado.');
		}
		else
		{
			document.reg_liq_empleado.submit();
		}
	}
</script>
<form name="reg_liq_empleado" action="liquidacion_empleado_2.php" target="frame2" method="post">
	<center>
		<table>
    	<tr>
        	<th>Liquidaci&oacute;n Empleado</th>
        </tr>
    	<tr>
        	<td>Empleado
            <select name="sel_nit">
            	<option value="0" onclick="enviar();">Seleccione</option>
            <?php
            while($res_nits = mssql_fetch_array($con_nits))
			{
			?>
            	<option value="<?php echo $res_nits['nit_id']; ?>" onclick="enviar();"><?php echo $res_nits['nits_nombres']." ".$res_nits['nits_apellidos']; ?></option>
            <?php	
			}
			?>
            </select>
            </td>
        </tr>
    </table>
	</center>
</form>
</body>
</html>
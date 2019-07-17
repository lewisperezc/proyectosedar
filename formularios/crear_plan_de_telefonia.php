<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits = new nits();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Crear Plan De Telefonia</title>
<script src="../librerias/js/validacion_num_letras.js" type="text/javascript" language="javascript"></script>
<script src="librerias/js/validacion_num_letras.js" type="text/javascript" language="javascript"></script>
<script src="../librerias/js/jquery-1.5.0.js" type="text/javascript" language="javascript"></script>
<script src="librerias/js/jquery-1.5.0.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<script>
function Agregar()
{
	var pos = $("#planes>tbody>tr").length-2;
	<?php
	$con_tod_proveedores=$ins_nits->con_tip_nit(3);
	?>
	campo='<tr><td><input type="text" name="cre_pla_tel_nombre'+pos+'" id="cre_pla_tel_nombre'+pos+'" required="required"/></td>';
	campo+='<td><input type="text" name="cre_pla_tel_valor'+pos+'" id="cre_pla_tel_valor'+pos+'" required="required" onkeypress="return permite(event,num)"/></td>';
    campo+='<td><select name="cre_pla_tel_proveedor'+pos+'" id="cre_pla_tel_proveedor'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option>';
	<?php
	while($res_tod_proveedores=mssql_fetch_array($con_tod_proveedores))
	{ ?>
    campo+='<option value="<?php echo $res_tod_proveedores['nit_id']; ?>"><?php echo $res_tod_proveedores['nits_nombres']; ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td></tr>';
	$("#planes").append(campo);
	$("#can_filas").val(pos);
}
</script>
<?php
$con_tod_proveedores = $ins_nits->con_tip_nit(3);
?>
<form name="plan_telefonia" action="control/guardar_plan_telefonia.php" method="post">
<center>
	<table id="planes">
    	<tr>
        	<th colspan="6">Crear Plan De Telefonia</th>
        </tr>
        <tr>
        	<th>Nombre</th>
            <th>Valor</th>
            <th>Proveedor</th>
        </tr>
        <tr>
            <td><input type="text" name="cre_pla_tel_nombre0" id="cre_pla_tel_nombre0" required="required"/></td>
            <td><input type="text" name="cre_pla_tel_valor0" id="cre_pla_tel_valor0" required="required" onkeypress="return permite(event,'num')"/></td>
            <td><select name="cre_pla_tel_proveedor0" id="cre_pla_tel_proveedor0" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--Seleccione--</option>
            <?php while($res_tod_proveedores = mssql_fetch_array($con_tod_proveedores)){ ?>
            	  <option value="<?php echo $res_tod_proveedores['nit_id']; ?>">
				  <?php echo $res_tod_proveedores['nits_nombres']; ?></option>
            <?php } ?>
            </select></td>
        </tr>
	</table>
    <table>
        <tr>
        	<td>
            <input type="button" class="art-button" value="Agregar" name="agr" id="agr" onclick="Agregar();"/>
            <input type="hidden" name="can_filas" id="can_filas"/>
            <input type="submit" class="art-button" name="guardar" value="Guardar"/>
            </td>
        </tr>
    </table>
</center>
</form>
</body>
</html>
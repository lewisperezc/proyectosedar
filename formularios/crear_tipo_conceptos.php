<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function AgrFila()
{
	var pos = $("#registros>tbody>tr").length-2;
	campo='<tr><td><input type="text" name="tip_concep_nombre'+pos+'" id="tip_concep_nombre'+pos+'" onKeyPress="return permite(event,car)" required="required"/></td>';
	campo+='<td><input type="text" name="tip_concep_concecutipo'+pos+'" id="tip_concep_concecutipo'+pos+'" onKeyPress="return permite(event,car)" required="required"/></td></tr>';
	$("#registros").append(campo);
	$("#cuantos_campos").val(pos);
}
</script>
<form name="tipos_conceptos" id="" method="post" action="./control/guardar_tipo_concepto.php">
<center>
<table id="registros">
    <tr>
       	<th colspan="2"><h4>Crear Grupo Conceptos</h4></th>
    </tr>
	<tr>
    	<td>Nombre</td>
        <td>Grupo  Consecutivo</td>
    </tr>
    <tr>
        <td><input type="text" name="tip_concep_nombre0" id="tip_concep_nombre0" required="required"/></td>
        <td><input type="text" name="tip_concep_concecutipo0" id="tip_concep_concecutipo0" required="required"/></td>
	</tr> 
</table>
<table>     
    <tr>
    	<td><input type="button" class="art-button" name="agregar" value="Agregar Item" onclick="AgrFila();"/>
        <input type="hidden" name="cuantos_campos" id="cuantos_campos">
        <input type="submit" class="art-button" name="guardar" value="Guardar"/></td>
    </tr>
</table>
</center>
</form>
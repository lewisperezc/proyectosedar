<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/mes_contable.class.php');
$ins_cuenta = new cuenta();
$ins_nits = new nits();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$tipo=$_GET['tipo'];
if($tipo==1)
	$nit_id = $_SESSION['aso_id'];
elseif($tipo==2)
	$nit_id = $_SESSION['emp_id']; 
	
$con_nom_nit=$ins_nits->cons_nombres_nit($nit_id);
$res_nom_nit=mssql_fetch_array($con_nom_nit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Novedad</title>
<script type="text/javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
$(document).ready(function()
{
	$("#gua").click(function(evento)
	{
		var cadena = document.reg_novedad.mes_sele.value;
    var ano = $("#estAno").val();
    	cadena = cadena.split("-");
    	if(cadena[0]==1)
		{
	    	alert("No se puede ingresar mas datos en este mes!!!");
			return false
		}
   });
});
</script>
<script>
function Agregar()
{
	var pos = $("#novedades>tbody>tr").length-3;
	<?php
	$con_cue_nomina=$ins_cuenta->con_cue_pabs(25050);
	?>
	campo='<tr><td><select name="nov_nombre'+pos+'" id="nov_nombre'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option>';
	<?php
	while($res_cue_nomina=mssql_fetch_array($con_cue_nomina))
	{
	?>
	campo+='<option value="<?php echo $res_cue_nomina['cue_id']; ?>"><?php echo $res_cue_nomina['cue_nombre']; ?></option>';
	<?php
	}
	?>
	campo+='</select></td>';
	campo+='<td><input type="text" name="nov_valor'+pos+'" id="nov_valor'+pos+'" required="required" onkeypress="return permite(event,num)"/></td>';
	campo+='<td><textarea cols="20" rows="0" name="nov_observacion'+pos+'" id="nov_observacion'+pos+'" required="required"></textarea></td></tr>';
	$("#novedades").append(campo);
	$("#can_filas").val(pos);
}
</script>
</head>
<body>
<?php
$con_cue_nomina=$ins_cuenta->con_cue_pabs(25050);
?>
<form name="reg_novedad" method="post" action="../control/guardar_novedad.php">
<center>
  <table bordercolor="#009999" border="1">
   <tr>
    <td><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
    <input type="hidden" name="tipo_persona" id="tipo_persona" value="<?php echo $tipo; ?>"/>
     Mes Contable: <select name="mes_sele" id="mes_sele">
       <?php
		while($dat_meses = mssql_fetch_array($meses))
		 echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  ?>  
      </select>  
    </td>
   </tr>
  </table>
  <table bordercolor="#009999" border="1" id="novedades">
   	  <tr>
      	<th colspan="3">Registrar Novedad</th>
      </tr>
      <tr>
      	<th colspan="3"><?php echo $res_nom_nit['nombres']; ?></th>
      </tr>
      <tr>
      	<th>Novedad</th>
      	<th>Valor</th>
        <th>Observación</th>
      </tr>
      <tr>
      	<td><select name="nov_nombre0" id="nov_nombre0" required x-moz-errormessage="Seleccione Una Opción Valida">
            	<option value="">--Seleccione--</option>
            <?php while($res_cue_nomina=mssql_fetch_array($con_cue_nomina))
			{
			?>
            <option value="<?php echo $res_cue_nomina['cue_id']; ?>"><?php echo $res_cue_nomina['cue_nombre']; ?></option>
            <?php			 
			}
			?>
            </select></td>
        <td><input type="text" name="nov_valor0" id="nov_valor0" required="required" onkeypress="return permite(event,'num')"/></td>
        <td><textarea cols="20" rows="0" name="nov_observacion0" id="nov_observacion0" required="required"></textarea></td>
      </tr>
	</table>
    <table>
    	<tr>
        	<td colspan="3">
            <input type="button" class="art-button" value="Agregar" name="agregar" onclick="Agregar();"/>
       		<input type="hidden" name="can_filas" id="can_filas">
       		<input type="submit" class="art-button" value="Guardar" name="gua" id="gua"/>
            </td>
        </tr>
    </table>
 </center>   
</form>
</body>
</html>
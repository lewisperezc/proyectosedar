<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

$id_contrato = $_SESSION['id_contrato'];
$id_hos = $_SESSION['hos'];
include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();

$con_nit_con_contrato = $instancia_contrato->con_aso_exi_contrato(1,$id_contrato);
?>
<script>
function modificar1()
{
	for (i=0;i<document.forms[0].elements.length;i++) 
	{
    	if (document.forms[0].elements[i].disabled) 
		{
    		document.forms[0].elements[i].disabled = false;
		}
    }
	document.con_aso_existentes.eli.disabled=false;
}
function modificar2()
{
	for (i=0;i<document.forms[1].elements.length;i++) 
	{
    	if (document.forms[1].elements[i].disabled) 
		{
          document.forms[1].elements[i].disabled = false;
		}
    }
	document.con_agr_asociados.agr.disabled=false;
}
function enviar()
{
	document.con_agr_asociados.submit();
}
</script>
<form name="con_aso_existentes" id="aso_cen_costos" method="post" target="frame2" action="../control/guardar_asociado.php">
<center>
	<table>
	<tr>
    	<td><b>Afiliados Que Pertenecen Al Contrato</b></td>
	</tr>
    <td><hr /></td>
    <tr>
    	<td><select name="aso_cen_costos[]" id="aso_cen_costos[]" size=5 style='width:500px;height:175px;border:solid' multiple="multiple" disabled="disabled">
	      <?php
		   while($row = mssql_fetch_array($con_nit_con_contrato))
		   {
			?>
				<option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']." ".$row['nits_apellidos']; ?></option>	
			<?php
			}
			?> 
          </select></td>
    </tr>
   
</table>
</center>
</form>
<form name="retornar" id="retornar" method="post">
<center>
<table>
    <tr>
       <td><input type="submit" class="art-button" onClick="document.retornar.action = 'consultar_contrato_prestacion_servicios_2.php'" value="<< Atras" target="frame2"/>
       <input type="submit" class="art-button" onClick="document.retornar.action = 'consultar_contrato_prestacion_servicios_1.php'" value="<< Volver al Inicio" target="frame2"/></td>
    </tr>
    </table>
</center>
</form>
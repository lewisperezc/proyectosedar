<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>


<?php
$id_asociado = $_SESSION['aso_id'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$con_tip_documento = $instancia_nits->con_tip_identificacion();
$con_ben_asociado = $instancia_nits->con_ben_asociado($id_asociado);
$num_filas = mssql_num_rows($con_ben_asociado);
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function habilitar()
{
	for (i=0;i<document.forms[0].elements.length;i++) 
	{
    	if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		}
    }
	document.aso_beneficiario.guardar.disabled=false;
}


function Atras()
{
	var form=document.aso_beneficiario;
	form.action='consultar_asociado_4.php';
	form.submit();
}

function Siguiente()
{
	var form=document.aso_beneficiario;
	form.action='consultar_asociado_6.php';
	form.submit();
}

function PreguntarEliminar(ben_id,aso_id)
{
	
	var mensaje=confirm("Esta seguro que desea eliminar el beneficiario?");
	if(mensaje)
	{
		var form=document.aso_beneficiario;
		form.action='../control/eliminar_beneficiario_asociado.php?benef_id='+ben_id+'&asoci_id='+aso_id;
		form.submit();
	}
	else
	{
		$("#aso_beneficiario").submit(function(){return false;});
	}
	
}

function AgregarFila()
{
	<?php
	include_once('../clases/nits.class.php');
	$instancia_nits = new nits();
	$con_tip_documento = $instancia_nits->con_tip_identificacion();
	$con_tod_parentescos = $instancia_nits->con_tod_parentescos();
	?>
	
	var can_registros=$("#can_registros").val();
	if(can_registros='')
		can_registros=0;
	
	var elhtml='<tr><td><input type="text" name="aso_ape_beneficiario['+can_registros+']" required="required"/></td>';
	elhtml+='<td><input type="text" name="aso_nom_beneficiario['+can_registros+']" required="required"/></td>';
	elhtml+='<td><select name="aso_parentesco['+can_registros+']" required="required">';
    elhtml+='<option value="">--Seleccione--</option>';
    <?php
    while($row = mssql_fetch_array($con_tod_parentescos))
  	{
    ?>
    elhtml+='<option value="<?php echo $row['par_id'];?>"><?php echo $row['par_nombres']; ?></option>"';
    <?php
  	}
  	?>
    elhtml+='</select></td>';
	
	elhtml+='<td><select name="aso_tip_doc_beneficiario['+can_registros+']" required="required">';
    elhtml+='<option value="">--Seleccione--</option>';
    <?php
    while($row = mssql_fetch_array($con_tip_documento))
  	{
    ?>
    elhtml+='<option value="<?php echo $row['tip_ide_id'];?>"><?php echo $row['tip_ide_nombre']; ?></option>"';
    <?php
  	}
  	?>
    elhtml+='</select></td>';
	
	elhtml+='<td><input type="text" name="aso_num_doc_beneficiario['+can_registros+']" required="required"/></td>';
	elhtml+='<td><input type="text" name="aso_por_ben_beneficiario['+can_registros+']" required="required"/></td>';
	elhtml+='<td></td></tr>';
	
	var nuevo_valor=parseInt(can_registros)+1;
	$("#tbl_beneficiarios").append(elhtml);
	$("#can_registros").val(nuevo_valor);
	
}


</script>
<form id="aso_beneficiario" name="aso_beneficiario" method="post" action="../control/actualizar_asociado_4.php">
<center>
  <table id="tbl_beneficiarios">
  <tr>
       <td colspan="6"><h4>Datos Beneficiarios</h4></td>
  </tr>
  <tr>
       <td colspan="6"><hr/></td>
  </tr>
  <tr>
     <td><b>Apellidos Beneficiario</b></td>
     <td><b>Nombres Beneficiario</b></td>
     <td><b>Parentesco</b></td>
     <td><b>Tipo Documento</b></td>
     <td><b>N&uacute;mero Documento</b></td>
     <td><b>Porcentaje Beneficios</b></td>
     <td><b>Eliminar</b></td>
  </tr>
<?php
$i=0;
$valor=0;
while($dat_ben_asociado = mssql_fetch_array($con_ben_asociado))
{
  $con_tip_documento = $instancia_nits->con_tip_identificacion();
  $con_tod_parentescos = $instancia_nits->con_tod_parentescos();
?>
<tr>
  <input type="hidden" name="aso_id_beneficiario[<?php echo $i; ?>]" value="<?php echo $dat_ben_asociado['ben_id']; ?>"/>
  <td><input type="text" name="aso_ape_beneficiario[<?php echo $i; ?>]" onKeyPress="return permite(event,'car')" value="<?php echo $dat_ben_asociado['ben_apellidos']; ?>" disabled="disabled" required="required"/></td> 
  <td>
  <input type="text" required="required" name="aso_nom_beneficiario[<?php echo $i; ?>]" onKeyPress="return permite(event,'car')" value="<?php echo $dat_ben_asociado['ben_nombres']; ?>" disabled="disabled"/></td>
  <td><select name="aso_parentesco[<?php echo $i; ?>]" id="aso_parentesco[<?php echo $i; ?>]" disabled="disabled" required="required">
    <option value="">--Seleccione--</option>
    <?php
    while($row = mssql_fetch_array($con_tod_parentescos))
  	{
    	if($dat_ben_asociado['par_id'] == $row['par_id']) 
    	{
  	?>
      		<option value="<?php echo $row['par_id']; ?>" selected="selected"><?php echo $row['par_nombres']; ?></option>
    	<?php
        }
        else
        {
        ?>
    		<option value="<?php echo $row['par_id']; ?>"><?php echo $row['par_nombres']; ?></option>
     <?php
    	}
  	}
  	?>
    </select>
    </td>
  <td>
    <select name="aso_tip_doc_beneficiario[<?php echo $i; ?>]" required="required" disabled="disabled">
    <option value="">--Seleccione--</option>
    <?php
    while($row = mssql_fetch_array($con_tip_documento))
  {
    if($dat_ben_asociado['tip_ide_id'] == $row['tip_ide_id'])
    {
  ?>
    <option value="<?php echo $row['tip_ide_id']; ?>" selected="selected">
  <?php echo $row['tip_ide_nombre']; ?>
    </option>
    <?php
        }
        else
        {
        ?>
        <option value="<?php echo $row['tip_ide_id']; ?>"><?php echo $row['tip_ide_nombre']; ?></option>
        <?php
        }
  }
  ?>
    </select>
    </td>
  <td><input type="text" required="required" name="aso_num_doc_beneficiario[<?php echo $i; ?>]" onKeyPress="return permite(event,'num')" value="<?php echo $dat_ben_asociado['ben_num_identificacion']; ?>" disabled="disabled"/></td>
  <td><input type="text" required="required" name="aso_por_ben_beneficiario[<?php echo $i; ?>]" id="aso_por_ben_beneficiario<?php echo $i; ?>" onKeyPress="return permite(event,'num')" value="<?php echo $dat_ben_asociado['ben_por_beneficios']; ?>" disabled="disabled"/></td>
  <td><input type="radio" name="aso_por_ben_eliminar<?php echo $i; ?>" id="aso_por_ben_eliminar<?php echo $i; ?>" value="<?php echo $dat_ben_asociado['ben_id']; ?>" disabled="disabled" onclick="PreguntarEliminar(<?php echo $dat_ben_asociado['ben_id']; ?>,<?php echo $id_asociado; ?>)"/></td>
  </tr>
  <?php
      $i++;
    $valor = $i;
    }
//}
  ?>
  </table>
  <table>
  <tr>
     <td colspan="4">
        <input type="button" class="art-button" onClick="Atras();" value="<< Atras" target="frame2"/>
        <input type="button" class="art-button" value="Siguiente >>" target="frame2" onclick="Siguiente();"/>
        <?PHP
       	if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       	{
       	?>
        <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
        <input type="hidden" class="art-button" value="<?php echo $valor; ?>" name="can_registros" id="can_registros"/>
        <input type="submit" class="art-button" value="Guardar" name="guardar" id="guardar" disabled="disabled"/>
        <?PHP
		}
        ?>
        <input type="button" class="art-button" value="Agregar fila" disabled name="agregar" onclick="AgregarFila();"/>
        </td>
   </tr>      
</table>
</center>
</form>
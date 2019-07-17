<?php 
	session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
	include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();

$id_contrato = $_SESSION['id_contrato'];
$id_hos = $_SESSION['hos'];
$cen_hosp = $_POST['cen_cos_hospital'];
$con_pol_contrato = $instancia_contrato->consultar_poliza_o_impuesto(122,$id_contrato);
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
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
	  	  document.con_adm_ips_poliza.guardar.disabled=false;
  }
function causar_polizas()
{
	document.con_pre_ser_poliza.action='../control/causar_polizas.php'
	document.con_pre_ser_poliza.submit();
}

function Agregar()
{
	var pos = $("#NuePolImp>tbody>tr").length-3;
	<?php
	$con_tip_nit_1=$instancia_contrato->con_tip_nit(9);
	$con_tip_concepto_1=$instancia_contrato->con_tip_concepto(122);
	?>
    campo='<tr><td><select name="con_pre_ser_nom_pol_aseguradora_2'+pos+'" id="con_pre_ser_nom_pol_aseguradora_2'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione</option>';
	<?php
	while($res_tip_nit_1=mssql_fetch_array($con_tip_nit_1))
	{
	?>
    campo+='<option value="<?php echo $res_tip_nit_1['nit_id']; ?>"><?php echo substr($res_tip_nit_1['nits_nombres'],0,30); ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td>';
	campo+='<td><select name="con_pre_ser_con_pol_nombre_2'+pos+'" id="con_pre_ser_con_pol_nombre_2'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione</option>';
	<?php
	while($res_tip_concepto_1=mssql_fetch_array($con_tip_concepto_1))
	{ ?>
    campo+='<option value="<?php echo $res_tip_concepto_1['con_id']; ?>"><?php echo $res_tip_concepto_1['con_nombre']; ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td>';
	campo+='<td><input type="text" name="con_pre_ser_pol_porcentaje_2'+pos+'" id="con_pre_ser_pol_porcentaje_2'+pos+'" onKeyPress="return permite(event,num)" required="required"/></td></tr>';
	$("#NuePolImp").append(campo);
	$("#can_filas").val(pos);
}
function Atras()
{
	var form=document.con_pre_ser_poliza;
	form.action='consultar_contrato_prestacion_servicios_1.php';
	form.submit();
}
function Siguiente()
{
	var form=document.con_pre_ser_poliza;
	form.action='consultar_contrato_prestacion_servicios_4.php';
	form.submit();
}
</script>
<?php
$con_tip_nit_1=$instancia_contrato->con_tip_nit(9);
$con_tip_concepto_1=$instancia_contrato->con_tip_concepto(122);
?>
<form name="con_pre_ser_poliza" method="post">
<center>
  <table>
    <tr>
        <td colspan="4"><b>Polizas o Impuestos del contrato</b></td>
    </tr>
    <tr>
        <td colspan="4"><hr /></td>
    </tr>
    <tr>
      <td width="173"><b>Aseguradora</b></td>
        <td width="187"><b>Poliza o Impuesto</b></td>
        <td width="201"><b>Valor</b></td>  
        <td width="201"><b>Causar diferido</b></td>   
    </tr>
<?php 
    $i = 0;
      while($dat_pol_contrato=mssql_fetch_array($con_pol_contrato))
    {
       $con_tip_nit=$instancia_contrato->con_tip_nit(9);
       $con_tip_concepto=$instancia_contrato->con_tip_concepto(122);
?>
      <tr>
      <td>
           <select name="con_pre_ser_nom_pol_aseguradora[<?php echo $i; ?>]">             
           <option value="NULL">--Seleccione--</option>
       <?php
           while($row = mssql_fetch_array($con_tip_nit))
           {
           if($dat_pol_contrato['nit_id'] == $row['nit_id'])
         {
       ?>
               <option value="<?php echo $row['nit_id']; ?>" selected><?php echo substr($row['nits_nombres'],0,30); ?></option>
           <?php
                 
         }
             else
         {
       ?>
           <option value="<?php echo $row['nit_id']; ?>"><?php echo substr($row['nits_nombres'],0,30); ?></option>
           <?php
           }
         }
       ?>                    
        </select>
        </td>
        <td>
        <select name="con_pre_ser_con_pol_nombre[<?php echo $i; ?>]">
        <option value="NULL" onClick="ver_tipo_poliza">--Seleccione--</option>
        <?php
        while($row = mssql_fetch_array($con_tip_concepto))
        {
            if($dat_pol_contrato['con_id'] == $row['con_id'])
            {
            ?>
                <option value="<?php echo $row['con_id']; ?>" selected><?php echo $row['con_nombre']; ?></option>
            <?php
            }
            else
            {
            ?>
                <option value="<?php echo $row['con_id']; ?>"><?php echo $row['con_nombre']; ?></option>
            <?php
            }
        }
  ?>
        </select>
        </td>
        <td><input type="text" name="con_pre_ser_pol_porcentaje[<?php echo $i; ?>]" value="<?php echo $dat_pol_contrato['con_por_con_porcentaje']; ?>"/></td>
        <td>
        <?php
      if($dat_pol_contrato['con_causado']==1)
          echo "<input type='checkbox' name='cau_pol".$i."' id='cau_pol".$i."' checked=checked'/>";
      else
      echo "<input type='checkbox' name='cau_pol".$i."' id='cau_pol".$i."' value='".$dat_pol_contrato['con_por_con_id']."'/>"; 
        echo "</td>";
    echo "</tr>";
      $i++; 
      }
    ?>
     <tr>
  <td colspan="4">
        <input type="hidden" name="polizas" id="polizas" value="<?php echo $i; ?>"/>
        <input type="button" class="art-button" onClick="Atras();" value="<< Atras" target="frame2"/>
        <input type="button" class="art-button" value="Siguiente >>" target="frame2" onclick="Siguiente();"/>
        <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();" disabled="disabled"/>
        <input type="submit" class="art-button" value="Guardar" name="guardar" onclick="" disabled="disabled"/>
        <input type="button" class="art-button" name="cau_pol" value="Causar polizas" onClick="causar_polizas();"/>
        </td>
   </tr>
   <tr>
    <td colspan="3"><hr /></td>
   </tr>
   </table>
   <table id="NuePolImp">
    <tr>
      <td colspan="3"><b>Agregar Poliza o Impuesto Al Contrato</b></td>
    </tr>
    <tr>
      <td colspan="3"><hr /></td>
    </tr>
    <tr>
      <td><b>Aseguradora</b></td>
        <td><b>Poliza o Impuesto</b></td>
        <td><b>Valor</b></td>
    </tr>
    <tr>
      <td>
           <select name="con_pre_ser_nom_pol_aseguradora_20" id="con_pre_ser_nom_pol_aseguradora_20" required x-moz-errormessage="Seleccione Una Opcion Valida">
           <option value="">--Seleccione--</option>
       <?php 
           while($res_tip_nit_1=mssql_fetch_array($con_tip_nit_1))
           {
           
       ?>
           <option value="<?php echo $res_tip_nit_1['nit_id']; ?>"><?php echo substr($res_tip_nit_1['nits_nombres'],0,30); ?></option>
           <?php
         }
       ?>                    
        </select>
        </td>
        <td>
        <select name="con_pre_ser_con_pol_nombre_20" id="con_pre_ser_con_pol_nombre_20" required x-moz-errormessage="Seleccione Una Opcion Valida">
        <option value="">--Seleccione--</option>
        <?php
           while($res_tip_concepto_1=mssql_fetch_array($con_tip_concepto_1))
           {
    ?>
              <option value="<?php echo $res_tip_concepto_1['con_id']; ?>"><?php echo $res_tip_concepto_1['con_nombre']; ?></option>
        <?php
         }
    ?>
        </select>
        </td>
        <td><input type="text" name="con_pre_ser_pol_porcentaje_20" id="con_pre_ser_pol_porcentaje_20" required="required"/></td>
    </tr>
    </table>
    <table>
    <tr>
      <td colspan="4"><input type="button" class="art-button" name="agregar" value="Agregar" onclick="Agregar();"/>
        <input type="hidden" name="can_filas" id="can_filas"/>
      <input type="submit" class="art-button" name="guardar" value="Guardar nueva poliza" onClick="document.con_pre_ser_poliza.action='../control/agregar_poliza_contrato_prestacion_servicios.php'"/>
        </td>
   </tr>
   </table>
</center>
</form>
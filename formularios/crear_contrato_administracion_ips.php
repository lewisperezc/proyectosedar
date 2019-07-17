<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();
$con_est_contrato = $instancia_contrato->con_est_contrato();
$con_cen_cos = $instancia_contrato->con_cen_cos_es_nit();
$con_est_con_legalizado = $instancia_contrato->con_est_con_legalizado();
?>
<script src="../librerias/js/datetimepicker.js" language="javascript" type="text/javascript"></script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
function Sumar()
{
	interval = setInterval("calcular()",1);
}
function calcular()
{
	uno = document.con_adm_ips.con_adm_ips_valor.value;
	dos = document.con_adm_ips.con_adm_ips_vigencia.value; 
	document.con_adm_ips.con_adm_ips_cuo_mensual.value = (uno * 1) / (dos * 1);
}
function NoSumar()
{
clearInterval(interval);
}

function validar_vacios(con_adm_ips) {

	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	//VALIDAMOS EL CAMPO NOMBRE
	if (document.con_adm_ips.con_adm_ips_num_consecutivo.value == "") 
	{ CamposVacios += "* Consecutivo\n"; }
	if (document.con_adm_ips.con_adm_ips_hospital.selectedIndex == 0)
	{ CamposVacios += "* Hospital\n"; }
	if (document.con_adm_ips.con_adm_ips_vigencia.value == "") 
	{ CamposVacios += "* Vigencia Contrato\n"; }
	if(document.con_adm_ips.con_adm_ips_valor.value == "")
	{ CamposVacios += "* Valor Contrato\n"; }
	if(document.con_adm_ips.con_adm_ips_cuo_mensual.value == "")
	{ CamposVacios += "* Valor Factura Mensual\n"; }
	if(document.con_adm_ips.con_adm_ips_fec_inicial.value == "")
	{ CamposVacios += "* Fecha Inicial\n"; }
	if(document.con_adm_ips.con_adm_ips_fec_fin.value == "")
	{ CamposVacios += "* Fecha Final\n"; }
	if(document.con_adm_ips.con_adm_ips_estado.selectedIndex == 0)
	{ CamposVacios += "* Estado Contrato\n"; }
	if(document.con_adm_ips.con_adm_ips_est_legalizado.selectedIndex == 0)
	{ CamposVacios += "* Legalizado?\n"; }
    //SI EN LA VARIABLE CAMPOSVACIONS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
	document.con_adm_ips.submit();
} 

</script>
<form name="con_adm_ips" method="post" action="crear_contrato_administracion_ips_2.php" target="frame4">
<center>
	<table>
   <tr>
       	<td colspan="4"><h4>Registrar Contrato Administraci&oacute;n IPS</h4></td>
   </tr>
   <tr>
   		<td height="25" colspan="1">Consecutivo</td>
    <td><input type="text" name="con_adm_ips_num_consecutivo" value="<?php echo $_SESSION['con_adm_ips_num_consecutivo']; ?>"/></td>
      	<td >Hospital</td>
        <td><select name="con_adm_ips_hospital">
    	<option value="0">--Seleccione--</option>
        <?php
	         while($row = mssql_fetch_array($con_cen_cos))
	         {
			 if($_SESSION['con_adm_ips_hospital'] == $row['cen_cos_id'])
			 {
	    ?>
               <option value="<?php echo $row['cen_cos_id']; ?>" selected="selected"><?php echo $row['cen_cos_nombre']; ?></option>
        <?php 
			 }
			 else
			 {
		?>
        		<option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_nombre']; ?></option>
        <?php
			 }
	         }
	    ?>
        </select></td>    
   <tr>
   		<td>Vigencia Contrato</td>	 
   		<td> <input name="con_adm_ips_vigencia" type="text" onFocus="NoSumar();" onBlur="Sumar();" onKeyPress="return permite(event,'num')" value="<?php echo $_SESSION['con_adm_ips_vigencia']; ?>"/> MESES</td>
        <td>Valor Contrato</td>
        <td> $<input name="con_adm_ips_valor" type="text" onFocus="Sumar();" onBlur="NoSumar();" onKeyPress="return permite(event,'num')" value="<?php echo $_SESSION['con_adm_ips_valor']; ?>"/></td>
   </tr>
   <tr>
   		<td>Valor Factura Mensual</td>
        <td><input type="text" name="con_adm_ips_cuo_mensual" readonly="readonly" value="<?php echo $_SESSION['con_adm_ips_cuo_mensual']; ?>"/></td>
   </tr>                  
   <tr>
        <td>Fecha Inicial</td>
        <td><input type="text" name="con_adm_ips_fec_inicial" id="con_adm_ips_fec_inicial" readonly="readonly" value="<?php echo $_SESSION['con_adm_ips_fec_inicial']; ?>"/>
        <a href="javascript:NewCal('con_adm_ips_fec_inicial','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
         </td>
        <td>Fecha Final</td>
       <td><input type="text" name="con_adm_ips_fec_fin" id="con_adm_ips_fec_fin" readonly="readonly" value="<?php echo $_SESSION['con_adm_ips_fec_fin']; ?>"/>
        <a href="javascript:NewCal('con_adm_ips_fec_fin','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
    </tr>
    <tr>
          <td>Estado Contrato</td>
          <td><select name="con_adm_ips_estado">
          <option value="" >--Seleccione--</option>
		  <?php
	  			while($row = mssql_fetch_array($con_est_contrato))
	  			{
				if($_SESSION['con_adm_ips_estado'] == $row['est_con_id'])
				{
	 	  ?>
          			 <option value="<?php echo $row['est_con_id']; ?>" selected="selected"><?php echo $row['est_con_nombre']; ?></option>
          <?php
				}
				else
				{
		  ?>
          			<option value="<?php echo $row['est_con_id']; ?>"><?php echo $row['est_con_nombre']; ?></option>
          <?php
				}
	  			} 
	 	  ?>                    
          </select></td>
          <td>Legalizado?</td>
          <td><select name="con_adm_ips_est_legalizado">
          <option value="" >--Seleccione--</option>
		  <?php
	  			while($row = mssql_fetch_array($con_est_con_legalizado))
	  			{
				if($_SESSION['con_adm_ips_est_legalizado'] == $row['est_con_leg_id'])
				{

	 	  ?>
          			 <option value="<?php echo $row['est_con_leg_id']; ?>" selected="selected"><?php echo $row['est_con_leg_nombre']; ?></option>
          <?php
				}
				else
				{
		  ?>
          			<option value="<?php echo $row['est_con_leg_id']; ?>"><?php echo $row['est_con_leg_nombre']; ?></option>
          <?php
				}
	  			} 
	 	  ?>                    
          </select></td>
    </tr>
    <tr>
         <td><input name="enviar" id="enviar" type="button" class="art-button" onclick="validar_vacios()" value="Sigiente >>"/></td>
    </tr>
 </table>
</center>
</form>
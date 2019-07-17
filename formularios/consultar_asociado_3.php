<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<?php
$id_asociado = $_SESSION['aso_id'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();

$datos = $instancia_nits->con_dat_per_asociado($id_asociado);
$dat_asociacion = mssql_fetch_array($datos);


$con_banco = $instancia_nits->cons_bancos();
$con_tip_cuenta = $instancia_nits->con_tip_cuenta();

$con_tip_nit = $instancia_nits->con_tip_nit(7);
$consulta_eps = $instancia_nits->con_eps_asociado($id_asociado);
$con_eps = mssql_fetch_array($consulta_eps);

$con_tip_nit_2 = $instancia_nits->con_tip_nit(5);
$consulta_arp = $instancia_nits->con_arp_asociado($id_asociado);
$con_arp = mssql_fetch_array($consulta_arp);

$con_tip_seg_social = $instancia_nits->con_tip_seg_social();


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
  	document.aso_datos_asociacion.guardar_datos.disabled=false;
}
function Atras()
{
	var form=document.aso_datos_asociacion;
	form.action='consultar_asociado_2.php';
	form.submit();
}
function Siguiente()
{
	var form=document.aso_datos_asociacion;
	form.action='consultar_asociado_4.php';
	form.submit();
}


function Enviar_Guardar()
{
	if($("#tipo_descuento_seg_social").is(':checked')==true)
	{
		if($("#aso_mon_fij_seg_social").val()=="" || $("#aso_mes_ini_cot_mon_fijo").val()=="" || $("#aso_ano_ini_cot_mon_fijo").val()=="")
		{
			alert("Debe ingresar el valor del monto fijo, el mes y el a\u00f1o de inicio.");
			$("#aso_datos_asociacion").submit(function(){return false;});
			
		}
		else
		{
			//alert("Debe guardar!");
			document.aso_datos_asociacion.submit();
			
		}
	}
	else
	{
		//alert("Debe guardar en el else!");
		document.aso_datos_asociacion.submit();
	}	
}

function TipoSegSocial(estado)
{
	//alert(estado);
	if(estado==1)
	{
		if($("#tipo_descuento_seg_social").is(':checked')==true)
		{
			//alert('Esta checkeado!');
			$("#monto_fijo_tiene").css("display","block");
			$("#monto_fijo_no_tiene").css("display","none");
		}
		else
		{
			if($("#tipo_descuento_seg_social").is(':checked')==false)
			{
				//alert('No esta checkeado!');
				$("#monto_fijo_tiene").css("display","none");
				$("#monto_fijo_no_tiene").css("display","none");	
			}
		}
	}
	
	if(estado==2)
	{
		if($("#tipo_descuento_seg_social").is(':checked')==true)
		{
			//alert('Esta checkeado!');
			$("#monto_fijo_tiene").css("display","none");
			$("#monto_fijo_no_tiene").css("display","block");
		}
		else
		{
			if($("#tipo_descuento_seg_social").is(':checked')==false)
			{
				//alert('No esta checkeado!');
				$("#monto_fijo_tiene").css("display","none");
				$("#monto_fijo_no_tiene").css("display","none");	
			}
		}
	}
	
	
}

</script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<form name="aso_datos_asociacion" id="aso_datos_asociacion" action="../control/actualizar_asociado_2.php" method="post" >
<center>
  <table>
  <tr>
      <td colspan="6" ><h4>Datos Asociaci&oacute;n</h4></td>
  </tr>
  <tr>
    <td colspan="6" ><hr /></td>
  </tr>
  <tr>    
      <td>Banco</td>
        <td><select name="aso_banco" id="aso_banco" disabled="disabled">
        <option value="NULL">--Seleccione--</option>
        <?php
          while($row = mssql_fetch_array($con_banco))
      {
            if($dat_asociacion['cod_banco'] == $row['cod_banco'])
        {
    ?>
              <option value="<?php echo $row['cod_banco']; ?>" selected="selected">
          <?php echo substr($row['banco'],0,30); ?>
                    </option>
        <?php
        }
        else
        {
    ?>
              <option value="<?php echo $row['cod_banco']; ?>"><?php echo substr($row['banco'],0,30); ?></option>
        <?php
        }
      }
    ?>
        </select></td>
    <td>Tipo De Cuenta</td>
    <td><select name="aso_tip_cuenta" disabled="disabled">
          <option value="NULL">--Seleccicone--</option>
          <?php
              while($row = mssql_fetch_array($con_tip_cuenta))
        {
          if($dat_asociacion['tip_cue_ban_id'] == $row['tip_cue_ban_id'])
          {
      ?>
                    <option value="<?php echo $row['tip_cue_ban_id']; ?>" selected="selected">
              <?php echo $row['tip_cue_ban_nombre']; ?>
                      </option>
            <?php
          }
          else
          {
      ?>
                    <option value="<?php echo $row['tip_cue_ban_id']; ?>">
            <?php echo $row['tip_cue_ban_nombre']; ?>
                      </option>
                  
            <?php
          }
              }
      ?>
            </select> </td>
  </tr>
  <tr>
        <td>N&uacute;mero Cuenta</td>
    <td><input name="aso_num_cuenta" type="text" onkeypress="return permite(event,'num')" value="<?php echo $dat_asociacion['nits_num_cue_bancaria']; ?>" disabled="disabled"/></td>
    <td>EPS</td>
    <td><select name="aso_eps" disabled="disabled" required="required">
           <option value="">--Seleccione--</option>
             <?php
              while($row = mssql_fetch_array($con_tip_nit))
        	{
          if($con_eps['nit_id'] == $row['nit_id'])
          {
      ?>
                <option value="<?php echo $row['nit_id']; ?>" selected="selected">
          		<?php echo substr($row['nits_nombres'],0,30); ?>
                </option>
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
  </tr>
  <tr>
    <td>ARL</td>
    <td><select name="aso_arp" disabled="disabled" required="required">
           <option value="">--Seleccione--</option>
             <?php
              while($row = mssql_fetch_array($con_tip_nit_2))
        {
          if($con_arp['nit_id'] == $row['nit_id'])
          {
      ?>
                <option value="<?php echo $row['nit_id']; ?>" selected="selected">
          <?php echo $row['nits_nombres']; ?>
                    </option>
            <?php
          }
          else
          {
      ?>
                  <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']; ?></option>
            <?php
          }
        }
       ?>
             </select>
        </td>
        <td>Tipo Seguridad Social</td>
      	<td><select name="aso_tip_seg_social" disabled="disabled">
        <option value="NULL">--Seleccione--</option>
      <?php
      while($res_tip_seg_soc = mssql_fetch_array($con_tip_seg_social))
      {
	      if($dat_asociacion['tip_segSoc_id'] == $res_tip_seg_soc['tip_segSoc_id'])
	      {
	      ?>
	      	<option value="<?php echo $res_tip_seg_soc['tip_segSoc_id']; ?>" selected="selected"><?php echo $res_tip_seg_soc['tip_segSoc_nombre']." - ".$res_tip_seg_soc['tip_segSoc_porcentaje']."%"; ?></option>
	      <?php
		  }
		  else
		  {?>
	      	<option value="<?php echo $res_tip_seg_soc['tip_segSoc_id']; ?>"><?php echo $res_tip_seg_soc['tip_segSoc_nombre']." - ".$res_tip_seg_soc['tip_segSoc_porcentaje']."%"; ?></option>
	      <?php
		  }
	  }
	  ?>
    </select></td>
  </tr>
  <?php
  if($dat_asociacion['nit_mon_fij_seg_social']==1)//TIENE MONTO FIJO
  {
  ?>
	<tr>
		<td>Monto fijo</td><td><input type="checkbox" disabled="disabled" checked="checked" disabled name="tipo_descuento_seg_social" id="tipo_descuento_seg_social" onchange="TipoSegSocial(1);" /></td>
	</tr>
	<tr><td colspan="4">
	<table id="monto_fijo_tiene">
		<tr>
	    	<td>Valor $</td><td><input placeholder="Valor monfo fijo" disabled="disabled" name="aso_mon_fij_seg_social" disabled id="aso_mon_fij_seg_social" value="<?php echo $dat_asociacion['nit_val_seg_social'] ?>" type="text" pattern="[0-9]+"/></td>
	    </tr>
	    
	    <tr>
	    	<td colspan="6"><b>Indique el mes y el a&ntilde;o en el que el afiliado inicia a cotizar con el monto fijo.</b></td>
	    </tr>
	    <tr>
	    	<td>Mes<input type="text" name="aso_mes_ini_cot_mon_fijo" id="aso_mes_ini_cot_mon_fijo" placeholder="Mes" disabled="disabled" value="<?php echo $dat_asociacion['nit_mes_ini_mon_fijo'] ?>" pattern="[0-9]+"/></td>
	    </tr>
	    <tr>
	    	<td>A&ntilde;o<input type="text" name="aso_ano_ini_cot_mon_fijo" id="aso_ano_ini_cot_mon_fijo" placeholder="A&ntilde;o" disabled="disabled" value="<?php echo $dat_asociacion['nit_ano_ini_mon_fijo'] ?>" pattern="[0-9]+"/></td>
	    </tr>
    </table>
    </td></tr>
  <?php
  }
  else
  {

  ?>
  	
  	<tr>
		<td>Monto fijo</td><td><input type="checkbox" disabled="disabled" name="tipo_descuento_seg_social" id="tipo_descuento_seg_social" onchange="TipoSegSocial(2);" /></td>
	</tr>
	
	<tr><td colspan="4">
	<table id="monto_fijo_no_tiene" style="display:none;">
		<tr>
	    	<td>Valor $</td><td><input placeholder="Valor monfo fijo" name="aso_mon_fij_seg_social" id="aso_mon_fij_seg_social" type="text" pattern="[0-9]+"/></td>
	    </tr>
	    
	    <tr>
	    	<td colspan="6"><b>Indique el mes y el a&ntilde;o en el que el afiliado inicia a cotizar con el monto fijo.</b></td>
	    </tr>
	    <tr>
	    	<td>Mes<input type="text" name="aso_mes_ini_cot_mon_fijo" id="aso_mes_ini_cot_mon_fijo" placeholder="Mes" disabled="disabled" pattern="[0-9]+"/></td>
	    </tr>
	    <tr>
	    	<td>A&ntilde;o<input type="text" name="aso_ano_ini_cot_mon_fijo" id="aso_ano_ini_cot_mon_fijo" placeholder="A&ntilde;o" disabled="disabled" pattern="[0-9]+"/></td>
	    </tr>
    </table>
    
    </td></tr>
    
  <?php      
  }
  ?>
  <tr>
        <td colspan="4"><input type="button" class="art-button" name="atras" id="atras" onClick="Atras();" value="<< Atras" target="frame2"/>
        <input type="button" class="art-button" name="siguiente"  id="siguiente" onclick="Siguiente();" value="Siguiente >>" target="frame2"/>
        <?PHP
       if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       {
       ?>
       <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
       <input type="submit" class="art-button" value="Guardar" name="guardar" id="guardar" disabled="disabled"/>
       <?PHP
       }
       ?>
        </td>
  </tr>
</table>
</center>
</form>

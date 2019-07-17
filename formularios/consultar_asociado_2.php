<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
	
	
	include_once('../clases/nits.class.php');
    $instancia_nits = new nits();
	//INICIO CAPTURO EL ID DEL ASOCIADO QUE ME SELECCIONAN EN LA LISTA PARA TRAER LOS DATOS CORRESPONDIENTES A ESTE
	if($_GET['aso_id'])
	{
		$_SESSION['aso_id'] = $_GET['aso_id'];
	}
	$id_asociado = $_SESSION['aso_id'];
	//FIN CAPTURO EL ID DEL ASOCIADO QUE ME SELECCIONAN EN LA LISTA PARA TRAER LOS DATOS CORRESPONDIENTES A ESTE
	$datos = $instancia_nits->con_dat_per_asociado($id_asociado);
	$dat_personales = mssql_fetch_array($datos);
	
	//INICIO CONSULTO LAS CIUDADES Y DPTOS DEL ASOCIADO
	//Inicio Nacimiento
	$data_1 = $instancia_nits->con_ciu_dep_asociado(1,$id_asociado);
	$dat_dep_1 = mssql_fetch_array($data_1);
	$ciu_1 = $instancia_nits->con_ciu_dep_asociado(1,$id_asociado);
	//Fin Nacimiento
	//Inicio Residencia
	$data_2 = $instancia_nits->con_ciu_dep_asociado(2,$id_asociado);
	$dat_dep_2 = mssql_fetch_array($data_2);
	$ciu_2 = $instancia_nits->con_ciu_dep_asociado(2,$id_asociado);
	//Fin Residencia
	//$dat_ciu = mssql_fetch_array($data_3);
	//FIN CONSULTO LAS CIUDADES Y DPTOS DEL ASOCIADO
	
	$tip_identificacion = $instancia_nits->con_tip_identificacion();
	$est_civil = $instancia_nits->con_est_civil();
	
	$ciudades=$instancia_nits->consultar_ciudades();
	
?>
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
	document.datos_aso_1.guardar.disabled=false;
}

function Siguiente()
{
	var form=document.datos_aso_1;
	form.action='consultar_asociado_3.php';
	form.submit();
}
function Atras()
{
	var form=document.datos_aso_1;
	form.action='consultar_asociado_1.php';
	form.submit();
}
</script>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function(){
   $("#aso_fon_vacaciones").click(function(evento){
      if ($("#aso_fon_vacaciones").attr("checked")){
         $("#result").css("display","block");
      }else{
		  $("#result").css("display","none");
	  }
   });
});

$(document).ready(function()
{
	$("#guardar").click(function(evento)
	{
		var valor_pabs=document.datos_aso_1.aso_por_pabs.value;
	    var cam_anterior_1=document.datos_aso_1.select3.value;
		var cam_anterior_2=document.datos_aso_1.select4.value;
		if(cam_anterior_1!=""&&cam_anterior_2!="")
		{
        	if(valor_pabs<1||valor_pabs>40)
	    	{
				alert('El Porcentaje De PABS Debe Ser Entre 1% Y 40%');
				document.datos_aso_1.aso_por_pabs.focus();
				return false;
	 		}
		}
   });
});

function MuestraCampo(elvalor)
{
	if(elvalor==1||elvalor=="")
	{
    	$("#elporcen1").css("display","none");
		$("#elporcen2").css("display","none");
    }
	else
	{
		  $("#elporcen1").css("display","none");
		  $("#elporcen2").css("display","block");
	}
}

function popUp(URL){
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=900,left=240,top=112');");
}
</script>
<?php
//INICIO FUNCION PARA GENERAR LAS CIUDADES SEGUN EL DPTO QUE SELECCIONEN
function genera_departamentos_1($dep_1)
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)' disabled='disabled' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value=''>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		if($row['dep_id'] == $dep_1)
		{
			echo "<option value='".$row['dep_id']."' selected='selected'>".$row['dep_nombre']."</option>";
		}
		else
		{
			echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
		}
	echo "</select>";
}

function genera_departamentos_2($dep_2)
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select3' id='select3' onChange='cargaContenido_2(this.id)' disabled='disabled' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value=''>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		if($row['dep_id'] == $dep_2)
		{
			echo "<option value='".$row['dep_id']."' selected='selected'>".$row['dep_nombre']."</option>";
		}
		else
		{
			echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
		}
	echo "</select>";
}
//FIN FUNCION PARA GENERAR LAS CIUDADES SEGUN EL DPTO QUE SELECCIONEN
?>
<form name="datos_aso_1" method="post" action="../control/actualizar_asociado_1.php" target="frame2">
<center>
	<?php
		//INICIO PARTO EL CAMPO QUE TRAE LOS 2 APELLIDOS PARA PONER CADA UNO EN EL CAMPO CORRESPONDIENTE DEL FORMULAIRO
		$pieza = explode(" ",$dat_personales['nits_apellidos']);
		//FIN PARTO EL CAMPO QUE TRAE LOS 2 APELLIDOS PARA PONER CADA UNO EN EL CAMPO CORRESPONDIENTE DEL
?>
<table>
  <tr>
    <td colspan="6" ><h4>Datos Personales</h4></td>
  </tr>
  <tr>
    <td colspan="6" ><hr /></td>
  </tr>
  <tr>
	<td>Primer Apellido</td> 
	<td><input  name="aso_pri_apellido" type="text" onkeypress="return permite(event,'car')" value="<?php echo $pieza[0]; ?>" disabled="disabled" required="required"/></td>
       
		<td>Segundo Apellido</td>
		<td><input   name="aso_seg_apellido" type="text" onkeypress="return permite(event,'car')" value="<?php echo $pieza[1]." ".$pieza[2]; ?>" disabled="disabled" required="required"/></td>
  </tr>
  <tr>
		<td>Nombres</td>
		<td><input  name="aso_nombres" type="text" onkeypress="return permite(event,'car')" value="<?php echo $dat_personales['nits_nombres']; ?>" disabled="disabled" required="required"/></td>
		<td>Tipo documento</td>
		<td><select name="aso_tip_documento" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--Seleccione--</option>
             <?php
             	while($row = mssql_fetch_array($tip_identificacion))
				{
				  if($dat_personales['tip_ide_id'] == $row['tip_ide_id'])
				  {
              ?><option value="<?php echo $row['tip_ide_id']; ?>" selected='selected'><?php echo $row['tip_ide_nombre']; ?></option>
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
  </tr>
  <tr>
		<td>Numero Documento</td>
		<td><input type="text" name="aso_num_documento" readonly="readonly" onkeypress="return permite(event,'num')" value="<?php echo $dat_personales['nits_num_documento']; ?>" disabled="disabled" required="required"/></td>
		<td>Fecha De Nacimineto</td>
		<td><input type="text" name="aso_nac_fecha" id="aso_nac_fecha" value="<?php echo $dat_personales['nits_fec_nacimiento']; ?>" disabled="disabled" required="required"/>
        <a href="javascript:NewCal('aso_nac_fecha','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
         </td>
  </tr>
  <tr>
		<td>Genero</td>
		<td><input  name="aso_genero" value="1" type="radio" <?php if($dat_personales['nit_gen_id'] == 1){ ?> checked="checked" <?php } ?> disabled="disabled"/>Hombre<input name="aso_genero" value="2" type="radio" <?php if($dat_personales['nit_gen_id'] == 2){ ?> checked="checked" <?php } ?> disabled="disabled"/>Mujer</td>
		<td>Estado Civil</td>
		<td><select name="aso_est_civil" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
        	<option value="">--Seleccione--</option>
        	<?php
            	while($row = mssql_fetch_array($est_civil))
				{
					if($dat_personales['est_civ_id'] == $row['est_civ_id'])
					{
			?>
            			<option value="<?php echo $row['est_civ_id']; ?>" selected="selected">
							<?php echo $row['est_civ_nombre'];?>
                        </option>
            <?php
					}
					else
					{
			?>
            			<option value="<?php echo $row['est_civ_id']; ?>"><?php echo $row['est_civ_nombre']; ?></option>
            <?php
					}
				}
			?>
           	</select>
        </td>
  </tr>
 <tr>
  	   <td>Departamento Nacimiento</td>
       <td><?php genera_departamentos_1($dat_dep_1['dep_id']); ?></td>
	   <td>Ciudad Nacimiento</td>
	   <td><select name="select2" id="select2" disabled="disabled">
           <?php
		   		while($row = mssql_fetch_array($ciu_1))
				{
					if($ciudades['ciu_id'] == $row['ciu_id'])
					{
			?>
            			<option value="<?php echo $row['ciu_id']; ?>" selected="selected"/><?php echo $row['ciu_nombre']; ?></option>
            <?php
					}
					else
					{
			?>
         				<option value="<?php echo $row['ciu_id']; ?>"/><?php echo $row['ciu_nombre']; ?></option>   			
            <?php
					}
				}
		    ?>
		   </select>
       </td>
  </tr>
  <tr>
  	   <td>Direccion Residencia</td>
	   <td><input type="text"  name="aso_dir_residencia" value="<?php echo $dat_personales['nits_dir_residencia']; ?>" disabled="disabled" required="required"/></td>
       <td>Telefono Residencia</td>
	   <td><input name="aso_tel_residencia"type="text"  onkeypress="return permite(event,'num')"  value="<?php echo $dat_personales['nits_tel_residencia']; ?>" disabled="disabled"/></td>
  </tr>
  <tr>
  	   <td>Numero Celular</td>
	   <td><input name="aso_num_celular" type="text" onkeypress="return permite(event,'num')" value="<?php echo $dat_personales['nits_num_celular']; ?>" disabled="disabled"/></td>
  	   <td>Correo Electronico</td>
	   <td><input name="aso_cor_electronico" type="text" value="<?php echo $dat_personales['nits_cor_electronico']; ?>" disabled="disabled"/></td>				
  </tr>
  <tr>
  		<td>Correo Electronico Adicional</td>
  		<td><input name="aso_cor_electronico_adicional" type="text" value="<?php echo $dat_personales['nit_cor_electronico_adicional']; ?>" disabled="disabled"/></td>				
  </tr>
  <tr>
      <td>Departamento Residencia</td>
       <td><?php genera_departamentos_2($dat_dep_2['dep_id']); ?></td>
	   <td>Ciudad Residencia</td>
	   <td><select name="select4" id="select4" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
		   <?php
		   		while($row = mssql_fetch_array($ciu_2))
				{
					if($ciudades['ciu_id'] == $row['ciu_id'])
					{
		  ?>
            			<option value="<?php echo $row['ciu_id']; ?>" selected="selected"/><?php echo $row['ciu_nombre']; ?></option>
          <?php
					}
					else
					{
		  ?>
         				<option value="<?php echo $row['ciu_id']; ?>"/><?php echo $row['ciu_nombre']; ?></option>   			
          <?php
					}
				}
		  ?>  
		   </select>
       </td>
  </tr>
  <tr>
	  <td>Porcentaje PABS </td>
	  <td><input name="aso_por_pabs" id="aso_por_pabs" type="number" onKeyPress="return permite(event,'num')" value="<?php echo $dat_personales['nits_por_pabs']; ?>" disabled="disabled" required="required" x-moz-errormessage="Debe Ingresar Un Valor Entre 1% Y 40%"/>%</td>
      <!--<td>Rte Fuente</td>
      <td>
      <input type="text" name="aso_por_ret_fuente2" onkeypress="return permite(event,'num')" value="<?php echo $dat_personales['nit_por_ret_fuente']; ?>" disabled="disabled"/>%</td>-->
  </tr>
  <tr>
     <td>Fondo De Vacaciones</td><td><?php if($dat_personales['nits_fon_vacaciones'] == 'SI'){ ?><input type="checkbox" name="aso_fon_vacaciones" id="aso_fon_vacaciones" checked="checked" readonly disabled required/><?php
	 ?>
	 <td>
    <div id="result" style="display: block;">
    Porcentaje<input type="text" value="4" name="aso_por_fon_pensiones" id="aso_por_fon_pensiones" size="3" maxlength="3" value="<?php echo $dat_personales['nit_por_fon_vacaciones']; ?>" readonly disabled required/>
    </div>
     </td>
     <?php
     }
	 ?>
     <?php if($dat_personales['nits_fon_vacaciones'] == 'NO'){ ?><input type="checkbox" name="aso_fon_vacaciones" id="aso_fon_vacaciones" readonly disabled/>
	 <td>
     <div id="result" style="display: none;">
     Porcentaje <input type="text" value="4" name="aso_por_fon_pensiones" id="aso_por_fon_pensiones" size="3" maxlength="3" readonly disabled required/>
    </div>
     </td>
     <?php
     }
	 ?></td>
  </tr>
  <tr>
  <td>Procedimiento</td>
  <td>
  <select required x-moz-errormessage="Seleccione Una Opcion Valida" name="aso_tip_procedimiento" id="aso_tip_procedimiento" onchange="MuestraCampo(this.value);" disabled="disabled">
  <?php
  if($dat_personales['nit_tip_procedimiento']=="")
  {
  ?>
  	<option value="" selected="selected">Seleccione</option>
    <option value="1">1</option>
    <option value="2">2</option>
  <?php
  }
  elseif($dat_personales['nit_tip_procedimiento']==1)
  {
  ?>
  	<option value="">Seleccione</option>
    <option value="1" selected="selected">1</option>
    <option value="2">2</option>
  <?php
  }
  elseif($dat_personales['nit_tip_procedimiento']==2)
  {
  ?>
  <option value="">Seleccione</option>
  	<option value="1">1</option>
    <option value="2" selected="selected">2</option>
  <?php
  }
  ?>
  </select></td>
  <?php
  if($dat_personales['nit_tip_procedimiento']==2)
  {
  ?>
  <td id="elporcen1" style="display:block;">
  	<input type="hidden" name="elprocedimiento" id="elprocedimiento" value="1"/>
    Rte Fuente<input name="aso_por_ret_fuente" id="aso_por_ret_fuente" onKeyPress="return permite(event,'num')" value="<?php echo $dat_personales['nit_por_ret_fuente']; ?>" type="number" size="2" maxlength="3" disabled="disabled"/>%
  </td>
  <?php
  }
  ?>
  <td id="elporcen2" style="display:none;">
  Rte Fuente<input name="aso_por_ret_fuente2" id="aso_por_ret_fuente2" onKeyPress="return permite(event,'num')" value="<?php echo $dat_personales['nit_por_ret_fuente']; ?>" type="number" size="2" maxlength="3" disabled="disabled"/>%
  </td>
  </tr>
  <tr>
   <td>Porcentaje fondo de retiro sindical</td>
   <td><input disabled value="<?php echo $dat_personales['nit_por_fon_ret_sindical']; ?>" required name="aso_por_fon_retiro_sindical" id="aso_por_fon_retiro_sindical" type="text" size="2" maxlength="1" pattern="[0,4,8]"/>%</td>
  </tr>
  <tr>
   <td>Fecha de afiliacion</td>
   <td>
   <input type="text" name="aso_fec_afiliacion" id="aso_fec_afiliacion" value="<?php echo $dat_personales['nit_fec_afiliacion']; ?>" disabled="disabled"/>
        <a href="javascript:NewCal('aso_fec_afiliacion','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
   </td>
   <td>Fecha de retiro</td>
   <td>
   <input type="text" name="aso_fec_retiro" id="aso_fec_retiro" value="<?php echo $dat_personales['nit_fec_retiro']; ?>" disabled="disabled"/>
        <a href="javascript:NewCal('aso_fec_retiro','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
   </td>
  </tr>
  <tr>
	   <!--<td colspan="4"><a href="crear_asociado_2.php" target="frame2">Siguiente >></a></td>-->
       <td colspan="4">
       <input type="button" class="art-button" onclick="Atras();" name="atras" value="<< Atras"/>
       <input type="button" class="art-button" onclick="Siguiente();" name="siguiente" value="Siguiente >>"/>
       <?PHP
       if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       {
       ?>
       <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
       <input type="submit" class="art-button" value="Guardar" name="guardar" id="guardar" disabled="disabled"/>
       <?PHP
       }
       ?>
       
       <input type="button" class="art-button" value="Registrar Novedad" name="reg_novedad" onClick="javascript:popUp('registrar_novedad.php?tipo=1')"/>
       </td>
  </tr>
  </table>
</center>
</form>
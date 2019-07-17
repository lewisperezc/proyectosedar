<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$con_tip_identificacion = $instancia_nits->con_tip_identificacion();
$con_est_civil = $instancia_nits->con_est_civil();
$con_ciudad = $instancia_nits->consultar_ciudades();
?>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<?php
//INICIO FUNCION PARA GENERAR LAS CIUDADES SEGUN EL DPTO QUE SELECCIONEN
function genera_departamentos_1()
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)'>";
	echo "<option value='0'>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
	echo "</select>";
}

function genera_departamentos_2()
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select3' id='select3' onChange='cargaContenido_2(this.id)'>";
	echo "<option value='0'>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
	echo "</select>";
}
//INICIO FUNCION PARA GENERAR LAS CIUDADES SEGUN EL DPTO QUE SELECCIONEN
?>
<script src="librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_email.js"></script>
<script>
function validar_vacios(emp_datos_personales)
  {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	if (document.emp_datos_personales.emp_pri_apellido.value == "") 
	{ CamposVacios += "* Primer Apellido\n"; }
	if (document.emp_datos_personales.emp_seg_apellido.value == "") 
	{ CamposVacios += "* Segundo Apellido\n"; }
	if (document.emp_datos_personales.emp_nombres.value == "") 
	{ CamposVacios += "* Nombres\n"; }
	if (document.emp_datos_personales.emp_tip_documento.selectedIndex == 0) 
	{ CamposVacios += "* Tipo Documento\n"; }
	if (document.emp_datos_personales.emp_num_documento.value == "") 
	{ CamposVacios += "* Numero Documento\n"; }
	if (document.emp_datos_personales.emp_fec_nacimiento.value == "") 
	{ CamposVacios += "* Fecha De Nacimiento\n"; }
	if(document.emp_datos_personales.emp_est_civil.selectedIndex == 0) 
	{ CamposVacios += "* Estado Civil\n"; }
	if(document.emp_datos_personales.select2.selectedIndex == 0) 
	{ CamposVacios += "* Ciudad Nacimiento\n"; }
	if (document.emp_datos_personales.emp_dir_residencia.value == "") 
	{ CamposVacios += "* Direccion Residencia\n"; }
	if(document.emp_datos_personales.select4.selectedIndex == 0) 
	{ CamposVacios += "* Ciudad Residencia\n"; }
    //SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
		document.emp_datos_personales.submit();
}
</script>

<form name="emp_datos_personales" id="emp_datos_personales" action="crear_empleado_2.php" method="post">
<center>
	<table>
  <tr>
    <td colspan="4" ><h4>Creaci&oacute;n Empleado</h4></td>
  </tr>
  <tr>
    <td colspan="6" ><h4>Datos Personales</h4></td>
  </tr>
  <tr>
	<td>Primer Apellido</td> 
	<td><input  name="emp_pri_apellido" type="text" onkeypress="return permite(event,'car')" value="<?php echo $_SESSION['emp_pri_apellido']; ?>"/></td>
		<td>Segundo Apellido</td>
		<td><input   name="emp_seg_apellido" type="text" onkeypress="return permite(event,'car')" value="<?php echo $_SESSION['emp_seg_apellido']; ?>"/></td>
  </tr>
  <tr>
		<td>Nombres</td>
		<td><input  name="emp_nombres" type="text" onkeypress="return permite(event,'car')" value="<?php echo $_SESSION['emp_nombres']; ?>"/></td>
		<td>Tipo documento</td>
		<td><select name="emp_tip_documento">
        	 <option value="0">--Seleccione--</option>
             <?php
             	while($row = mssql_fetch_array($con_tip_identificacion))
				{
					if($_SESSION['emp_tip_documento'] == $row['tip_ide_id']){
			?>
            		<option value="<?php echo $row['tip_ide_id']; ?>" selected="selected"><?php echo $row['tip_ide_nombre']; ?></option>
            <?php
					}
					else{
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
		<td><input type="text" name="emp_num_documento" onkeypress="return permite(event,'num')" value="<?php echo $_SESSION['emp_num_documento']; ?>"/></td>
		<td>Fecha De Nacimineto</td>
		<td><input type="text" name="emp_fec_nacimiento" id="emp_fec_nacimiento" value="<?php echo $_SESSION['emp_fec_nacimiento']; ?>"/>
        	<a href="javascript:NewCal('emp_fec_nacimiento','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
        </td>
  </tr>
  <tr>
		<td>Genero</td>
		<td><input  name="emp_genero" value="1" type="radio" checked="checked"/>Hombre<input name="emp_genero" value="2" type="radio"/>Mujer</td>
		<td>Estado Civil</td>
		<td><select name="emp_est_civil">
        		   <option value="0">--Seleccione--</option>
             <?php
             	while($row = mssql_fetch_array($con_est_civil))
				{
					if($_SESSION['emp_est_civil'] == $row['est_civ_id']){
			 ?>
             		<option value="<?php echo $row['est_civ_id']; ?>" selected="selected"><?php echo $row['est_civ_nombre']; ?></option>
             <?php
					}
					else{
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
       <td><?php genera_departamentos_1(); ?></td>
	   <td>Ciudad Nacimiento</td>
	   <td><select name="select2" id="select2" disabled="disabled">
				     <option  value="0">--Seleccione--</option>
		   </select>
       </td>
  </tr>
  <tr>
  	   <td>Direccion Residencia</td>
	   <td><input type="text"  name="emp_dir_residencia" value="<?php echo $_SESSION['emp_dir_residencia']; ?>"/></td>
       <td>Telefono Residencia</td>
	   <td><input name="emp_tel_residencia"type="text"  onkeypress="return permite(event,'num')"  value="<?php echo $_SESSION['emp_tel_residencia']; ?>"/></td>
  </tr>
  <tr>
  	   <td>Numero Celular</td>
	   <td><input name="emp_num_celular" type="text" onkeypress="return permite(event,'num')" value="<?php echo $_SESSION['emp_num_celular']; ?>"/></td>
  	   <td>Correo Electronico</td>
	   <td><input name="emp_cor_electronico" type="text" value="<?php echo $_SESSION['emp_cor_electronico']; ?>"/></td>				
  </tr>
  <tr>
    <td>Correo Electronico adicional</td>
    <td><input name="emp_cor_electronico_adicional" type="text" value="<?php echo $_SESSION['emp_cor_electronico_adicional']; ?>"/></td>
  </tr>
  <tr>
    <td>Departamento Residencia</td>
    <td><?php genera_departamentos_2(); ?></td>
    <td>Ciudad Residencia</td>
    <td><select name="select4" id="select4" disabled="disabled">
      <option  value="0">--Seleccione--</option>
    </select></td>
    </tr>
  <tr>
    <td colspan="4"><input type="button" class="art-button" value="Siguiente &gt;&gt;" target="frame2" onclick="validar_vacios();"/></td>
  </tr>
</table>
</center>
</form>
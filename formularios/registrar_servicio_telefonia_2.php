<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
function validar_vacios(form_reg_ser_telefonia_2)
  {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	if (document.form_reg_ser_telefonia_2.nit.value == 'NULL') 
	{ CamposVacios += "* Persona\n"; }
	if (document.form_reg_ser_telefonia_2.reg_tel_num_linea.value == 0) 
	{ CamposVacios += "* N° Linea\n"; }
    //SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
		document.form_reg_ser_telefonia_2.action='../control/guardar_registro_telefonia.php';
		document.form_reg_ser_telefonia_2.submit();
}
</script>
</head>
<body>
<?php
include_once('../clases/nits.class.php');
$ins_nits = new nits();
include_once('../clases/plan_telefonia.class.php');
include_once('../clases/telefonia.class.php');
$ins_telefonia = new telefonia();
$ins_plan_telefonia = new plan_telefonia();
$cons_tod_pla_telefonia = $ins_plan_telefonia->cons_tod_pla_telefonia();
$con_tod_est_reg_telefonia = $ins_telefonia->con_tod_est_reg_telefonia();

if(!$agregar){
$_SESSION['tipo_nit'] = $_POST['tipo_nit'];
}
$tipo_nit = $_SESSION['tipo_nit'];
$cons_nits = $ins_nits->con_aso_por_id_estado($tipo_nit,1);
?>
<form name="form_reg_ser_telefonia_2" method="post" action="registrar_servicio_telefonia_2.php" target="frame2">
<?php
$_SESSION['nit'] = $_POST['nit'];
$_SESSION['reg_tel_num_linea'] = $_POST['reg_tel_num_linea'];
$_SESSION['plan'] = $_POST['plan'];
$_SESSION['tipo'] = $_POST['tipo'];
$_SESSION['estado'] = $_POST['estado'];
?>
<center>
<table border="1">
	<tr>
    	<th>Persona</th>
        <th>Tipo Descuento</th>
        <th>N° Linea</th>
    </tr>
	<tr>
    	<td>
        <select name="nit">
                    <option value="NULL">Seleccione</option>
             <?php while($nits = mssql_fetch_array($cons_nits)){ 
			 if($_SESSION['nit'] == $nits['nit_id']){
			 ?>
             		<option value="<?php echo $nits['nit_id']; ?>" selected="selected"><?php echo $nits['nits_nombres']." ".$nits['nits_apellidos']; ?></option>
             <?php
             }
			 else{
			 ?>
             <option value="<?php echo $nits['nit_id']; ?>"><?php echo $nits['nits_nombres']." ".$nits['nits_apellidos']; ?></option>
             <?php
			 }
			 }?>
        </select>
        </td>
        <td>
        	<select name="tipo" id="tipo">
            	<?php if($_SESSION['tipo'] == "" || $_SESSION['tipo'] == "NULL")
						{
                		  echo "<option value='NULL' selected='selected'>--Seleccione--</option>";
						  echo "<option value='1'>FABS</option>";
						  echo "<option value='2'>Pagare</option>";
						  echo "<option value='3'>Gasto</option>";
						}
                 elseif($_SESSION['tipo'] == 1)
				 	  {
                		echo "<option value='NULL'>--Seleccione--</option>";
						  echo "<option value='1' selected='selected'>FABS</option>";
						  echo "<option value='2'>Pagare</option>";
						  echo "<option value='3'>Gasto</option>";
					  }
                 elseif($_SESSION['tipo'] == 2) 
                	   {
                		  echo "<option value='NULL'>--Seleccione--</option>";
						  echo "<option value='1'>FABS</option>";
						  echo "<option value='2' selected='selected'>Pagare</option>";
						  echo "<option value='3'>Gasto</option>";
					    }
                 elseif($_SESSION['tipo'] == 3) 
                		{
                		  echo "<option value='NULL'>--Seleccione--</option>";
						  echo "<option value='1'>FABS</option>";
						  echo "<option value='2' >Pagare</option>";
						  echo "<option value='3' selected='selected'>Gasto</option>";
					    } ?>
            </select>
        </td>
        <td><input type="text" name="reg_tel_num_linea" onkeypress="return permite(event,'num')" value="<?php echo $_SESSION['reg_tel_num_linea']; ?>" maxlength="10" size="10" /></td>
    </tr>
</table>
<?php
$numero=1;
if (!empty($_REQUEST['numero'])){
$numero=$_REQUEST['numero'];
}
?>

<table border="1">
	<tr>
    <th>Plan</th>
    <th>Estado</th>
    </tr>
  <?php
    $recor = 0;
    while($recor < $numero)
	{
	$cons_tod_pla_telefonia = $ins_plan_telefonia->cons_tod_pla_telefonia();
	$con_tod_est_reg_telefonia = $ins_telefonia->con_tod_est_reg_telefonia();
  ?>
	<tr>
    	<td><select name="plan[]">
        	<option value="NULL">--Seleccione--</option>
            <?php while($planes = mssql_fetch_array($cons_tod_pla_telefonia)){
			if($_SESSION['plan'][$recor] == $planes['pla_tel_id']){
			?>
            <option value="<?php echo $planes['pla_tel_id']; ?>" selected="selected"><?php echo $planes['pla_tel_nombre']; ?></option>
            <?php
            }
			else{
			?>
            <option value="<?php echo $planes['pla_tel_id']; ?>"><?php echo $planes['pla_tel_nombre']; ?></option>
            <?php	
			}
			}
			?>
            </select>
        </td>
        <td>
        	<select name="estado[]">
            		<option value="NULL">--Seleccione--</option>
            <?php while($res_tod_est_reg_telefonia = mssql_fetch_array($con_tod_est_reg_telefonia)){ 
			if($_SESSION['estado'][$recor] == $res_tod_est_reg_telefonia['est_reg_tel_id']){
			?>
            <option value="<?php echo $res_tod_est_reg_telefonia['est_reg_tel_id']; ?>" selected="selected">
			<?php echo $res_tod_est_reg_telefonia['est_reg_tel_nombres']; ?></option>
            <?php
			}
			else{
			?>
            <option value="<?php echo $res_tod_est_reg_telefonia['est_reg_tel_id']; ?>">
			<?php echo $res_tod_est_reg_telefonia['est_reg_tel_nombres']; ?></option>
            <?php
			}
			}
			?>
             </select>
        </td>
    </tr>
    <?php
     $recor++;   
     }
    $numero++;
  	?>
     <tr>
    	<td colspan="4"><input type="submit" class="art-button" name="agregar" id="agregar" value="Agregar"/>
       <input type="hidden" name="numero" id="numero" value="<?php echo $numero; ?>">
        <input type="button" class="art-button" name="registrar" value="Registrar" onclick="validar_vacios();"/></td>
    </tr>
</table>
</center>
</form>
<?php
//}
?>
</body>
</html>
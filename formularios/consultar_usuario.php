<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/usuario.class.php');
@include_once('clases/usuario.class.php');

$ins_usuario = new usuario();
$ins_nits = new nits();
$existe = "IS NOT NULL";
$con_nit_con_perfil = $ins_nits->con_nit_sin_perfil($existe,2,1);
$_SESSION['tipo_que'] = 2;
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_email.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function valida_select()
{
	var form = document.con_usuario_1;
	form.submit();
}
$(document).ready(function()
{
	$("#gua").click(function(evento)
	{
		var form = document.con_usuario_2;
		var cam_anterior=document.con_usuario_2.usuario.value;
		if(cam_anterior!="")
		{
			if(form.pass.value!=form.confir.value)
			{
				alert('Los password no coinciden');
				form.pass.focus();
				return false;
			}
		}
	});
});
function habilitar()
  {
    for (i=0;i<document.forms[1].elements.length;i++) 
	  {
        if (document.forms[1].elements[i].disabled)
          document.forms[1].elements[i].disabled = false;
		    
    }
	  document.con_usuario_2.gua.disabled=false;
  }
</script>
</script>
<form name="con_usuario_1" id="con_usuario_1" action="#" method="post">
<center>
<table>
	<tr>
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $_SESSION['tipo_que']; ?>"/>
    <?php if(mssql_num_rows($con_nit_con_perfil) > 0){ ?>
    	<td><b>Empleado</b></td><td><select name="nit_sel"><option value="" required x-moz-errormessage="Seleccione Una Opcion Valida">Seleccione</option>
        <?php while($res_dat_nit = mssql_fetch_array($con_nit_con_perfil)){ ?>
        <option value="<?php echo $res_dat_nit['nit_id']; ?>" onclick="valida_select();"><?php echo $res_dat_nit['nombres']; ?></option>
        <?php } ?>
    	</select></td>
        <?php }else{ echo "<b>No Se Encontraron Usuarios</b>"; } ?>
    </tr>
</table>
</center>
</form>
<?php
if(isset($_POST['nit_sel'])){
$nit_id = $_POST['nit_sel'];

$con_nit_con_perfil_2 = $ins_nits->con_nit_sin_perfil($existe,2,1);

$con_perfiles = $ins_usuario->get_perfiles();

$res_usu_por_id = $ins_usuario->con_usu_por_id($nit_id);
?>
<form name="con_usuario_2" id="con_usuario_2" action="control/asignarUsuario.php?usuario=<?php echo $nit_id; ?>" method="post">
<center>
<table>
  <tr>
  	<th colspan="2"><?php echo $res_usu_por_id['nombres']; ?></th>
  </tr>
  
  <tr>
   <td>Empleado</td>
   <td>
    <select name="emp" id="emp" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
     <option value="">Seleccione...</option>
     <?php while($dat_emp = mssql_fetch_array($con_nit_con_perfil_2)){
      if($dat_emp['nit_id'] == $res_usu_por_id['nit_id']){
	  ?>
	  <option value="<?php echo $dat_emp['nit_id']; ?>" selected="selected"><?php echo $dat_emp['nombres']; ?></option>
	 <?php 
	 }
	 else{
	 ?>
     <option value="<?php echo $dat_emp['nit_id']; ?>"><?php echo $dat_emp['nombres']; ?></option>
     <?php	 
	 }
	 } 
	 ?>
    </select>
   </td>
  </tr>
  
  <tr>
   <td>Perfil</td>
   <td>
    <select name="perfil" id="perfil" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
     <option value="">Seleccione...</option>
     <?php
	  while($dat_perf = mssql_fetch_array($con_perfiles)){
	  if($dat_perf['per_id'] == $res_usu_por_id['per_id']){
	  ?>
	  <option value="<?php echo $dat_perf['per_id']; ?>" selected="selected"><?php echo $dat_perf['per_nombre']; ?></option>
     <?php
	  }
	  else{
	  ?>
      <option value="<?php echo $dat_perf['per_id']; ?>"><?php echo $dat_perf['per_nombre']; ?></option>
      <?php
	  }
	  }
	 ?>
    </select>
   </td>
  </tr>
  <tr>
   <td>Usuario</td>
   <td><input type="text" name="usuario" id="usuario" value="<?php echo $res_usu_por_id['nit_nom_usuario']; ?>" disabled="disabled" required="required"/></td>
  </tr>
  <tr> 
   <td>Password</td>
   <?php
   $desencriptar_clave = $ins_usuario->decrypt($res_usu_por_id['nit_password'],"g5@anestecoop.com");
   ?>
   <td><input type="text" name="pass" id="pass" value="<?php echo $desencriptar_clave; ?>" disabled="disabled" required="required"/></td>
  </tr>
  <tr> 
   <td>Confirmar Password</td>
   <td><input type="text" name="confir" id="confir" value="<?php echo $desencriptar_clave; ?>" disabled="disabled" required="required"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="button" class="art-button" name="mod" id="mod" value="Modificar" onclick="habilitar();"/>
    <input type="submit" class="art-button" id="gua" name="gua" value="Guardar" disabled="disabled"/></td>
  </tr>
 </table>
</center>
</form>
<?php
}
?>
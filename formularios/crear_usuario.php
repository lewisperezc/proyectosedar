<?php 
  session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $_SESSION['emp'] = $_POST['nit'];
  $ano = $_SESSION['elaniocontable'];
  @include_once('clases/usuario.class.php');
  @include_once('../clases/usuario.class.php');
  @include_once('clases/nits.class.php');
  @include_once('../clases/nits.class.php');
  $nit = new nits();
  $usuarios = new usuario();
  $existe = "IS NULL";
  $empleados = $nit->con_nit_sin_perfil($existe,2,1);
  $perfiles = $usuarios->get_perfiles();
  $_SESSION['tipo_que'] = 1;
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_email.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
$(document).ready(function()
{
	$("#boton").click(function(evento)
	{
		var form = document.per_nit;
		var cam_anterior=document.per_nit.usuario.value;
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
</script>
<form name="per_nit" id="per_nit" action="control/asignarUsuario.php" method="post">
<center>
 <table>
  <tr>
  <input type="hidden" name="usu_existe" id="usu_existe" value="<?php echo $ver_usu_existe; ?>"/>
  <input type="hidden" name="tipo" id="tipo" value="<?php echo $_SESSION['tipo_que']; ?>"/>
   <td>Empleado</td>
   <td>
    <select name="emp" id="emp" required x-moz-errormessage="Seleccione Una Opcion Valida">
     <option value="">Seleccione...</option>
     <?php
	  while($dat_emp = mssql_fetch_array($empleados))
	    echo "<option value='".$dat_emp['nit_id']."'>".$dat_emp['nombres']."</option>";
	 ?>
    </select>
   </td>
  </tr>
  <tr>
   <td>Perfil</td>
   <td>
    <select name="perfil" id="perfil" required x-moz-errormessage="Seleccione Una Opcion Valida">
     <option value="">Seleccione...</option>
     <?php
	  while($dat_perf = mssql_fetch_array($perfiles))
	    echo "<option value='".$dat_perf['per_id']."'>".$dat_perf['per_nombre']."</option>";
	 ?>
    </select>
   </td>
  </tr>
  <tr>
   <td>Usuario</td>
   <td><input type="text" name="usuario" id="usuario" required="required"/></td>
  </tr>
  <tr> 
   <td>Password</td>
   <td><input type="password" name="pass" id="pass" required="required"/></td>
  </tr>
  <tr> 
   <td>Confirmar Password</td>
   <td><input type="password" name="confir" id="confir" required="required"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" class="art-button" id="boton" name="boton" value="Guardar"/></td>
  </tr>
 </table>
</center>
</form>
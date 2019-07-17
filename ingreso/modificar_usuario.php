<?php 
  session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $_SESSION['emp'] = $_POST['nit'];
  @include_once('clases/usuario.class.php');
  @include_once('../clases/usuario.class.php');
  @include_once('clases/nits.class.php');
  @include_once('../clases/nits.class.php');
  $nit = new nits();
  $usuarios = new usuario();
  $existe = "IS NOT NULL";
  $empleados = $nit->con_nit_sin_perfil($existe,2,1);
?>

<script type="text/javascript" language="javascript">
 function enviar()
 {
	 alert(document.per_nit.ant.value);
	 alert(document.per_nit.pass_ant.value);
	 if(document.per_nit.ant.value != document.per_nit.pass_ant.value)
	 {
	    alert("El password Anterior no coinside con el digitado");
		return false;
	 }
	 if(document.per_nit.nuevo.value != document.per_nit.confir.value || document.per_nit.confir.value == "" || document.per_nit.nuevo.value == "" )
	 {
		 alert("El nuevo password y la confirmacion no coinciden.");
		 return false;
	 }
	document.per_nit.submit();
 }
</script>
<form name="per_nit" id="per_nit" action="#" method="post">
<center>
 <table>
  <tr>
    <td>Usuario</td>
    <td><input type="text" name="usuario" id="usuario" value="<?php echo $_SESSION["k_username"]; ?>" disabled="disabled" onkeyup="per_nit.usuario.value=per_nit.usuario.value.toUpperCase();" /></td>
  </tr>
  <tr> 
   <td>Password Anterior</td>
   <td><input type="password" name="ant" id="ant" onkeyup="per_nit.ant.value=per_nit.ant.value.toUpperCase();" /><input type="hidden" name="pass_ant" id="pass_ant" 
        value="<?php $pass = $usuarios->decrypt($_SESSION['k_password'],'g5@anestecoop.com'); echo $pass;?>" onkeyup="per_nit.ant.value=per_nit.ant.value.toUpperCase();"/></td>
  </tr>
  <tr> 
   <td>Nuevo Password</td>
   <td><input type="password" name="nuevo" id="nuevo" /></td>
  </tr>
  <tr> 
   <td>Confirmar Password</td>
   <td><input type="password" name="confir" id="confir" /></td>
  </tr>
  <tr>
    <td colspan="2"><input type="button" id="boton" value="Asignar" onclick="enviar();"/></td>
  </tr>
 </table>
</center>
</form>
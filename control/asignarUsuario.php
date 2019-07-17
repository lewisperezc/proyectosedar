<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/usuario.class.php');
$ins_usuario = new usuario();

$tipo_query = $_SESSION['tipo_que'];

$perfil = $_POST['perfil'];
$usuario = strtoupper($_POST['usuario']);
$password = strtoupper($_POST['pass']);
$existe = "IS NOT NULL";
$ver_usu_existe = $ins_usuario->ver_usu_existe($existe,2,1,$usuario);

if($tipo_query==2)
{
	$nit_id=$_GET['usuario'];
	$encriptar_clave = $ins_usuario->encrypt($password,"g5@anestecoop.com");
	$gua_usuario = $ins_usuario->crear_usuario($tipo_query,$perfil,$usuario,$encriptar_clave,$nit_id);
}
else{
	if($tipo_query==1){
			$nit_id = $_POST['emp'];
			if($ver_usu_existe < 1){
			$encriptar_clave = $ins_usuario->encrypt($password,"g5@anestecoop.com");
			$gua_usuario = $ins_usuario->crear_usuario($tipo_query,$perfil,$usuario,$encriptar_clave,$nit_id);
			}
			else{
			echo "<script>
				  	alert('Este nombre de usuario no se encuentra disponible.');
				  	location.href='../index.php?c=113';
			  	  </script>";
			}
	}
}
unset($_SESSION['tipo_que']);
?>
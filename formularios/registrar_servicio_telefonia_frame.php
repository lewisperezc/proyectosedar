<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="50,*" cols="*" framespacing="0" border="0" frameborder="no">
	<iframe src="formularios/registrar_servicio_telefonia_1.php" name="frame1" width="100%" height="35" scrolling="no"></iframe>
	<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="400" scrolling="no"></iframe>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89">
</frameset><noframes></noframes>
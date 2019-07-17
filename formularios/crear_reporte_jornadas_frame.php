<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="65,*" cols="*">
	<iframe src="formularios/crear_reporte_jornadas.php" name="frame1" width="100%" height="35" scrolling="no"></iframe>
	<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="560" scrolling="auto"></iframe>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89">
</frameset><noframes></noframes><noframes></noframe>
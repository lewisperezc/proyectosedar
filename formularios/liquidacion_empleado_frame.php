<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="28%,72%" border="6">
	<iframe src="formularios/liquidacion_empleado_1.php" name="frame1" width="100%" height="70"></iframe><br />
	<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="480"></iframe>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
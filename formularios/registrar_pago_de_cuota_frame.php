<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="65,*" cols="*">
	<iframe src="formularios/pagina_blanca.php" name="frame1" width="100%" height="5" scrolling="no"></iframe>
	<iframe src="formularios/registrar_pago_de_cuota_1.php" name="frame2" width="100%" height="35" scrolling="no"></iframe>
<frameset rows="*,83" cols="*">
	<iframe src="formularios/pagina_blanca.php" name="frame3" width="100%" height="400" scrolling="no"></iframe>
    <iframe src="formularios/pagina_blanca.php" name="frame4" width="100%" height="35" scrolling="auto"></iframe>
    <iframe src="formularios/pagina_blanca.php" name="frame5" width="100%" height="10" scrolling="auto"></iframe>
<frame src="UntitledFrame-70"><frame src="UntitledFrame-71"></frameset>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89">
</frameset><noframes></noframes>
<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="65,*" cols="*">
	<iframe src="formularios/pagina_blanca.php" name="frame1" width="100%" height="5"></iframe>
	<iframe src="formularios/consultar_contrato_externo_1.php" name="frame2" width="100%" height="800"></iframe>
<frame src="UntitledFrame-5"><frame src="UntitledFrame-6"></frameset><noframes></noframes>
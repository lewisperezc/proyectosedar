<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<iframe src="formularios/saldos_1.php" scrolling="no" frameborder="0" name="frame1" width="100%" height="58"></iframe>
<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="480"></iframe>
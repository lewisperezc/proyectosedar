<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="18%,99%" border="6">

	<iframe src="formularios/pago_tesoreria.php" name="menu" width="100%" height="70"></iframe><br />
	<iframe src="formularios/pagina_blanca.php" name="contenido" width="100%" height="480"></iframe>
    
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
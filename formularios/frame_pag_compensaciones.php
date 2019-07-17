<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="18%,99%" border="6">
	<iframe src="formularios/crear_nomina.php" name="menu" width="99%" height="70"></iframe><br />
	<iframe src="formularios/pagina_blanca.php" name="nomina" width="99%" height="600"></iframe>
<frame src="UntitledFrame-41"><frame src="UntitledFrame-5"></frameset><noframes></noframes>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="18%,99%" border="6">

	<iframe src="formularios/crear_nominaAdmin.php" name="menu" width="100%" height="30px"></iframe><br />
	<iframe src="formularios/pagina_blanca.php" name="nomina" width="100%" height="620px"></iframe>
    
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
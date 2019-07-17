<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="18%,99%" border="6">

	<iframe src="formularios/pagina_blanca.php" name="frame1" width="100%" height="10"></iframe>
	<iframe src="formularios/consultar_nit_general.php" name="frame2" width="100%" height="480" frameborder="0" border="0" framespacing="0"></iframe>
    
</frameset>

<!--
<frameset rows="18%,99%" border="6">
	<iframe src="formularios/pagina_blanca.php" name="frame1" width="100%" height="10"></iframe><br />
	<iframe src="formularios/crear_empleado_1.php" name="frame2" width="100%" height="480"></iframe>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
-->
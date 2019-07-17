<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>

<frameset rows="18%,99%" border="6">

	<iframe src="formularios/consultar_movimientos.php" name="frame1" width="100%" height="75" scrolling="no"></iframe><br />
	<iframe src="formularios/pagina_blanca_movimientos.php" name="frame2" width="100%" height="70" scrolling="no"></iframe>
<frameset rows="18%,99%" border="6">
    <iframe src="formularios/pagina_blanca_movimientos.php" name="frame3" width="100%" height="480" ></iframe>
<frame src="UntitledFrame-39"><frame src="UntitledFrame-40"></frameset>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
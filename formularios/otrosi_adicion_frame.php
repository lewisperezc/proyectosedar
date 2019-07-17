<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); } 
$ano = $_SESSION['elaniocontable'];?>

<frameset rows="63px,480px">

	<iframe src="formularios/agregar_otrosi_adicion_1.php" name="frame1" width="100%" height="63px" scrolling="no"></iframe><br />
	<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="480px"></iframe>
    
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89"></frameset><noframes></noframes>
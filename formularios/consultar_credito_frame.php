<?php 
	session_start();
    $_SESSION['sel_tip_persona'];
    if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
	$ano = $_SESSION['elaniocontable'];
?>
<frameset rows="65,*" cols="*">

<iframe src="formularios/consultar_credito_1.php" name="frame1" width="100%" height="58" scrolling="no" border='0'></iframe>
	<iframe src="formularios/pagina_blanca.php" name="frame2" width="100%" height="55" scrolling="no" border='0'></iframe>
<frameset rows="*,83" cols="*">
	<iframe src="formularios/pagina_blanca.php" name="frame3" width="100%" height="480" scrolling="si" border='0'></iframe>  
<frame src="UntitledFrame-70"><frame src="UntitledFrame-71"></frameset>
<frame src="UntitledFrame-88"><frame src="UntitledFrame-89">
</frameset><noframes></noframes>
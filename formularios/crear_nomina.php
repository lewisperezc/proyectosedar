<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?><head><link rel="stylesheet" type="text/css" href="../estilos/menu.css"/></head>
<?php
$principal="1169,";
$lacadena=$_SESSION['k_cen_costo'];
$comparacion=strpos($lacadena,$principal);
if($comparacion===false)
{
?>
	<a href="form_pag_compensaciones.php" target="nomina">Pagar Compensaciones||</a>
    <a href="form_con_compensaciones.php" target="nomina">Consultar Compensaciones||</a>
<?php
}
else
{
?>
    <a href="form_pag_compensaciones.php" target="nomina">Pagar Compensaciones||</a>
    <a href="form_con_compensaciones.php" target="nomina">Consultar Compensaciones||</a>
    <a href="form_con_leg_descontata.php" target="nomina">Consultar Legalizacion descontada por contrato||</a>
<?php
}
?>
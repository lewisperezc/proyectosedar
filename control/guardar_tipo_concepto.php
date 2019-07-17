<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/concepto.class.php');
$tip_concepto = new concepto();
$cuantos_campos=$_POST['cuantos_campos'];
$h=0;
while($h<=$cuantos_campos)
{
	$tip_concep_nombre[$h]=$_POST['tip_concep_nombre'.$h];
	$tip_concep_concecutipo[$h]=$_POST['tip_concep_concecutipo'.$h];
	$h++;
}
$j=0;;
while($j<=$cuantos_campos)
{
	$verificar =$tip_concepto->verificar_tipo_concepto($tip_concep_concecutipo[$j]);
	$res = mssql_fetch_array($verificar);
	if($res['tip_concep_concecutipo']<=0)
		$guardar=$tip_concepto->crear_tipo_concepto(strtoupper($tip_concep_nombre[$j]),$tip_concep_concecutipo[$j]);
 $j++;
}
if($guardar)
	echo "<script>alert('Tipo(s) de concepto guardado(s) correctamente.');</script>";
else
	echo "<script>alert('Error al guardar el tipo de concepto, intentelo de nuevo.');</script>";
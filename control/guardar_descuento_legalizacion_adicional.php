<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();
$recibo_caja=$_POST['des_nom_rec_caja'];
$fecha=date('d-m-Y');


$des_tipo=11;//LEGALIZACION
$des_tip_adicion=1;//POR EL MODULO DE NOMINA
$des_descripcion='DESCUENTO DE LEGALIZACION REGISTRADO DESDE EL MODULO DE NOMINA';


$cuantos=$_POST['cuantos'];
$i=0;
while($i<=$cuantos)
{
	//echo "entra";
	$valor=$_POST['des_nom_valor'.$i];
	
	$gua_des_leg_adicional=$ins_mov_contable->GuardarDescuentoLegalizacionAdicional($recibo_caja,$valor,$des_tipo,$des_tip_adicion,$des_descripcion);
	$i++;
}
if($gua_des_leg_adicional)
	echo "<script>alert('Descuento de legalizacion registrado correctamente.');</script>";
else
	echo "<script>alert('Error al registrar el descuento de legalizacion, Intentelo de nuevo.');</script>";

echo "<script>window.close();window.opener.Recargar();</script>";

?>
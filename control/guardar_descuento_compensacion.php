<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();
$asociado=$_POST['des_nom_asociado'];
$factura=$_POST['des_nom_factura'];
$recibo_caja=$_POST['des_nom_rec_caja'];
$fecha=date('d-m-Y');
$estado=1;//SIN DESCONTARSE DE LA PAGADA
$cuantos=$_POST['cuantos'];
$i=0;
while($i<=$cuantos)
{
	$valor=$_POST['des_nom_valor'.$i];
	$cuenta=$_POST['des_nom_cuenta'.$i];
		if(trim($valor)!=""&&trim($cuenta)!="")
		{
			$gua_des_compensacion=$ins_mov_contable->gua_des_compensacion($asociado,$valor,$factura,$cuenta,$fecha,$recibo_caja,$estado);
		}
	$i++;
}
if($gua_des_compensacion)
	echo "<script>alert('Descuento de nomina registrado correctamente.');</script>";
else
	echo "<script>alert('Error al registrar el descuento de nomina, Intentelo de nuevo.');</script>";

echo "<script>window.close()</script>";
?>
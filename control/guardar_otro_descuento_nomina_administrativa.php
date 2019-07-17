<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/compensacion_nomina.class.php');
$ins_com_nomina=new compensacion_nomina();
$nit=$_POST['des_nom_adm_nit'];
$sigla_causacion=$_POST['des_nom_adm_causacion'];
$sigla_pago=$_POST['des_nom_adm_pago'];
$fecha=date('d-m-Y');
$estado=1;//SIN DESCONTARSE DE LA PAGADA
$cuentos=$_POST['cuantos'];
$i=0;
while($i<=$cuantos)
{
	$valor=$_POST['des_nom_valor'.$i];
	$cuenta=$_POST['des_nom_cuenta'.$i];
		if(trim($valor)!=""&&trim($cuenta)!="")
		{
			$gua_des_compensacion=$ins_com_nomina->GuardarDescuentoNominaAdministrativa($nit,$valor,$sigla_causacion,$cuenta,$fecha,$sigla_pago,$estado);
		}
	$i++;
}
if($gua_des_compensacion)
	echo "<script>alert('Descuento de nomina registrado correctamente.');</script>";
else
	echo "<script>alert('Error al registrar el descuento de nomina, Intentelo de nuevo.');</script>";

echo "<script>window.close()</script>";
?>
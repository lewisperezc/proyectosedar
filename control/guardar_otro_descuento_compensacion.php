<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/reporte_jornadas.class.php');
$ins_mov_contable=new movimientos_contables();
$ins_rec_caja=new rec_caja();
$ins_rep_jornadas=new reporte_jornadas();

$con_dat_rec_caja=$ins_rec_caja->buscar_recibos($_POST['fac_des_adicional']);
$res_dat_rec_caja=mssql_fetch_array($con_dat_rec_caja);

if($_GET['tipo_descuento']==1)
{
	$tip_des_adicional=$_POST['tip_des_adicional'];
	$val_des_adicional=$_POST['val_des_adicional'];

	$des_distribucion=1;

	$gua_otr_des_compensacion=$ins_mov_contable->GuardarOtroDescuentoCompensacion($res_dat_rec_caja['rec_caj_id'],$val_des_adicional,$tip_des_adicional,$des_distribucion);

}

elseif($_GET['tipo_descuento']==2)
{

	$tot_descuento_adicional=$_POST['tot_descuento_adicional'];
	$tip_des_adicional_afiliados=$_POST['tip_des_adicional_afiliados'];

	$gua_otr_des_compensacion=$ins_mov_contable->GuardarOtroDescuentoCompensacion2($res_dat_rec_caja['rec_caj_id'],$tot_descuento_adicional,$tip_des_adicional_afiliados);

	for($p=0;$p<$_POST['tot_afiliados'];$p++)
	{
		$guardar_distribucion=$ins_rep_jornadas->distGlosa($_POST['repJor'.$p],$_POST['jorna'.$p],$res_dat_rec_caja['rec_caj_id']);
	}
}

if($guardar_distribucion)
	echo "<script>alert('Descuento de nomina registrado correctamente.');</script>";
else
	echo "<script>alert('Error al registrar el descuento de nomina, Intentelo de nuevo.');</script>";

echo "<script>window.close()</script>";
?>
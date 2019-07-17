<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$dato = new nits();
$nit_ciudad=new nits();
$principal=2;
$raz=strtoupper($_POST[raz]);
if(!empty($_POST[dig_veri]))
	$num_nit=$_POST[num_nit]."-".$_POST[dig_veri];
else
	$num_nit=$_POST[num_nit];
$costo=$_POST[costo];
if($costo=="")
$costo = "NULL";
$reg=strtoupper($_POST[reg]);
$tip_reg=$_POST[tip_reg];
$ciudad=$_POST[select2];
$direccion=strtoupper($_POST[direccion]);
$telefono=strtoupper($_POST[telefono]);
$fax=strtoupper($_POST[fax]);
$representante=strtoupper($_POST[representante]);
$contacto=strtoupper($_POST[contacto]);
$correo=strtoupper($_POST[correo]);
$clase=$_POST['clase_hos'];
$tip_ide_id=8;
$cod_cen_costo="NULL";
$cuenta = "NULL";
$tip_hosp = "NULL";
$nucleo=0;
$nit_uni_funcional=$_POST['nit_uni_funcional'];
if($principal==2)
{
	$nit=$dato->CrearHospital($raz,$num_nit,$nucleo,$cod_cen_costo,$reg,$tip_reg,$ciudad,$direccion,$telefono,$fax,$representante,$correo,$contacto,$tip_ide_id,$principal,$tip_hosp,$cuenta,$clase,$nit_uni_funcional);
    echo "<script>alert(\"Se ha creado un centro de costo satisfactoriamente.\");</script>";
}
else
{
    $nit=$dato->CrearHospital($raz,$num_nit,$nucleo,$cod_cen_costo,$reg,$tip_reg,$ciudad,$direccion,$telefono,$fax,$representante,$correo,$contacto,$tip_ide_id,$principal,$tip_hosp,$cuenta,$clase,$nit_uni_funcional);
    echo "<script>alert(\"* Se ha creado un centro de costo Principal satisfactoriamente.\")</script>";
}
?>
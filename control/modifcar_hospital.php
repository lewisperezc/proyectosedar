<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/nits.class.php');
$ins_nits=new nits();
$hos_nombre=strtoupper($_POST['hos_nombre']);
$hos_nit=$_POST['hos_nit'];
$hos_dig_verificacion=$_POST['hos_dig_verificacion'];
$nit_competo=$hos_nit."-".$hos_dig_verificacion;
$hos_codigo=strtoupper($_POST['hos_codigo']);
if($hos_codigo=="")
	$hos_codigo="NULL";
$hos_regimen=$_POST['hos_regimen'];
$hos_tip_regimen=$_POST['hos_tip_regimen'];
$select1=$_POST['select1'];//DEPARTAMENTO
$select2=$_POST['select2'];//CIUDAD
$hos_direccion=strtoupper($_POST['hos_direccion']);
$hos_telefono=strtoupper($_POST['hos_telefono']);
$hos_fax=strtoupper($_POST['hos_fax']);
$clase=$_POST['clase_hos'];
if($hos_fax=="")
	$hos_fax=" ";
$hos_representante=strtoupper($_POST['hos_representante']);
if($hos_representante=="")
	$hos_representante=" ";
$hos_correo=strtoupper($_POST['hos_correo']);
$hos_contacto=strtoupper($_POST['hos_contacto']);
if($hos_contacto=="")
	$hos_contacto=" ";
$hospital_id=$_GET['hospital_id'];

$nucleo="NULL";
$principal="NULL";


$nit_uni_funcional=$_POST['nit_uni_funcional'];


$actualizar_hospital=$ins_nits->actualizar_hospital($hos_nombre,$nit_competo,$hos_regimen,$hos_tip_regimen,$hos_direccion,$hos_telefono,$hos_fax,$hos_representante,$hos_correo,$hos_contacto,$hos_codigo,$hospital_id,$clase,$nit_uni_funcional);

if($actualizar_hospital)
{
	$act_ciu_dep_hospital=$ins_nits->act_ciu_dep_1_asociado($select2,1,$hospital_id);
	if($act_ciu_dep_hospital)
		echo "<script>alert('Hospital actualizado correctamente.');//location.href = '../index.php?c=25';</script>";
	else
		echo "<script>alert('Error al actualizar el hospital, Intentelo de nuevo.');//location.href = '../index.php?c=25';
	      	  </script>";
}
?>


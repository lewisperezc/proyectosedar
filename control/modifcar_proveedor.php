<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/nits.class.php');
$ins_nit = new nits();
$pro_id=$_GET['pro_id'];
$tip=$_GET['tip'];
if($tip!="")
	$tipo=13;
else
	$tipo=3;
$pro_raz_social=strtoupper($_POST['pro_raz_social']);
$pro_nit=$_POST['pro_nit'];
$pro_dig_verificacion=$_POST['pro_dig_verificacion'];
$nit_completo=$pro_nit."-".$pro_dig_verificacion;
$pro_representante=strtoupper($_POST['pro_representante']);
if($pro_representante=="")
	$pro_representante="NA";
$pro_tip_documento=$_POST['pro_tip_documento'];
$pro_regimen=$_POST['pro_regimen'];
$pro_tip_regimen=$_POST['pro_tip_regimen'];
$select1=$_POST['select1'];//DEPARTAMENTO
$select2=$_POST['select2'];//CIUDAD
$pro_direccion=strtoupper($_POST['pro_direccion']);
$pro_telefono=strtoupper($_POST['pro_telefono']);
$pro_contacto=strtoupper($_POST['pro_contacto']);
if($pro_contacto=="")
	$pro_contacto="NA";
$pro_correo=strtoupper($_POST['pro_correo']);
$pro_fax=strtoupper($_POST['pro_fax']);
if($pro_fax=="")
	$pro_fax="NA";
$pro_banco=$_POST['pro_banco'];
$pro_tip_cuenta=$_POST['pro_tip_cuenta'];
$pro_num_cuenta=strtoupper($_POST['pro_num_cuenta']);
$pro_diaPro=$_POST['diaPro'];
if($pro_diaPro=='' || $pro_diaPro=='NULL')
	$pro_diaPro=0;
$actualizar_proveedor=$ins_nit->actualizarProveedor($pro_raz_social,$nit_completo,$pro_representante,$pro_tip_documento,$pro_regimen,$pro_tip_regimen,$pro_direccion,$pro_telefono,$pro_contacto,$pro_correo,$pro_fax,$pro_banco,$pro_tip_cuenta,$pro_num_cuenta,$tipo,$pro_id,$pro_diaPro);

if($actualizar_proveedor)
	echo "<script>
			alert('Proveedor actualizado correctamente.');
			history.back(-1);
		  </script>";
else
  echo "<script>
  			alert('Error al actualizar el proveedor, Intentelo de nuevo.');
			history.back(-1);
		</script>";
?>
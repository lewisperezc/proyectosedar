<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$ins_nits=new nits();
$pro_nombre=strtoupper($_POST['pro_nombre']);
$pro_nit=$_POST['pro_nit'];
/*$pro_dig_verificacion=$_POST['pro_dig_verificacion'];
if(trim($pro_dig_verificacion)!="")
$pro_num_documento=$pro_nit."-".$pro_dig_verificacion;
else
*/
$pro_num_documento=$pro_nit;
$pro_representante=strtoupper($_POST['pro_representante']);
$tip_nit_id=3;
$pro_tip_documento=$_POST['pro_tip_documento'];
$pro_regimen=$_POST['pro_regimen'];
$pro_tip_regimen=trim($_POST['pro_tip_regimen']);
$select2=$_POST['select2'];//CIUDAD
$pro_direccion=strtoupper($_POST['pro_direccion']);
$pro_telefono=strtoupper($_POST['pro_telefono']);
$pro_contacto=strtoupper($_POST['pro_contacto']);
$pro_correo=strtoupper($_POST['pro_correo']);
$pro_fax=strtoupper($_POST['pro_fax']);
$pro_banco=$_POST['pro_banco'];
$pro_tip_cuenta=$_POST['pro_tip_cuenta'];
$pro_num_cuenta=strtoupper($_POST['pro_num_cuenta']);
$pro_diaPro=$_POST['diaPro'];

$guardar_proveedor=$ins_nits->ins_proveedor($pro_nombre,$pro_num_documento,$pro_representante,$tip_nit_id,$pro_tip_documento,$pro_regimen,$pro_tip_regimen,$select2,$pro_direccion,$pro_telefono,$pro_contacto,$pro_correo,$pro_fax,$pro_banco,$pro_tip_cuenta,$pro_num_cuenta,$pro_diaPro);
if($guardar_proveedor)
{
	echo "<script>
				alert('Proveedor creado correctamente.');
				history.back(-1);
		  </script>";
}
else
{
	echo "<script>
				alert('Error al crear el proveedor, Intentelo de nuevo.');
				history.back(-1);
		  </script>";
}
?>
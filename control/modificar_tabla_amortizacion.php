<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');

$credito = new credito();
$can_cuotas=$_POST['cant_credito'];
if(empty($can_cuotas))
	$can_cuotas=$_POST['cant_crEmpleados'];
$j=0;


$_SESSION["tipo_nit"]=$_POST['tipo_nit'];
//echo "el valor es: ".$_POST['tipo_nit'];
if($_SESSION["tipo_nit"]==1)//EMPLEADO
	$fecha=date('d-m-Y');
elseif($_SESSION["tipo_nit"]==2)
	$fecha=$_POST['fec_rec_empleado'];

for($i=0;$i<$can_cuotas;$i++)
{
	if($_POST['descontar'.$i]=="on")
	{
		
		$credito->des_cuoCredito($_POST['capital'.$i],$_POST['interes'.$i],$_POST['credito'.$i],$_POST['factura_recaudo'],$fecha,$_POST['nit_id'.$i],2,'','','');
		$_SESSION['cred_recaudo'][$j]=$_POST['credito'.$i];
		$_SESSION['saldo'][$j]=$_POST['saldo'.$i];
		$_SESSION['nit_num_documento'][$j]=$_POST['nit_num_documento'.$i];
		$_SESSION['nombres_tercero'][$j]=$_POST['nombres_tercero'.$i];
		$_SESSION['cap_recaudo'][$j]=$_POST['capital'.$i];
		$_SESSION['int_recaudo'][$j]=$_POST['interes'.$i];
		$_SESSION['cuota'][$j]=$_POST['cuota'.$i];
		$j++;
	}
}

$_SESSION['cant']=$j;
echo "<script>alert('Cuotas recaudadas correctamente');</script>";
echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../reportes_PDF/rep_recaudo.php'>";
?>
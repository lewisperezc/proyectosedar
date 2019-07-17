<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/saldos.class.php');

$inst_credito = new credito();
$inst_transaccion = new transacciones();
$mov_con = new movimientos_contables();
$ins_cuenta = new cuenta();
$ins_saldos=new saldos();
//Inicio Datos Que Capturo Del Formulario form_ent_credito.php

$fec_pago = date('d-m-Y');
$centro = $_POST['centro'];
$cantidad = $_POST['cantidad'];
$sigla = $_POST['sigla'];
$descuento = $_POST['des'];
$factura = $_POST['factura'];
$mes=date('m');

if(empty($_POST['recibo']))
	$recibo='NULL';
else
	$recibo=$_POST['recibo'];

//echo "cant es: ".$cantidad;
for($i=0;$i<=$cantidad;$i++)
{
	//echo "entra ".$i."<br>";
	if($_POST['descontar'.$i] == "on")
	{
		$cre_sele = $_POST['num_cuota'.$i];
		$tercero = $_POST['nit'.$i];
		$val_cuota = $_POST['total'.$i];
		$val_interes = $_POST['interes'.$i];
		$val_capital = $_POST['capital'.$i];
		$cre_pagar = $_POST['credito'.$i];
		$act_cuota=$inst_credito->act_cuota($cre_sele,$recibo,$val_interes,$val_capital);
	}
	elseif($_POST['eliminar'.$i] == "on")
		$inst_credito->elimCreRecaudo($_POST['num_cuota'.$i]);
}
echo "<script>window.close()</script>";
?>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/telefonia.class.php');
include_once('../clases/credito.class.php');
$ins_telefonia = new telefonia();
$ins_credito=new credito();
$nit = $_POST['nit'];
$reg_tel_num_linea = $_POST['reg_tel_num_linea'];
$plan = $_POST['plan'];
$tipo = $_POST['tipo'];
$estado = $_POST['estado'];


$guardar_linea_telefonia = $ins_telefonia->gua_lin_telefonia($reg_tel_num_linea,$nit,1,$tipo);

$i = 0;
while($i < sizeof($plan)){
	if($plan[$i] != "NULL" && $tipo != "NULL" && $estado[$i] != "NULL"){
	$guardar_registro_telefonia = $ins_telefonia->gua_reg_telefonia($plan[$i],$guardar_linea_telefonia,$estado[$i]);
	}
	$i++;
}

//PAGARE
if($tipo==2){

$con_ult_lin_telefonia=$ins_telefonia->con_ult_lin_telefonia();
$cre_observacion="PAGO LINEA TELEFONICA";
$fecha=date('d-m-Y');

$conse = $ins_credito->obt_consecutivo();
$guardar_credito=$ins_credito->ins_reg_cre_tel($nit,13250502,$cre_observacion,$con_ult_lin_telefonia,1,1,$fecha,$cre_observacion);
$act_conse = $ins_credito->act_consecutivo();
}

if($guardar_registro_telefonia&&$guardar_credito){
echo "<script>
	 	alert('Registro de telefonia insertado correctamente.');
	 </script>";
}
else{
echo "<script>
	 	alert('Error al guardar el registro de telefonia, Intentelo de nuevo.');
	 </script>";
}

//LIMPIAR LAS SESSIONES//
unset($_SESSION['tipo_nit']);
unset($_SESSION['nit']);
unset($_SESSION['reg_tel_num_linea']);
unset($_SESSION['plan']);
unset($_SESSION['tipo']);
unset($_SESSION['estado']);
/////////////////////////

?>
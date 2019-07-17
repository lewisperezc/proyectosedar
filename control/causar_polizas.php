<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/contrato.class.php');
include_once('../clases/moviminetos_contables.class.php');
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" type="text/javascript">
function abreFactura(URL)
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
}
</script>
<?php
$instancia_contrato = new contrato();
$ins_movimiento = new movimientos_contables();

$sel_contrato = $_SESSION['id_contrato'];
$hospital = $_SESSION['hos'];
$centro = $instancia_contrato->con_nit_cen_costo($hospital);

/****************************************************************************/
for($i=0;$i<$_POST['polizas'];$i++)
{
  if(!empty($_POST['cau_pol'.$i]))
  {
	$mov_contable=$ins_movimiento->guarCam_movimiento($centro,$sel_contrato,"IMP_".$sel_contrato,$_POST['con_pre_ser_nom_pol_aseguradora'][$i],date('d-m-Y'),$sel_contrato,date('d-m-Y'),1,$_POST['con_pre_ser_pol_porcentaje'][$i],$_POST['con_pre_ser_con_pol_nombre'][$i],0,0,date('m'),$ano);
	$act_poliza = $instancia_contrato->actPoliza($_POST['cau_pol'.$i]);
  }
}
/*****************************************************************************/
?>
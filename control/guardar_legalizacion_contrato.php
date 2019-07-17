<?php
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();

$datos=explode("-",($_POST['otrosi_adicion_seleccionado']));
$contrato_id=$datos[3];
$adi_otrosi=$datos[0];

$i=1;
while($i<=$_POST['can_fil_nue_poliza'])
{
	if(isset($_POST['tip_pol_impuesto'.$i])&&$_POST['tip_pol_impuesto'.$i]==1)//DESCONTABLE
	{
		$gua_datos=$ins_contrato->GuardarLegalizacionDescontable($contrato_id,$_POST['con_pol_nombre'.$i],$_POST['con_nom_pol_aseguradora'.$i],$_POST['con_pol_porcentaje'.$i],0,1,$adi_otrosi,$_POST['obs_pol_impuesto'.$i]);
	}
	elseif(isset($_POST['tip_pol_impuesto'.$i])&&$_POST['tip_pol_impuesto'.$i]==2)//INFORMATIVO
	{
		$gua_datos=$ins_contrato->GuardarLegalizacionInformativo($contrato_id,$_POST['con_pol_nombre'.$i],$_POST['con_nom_pol_aseguradora'.$i],$_POST['con_pol_porcentaje'.$i],$adi_otrosi,$_POST['obs_pol_impuesto'.$i]);
	}
	$i++;
}
if($gua_datos)
	echo "<script>alert('informacion registrada correctamente.')</script>";
else
	echo "<script>alert('Error al registrar la informacion, intentelo de nuevo.')</script>";
?>
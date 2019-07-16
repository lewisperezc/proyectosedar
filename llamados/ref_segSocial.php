<?php
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/centro_de_costos.class.php');
$ins_mov_contable = new movimientos_contables();
$ins_nits = new nits();
$centro = new centro_de_costos();
$nit=$_POST['nit'];
$valor=$_POST['valor'];
$esta = $_POST['esta'];
$tip_seguridad = $ins_nits->tip_seguridad($nit);
$porcen = $ins_nits->porSeguridad($tip_seguridad);
$minimo = $ins_nits->sal_minimo();
$porcentajes = $ins_nits->porcentajes();
$dat_porcentajes = mssql_fetch_array($porcentajes); 
$val_sin=0;
if(round($valor/$minimo,2)>=4)
	$esta=1;
if($esta==1&&$tip_seguridad!=3&&$tip_seguridad!=4)
{
	$resultado = round($valor/$minimo);
	/*if($nit==1290)
		echo $resultado;*/
	if((int)($resultado)==16)
	  {
		$subsistencia = $valor*(0.005+($dat_porcentajes['adic_subsis16']/100));
		$val_solidaridad = ($valor*(0.5/100))+$subsistencia;
		echo round((int)(($valor*($porcen/100))+$val_solidaridad),-2);
	  }
	elseif((int)($resultado)==17)
	  { 
		$subsistencia = $valor*(0.005+($dat_porcentajes['adic_subsis17']/100));
		$val_solidaridad = ($valor*(0.5/100))+$subsistencia;
		echo round((int)(($valor*($porcen/100))+$val_solidaridad),-2);
	  }
	elseif((int)($resultado)==18)
	  { 
		$subsistencia = $valor*(0.005+($dat_porcentajes['adic_subsis18']/100));
		$val_solidaridad = ($valor*(0.5/100))+$subsistencia;
		echo round((int)(($valor*($porcen/100))+$val_solidaridad),-2);
	  }
	elseif((int)($resultado)==19)
	  {
		$subsistencia = $valor*(0.005+($dat_porcentajes['adic_subsis19']/100));
		$val_solidaridad = ($valor*(0.5/100))+$subsistencia;
		echo round((int)(($valor*($porcen/100))+$val_solidaridad),-2);
	  }
	elseif($resultado>=20)
	  {
		$subsistencia = $valor*(0.005+($dat_porcentajes['adic_subsismayor']/100));
		$val_solidaridad = ($valor*(0.5/100))+$subsistencia;
		echo round((int)(($valor*($porcen/100))+$val_solidaridad),-2);
	  }
	else
	{
		$val_solidaridad = round($valor*(0.5/100),-2);
		$val_subsistencia= round($valor*(0.5/100),-2);
		echo round((int)(($valor*($porcen/100))+($val_solidaridad+$val_subsistencia)),-2);
	}
}
else
{
  echo round((int)($valor*($porcen/100)),-2);
}
?>
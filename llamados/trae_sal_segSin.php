<?php
/*include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/centro_de_costos.class.php');

$ins_mov_contable = new movimientos_contables();
$ins_nits = new nits();
$centro = new centro_de_costos();
$lista = $_POST['lista'];
//echo "el valor de la lista es: ".$lista."<br>";
$nits = $ins_mov_contable->rest_nits_seg_social($lista);
$minimo = $ins_nits->sal_minimo();
$i=0;
while($unarray=mssql_fetch_array($nits))
{
	$res[$i]['id_aso'] = $unarray['nit_id'];
	$dat_completo = $ins_nits->consul_nits($res[$i]['id_aso']);
	$datos = mssql_fetch_array($dat_completo);
	$tipo_aso = $datos['nit_tip_segSocial'];
	if($datos['nit_est_id'] == 1 || $datos['nit_est_id'] == 2)
	{
		$res[$i]["iden_aso"] = $datos['nits_num_documento'];
		$res[$i]['aso_sin_sal_nombres'] = $unarray['nombres'];
	
		$val_seg = $ins_nits->por_segSocial_min($res[$i]['id_aso'],$minimo);
		$fondos = $ins_nits->fon_nits($res[$i]['id_aso']);
		$dat_fondos = mssql_fetch_array($fondos);
		
		if($tipo_aso==1)
		{
			$por_eps = 31.30;
			$por_pension = 40.06;
			$por_arp = 6.10;
			$por_caja = 10.02;
		    $por_sena = 0;
			$por_icbf = 0;
		}
		elseif($tipo_aso==2)
		{
			$por_eps = 28.12;
			$por_pension = 35.99;			
			$por_arp = 15.65;
			$por_caja = 9;
			$por_sena = 0;
			$por_icbf = 0;
		}
		elseif($tipo_aso==3)
		{
			$por_eps = 52.21;
			$por_arp = 10.19;
			$por_caja = 16.71;
			$por_sena = 0;
			$por_icbf = 0;
		}
		elseif($tipo_aso==4)
		{
			$por_eps = 43.92;
			$por_arp = 24.46;
			$por_caja = 14.05;
			$por_sena = 0;
			$por_icbf = 0;
		}
		
		//EPS
		if(!empty($dat_fondos['nits_eps']))
		{
			$dat_eps = $ins_nits->consul_nits($dat_fondos['nits_eps']);
			$datos = mssql_fetch_array($dat_eps);
	
			$res[$i]["id_eps"] = $datos['nit_id'];
			$res[$i]["iden_eps"] = $datos['nits_num_documento'];
			$res[$i]["nom_eps"] = $datos['nits_nombres']." ".$datos['nits_documento'];
			$res[$i]["val_eps"] = round($val_seg * ($por_eps/100),-2);
		}
	
		//ARP
		if(!empty($dat_fondos['nits_arp']))
		{
			$dat_arp = $ins_nits->consul_nits($dat_fondos['nits_arp']);
			$datos_arp = mssql_fetch_array($dat_arp);
			
			$res[$i]["id_arp"] = $datos_arp['nit_id'];
			$res[$i]["iden_arp"] = $datos_arp['nits_num_documento'];
			$res[$i]["nom_arp"] = $datos_arp['nits_nombres']." ".$datos_arp['nits_documento'];
			$res[$i]["val_arp"] = round($val_seg * ($por_arp/100),-2);
		}
	
	//PENSIONES
		if(!empty($dat_fondos['nit_pensiones']))
		{
		$dat_pensiones = $ins_nits->consul_nits($dat_fondos['nit_pensiones']);
		$datos_pensiones = mssql_fetch_array($dat_pensiones);
		
		$res[$i]["id_pensiones"] = $datos_pensiones['nit_id'];
		$res[$i]["iden_pensiones"] = $datos_pensiones['nits_num_documento'];
		$res[$i]["nom_pensiones"] = $datos_pensiones['nits_nombres']." ".$datos_pensiones['nits_documento'];
		$res[$i]["val_pension"] = round($val_seg * ($por_pension/100),-2);
		}
	
	//CAJA
		if(!empty($dat_fondos['nit_cajaCompensacion']))
		{
		$dat_caja = $ins_nits->consul_nits($dat_fondos['nit_cajaCompensacion']);
		$datos_caja = mssql_fetch_array($dat_caja);
		
		$res[$i]["id_caja"] = $datos_caja['nit_id'];
		$res[$i]["iden_caja"] = $datos_caja['nits_num_documento'];
		$res[$i]["nom_caja"] = $datos_caja['nits_nombres']." ".$datos_caja['nits_documento'];
		$res[$i]["val_caja"] = round($val_seg * ($por_caja/100),-1);
		}
		
		$sena = $ins_nits->sena();
		$dat_sena = mssql_fetch_array($sena);
		$res[$i]["id_sena"] = $dat_sena['nit_id'];
		$res[$i]["nom_sena"] = $dat_sena['nits_nombres'];
		$res[$i]["iden_sena"] = $dat_sena['nits_num_documento'];
		$res[$i]["val_sena"] = round($val_seg * ($por_sena/100),-2);
		
		$icbf = $ins_nits->icbf();
		$dat_icbf = mssql_fetch_array($icbf);
		$res[$i]["id_icbf"] = $dat_icbf['nit_id'];
		$res[$i]["nom_icbf"] = $dat_icbf['nits_nombres'];
		$res[$i]["iden_icbf"] = $dat_icbf['nits_num_documento'];
		$res[$i]["val_icbf"] = round($val_seg * ($por_icbf/100),-2);
		$i++;
	}
}
echo json_encode($res);*/
?>
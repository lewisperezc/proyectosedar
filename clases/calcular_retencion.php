<?php
  include_once('../conexion/conexion.php');
	$dinero=12635852;
	$base = ($dinero - ($dinero*8/100))*75/100;
	$uvt = 26841;
	$veces = round($base/$uvt,2);
	echo $veces."<br>";
/************************************IMAS******************************************/
	if($veces>=0 && $veces<=95)
		$imas=0;
	elseif($veces>=96 && $veces<=150)
	{
		$operacion=$veces-95;//$resultado=numero de veces que está el uvt en el ingreso base menos 95 uvt
		$res_uvt=($operacion*19)/100;
		$imas=$res_uvt*$uvt;
	}
	elseif($veces>=151 && $veces<=360)
	{
		$operacion=$veces-150;
		$tot_uvt = $operacion*(28/100)+10;
		$imas = $tot_uvt*$uvt;
	}
	elseif($veces>=361)
	{
		$operacion=$veces-360;
		$res_uvt=$operacion*(33/100)+69;
		$imas=$res_uvt*$uvt;
	}
	echo $imas."<br>";
/**************************************IMAN***************************************/
	if($veces<=177)
		$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces-3 AND $veces+3";
	elseif($veces>177 && $veces<340)
		$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces-7 AND $veces+7";
	elseif($veces>340)
		$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces-17 AND $veces+17";
	$query = mssql_query($sql);
	$i=0;
	while($row=mssql_fetch_array($query))
	{
		$resul['uvt'][$i]=$row['ima_uvt'];
		$resul['pesos'][$i]=$row['ima_ret_pesos'];
		$menor = $veces-$row['ima_uvt'];
		if($menor<0)
		  $resul['menor'][$i]=($menor*-1);
		else
		   $resul['menor'][$i]=$menor;
		$i++;
	}

	$mayor=0;
	$pos=0;
	$temp=0;
	for($i=0;$i<sizeof($resul);$i++)
	 {
	 	$temp = $resul['menor'][$i];
	 	if($mayor<$temp)
	 	{
	 		$mayor=$temp;
	 		$pos = $i;
	 	}
	 }

	$retencion=$resul['pesos'][$pos];
	echo $retencion;
	
	//echo "el total: ".max($imas,$retencion);
?>
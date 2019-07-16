<?php session_start();
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
$ins_cuentas=new cuenta();
$ins_sal_cuentas= new insercion();
$ins_credito=new credito();
$eltipo=$_POST['eltipo'];
$elvalor=$_POST['elvalor'];
$anio=$_SESSION['elaniocontable'];
$elnit=$_POST['elnit'];

$ins_mov_contable=new movimientos_contables();


//$sal_anterior=0;

if(substr($elvalor,0,1)==1||substr($elvalor,0,1)==5||substr($elvalor,0,1)==6)
	$tipo_cuen=1;
else
	$tipo_cuen=2;

if($eltipo==1)
{
	//echo "entra por este";
	//POR CUENTA
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorCueAnio($elvalor,$anio);
	//$sal_anterior = $ins_sal_cuentas->sal_cueAno($elvalor,($anio-1),$tipo_cuen);
	$sal_anterior=$ins_mov_contable->SaldoAnteriorCuenta($elvalor,$anio);
	$result=$ins_cuentas->ConCueEsMayPorCueId($elvalor);
	if($result=='si')
		$esmayor=1;
	elseif($result=='no')
		$esmayor=2;
}
elseif($eltipo==2)
{
	//echo "Entra por aca: ".$elnit."___".($anio-1)."___".$elvalor."___".$tipo_cuen;
	//POR NIT
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorNitAnio($elnit,$anio,$elvalor);
	$sal_anterior=$ins_mov_contable->SaldoAnteriorCuentaTercero($elvalor,$elnit,$anio);
	//$sal_anterior = $ins_sal_cuentas->sal_cueAnoTerc($elnit,($anio-1),$elvalor,$tipo_cuen);
	$esmayor=0;
}

/*
elseif($eltipo==3)
{
	//POR CENTRO
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorCenCosAnio($elvalor,$anio);
	$esmayor=0;
}
*/

elseif($eltipo==4)
{
	//SALDOS CREDITOS
	$cuenta='13';
	$con_sal_cuentas=$ins_sal_cuentas->conSalCreditoSinInteres($elvalor,$anio,$cuenta);
	$esmayor=0;
}
$res="";
$i=1;
$temp_mes=array();
$saldo=0;
$debito=0;
$credito=0;

while($row=mssql_fetch_array($con_sal_cuentas))
{
	//if($i<=12)
	//{
		$temp_mes[$i]=$row['mov_mes_contable'];
		
		
			if($eltipo==1)//POR CUENTA
				$saldo=$ins_mov_contable->SaldoAnteriorCuenta($elvalor,($anio));
			elseif($eltipo==2)
				$saldo=$ins_mov_contable->SaldoAnteriorCuentaTercero($elvalor,$elnit,($anio));
		
	
			if($row['mov_tipo']==1)
			{
			  $res[$row['mov_mes_contable']]['debito']=$row['valor'];
			  $debito+=$row['valor'];
			}
			else
			{
			  $res[$row['mov_mes_contable']]['credito']=$row['valor'];
			  $credito+=$row['valor'];
			}
			if(substr($elvalor,0,1)==1||substr($elvalor,0,1)==5||substr($elvalor,0,1)==6)
			   $saldo=$sal_anterior+$debito-$credito;
			else
			   $saldo=$sal_anterior+$credito-$debito;
			$res[$row['mov_mes_contable']]['saldo']=$saldo;
	//}
	$i++;
}

for($i=1;$i<=13;$i++)
{
	if(!in_array($i,$temp_mes))
	{
	 $res[$i]['credito']=0;
	 $res[$i]['debito']=0;
	 $res[$i]['saldo']=0;
	 $res[$i]['saldo_anterior']=0;
	}
}
$res[1]['saldo_anterior']=$sal_anterior;
echo json_encode($res);
?>
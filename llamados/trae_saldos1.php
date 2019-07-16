<?php session_start();
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/cuenta.class.php');
$ins_cuentas=new cuenta();
$ins_sal_cuentas= new insercion();
$eltipo=$_POST['eltipo'];
$elvalor=$_POST['elvalor'];
$anio=$_SESSION['elaniocontable'];
if($eltipo==1)
{
	//POR CUENTA
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorCueAnio($elvalor,$anio);
	$result=$ins_cuentas->ConCueEsMayPorCueId($elvalor);
	if($result=='si')
		$esmayor=1;
	elseif($result=='no')
		$esmayor=2;
}
elseif($eltipo==2)
{
	//POR NIT
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorNitAnio($elvalor,$anio);
	$esmayor=0;
}
elseif($eltipo==3)
{
	//POR CENTRO
	$con_sal_cuentas=$ins_sal_cuentas->ConSalPorCenCosAnio($elvalor,$anio);
	$esmayor=0;
}
$res="";
$i=0;
$saldo=0;
$res.="<thead></thead>";
$res.="<tr><td><b>Mes</b></td><td><b>Debito</b></td><td><b>Credito</b></td><td><b>Saldo</b></td></tr>";
$res.="<tr class='gradeA' align='center'>";
while($row=mssql_fetch_array($con_sal_cuentas))
{
	
	if($i%2==0)
	{
		$res.="<td>
		<a href='Javascript:void(0)' onClick='VentanaEmergente($eltipo,$esmayor,$elvalor,$row[mov_mes_contable],$anio)'>".$row['mes_nombre']."</a></td>";
        $res.="<td><input type='text' name='elmes' id='elmes' value='".$row['valor']."'/></td>";
		$res.=$saldo+=$row['valor'];
    }
	else
	{
		$res.="<td><input type='text' name='elmes' id='elmes' value='".$row['valor']."'/></td>";
		$res.=$saldo+=$row['valor'];
		$res.="<td><input type='text' name='elmes' id='elmes' value='".$saldo."'/></td></tr>";
		$res.="<tr class='gradeA' align='center'>";
	 }
	$i++;
}
$res.="<tbody></tbody>";
echo $res;
?>
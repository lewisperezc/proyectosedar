<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=prueba_terceros");
header("Pragma: no-cache");
header("Expires: 0");

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/mes_contable.class.php');

$nit = new nits();
$varios=new varios();
$mes_contable = new mes_contable();
$eltipo=$_GET['eltipo'];
if($eltipo==1)
{
	$sql = "EXECUTE leg_contratos";
	$query = mssql_query($sql);
}
$elvalor=$_GET['elval'];
if($eltipo==2)
{
	$sql="EXECUTE leg_contratosCiudad $elvalor";
	$query = mssql_query($sql);
}
if($eltipo==3)
{
	$sql="EXECUTE leg_contratosCentro $elvalor";
	$query = mssql_query($sql);
}
$fec_dia = date('d-m-Y');
echo "<table border='1'>";
echo "<tr><th>NIT</th><th>TERCERO</th><th>CONTRATO</th><th>VALOR CONTRATO</th><th>FECHA INICIO</th><th>FECHA FIN</th><th>VALOR LEGALIZACION</th><th>VALOR DESCONTADO</th><th>PORCENTAJE DE LAGALIZACION</th></tr>";
if($query)
{
	$sql_rep = "SELECT * FROM reportes";
	$query_rep = mssql_query($sql_rep);
	$num_rows = mssql_num_rows($query_rep);
	while($row=mssql_fetch_array($query_rep))
	{
		if($row['cuatro']==0)
			$row['cuatro']=1;
		$porcentaje = ($row['nueve']+$row['ocho'])/$row['cuatro'];
		echo "<tr>";
		echo "<td>".$row['diez']."</td><td>".$row['once']."</td><td>".$row['tres']."</td><td>".number_format((float)$row['cuatro'],0)."</td><td>".$row['seis']."</td><td>".$row['siete']."</td><td>".number_format((float)$row['ocho'],0)."</td><td>".number_format((float)$row['nueve'],0)."</td><td>".number_format((float)$porcentaje,4)."</td>";
		echo "</tr>";
	}
	$i++;
}
echo "</table>";
?>
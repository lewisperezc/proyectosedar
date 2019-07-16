<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=prueba_terceros");
header("Pragma: no-cache");
header("Expires: 0");

include('../pdf/class.ezpdf.php');
$mes = $_GET['mes_sele'];
$ano = $_GET['ano_sele'];

include_once('../conexion/conexion.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
$cuenta = new cuenta();
$mov_contable = new movimientos_contables();
$cuen_movimientos = $mov_contable->cuen_movimi();
$nit = new nits();

$sql = "EXECUTE bal_tercero $mes,$ano";
$query = mssql_query($sql);
echo "<table border='1'>";
echo "<tr><td colspan='6'>Balance de Prueba</td></tr>";
echo "<tr><td>Cuenta</td><td>Descripcion</td><td>NIT</td><td>Tercero</td><td>Saldo Inicial</td><td>Debito</td><td>Credito</td><td>Total</td></tr>";
if($query)
{
	$balance = "SELECT * FROM reportes";
	$que_balance = mssql_query($balance);
	$i=1;
	while($row = mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
		  echo "<tr>";
		  echo "<td>".$row['uno']."</td>";
		  echo "<td>".substr($row['dos'],0,25)."</td>";
		  if($row['siete']!=0)
		  {
			  $dat_nits = $nit->consul_nits($row['siete']);
			  $datos = mssql_fetch_array($dat_nits);
			  echo "<td>".$datos['nits_num_documento']."</td>";
		      echo "<td>".$datos['nits_nombres']." ".$datos['nits_apellidos']."</td>";
		  }
		  else
			  echo "<td></td><td></td>";
		  echo "<td>".number_format($row['tres'],2)."</td>";
		  echo "<td>".number_format($row['cinco'],2)."</td>";
		  if(substr($row['uno'],0,1) == 1 || substr($row['uno'],0,1) == 5 || substr($row['uno'],0,1) == 6)
			echo "<td>".number_format($row['tres']-$row['cinco'],2)."</td>";
		  else
			echo "<td>".number_format($row['cinco']-$row['tres'],2)."</td>";
		  $i++;
		 echo "</tr>";
	  }
	}
}
echo "</table>";
?>
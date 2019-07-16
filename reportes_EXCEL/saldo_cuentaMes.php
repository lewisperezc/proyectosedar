<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=prueba_terceros");
header("Pragma: no-cache");
header("Expires: 0");

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/moviminetos_contables.class.php');

$cuenta_inicial=$_POST['cuenta_inicial'];
$ano_ini=$_POST['ano_ini'];
$mes_ini=$_POST['mes_ini'];

$ins_nit = new nits();
$ins_sal_cuentas = new insercion();
$ins_mov_contable=new movimientos_contables();
$sal_cuenta=$ins_sal_cuentas->ConsultarSaldoCuentaAnioMes($cuenta_inicial,$ano_ini,$mes_ini);

echo "<table border='1'>";
echo "<tr>";
	echo "<th>DOCUMENTO</th>";
	echo "<th>NOMBRES</th>";
	echo "<th>APELLIDOS</th>";
	echo "<th>SALDO INICIAL</th>";
	echo "<th>DEBITO</th>";
	echo "<th>CREDITO</th>";
	echo "<th>SALDO FINAL</th>";
while($row=mssql_fetch_array($sal_cuenta))
	{
		echo "<tr>";
		echo "<td>".$row['cuatro']."</td>";
		echo "<td>".$row['dos']."</td>";
		echo "<td>".$row['tres']."</td>";
		echo "<td>".round($row['seis'],0)."</td>";
		echo "<td>".round($row['siete'],0)."</td>";
		echo "<td>".round($row['ocho'],0)."</td>";
		echo "<td>".round($row['nueve'],0)."</td>";
		echo "</tr>";
	}
echo "</table>";
?>
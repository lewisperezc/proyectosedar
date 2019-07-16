<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=balance");
header("Pragma: no-cache");
header("Expires: 0");

include('../pdf/class.ezpdf.php');
$mes = $_GET['mes_sele'];
$ano = $_GET['ano_sele'];

include_once('../conexion/conexion.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/saldos.class.php');
$cuenta = new cuenta();
$mov_contable = new movimientos_contables();
$saldos = new saldos();
$cuen_movimientos = $mov_contable->cuen_movimi();

$sql = "EXECUTE bal_prueba $mes,$ano";
$query = mssql_query($sql);
echo "<table border='1'>";
echo "<tr><td colspan='6'>Balance de Prueba</td></tr>";
echo "<tr><td>Cuenta</td><td>Descripcion</td><td>Saldo inicial</td><td>Debito</td><td>Credito</td><td>Total</td></tr>";
if($query)
{
	$balance = "SELECT * FROM reportes ORDER BY uno asc";
	 $que_balance = mssql_query($balance);
	 while($row=mssql_fetch_array($que_balance))
		{
		  if($row['tres']!=0 || $row['cinco']!=0)
		  {
		  	 $debito+=$row['tres'];
		  	 $credito+=$row['cinco'];
			 $saldo=$saldos->salInicial($mes,$ano,$row['uno']);
			 $cont=strlen($row['uno']);
			 if($cont==6)
			 {
			 	$saldo_inicial+=$saldo;
			 	$deb_final+=$row['tres'];
			 	$cre_final+=$row['cinco'];
			 }
			 while($cont>0)
			  {
			  	$cuen[$temp]=substr($row['uno'],0,$cont);
				$val[$temp]=$saldo;
				$cont-=1;
				$temp++;
			  }
		  }
		}

	  $k=0;
	  for($l=0;$l<sizeof($cuen);$l++)
		{
		 if($l==0)
		 {
			$cuen_orga[$k] = $cuen[$l];
			$val_orga[$k] = $val[$l];
			$k++;
		 }
		else
		 {
			$temp=0;
			for($p=0;$p<=$k;$p++)
			{
				if($cuen_orga[$p]==$cuen[$l])
				 { 
				  $val_orga[$p] += $val[$l];
				  $temp=1;
				  break;
				 }
			}
			if($temp==0)
			{
		 	 $cuen_orga[$k] = $cuen[$l];
			 $val_orga[$k] = $val[$l];
			 $k++;
			}
		 }
	    }

	$balance = "SELECT * FROM reportes";
	$que_balance = mssql_query($balance);
	$i=1;
	while($row = mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
		  echo "<tr>";
		  echo "<td>".$row['uno']."</td>";
		  echo "<td>".substr($row['dos'],0,40)."</td>";
		  for($p=0;$p<sizeof($cuen_orga);$p++)
		  {
			if($row['uno']==$cuen_orga[$p])
			 {
				$saldo = $val_orga[$p];
				break;
			 }
		  }
		  echo "<td>".number_format($saldo,2)."</td>";
		  echo "<td>".number_format($row['tres'],2)."</td>";
		  echo "<td>".number_format($row['cinco'],2)."</td>";
		  if(substr($row['uno'],0,1) == 1 || substr($row['uno'],0,1) == 5 || substr($row['uno'],0,1) == 6)
			echo "<td>".number_format($saldo+($row['tres']-$row['cinco']),2)."</td>";
		  else
			echo "<td>".number_format($saldo+($row['cinco']-$row['tres']),2)."</td>";
		  $i++;
		 echo "</tr>";
	  }
	}
}
echo "</table>";
?>
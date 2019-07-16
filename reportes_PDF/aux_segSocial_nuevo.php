<?php 
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/moviminetos_contables.class.php');

$ins_mov_contable=new movimientos_contables();

$nit = new nits();
$mes_contable = new mes_contable();
$saldos = new saldos();
$documento= $_POST["documento"];

///////////////INICIO SALDO ANTERIOR//////////////
$cuenta='13250594';

$nit_id=$nit->busNit($documento);
$dat_nit=$nit->contodatnitpordocumento($documento);

$saldo_ant=$ins_mov_contable->SaldoAnteriorCuentaTercero($cuenta,$nit_id,$_SESSION['elaniocontable']);
///////////////FIN SALDO ANTERIOR//////////////

//$pdf->SetY(52);$pdf->SetX(35);$pdf->Cell(21,8,"NOMBRES");
//$pdf->SetY(49);$pdf->SetX(125);$pdf->Cell(21,8,"SALDO INICIAL ".($_SESSION['elaniocontable'])." = ".number_format(($saldo_anterior)));


$cue_seg_soc_afiliado='13250594';

$sql="EXECUTE seguridad_social_nuevo '$documento',$_SESSION[elaniocontable],'$cue_seg_soc_afiliado'";
$query = mssql_query($sql);

if($_SESSION[elaniocontable]==2016)
{
	$sql_nat="SELECT mov_tipo FROM movimientos_contables WHERE mov_compro='CIE-2017' AND mov_cuent='$cuenta'
	AND mov_nit_tercero='$nit_id'";
	//echo $sql_nat;
	$query_nat = mssql_query($sql_nat);
	$eje_cuent=mssql_fetch_array($query_nat);

	if($eje_cuent['mov_tipo']==2)//NEGATIVO
		$saldo_anterior=$saldo_ant*(-1);
	else
		$saldo_anterior=$saldo_ant;
}
else {
	$saldo_anterior=$saldo_ant;
}

$html="";


$html.='<table border="0">';
	$html.='<tr><th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="760" height="210" alt="Logo Sedar" /></th>';
	$html.='<tr><th colspan="2">NOMBRES: '.$dat_nit["nits_num_documento"]." - ".$dat_nit[nits_nombres]." ".$dat_nit[nits_apellidos].'</th>';
	$html.='<th>FECHA: '.date('d-m-Y').'</th>';
	$html.='<th colspan="3">SALDO INICIAL '.$_SESSION[elaniocontable].":   ".number_format($saldo_anterior).'</th></tr>';
	$html.='<tr>';
	$html.='<th>Fecha</th>';
	$html.='<th>Concepto</th>';
	$html.='<th>IBC</th>';
	$html.='<th>Pagado a los fondos</th>';
	$html.='<th>Descontado de compensaci&oacute;n</th>';
	$html.='<th>Saldo</th>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th colspan="6"><hr></th>';
	$html.='</tr>';

$saldo=0;
if($query)
{
	$j=1;
	while($j<=12)
	{
			$tot_mes_debito=0;
			$tot_mes_credito=0;
			$tot_mes_saldo=0;
			$val_ibc=0;
			
			$balance = "SELECT * FROM reportes WHERE siete=$j ORDER BY CAST (siete AS INT) ASC,CAST(seis AS INT) ASC";
			$que_balance=mssql_query($balance);
			while($res_balance=mssql_fetch_array($que_balance))
			{
			$html.='<tr>';
			$html.='<td>'.$res_balance['cuatro'].'</td>';
			if($res_balance['seis']==1)//PAGO A LOS FONDOS
			{
				if($j==1)
				{
					$mes_servicio=11;
					$mes_causado=12;
					$anio_causado=$_SESSION['elaniocontable']-1;
					$anio_servicio=$_SESSION['elaniocontable']-1;
				}
					
				elseif($j==2)
				{
					$mes_servicio=12;
					$mes_causado=1;
					$anio_causado=$_SESSION['elaniocontable'];
					$anio_servicio=$_SESSION['elaniocontable']-1;
				}
				else
				{
					$mes_servicio=$j-2;
					$mes_causado=$j-1;
					$anio_servicio=$_SESSION['elaniocontable'];
					$anio_servicio=$_SESSION['elaniocontable'];
					
				}
				$mes_pagado=$j;
				$anio_pagado=$_SESSION['elaniocontable'];
				
				$que_ibc="SELECT dbo.ObtenerIBCPagoSeguridadSocial('$nit_id',$mes_pagado,$anio_pagado) AS ibc";
				//echo $que_ibc."<br>"; 
				$eje_ibc=mssql_query($que_ibc);
				$res_ibc=mssql_fetch_array($eje_ibc);
				$val_ibc=number_format(round($res_ibc["ibc"],-3));
				
				$html.='<td>Pago a los Fondos (Salud, Pension, ARL)</td>';
				if($val_ibc==0)
					$val_ibc='';
				
				$html.='<td>'.$val_ibc.'</td>';
				$html.='<td>'.number_format($res_balance["cinco"]).'</td>';
				$html.='<td>'.number_format(0).'</td>';
				$debito=$res_balance["cinco"];
				$credito=0;
				$tot_mes_debito+=$debito;
			}
			else//DESCONTO DE COMPENSACION
			{
				$nombre_mes=$mes_contable->nomMes($res_balance["once"]);
				$html.='<td>Descuento de compensaci&oacute;n Factura '.$res_balance["diez"]." - ".$nombre_mes.'</td>';
				$html.='<td></td>';
				$html.='<td>'.number_format(0).'</td>';
				$html.='<td>'.number_format($res_balance["cinco"]).'</td>';
				$credito=$res_balance["cinco"];
				$debito=0;
				$tot_mes_credito+=$credito;
			}
			if($i==0)
				$saldo+=$saldo_anterior+$debito-$credito;
			else
				$saldo+=$debito-$credito;
			$html.='<td>'.number_format($saldo).'</td>';
			$html.='</tr>';
			$i++;
			}
			
			$nombre_mes=$mes_contable->nomMes($j);
			
			$html.="<tr style='font-weight:bold;'>";
			$html.="<td>TOTAL MES DE ".strtoupper($nombre_mes)."</td>";
			$html.="<td colspan='2'><hr></td>";
			$html.="<td>".number_format($tot_mes_debito)."</td>";
			$html.="<td>".number_format($tot_mes_credito)."</td>";
			$html.="<td>".number_format($saldo)."</td>";
			$html.="</tr>";
		$j++;
		}
}
$html.='</table>';
$_SESSION['informacion']=$html;
//echo $_SESSION['informacion'];
echo '<script>location.href="aux_segSocial_nuevo_1.php";</script>';
?>
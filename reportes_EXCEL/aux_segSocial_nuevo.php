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
//$cuenta_1='13250591';
$cuenta_1='13250594';

$nit_id=$nit->busNit($documento);
$dat_nit=$nit->contodatnitpordocumento($documento);


$saldo_anterior=$ins_mov_contable->SaldoAnteriorCuentaTercero($cuenta_1,$nit_id,$_SESSION['elaniocontable']);

///////////////FIN SALDO ANTERIOR//////////////

//$pdf->SetY(52);$pdf->SetX(35);$pdf->Cell(21,8,"NOMBRES");
//$pdf->SetY(49);$pdf->SetX(125);$pdf->Cell(21,8,"SALDO INICIAL ".($_SESSION['elaniocontable'])." = ".number_format(($saldo_anterior)));

$cue_seg_soc_afiliado_1='13250594';
//$cue_seg_soc_afiliado_2='13250591';

/*
@cedula VARCHAR(100),@anio INT,
					  @cue_seg_soc_afiliado_1 VARCHAR(100),@cue_seg_soc_afiliado_2 VARCHAR(100) 
*/

$sql="EXECUTE seguridad_social_nuevo '$documento',$_SESSION[elaniocontable],'$cue_seg_soc_afiliado_1'";
$query = mssql_query($sql);

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=AuxCueTercero");
header("Pragma: no-cache");
header("Expires: 0");

$html="";

$html.='<table border="1">';
	$html.='<tr><th colspan="3">NOMBRES: '.$dat_nit["nits_num_documento"]." - ".$dat_nit[nits_nombres]." ".$dat_nit[nits_apellidos].'</th>';
	$html.='<th colspan="3">SALDO INICIAL '.$_SESSION[elaniocontable].":   ".number_format($saldo_anterior).'</th></tr>';
	$html.='<tr>';
	$html.='<th>Fecha</th>';
	$html.='<th>Concepto</th>';
	$html.='<th>IBC</th>';
	$html.='<th>Pagado a los fondos</th>';
	$html.='<th>Descontado de compensaci&oacute;n</th>';
	$html.='<th>Saldo</th>';
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
				}
				$mes_pagado=$j;
				$anio_pagado=$_SESSION['elaniocontable'];
				
				$que_ibc="SELECT dbo.ObtenerIBCPagoSeguridadSocial('$mes_servicio','$anio_servicio','$nit_id',$mes_causado,$anio_causado,$mes_pagado,$anio_pagado) AS ibc";
				//echo $que_ibc."<br>"; 
				$eje_ibc=mssql_query($que_ibc);
				$res_ibc=mssql_fetch_array($eje_ibc);
				$val_ibc=number_format($res_ibc["ibc"]);
				
				
				$html.='<td>Pago a los Fondos (Salud, Pension, ARL)</td>';
				if($val_ibc==0)
					$val_ibc='NA';
				$html.='<td>'.$val_ibc.'</td>';
				$html.='<td>'.number_format($res_balance["cinco"]).'</td>';
				$html.='<td>'.number_format(0).'</td>';
				$debito=$res_balance["cinco"];
				$credito=0;
				$tot_mes_debito+=$debito;
			}
			else//DEWSCONTO DE COMPENSACION
			{
				$nombre_mes=$mes_contable->nomMes($res_balance["once"]);
				$html.='<td>Descuento de compensaci&oacute;n Factura '.$res_balance["diez"]." - ".$nombre_mes.'</td>';
				$html.='<td>NA</td>';
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
			$html.="<td colspan='3'>TOTAL MES DE ".strtoupper($nombre_mes)."</td>";
			$html.="<td>".number_format($tot_mes_debito)."</td>";
			$html.="<td>".number_format($tot_mes_credito)."</td>";
			$html.="<td>".number_format($saldo)."</td>";
			$html.="</tr>";
		$j++;
		}
}
$html.='</table>';
echo $html;
//echo '<script>location.href="aux_segSocial_nuevo_1.php";</script>';
?>
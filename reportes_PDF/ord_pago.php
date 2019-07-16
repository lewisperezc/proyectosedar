<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/reporte.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/mes_contable.class.php');

$nit = new nits();
$reporte = new reporte();
$instancia_factura = new factura();
$mes = new mes_contable();
//valor=748&sigla=PAG-COM-1200&elrecibo=969
$nomina=$_GET['valor'];
//echo "la factura es:".$nomina."<br>";
$valfactura=$_GET['valfactura'];
$recibo=$_GET['elrecibo'];
$conse_recibo=$_GET['conse_recibo'];
$tipo_reporte=$_GET['tipo_reporte'];


$html="";

$html.='<table border="0" style="font-size:12px;width:100%">';
$html.='<tr><th><img src="../imagenes/logo_sedar_dentro.png" width="620" height="120" alt="Logo Sedar" /></th>';
$html.='</table>';


if($tipo_reporte==1)//CUANDO SE PAGA
{
	
	//echo "por aqui";
  	$que_1="SELECT rec_caj_id FROM recibo_caja WHERE rec_caj_factura='$nomina' AND rec_caj_id='$recibo'";
	//echo $que_1."<br>";
	$eje_1=mssql_query($que_1);
	$res_1=mssql_fetch_array($eje_1);
	$los_id=$res_1['rec_caj_id'];
	
	$que_2="SELECT DISTINCT mov_cent_costo,mov_compro
	FROM movimientos_contables WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = '$nomina'
	AND mov_doc_numer='$los_id'";
	//echo $que_2."<br>";
	$eje_2=mssql_query($que_2);
	$res_2=mssql_fetch_array($eje_2);
	$la_nomina=$res_2['mov_compro'];
	$el_centro=$res_2['mov_cent_costo'];
  	$dat_reportes = $reporte->ord_pago($la_nomina,$recibo);
  	$i=1;
  	$dat_rep_no = 0;
	
	
}

else//CUANDO SE CONSULTA
{
	//echo "entra por aqui";
	$que_1="SELECT rec_caj_id FROM recibo_caja WHERE rec_caj_factura='$nomina' AND rec_caj_id='$recibo'";
	//echo $que_1."<br>";
	$eje_1=mssql_query($que_1);
	$res_1=mssql_fetch_array($eje_1);
	$los_id=$res_1['rec_caj_id'];
	
	$que_2="SELECT DISTINCT mov_cent_costo,mov_compro FROM movimientos_contables
	WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = '$nomina' AND mov_doc_numer='$los_id'";
	//echo $que_2."<br>";
	$eje_2=mssql_query($que_2);
	$res_2=mssql_fetch_array($eje_2);
	$la_nomina=$res_2['mov_compro'];
	$el_centro=$res_2['mov_cent_costo'];
	
	
	
	$nomina = $_GET['valor'];
	if($recibo!="")
	{
		
		$datos_reportes = "SELECT mv.mov_compro,mv.mov_nume,mv.mov_fec_elabo,mv.mov_nit_tercero,cc.cen_cos_nombre,nit.nit_id,
					nit.nits_num_documento,nit.nits_nombres,nit.nits_apellidos,nit.nits_num_cue_bancaria,nit.nits_ban_id,
					ban.banco,mv.mov_valor,rc.rec_caj_id,rc.rec_caj_descripcion,fac.fac_consecutivo,fac.fac_val_total,
					rc.rec_caj_monto,mv.mov_tipo
					FROM movimientos_contables mv INNER JOIN factura fac on mv.mov_nume = fac.fac_id 
					LEFT JOIN recibo_caja rc on rc.rec_caj_factura=fac.fac_id 
					INNER JOIN nits nit on nit.nit_id=mv.mov_nit_tercero
					INNER JOIN bancos ban on ban.cod_banco=nits_ban_id
					INNER JOIN centros_costo cc on cc.cen_cos_id=mv.mov_cent_costo
					WHERE mov_compro='$la_nomina' AND mov_nume = $nomina AND mov_cuent = '25051001'
					AND mov_doc_numer='$recibo'
					--AND mov_tipo=2
					AND nit.nit_est_id=1 AND rc.rec_caj_id=$recibo
					ORDER BY nits_apellidos";
					//echo $datos_reportes."<br>";
					
		$dat_rep = "SELECT DISTINCT mv.mov_compro,mv.mov_nume,mv.mov_fec_elabo,mv.mov_nit_tercero,cc.cen_cos_nombre,nit.nit_id,
					nit.nits_num_documento,nit.nits_nombres,nit.nits_apellidos,nit.nits_num_cue_bancaria,nit.nits_ban_id,
					ban.banco,mv.mov_valor,rc.rec_caj_id,rc.rec_caj_descripcion,fac.fac_consecutivo,fac.fac_val_total,
					rc.rec_caj_monto,mv.mov_tipo
					FROM movimientos_contables mv INNER JOIN factura fac on mv.mov_nume = fac.fac_id 
					INNER JOIN recibo_caja rc on rc.rec_caj_factura=fac.fac_id 
					INNER JOIN nits nit on nit.nit_id=mv.mov_nit_tercero
					INNER JOIN bancos ban on ban.cod_banco=nits_ban_id
					INNER JOIN centros_costo cc on cc.cen_cos_id=mv.mov_cent_costo
					WHERE mov_compro='$la_nomina' AND mov_nume = $nomina AND mov_cuent = '25051001'
					AND mov_doc_numer='$recibo'
					--AND mov_tipo=2
					AND nit.nit_est_id=3 AND rc.rec_caj_id=$recibo
					ORDER BY nits_apellidos";
					//echo $dat_rep;
	}
	else
	{
		$datos_reportes = "SELECT mv.mov_compro,mv.mov_nume,mv.mov_fec_elabo,mv.mov_nit_tercero,cc.cen_cos_nombre,nit.nit_id,
					nit.nits_num_documento,nit.nits_nombres,nit.nits_apellidos,nit.nits_num_cue_bancaria,nit.nits_ban_id,
					ban.banco,mv.mov_valor,rc.rec_caj_id,rc.rec_caj_descripcion,fac.fac_consecutivo,fac.fac_val_total,
					rc.rec_caj_monto,mv.mov_tipo
					FROM movimientos_contables mv INNER JOIN factura fac on mv.mov_nume = fac.fac_id 
					LEFT JOIN recibo_caja rc on rc.rec_caj_factura=fac.fac_id 
					INNER JOIN nits nit on nit.nit_id=mv.mov_nit_tercero
					INNER JOIN bancos ban on ban.cod_banco=nits_ban_id
					INNER JOIN centros_costo cc on cc.cen_cos_id=mv.mov_cent_costo
					WHERE mov_compro='$la_nomina' AND mov_nume = $nomina AND mov_cuent = '25051001'
					AND mov_doc_numer='$recibo'
					--AND mov_tipo=2
					AND nit.nit_est_id=1 ORDER BY nits_apellidos";
		$dat_rep = "SELECT DISTINCT mv.mov_compro,mv.mov_nume,mv.mov_fec_elabo,mv.mov_nit_tercero,cc.cen_cos_nombre,nit.nit_id,
					nit.nits_num_documento,nit.nits_nombres,nit.nits_apellidos,nit.nits_num_cue_bancaria,nit.nits_ban_id,
					ban.banco,mv.mov_valor,rc.rec_caj_id,rc.rec_caj_descripcion,fac.fac_consecutivo,fac.fac_val_total,
					rc.rec_caj_monto,mv.mov_tipo
					FROM movimientos_contables mv INNER JOIN factura fac on mv.mov_nume = fac.fac_id 
					INNER JOIN recibo_caja rc on rc.rec_caj_factura=fac.fac_id 
					INNER JOIN nits nit on nit.nit_id=mv.mov_nit_tercero
					INNER JOIN bancos ban on ban.cod_banco=nits_ban_id
					INNER JOIN centros_costo cc on cc.cen_cos_id=mv.mov_cent_costo
					WHERE mov_compro='$la_nomina' AND mov_nume = $nomina AND mov_cuent = '25051001'
					AND mov_doc_numer='$recibo'
					--AND mov_tipo=2
					AND nit.nit_est_id=3 ORDER BY nits_apellidos";
	}
	//echo $datos_reportes."<br>";
	//echo $dat_rep;
	$dat_reportes = mssql_query($datos_reportes);
	$dat_rep_no = mssql_query($dat_rep);
}

$total=0;$banco;$i=1;$ban=1;
$can_lineas=0;

$html.='<table border="0" style="font-size:10px;width:100%">';

$html.='<tr><td colspan="4"><hr></td></tr>';
while($row=mssql_fetch_array($dat_reportes))
{
	$k=0;
	$can_lineas++;
	$temp=0;
	if($i==1)
	{
			$html.='<tr>';
				$html.='<td><b>FECHA: </b>'.$row['mov_fec_elabo'].'</td>';
				$html.='<td><b>CENTRO DE COSTO: </b>'.$row['cen_cos_nombre'].'</td>';
				$html.='<td><b>FACTURA No: </b>'.$row['fac_consecutivo'].'</td>';
				$html.='<td><b>PERIODO: </b>'.strtoupper($mes->periodo($row['fac_consecutivo'])).'</td>';
			$html.='</tr>';
			$k=1;
		
		if($row['rec_caj_id']!="")
		{
		  	//echo "Entra por el if";
		  	//$dat_recibo = $reporte->des_recibo($row['rec_caj_id']);
			$tipos="1,2,3,4,5,6,7,8,9,10,11";
		  	//$dat_recibo_2 = $reporte->des_recibo_2($row['rec_caj_id'],$tipos);
			$dat_recibo = $reporte->des_recibo_2($row['rec_caj_id'],$tipos);
		}
		else
		{
		  //echo "Entra por el else";
		  $dat_recibo = $instancia_factura->legFactura($row['mov_nume'],$row['rec_caj_id']);
		}
		
		$html.='</table>';
		
		$html.='<table border="0" style="font-size:12px;">';
		
		$html.='<tr>';
			$html.='<td style="text-align:left";><b>DATOS FACTURACION: </b></td>';
			
			if(($row['rec_caj_monto']+$dat_recibo_2+$dat_recibo)>$_GET['valfactura'])
				$html.='<td style="text-align:right";>$ '.number_format($row['rec_caj_monto'],0).'</td>';
			else
				$html.='<td style="text-align:right";>$ '.number_format($row['rec_caj_monto'],0).'</td>';
		$html.='</tr>';
		
		$html.='<tr>';
			$html.='<td style="text-align:left";><b>TOTAL DESCUENTOS: </b></td>';
			$html.='<td style="text-align:right";>$ '.number_format($dat_recibo,0).'</td>';
		$html.='</tr>';
		
		$html.='<tr>';
			$html.='<td colspan="2"><hr></td>';
		$html.='</tr>';
		
		
		$html.='<tr>';
		
			if(($row['rec_caj_monto']+$dat_recibo_2+$dat_recibo)>$_GET['valfactura'])
			{
				//echo "entra al if";
				$html.='<td colspan="2" style="text-align:right";>$ '.number_format(($row['rec_caj_monto']-$dat_recibo_2-$dat_recibo),0).'</td>';
			}
			else
			{
				//echo "entra al else";
				$html.='<td colspan="2" style="text-align:right";>$ '.number_format(($row['rec_caj_monto']),0).'</td>';
			}
				
		$html.='</tr>';
		
	
	$html.='</table>';
	
	$html.='<table border="0" style="font-size:10px;width:100%">';
	
	
	$html.='<tr>';
		$html.='<td colspan="5"><hr></td>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<td><b>CEDULA</b></td>';
	$html.='<td><b>NOMBRE</b></td>';
	$html.='<td><b>ENTIDAD</b></td>';
	$html.='<td><b>CUENTA</b></td>';
	$html.='<td style="text-align:right";><b>NETO A PAGAR</b></td>';
	$html.='</tr>';
	
	}
	
		$html.='<tr>';
		$html.='<td style="text-align:left";>'.$row['nits_num_documento'].'</td>';
		$html.='<td style="text-align:left";>'.$row['nits_apellidos']." ".$row['nits_nombres'].'</td>';
		$html.='<td style="text-align:left";>'.substr($row['banco'],0,25).'</td>';
		$html.='<td style="text-align:left";>'.$row['nits_num_cue_bancaria'].'</td>';
		
		if($row['mov_tipo']==1)//EL VALOR ES NEGATIVO
		{
			$val_pagar=$row['mov_valor']*-1;
		}
		else
		{
			$val_pagar=$row['mov_valor'];
		}
		
		$html.='<td style="text-align:right";>'.number_format($val_pagar,0).'</td>';
		$total +=$val_pagar;
		
		$val_banco=$val_pagar;
		
		
	$html.='</tr>';
	
	for($cont_ban=1;$cont_ban<=sizeof($banco);$cont_ban++)
	{
		$temp=0;
		if($banco[$cont_ban][0]==$row['nits_ban_id'])
		{
			$banco[$cont_ban][1]+=$val_banco;
			$temp=1;
			break;
		}
	}
	if($temp==0)
	{
		$banco[$ban][0]=$row['nits_ban_id'];
		$banco[$ban][1]=$val_banco;
		$banco[$ban][2]=$row['banco'];
		$ban++;
	}

	 
	$i++;
}

$p=0;$asoc=0;
if($dat_rep_no!=0)
{
	$can_lineas++;
	while($row = mssql_fetch_array($dat_rep_no))
	{
	$temp=0;
	if($p==0)
	{
		$html.='<tr>';
			$html.='<td><b>FECHA: </b>'.$row['mov_fec_elabo'].'</td>';
			$html.='<td><b>CENTRO DE COSTO: </b>'.$row['cen_cos_nombre'].'</td>';
			$html.='<td><b>FACTURA No: </b>'.$row['fac_consecutivo'].'</td>';
			$html.='<td><b>PERIODO: </b>'.strtoupper($mes->periodo($row['fac_consecutivo'])).'</td>';
			$html.='</tr>';
		
		
		$asoc=$row['nits_num_documento'];
		$html.='<tr>';
			$html.='<td style="text-align:left";>'.$row['nits_num_documento'].'</td>';
			$html.='<td style="text-align:left";>'.$row['nits_apellidos']." ".$row['nits_nombres'].'</td>';
			$html.='<td style="text-align:left";>'.substr($row['banco'],0,25).'</td>';
			$html.='<td style="text-align:left";>'.$row['nits_num_cue_bancaria'].'</td>';
			
			if($row['mov_tipo']==1)//EL VALOR ES NEGATIVO
			{
				$val_pagar=$row['mov_valor']*-1;
			}
			else
			{
				$val_pagar=$row['mov_valor'];
			}
			
			
			$html.='<td style="text-align:right";>'.number_format($val_pagar,0).'</td>';
			$total +=$val_pagar;$val_banco=$val_pagar;
			$html.='</tr>';
		
		  
		for($cont_ban=1;$cont_ban<=sizeof($banco);$cont_ban++)
		{
			$temp=0;
			if($banco[$cont_ban][0]==$row['nits_ban_id'])
			{
				$banco[$cont_ban][1]+=$val_banco;
				$temp=1;
				break;
			}
		}
		if($temp==0)
		{
			$banco[$ban][0]=$row['nits_ban_id'];
			$banco[$ban][1]=$val_banco;
			$banco[$ban][2]=$row['banco'];
			$ban++;
		}
		
	    $p++;
	}
	if($asoc!=$row['nits_num_documento'])
	{
		$asoc=$row['nits_num_documento'];
		$html.='<tr>';
			$html.='<td style="text-align:left";>'.$row['nits_num_documento'].'</td>';
			$html.='<td style="text-align:left";>'.$row['nits_apellidos']." ".$row['nits_nombres'].'</td>';
			$html.='<td style="text-align:left";>'.substr($row['banco'],0,25).'</td>';
			$html.='<td style="text-align:left";>'.$row['nits_num_cue_bancaria'].'</td>';
			
			if($row['mov_tipo']==1)//EL VALOR ES NEGATIVO
			{
				$val_pagar=$row['mov_valor']*-1;
			}
			else
			{
				$val_pagar=$row['mov_valor'];
			}
			
			$html.='<td style="text-align:right";>'.number_format($val_pagar,0).'</td>';
			$total +=$val_pagar;$val_banco=$val_pagar;
		$html.='</tr>';
		

		for($j=1;$j<=sizeof($banco);$j++)
		{
			//echo $banco[$j][0]."---".$row['nits_ban_id']."<br>";
			$temp=0;
			if($banco[$j][0]==$row['nits_ban_id'])
			{
			  $banco[$j][1] +=$val_banco;
			  $temp=1;
			  break;
			}
			else
			  $temp=0;
		}
		if($temp==0)
		{
			$banco[$i][0]=$row['nits_ban_id'];
			$banco[$i][1]=$val_banco;
			$banco[$i][2]=$row['banco'];
		}
	}
	$i++;
	}
}

$html.='<tr>';
	$html.='<th colspan="5"><hr></th>';
$html.='</tr>';

$html.='<tr>';
	$html.='<th colspan="4" style="text-align:right";>TOTAL:....................................</th>';
	$html.='<th style="text-align:right";>$ '.number_format($total,0).'</th>';
$html.='</tr>';

$html.='</table>';

$html.='<table border="0" style="font-size:12px;">';

$total=0;
for($j=0;$j<=$i;$j++)
{
	if($banco[$j][1]>0)
	{
		$i++;
		//$pdf->SetY(100+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($banco[$j][0],0,25));
		
		$html.='<tr>';
			$html.='<th style="text-align:left";>'.substr($banco[$j][2],0,25).'</th>';
			$html.='<th style="text-align:right";>$ '.number_format($banco[$j][1],0).'</th>';
		$html.='</tr>';
		$total += $banco[$j][1];
	}
}

$html.='<tr>';
	$html.='<th colspan="2"><hr></th>';
$html.='</tr>';

$html.='<tr>';
	$html.='<th style="text-align:right";>TOTAL:....................................</th>';
	$html.='<th style="text-align:right";>$ '.number_format($total,0).'</th>';
$html.='</tr>';

$html.='</table>';

require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR

?>
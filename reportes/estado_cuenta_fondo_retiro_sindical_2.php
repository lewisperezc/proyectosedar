<?php
session_start();

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('/clases/mes_contable.class.php');
@include_once('../clases/pabs.class.php');
@include_once('/clases/pabs.class.php');


function nomMes($mes)
{
	$sql="SELECT mes_nombre FROM mes_contable WHERE mes_id=$mes";
	$query = mssql_query($sql);
	if($query)
	{
		$dat_mes = mssql_fetch_array($query);
	    return $dat_mes['mes_nombre'];
	}
}

$nit = new nits();
$mes_contable = new mes_contable();
$ins_fabs = new pabs();
$inicio = explode("-",$_POST["cue_ini"],2);
$fin = explode("-",$_POST["cue_fin"],2);
$cue_ini= $inicio[1];
$cue_fin =$fin[1];
$ano=$_SESSION['elaniocontable'];
$mes_antes = $cue_ini-1;
$doc_ini = $_POST["doc_ini"];

//,$cue_ini,$cue_fin,$ano

/*$cue_fon_ret_sindical_1='23803009';//ANTIGUO PUCH
$cue_fon_ret_sindical_2='31400101';//ANTIGUO PUCH
$sql="EXECUTE estado_fondo_retiro_sindical '$cedula',$cue_fon_ret_sindical_1,$cue_fon_ret_sindical_2";//ANTIGUO PUCH*/


$cue_fon_ret_sindical_1='23803009';//NUEVO PUCH
$cue_fon_ret_sindical_2='31400101';//NUEVO PUCH
$sql="EXECUTE estado_fondo_retiro_sindical '$doc_ini',$cue_fon_ret_sindical_1,$cue_fon_ret_sindical_2";//NUEVO PUCH
//echo $sql;


//echo $sql;
$query = mssql_query($sql);
$html='';
if($query)
{
	$balance = "SELECT * FROM rep_fabs order by CAST(doce AS INT),CAST(diez AS INT),CAST(tres AS DATETIME)";
	$que_balance = mssql_query($balance);
	$html='<center>';
	$html.='<table style="font-size:10px;">';
	$html.='<tr>';
	$html.='<th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th colspan="6">ESTADO DE CUENTA FONDO DE RETIRO SINDICAL</th>';
	$html.='</tr>';
	
	$dat_asociados=$nit->contodatnitpordocumento($doc_ini);
	
	$html.='<tr>';
	$html.='<th>ANESTESIOLOGO:</th><td>'.$dat_asociados['nits_num_documento'].'</td>';
	$html.='<td colspan="2"> - '.$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos'].'</td>';
	
	$query="SELECT * from movimientos_contables where mov_nit_tercero='$dat_asociados[nit_id]'
	and (mov_cuent=$cue_fon_ret_sindical_1 OR mov_cuent=$cue_fon_ret_sindical_2)
	and mov_compro like('CIE-2017')";
	//echo $query;
    $ejecutar=mssql_query($query);
	while($res_sal_inicial=mssql_fetch_array($ejecutar))
	{
		$saldo_inicial=$saldo_inicial+$res_sal_inicial['mov_valor'];
	}
	
	$html.='<th>SALDO INICIAL:</th><td style="text-align:center;">'.number_format($saldo_inicial).'</td>';
	$html.='</tr>';
	
	$html.='<tr><td colspan="6">&nbsp;</td></tr>';
	
	$html.='<tr>';
	$html.='<th>MES CONTABLE</th>';
	$html.='<th>CENTRO DE COSTOS</th>';
	$html.='<th>DESCRIPCION</th>';
	$html.='<th>DEBITO</th>';
	$html.='<th>CREDITO</th>';
	$html.='<th>SALDO</th>';
	$html.='</tr>';
	$temp=0;
	$tempmes=0;
	$temimprimir=0;
	$tempciclo=0;
	while($row=mssql_fetch_array($que_balance))
	{	
		if($tempciclo==0)
		{
			$tempciclo=1;
			$tempmes=$row['diez'];
			$html.='<tr>';
			$html.='<td>'.nomMes($row["diez"])." - ".$row["doce"].'</td>';
			$html.='<td>'.strtoupper($row["cinco"]).'</td>';
			$html.='<td>'.strtoupper($row["cuatro"]." - ".$row["seis"]).'</td>';
				
			//SABER SI ES DEBITO O CRÉDITO
			if($row["trece"]==1)
			{
				$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
				$html.='<td style="text-align:center;">'.number_format(0).'</td>';
			}
			else
			{
				$html.='<td style="text-align:center;">'.number_format(0).'</td>';
				$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
			}
		
			//SUMAR EL SALDO AL PRIMER REGISTRO
			if($temp==0)
			{
				$tempmes=$row['diez'];
				if($row["trece"]==1)
				{
					$saldo_mes=$saldo_inicial-$row["dos"];
					$sum_sal_tot_mes=$saldo_mes;
				}
				else
				{
					$saldo_mes=$saldo_inicial+$row["dos"];
					$sum_sal_tot_mes=$saldo_mes;
				}
				$temp=1;
			}
			else
			{
				if($row["trece"]==1)
				{
					$saldo_mes=$saldo_mes-$row["dos"];
					$sum_sal_tot_mes=$saldo_mes;
				}
				else
				{
					$saldo_mes=$saldo_mes+$row["dos"];
					$sum_sal_tot_mes=$saldo_mes;
				}
			}
			
			$html.='<td style="text-align:center;">'.number_format($saldo_mes).'</td>';
			
			$html.='</tr>';
		}
		else
		{
			if($tempmes==$row['diez'])
			{
				$html.='<tr>';
				$html.='<td>'.nomMes($row["diez"])." - ".$row["doce"].'</td>';
				$html.='<td>'.strtoupper($row["cinco"]).'</td>';
				$html.='<td>'.strtoupper($row["cuatro"]." - ".$row["seis"]).'</td>';
				
				//SABER SI ES DEBITO O CRÉDITO
				if($row["trece"]==1)
				{
					$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
					$html.='<td style="text-align:center;">'.number_format(0).'</td>';
				}
				else
				{
					$html.='<td style="text-align:center;">'.number_format(0).'</td>';
					$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
				}
		
				//SUMAR EL SALDO AL PRIMER REGISTRO
				if($temp==0)
				{
					$tempmes=$row['diez'];
					if($row["trece"]==1)
					{
						$saldo_mes=$saldo_inicial-$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					else
					{
						$saldo_mes=$saldo_inicial+$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					$temp=1;
				}
				
				else
				{
					if($row["trece"]==1)
					{
						$saldo_mes=$saldo_mes-$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					else
					{
						$saldo_mes=$saldo_mes+$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
				}
			
				$html.='<td style="text-align:center;">'.number_format($saldo_mes).'</td>';
			
				$html.='</tr>';
			
				$nom_mes=nomMes($row['diez']);
			}
			else
			{
				$tempmes=$row['diez'];
				$html.='<tr><th colspan="5" style="text-align:right;">SALDO MES DE '.strtoupper($nom_mes).': ';
				$html.='...............................................................................................</th>';
				$html.='<th>'.number_format($sum_sal_tot_mes).'</th></tr>';
				$html.='<tr>';
				$html.='<td>'.nomMes($row["diez"])." - ".$row["doce"].'</td>';
				$html.='<td>'.strtoupper($row["cinco"]).'</td>';
				$html.='<td>'.strtoupper($row["cuatro"]." - ".$row["seis"]).'</td>';
				
				//SABER SI ES DEBITO O CRÉDITO
				if($row["trece"]==1)
				{
					$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
					$html.='<td style="text-align:center;">'.number_format(0).'</td>';
				}
				else
				{
					$html.='<td style="text-align:center;">'.number_format(0).'</td>';
					$html.='<td style="text-align:center;">'.number_format($row["dos"]).'</td>';
				}
		
				//SUMAR EL SALDO AL PRIMER REGISTRO
				if($temp==0)
				{
					$tempmes=$row['diez'];
					if($row["trece"]==1)
					{
						$saldo_mes=$saldo_inicial-$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					else
					{
						$saldo_mes=$saldo_inicial+$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					$temp=1;
				}
				else
				{
					$nom_mes=nomMes($row['diez']);
					if($row["trece"]==1)
					{
						$saldo_mes=$saldo_mes-$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
					else
					{
						$saldo_mes=$saldo_mes+$row["dos"];
						$sum_sal_tot_mes=$saldo_mes;
					}
				}
			
				$html.='<td style="text-align:center;">'.number_format($saldo_mes).'</td>';
				$html.='</tr>';
			}
		}
	}
	$html.='<tr><th colspan="5" style="text-align:right;">SALDO MES DE '.strtoupper($nom_mes).': ';
	$html.='...............................................................................................</th>';
	$html.='<th>'.number_format($sum_sal_tot_mes).'</th></tr>';
	$html.='</table>';
	$html.='</center>';
}
$_SESSION['informacion_retiro']=$html;
//echo $_SESSION['informacion'];
echo '<script>location.href="../reportes_PDF/estado_cuenta_fondo_retiro_sindical.php";</script>';
?>

<?php
session_start();
unset($_SESSION['inf_pag_dietas']);
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
$documento=$_POST['pag_die_cedula'];
if(isset($_POST['btn_ver_todos']))//VER TODOS
{
	$sql="SELECT mc.*,cue.cue_nombre,cc.cen_cos_nombre,mesc.mes_nombre FROM movimientos_contables mc
	INNER JOIN cuentas cue ON mc.mov_cuent=cue.cue_id
	INNER JOIN nits n ON mc.mov_nit_tercero=CAST(n.nit_id AS VARCHAR(10))
	INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
	INNER JOIN mes_contable mesc ON mc.mov_mes_contable=mesc.mes_id
	WHERE mc.mov_compro LIKE('DIE-AFI_%') AND n.nits_num_documento='$documento'
	ORDER BY mov_mes_contable ASC,mov_compro,mov_tipo ASC,mov_valor";
}
elseif(isset($_POST['pag_die_mes_pago'])&&$_POST['pag_die_mes_pago']!="")//VER POR MES
{
	$mes=$_POST['pag_die_mes_pago'];
	$sql="SELECT mc.*,cue.cue_nombre,cc.cen_cos_nombre,mesc.mes_nombre FROM movimientos_contables mc
	INNER JOIN cuentas cue ON mc.mov_cuent=cue.cue_id
	INNER JOIN nits n ON mc.mov_nit_tercero=CAST(n.nit_id AS VARCHAR(10))
	INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
	INNER JOIN mes_contable mesc ON mc.mov_mes_contable=mesc.mes_id
	WHERE mc.mov_compro LIKE('DIE-AFI_%') AND n.nits_num_documento='$documento' AND mc.mov_mes_contable='$mes'
	ORDER BY mov_mes_contable ASC,mov_compro,mov_tipo ASC,mov_valor";
}
//echo $sql;
$query = mssql_query($sql);
$html='';
if($query)
{
	$html='<center>';
	$html.='<table style="font-size:12px;">';
	$html.='<tr>';
	$html.='<th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th colspan="6">REPORTE DE PAGO DE DIETAS</th>';
	$html.='</tr>';
	
	$dat_asociados=$nit->contodatnitpordocumento($documento);
	
	$html.='<tr>';
	$html.='<th>ANESTESIOLOGO:</th><td>'.$dat_asociados['nits_num_documento'].'</td>';
	$html.='<td colspan="2"> - '.$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos'].'</td>';
	$html.='</tr>';
	
	$html.='<tr><td colspan="6">&nbsp;</td></tr>';
	
	$html.='<tr>';
	$html.='<th>FECHA</th>';
	$html.='<th>MES CONTABILIZADO</th>';
	$html.='<th>CENTRO DE COSTOS</th>';
	$html.='<th>DOCUMENTO</th>';
	$html.='<th>DESCRIPCION</th>';
	$html.='<th>DEBITO</th>';
	$html.='<th>CREDITO</th>';
	$html.='</tr>';
	$bandera=0;
	$inicio=0;
	while($row=mssql_fetch_array($query))
	{
			if($inicio==0)
			{
				$bandera=$row['mov_compro'];
				$html.='<tr>';
				$html.='<td>'.$row['mov_fec_elabo'].'</td>';
				$html.='<td>'.$row['mes_nombre'].'</td>';
				$html.='<td>'.$row['cen_cos_nombre'].'</td>';
				$html.='<td>'.$row['mov_compro'].'</td>';
				$html.='<td>'.$row['cue_nombre'].'</td>';
				if($row['mov_tipo']==1)
				{
					$html.='<td>'.number_format($row['mov_valor']).'</td>';
					$html.='<td>'.number_format(0).'</td>';
				}
				elseif($row['mov_tipo']==2)
				{
					$html.='<td>'.number_format(0).'</td>';
					$html.='<td>'.number_format($row['mov_valor']).'</td>';
				}
				$html.='</tr>';
				$inicio++;		
			}
			else
			{
				if($bandera==$row['mov_compro'])
				{
					$html.='<tr>';
					$html.='<td>'.$row['mov_fec_elabo'].'</td>';
					$html.='<td>'.$row['mes_nombre'].'</td>';
					$html.='<td>'.$row['cen_cos_nombre'].'</td>';
					$html.='<td>'.$row['mov_compro'].'</td>';
					$html.='<td>'.$row['cue_nombre'].'</td>';
					if($row['mov_tipo']==1)
					{
						$html.='<td>'.number_format($row['mov_valor']).'</td>';
						$html.='<td>'.number_format(0).'</td>';
					}
					elseif($row['mov_tipo']==2)
					{
						$html.='<td>'.number_format(0).'</td>';
						$html.='<td>'.number_format($row['mov_valor']).'</td>';
					}
					$html.='</tr>';				
				}
				else
				{
					$html.='<tr><td colspan="7"><hr></td></tr>';
					$bandera=$row['mov_compro'];
					$html.='<tr>';
					$html.='<td>'.$row['mov_fec_elabo'].'</td>';
					$html.='<td>'.$row['mes_nombre'].'</td>';
					$html.='<td>'.$row['cen_cos_nombre'].'</td>';
					$html.='<td>'.$row['mov_compro'].'</td>';
					$html.='<td>'.$row['cue_nombre'].'</td>';
					if($row['mov_tipo']==1)
					{
						$html.='<td>'.number_format($row['mov_valor']).'</td>';
						$html.='<td>'.number_format(0).'</td>';
					}
					elseif($row['mov_tipo']==2)
					{
						$html.='<td>'.number_format(0).'</td>';
						$html.='<td>'.number_format($row['mov_valor']).'</td>';
					}
					$html.='</tr>';
				}
			}
	}
	$html.='<tr><td colspan="7"><hr></td></tr>';
	$html.='</table>';
	$html.='</center>';
}
$_SESSION['inf_pag_dietas']=$html;
//echo $_SESSION['inf_pag_dietas'];
echo '<script>location.href="../reportes_PDF/pago_dietas.php";</script>';
?>

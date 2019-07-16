<?php
session_start();
unset($html);
unset($_SESSION['informacion_nomina_administrativa']);
include_once('../clases/nits.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/numerosALetras.class.php');
$ins_nits=new nits();
$ins_mov_contable=new movimientos_contables();
$mov_compro='CAU-NOM_ADM_';
$num_quincena=$_POST['per_pag_nomina'];
$mes_sele=$_POST['mes_sele'];
$nit_inicio=$_POST['nit_inicio'];
$nit_fin=$_POST['nit_fin'];
$con_dat_nits=$ins_nits->ConsultarEmpleadosNominaAdministrativa($mov_compro,$num_quincena,$mes_sele,$_SESSION['elaniocontable'],$nit_inicio,$nit_fin,2);
?>
<style type="text/css">
.container_12
{
	margin-left: 25px;
	margin-right: 25px;
}

	#header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
    #footer .page:after { content: counter(page, upper-roman);}
</style>
<?php
//$html.='<div id="header"><div>';
$html.='<div id="content">';
$i=0;
$tot_cre_nomina=0;
$tot_dep_nomina=0;
while($res_dat_nits=mssql_fetch_array($con_dat_nits))
{
	
	$con_dat_nomina=$ins_mov_contable->ConsultarDatosNominaAdministrativa($res_dat_nits['mov_compro'],$num_quincena,$mes_sele,$_SESSION['elaniocontable'],$res_dat_nits['nit_id']);
	
	if($i!=0)
		$html.='<div style="page-break-before: always;"></div>';
	$html.='<table style="width:100%">';
	$html.='<tr><th colspan="4"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar"/></th></tr>';
	$html.='<tr>';
	$html.='<th style="text-align:left;">NOMBRE:</th><td>'.$res_dat_nits['nits_apellidos']." ".$res_dat_nits['nits_nombres'].'</td>';
	$html.='<th style="text-align:left;">CEDULA:</th><td>'.$res_dat_nits['nits_num_documento'].'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th style="text-align:left;">CARGO:</th><td>'.$res_dat_nits['per_nombre'].'</td>';
	$html.='<th style="text-align:left;">BASICO:</th><td>$ '.number_format($res_dat_nits['nits_salario']).'</td>';
	$html.='</tr>';
	/*$html.='<tr>';
	$html.='<th style="text-align:left;">PERIODO DE PAGO:</th><td>'.$res_dat_nits['nits_salario'].'</td>';
	$html.='<th>&nbsp;</th>';
	$html.='<th>&nbsp;</th>';
	$html.='<th>&nbsp;</th>';
	$html.='</tr>';*/
	$html.='</table>';
	
	$html.='<table style="width:100%" border="1">';
	$html.='<tr>';
	$html.='<th style="text-align:left;">CONCEPTO</th>';
	$html.='<th style="text-align:left;">DEVENGADOS</th>';
	$html.='<th style="text-align:left;">DEDUCCIONES</th>';
	$html.='</tr>';
	$tot_debito=0;
	$tot_credito=0;
	$temp=1;
	while($res_dat_nomina=mssql_fetch_array($con_dat_nomina))
	{
		$num_filas=mssql_num_rows($con_dat_nomina);
		$html.='<tr>';
		if($res_dat_nomina['mov_cuent']!='25050501')//CUENTA POR PAGAR
		{
			$html.='<td>'.$res_dat_nomina["cue_nombre"].'</td>';
		
			if($res_dat_nomina["mov_tipo"]==1)//DEBITO
			{
				$html.='<td style="text-align:right;">'.number_format($res_dat_nomina["mov_valor"]).'</td>';
				$html.='<td style="text-align:right;">'.number_format(0).'</td>';
				$tot_debito=$tot_debito+$res_dat_nomina["mov_valor"];
				$tot_deb_nomina=$tot_deb_nomina+$res_dat_nomina["mov_valor"];
			}
			else//CREDITO
			{
				$html.='<td style="text-align:right;">'.number_format(0).'</td>';
				$html.='<td style="text-align:right;">'.number_format($res_dat_nomina["mov_valor"]).'</td>';
				$tot_credito=$tot_credito+$res_dat_nomina["mov_valor"];
				$tot_cre_nomina=$tot_cre_nomina+$res_dat_nomina["mov_valor"];
			}
		}
		else
		{
			$val_pagar=$res_dat_nomina["mov_valor"];
		}
		
		$html.='</tr>';
		
	}
	$html.='</table>';
	$html.='<table style="width:100%" border="1">';
	$html.='<tr>';
	$html.='<th style="text-align:left;">SUBTOTALES $ </th>';
	//$html.='<th>&nbsp;</th>';
	$html.='<th style="text-align:right;width:35%">'.number_format($tot_debito).'</th>';
	$html.='<th style="text-align:right;width:29%">'.number_format($tot_credito).'</th>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th style="text-align:left;">TOTAL A PAGAR $</th>';
	$html.='<th colspan="2" style="text-align:right;">'.number_format($val_pagar).'</th>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th colspan="3" style="text-align:right;">'.strtoupper(num2letras($val_pagar)).' PESOS</th>';
	$html.='</tr>';
	
	$html.='</table>';
	$i++;
}
$html.='</div>';
$_SESSION['informacion_nomina_administrativa']=$html;
//echo $_SESSION['informacion_nomina_administrativa'];
echo '<script>location.href="../reportes_PDF/nomina_administrativa.php";</script>';
?>
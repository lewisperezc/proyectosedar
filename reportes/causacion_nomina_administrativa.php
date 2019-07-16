<?php session_start();
@include_once('clases/varios.class.php');
@include_once('../clases/varios.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/nits.class.php');
@include_once('clases/moviminetos_contables.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/usuario.class.php');
@include_once('../clases/usuario.class.php');


$ins_mes_contable=new mes_contable();
$ins_varios=new varios();
$ins_nits=new nits();
$ins_mov_contable=new movimientos_contables();
$ins_usuario=new usuario();

$arreglo_empleados=$ins_varios->recibe_array_url($_GET['lista_empleados']);
$nom_mes_pago=$ins_mes_contable->nomMes($_GET['mes_pago']);


$html='<center>';
$html.='<table>';
$html.='<tr>';
$html.='<td>';
$html.='<table style="font-size:12px;">';

$html.='<tr>';
$html.='<th colspan="2"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
$html.='</tr>';
	
$html.='<tr>';
$html.='<th colspan="">AUXILIAR DE NOMINA</th>';
$html.='<th colspan="">'.strtoupper($nom_mes_pago).' - '.$_GET["anio_contable"].'</th>';
$html.='</tr>';
$html.='</table>';

$html.='</br>';
$i=0;
$tot_deb_nomina=0;
$tot_cre_nomina=0;

while($i<sizeof($arreglo_empleados))
{
	$con_dat_empleados=$ins_nits->consultar($arreglo_empleados[$i]);
	$res_dat_empleados=mssql_fetch_array($con_dat_empleados);
	
	$res_nom_cargo=$ins_usuario->ConDatPerPorId($res_dat_empleados['nit_perfil']);
	
	$con_dat_nomina=$ins_mov_contable->ConsultarDatosNominaAdministrativa($_GET['sigla'],$_GET['num_quincena'],$_GET['mes_contable'],$_GET['anio_contable'],$res_dat_empleados['nit_id']);
	$con_dia_tra_empleado=$ins_mov_contable->ConsultarDiasTrabajados($_GET['sigla'],$_GET['num_quincena'],$_GET['mes_contable'],$_GET['anio_contable'],$res_dat_empleados['nit_id']);
	$res_dia_tra_empleado=mssql_fetch_array($con_dia_tra_empleado);
	
	$html.='</br>';
	$html.='<table  style="border:1px double;text-align:left;font-size:12px;">';
	$html.='<tr>';
	$html.='<th>EMPLEADO:</th>';
	$html.='<td>'.$res_dat_empleados["nits_num_documento"].' - ';
	$html.=$res_dat_empleados["nits_apellidos"].' '.$res_dat_empleados["nits_nombres"].'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th>BASICO:</th>';
	$html.='<td>'.number_format($res_dat_empleados["nits_salario"]).'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th>CARGO:</th>';
	$html.='<td>'.$res_nom_cargo['per_nombre'].'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th>DIAS TRABAJADOS:</th>';
	$html.='<td>'.$res_dia_tra_empleado['mov_nume'].'</td>';
	$html.='</tr>';
	$html.='</table>';
	
	$html.='</br>';
	
	$html.='<table style="border:1px double;text-align:left;font-size:12px;">';
	$html.='<tr>';
	$html.='<th>CONCEPTO</th>';
	$html.='<th>DEVENGADOS</th>';
	$html.='<th>DEDUCCIONES</th>';
	$html.='</tr>';
	
	$tot_debito=0;
	$tot_credito=0;
	
	while($res_dat_nomina=mssql_fetch_array($con_dat_nomina))
	{
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
	$html.='<tr>';
	$html.='<td colspan="3"><hr></td>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th style="text-align:right;">SUBTOTALES:</th>';
	$html.='<td style="text-align:right;">'.number_format($tot_debito).'</td>';
	$html.='<td style="text-align:right;">'.number_format($tot_credito).'</td>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<td colspan="3">&nbsp;</td>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th style="text-align:right;">TOTAL A PAGAR AL EMPLEADO:</th>';
	$html.='<td style="text-align:right;">'.number_format($val_pagar).'</td>';
	$html.='</tr>';
	
	$html.='</table>';
	$i++;
}
$html.='</td>';
$html.='</tr>';

$html.='<tr>';
$html.='<td>';
$html.='<table style="border:1px double;text-align:left;font-size:15px;">';
$html.='<tr>';
$html.='<th>SUBTOTALES $</th>';
$html.='<th style="text-align:right;">'.number_format($tot_deb_nomina).'</th>';
$html.='<th>&nbsp;</th>';
$html.='<th style="text-align:right;">'.number_format($tot_cre_nomina).'</th>';
$html.='</tr>';
$html.='<tr>';
$html.='<th>TOTAL A PAGAR NOMINA ADMINISTRATIVA $</th>';
$html.='<th>&nbsp;</th>';
$html.='<th>&nbsp;</th>';
$html.='<th style="text-align:right;">'.number_format($tot_deb_nomina-$tot_cre_nomina).'</th>';
$html.='</tr>';
$html.='</table>';
$html.='</td>';
$html.='</tr>';

$html.='</table>';
$html.='</center>';

echo $html;
/*
require_once '../librerias/dompdf/dompdf_config.inc.php';

$dompdf = new DOMPDF();
$dompdf->load_html( file_get_contents( 'causacion_nomina_administrativa.php' ) );
$dompdf->render();
$dompdf->stream("mi_archivo.pdf");
*/
?>

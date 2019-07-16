<?php
session_start();
require_once("../librerias/dompdf/dompdf_config.inc.php");
@include_once('../clases/credito.class.php');
$ins_credito=new credito();

$html.='<table style="font-size:12px;">';
$html.='<tr>';
$html.='<th colspan="2"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
$html.='</tr>';
$html.='</table>';

$html.='<table border="1"><tr><th colspan="8">RECAUDO DE NOMINA</th></tr><tr><th>Fecha recaudo</th><th>Numero Pagare</th><th>Saldo</th><th>Documento</th><th>Tercero</th><th>Capital</th><th>Interes</th><th>Cuota</th></tr>';
$total_capital=0;
$total_interes=0;
$total_cuota=0;
//echo "datos: ".$_GET['tipo_nit']."<br>";
if($_SESSION["tipo_nit"]==1||$_GET['tipo_nit']==1)
{
	//echo "entra por el if";
	//RECAUDO AFILIADOS
	if(isset($_GET['tipo_recaudo_credito'])&&isset($_GET['tipo_recaudo_credito'])==2)//ES CONSULTA
	{
		//echo "entra por el if 2";
		$no_contiene='NOT-PRE%';
		$con_dat_recaudo=$ins_credito->ConsultarRecaudoPorFactura($_POST['fac_seleccionada'],$no_contiene);
		while($res_dat_recaudo=mssql_fetch_array($con_dat_recaudo))
		{	
			$res_saldo=$ins_credito->ConsultarSaldoCreditoRecaudo($res_dat_recaudo['des_cre_credito']);
			if($res_saldo==0)
				$saldo_credito=$res_dat_recaudo['des_cre_capital'];
			else
				$saldo_credito=$res_saldo+$res_dat_recaudo['des_cre_capital'];
			$html.="<tr>";
			$html.="<td>".$res_dat_recaudo['des_cre_fecha']."</td>";
			$html.="<td>".$res_dat_recaudo['des_cre_credito']."</td><td style='text-align:right'>".number_format($saldo_credito)."</td><td>".$res_dat_recaudo['nits_num_documento']."</td>";
			$html.="<td>".$res_dat_recaudo['nits_nombres'].' '.$res_dat_recaudo['nits_apellidos']."</td><td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_capital'])."</td>";
			$html.="<td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_interes'])."</td><td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_total'])."</td>";
			$html.='</tr>';
			$total_capital+=$res_dat_recaudo['des_cre_capital'];
			$total_interes+=$res_dat_recaudo['des_cre_interes'];
			$total_cuota+=$res_dat_recaudo['des_cre_total'];
		}
	}
	else
	{
		for($j=0;$j<$_SESSION['cant'];$j++)
		{
			$html.="<tr>";
			$html.="<td>".date('d-m-Y')."</td>";
			$html.="<td>".$_SESSION['cred_recaudo'][$j]."</td><td style='text-align:right'>".$_SESSION['saldo'][$j]."</td><td>".$_SESSION['nit_num_documento'][$j]."</td>";
			$html.="<td>".$_SESSION['nombres_tercero'][$j]."</td><td style='text-align:right'>".number_format($_SESSION['cap_recaudo'][$j])."</td>";
			$html.="<td style='text-align:right'>".number_format($_SESSION['int_recaudo'][$j])."</td><td style='text-align:right'>".number_format($_SESSION['cuota'][$j])."</td>";
			$html.='</tr>';
			$total_capital+=$_SESSION['cap_recaudo'][$j];
			$total_interes+=$_SESSION['int_recaudo'][$j];
			$total_cuota+=$_SESSION['cuota'][$j];
		}
	}
}
elseif($_SESSION["tipo_nit"]==2||$_GET['tipo_nit']==2)
{
	//echo "entra por el elseif";
	//RECAUDO EMPLEADOS
	if(isset($_GET['tipo_recaudo_credito'])&&isset($_GET['tipo_recaudo_credito'])==2)
	{
		//echo "entra por acáaa";
		$con_dat_recaudo=$ins_credito->ConsultarRecaudoPorMesAnioEmpleados($_POST['mes_recaudo'],$_POST['anio_recaudo'],2);
		while($res_dat_recaudo=mssql_fetch_array($con_dat_recaudo))
		{
			$res_saldo=$ins_credito->ConsultarSaldoCreditoRecaudo($res_dat_recaudo['des_cre_credito']);
			if($res_saldo==0)
				$saldo_credito=$res_dat_recaudo['des_cre_capital'];
			else
				$saldo_credito=$res_saldo+$res_dat_recaudo['des_cre_capital'];
			$html.="<tr>";
			$html.="<td>".$res_dat_recaudo['des_cre_fecha']."</td>";
			$html.="<td>".$res_dat_recaudo['des_cre_credito']."</td><td style='text-align:right'>".number_format($saldo_credito)."</td><td>".$res_dat_recaudo['nits_num_documento']."</td>";
			$html.="<td>".$res_dat_recaudo['nits_nombres'].' '.$res_dat_recaudo['nits_apellidos']."</td><td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_capital'])."</td>";
			$html.="<td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_interes'])."</td><td style='text-align:right'>".number_format($res_dat_recaudo['des_cre_total'])."</td>";
			$html.='</tr>';
			$total_capital+=$res_dat_recaudo['des_cre_capital'];
			$total_interes+=$res_dat_recaudo['des_cre_interes'];
			$total_cuota+=$res_dat_recaudo['des_cre_total'];
		}
	}
	else
	{
		for($j=0;$j<$_SESSION['cant'];$j++)
		{
			$html.="<tr>";
			$html.="<td>".date('d-m-Y')."</td>";
			$html.="<td>".$_SESSION['cred_recaudo'][$j]."</td><td style='text-align:right'>".$_SESSION['saldo'][$j]."</td><td>".$_SESSION['nit_num_documento'][$j]."</td>";
			$html.="<td>".$_SESSION['nombres_tercero'][$j]."</td><td style='text-align:right'>".number_format($_SESSION['cap_recaudo'][$j])."</td>";
			$html.="<td style='text-align:right'>".number_format($_SESSION['int_recaudo'][$j])."</td><td style='text-align:right'>".number_format($_SESSION['cuota'][$j])."</td>";
			$html.='</tr>';
			$total_capital+=$_SESSION['cap_recaudo'][$j];
			$total_interes+=$_SESSION['int_recaudo'][$j];
			$total_cuota+=$_SESSION['cuota'][$j];
		}
	}
}


$html.='<tr><th colspan="5">TOTALES:</th>';
$html.='<th style="text-align:right">'.number_format($total_capital).'</th>';
$html.='<th style="text-align:right">'.number_format($total_interes).'</th>';
$html.='<th style="text-align:right">'.number_format($total_cuota).'</th>';
$html.='</tr>';
$html.='</table>';
	
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR


unset($_SESSION['mes_comprobantes']);
unset($_SESSION['cant']);
unset($_SESSION['cred_recaudo']);
unset($_SESSION['saldo']);
unset($_SESSION['nit_num_documento']);
unset($_SESSION['nombres_tercero']);
unset($_SESSION['cap_recaudo']);
unset($_SESSION['int_recaudo']);
unset($_SESSION['cuota']);
unset($_SESSION["tipo_nit"]);
?>
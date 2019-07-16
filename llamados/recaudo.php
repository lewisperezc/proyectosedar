<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/credito.class.php');
$ano = $_SESSION['elaniocontable'];
error_reporting(E_ALL);

$nit = new nits();
$centro = new centro_de_costos();
$credito = new credito();
$factura = $_POST['factura'];
$conta_cre = $credito->contaCreditos($_SESSION['k_nit_id'],$ano,$factura);
$dat_credito = $credito->recaudo($factura);
$i=0;
$html='<table id="tab_recaudo"><tr><td>Mes contabilizado</td><td>Tipo pagare</td><td>Num Pagare</td><td>Saldo</td><td>Documento</td><td>Tercero</td><td>Capital a Abonar</td><td>Interes</td><td>Cuota Total</td><td>Descontar?</td></tr>';
while($row = mssql_fetch_array($dat_credito))
{
	$res_dat_credito=$credito->con_dat_cre_por_id($row['cre_id']);
	if($row['saldo']<$row['capDescuento'])
	{
		$cap_abonar=$row['saldo'];
		$cuo_total=$row['saldo'];
	}
	else
	{
		$cap_abonar=$row['capDescuento'];
		$cuo_total=$row['capDescuento'];
	}
	$html.='<tr id="tr'.$i.'">';
	$html.='<td><input type="text" readonly size="5" name="mes_contable'.$i.'" id="mes_contable'.$i.'" value="'.$row['mov_mes_contable'].'" /></td>';
	$html.='<td>'.$row['con_nombre'].'</td>';
	$html.='<td><input type="text" size="5" name="credito'.$i.'" id="credito'.$i.'" value="'.$row['cre_id'].'" /></td>';
	$html.='<td><input type="text" size="8" name="saldo'.$i.'" id="saldo'.$i.'" value="'.number_format($row['saldo']).'" /></td>';
	$html.='<td><input type="text" name="nit_num_documento'.$i.'" id="nit_num_documento'.$i.'" value="'.$row['nits_num_documento'].'"/></td>';
	$html.='<td><input type="hidden" name="nit_id'.$i.'" id="nit_id'.$i.'" value="'.$row['nit_id'].'"/>';
	$html.='<input type="text" name="nombres_tercero'.$i.'" id="nombres_tercero'.$i.'" value="'.$row['nits_nombres'].' '.$row['nits_apellidos'].'" /></td>';
	$html.='<td><input type="text" size="8" name="capital'.$i.'" id="capital'.$i.'" value="'.$cap_abonar.'" onChange="sum_pagCuota('.$i.');" /></td>';
	$html.='<td><input type="text" size="8" name="interes'.$i.'" id="interes'.$i.'" value="0" onChange="sum_pagCuota('.$i.');"/></td>';
	$html.='<td><input type="text" size="8" name="cuota'.$i.'" id="cuota'.$i.'" value="'.$cuo_total.'" /></td>';
	$html.='<td><input type="checkbox" name="descontar'.$i.'" id="descontar'.$i.'" onclick="calcular_interes('.$i.','.$row['cre_id'].','.$row['saldo'].');"/></td></tr>';
	$i++;
}
$html.='</table>';
echo $html;
?>
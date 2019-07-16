<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte");
header("Pragma: no-cache");
header("Expires: 0");

@include_once('../clases/nomina.class.php');
@include_once('/clases/nomina.class.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/cuenta.class.php');

$factura = $_GET['valor'];
$ins_nomina=new nomina();
$reporte = new reporte_jornadas();
$nits = "SELECT distinct mov_nit_tercero,mov_compro FROM movimientos_contables WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = $factura";
$arreglo = "";
$conse_nomina = "";
$nit = mssql_query($nits);
$j=0;
while($row = mssql_fetch_array($nit))
{
	if(!strpos($row['mov_nit_tercero'],'_'))
	{
		$arreglo[$j] = $row['mov_nit_tercero'];
		$conse_nomina = $row['mov_compro'];
		$j++;
	}
}
?>
<table style="font-size:12px; text-align:left" border="1">
	<tr>
        <th>CEDULA</th>
    	<th>NOMBRE</th>
        <th>NOVEDAD</th>
        <th>DESCUENTOS LEGALES</th>
        <th>VALOR TEORICO</th>
        <th>FONDO SOCIAL FABS</th>
        <th>FONDO RETIRO SINDICAL</th>
        <th>FONDO DE VACACIONES</th>
        <th>ADMINISTRACIÓN BÁSICA</th>
        <th>FONDO DE EDUCACION</th>
        <th>SEGURIDAD SOCIAL</th>
        <th>RETEFUENTE</th>
        <th>DESCUENTO FACTURA</th>
        <th>OTROS DESCUENTOS</th>
        <th>INGRESO BASE</th>
    </tr>
  
<?php
$i = 0;
  while($i<sizeof($arreglo)){
	$p=0;
	$res_nomina=$ins_nomina->trae_datos_nomina($conse_nomina,$arreglo[$i],2);
	$dat_asociado = mssql_fetch_array($res_nomina);
	$admon=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'263535');
	$honorarios=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23352501');
	$retefuente=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23651001');
	$compenasociado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23809501');
	$compenompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23809501');
	$segsocialnomcau=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'13250594');//25302001//13250591
	
	$vacnompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23359501');
	$fabspagado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'25301006');
	$fonretsindical=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'31400101');
	$pubcontrato=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'61651035');
	$nov = $honorarios + $retefuente + $compenasociado + $vacnompagada + $fabspagado + $fonretsindical + $pubcontrato + $segsocialnomcau;
	
	$porcentaje=$nov*5/100;//4 DE ADMINISTRACIÓN Y 1 DE EDUCACIÓN = 5
	
	$novedad=$nov+$porcentaje;
	
	$trae_descuentos=$ins_nomina->con_des_nomina($arreglo[$i],$conse_nomina);
	$numero=mssql_num_rows($trae_descuentos);
	if($numero!=0)
	{
		$resultado=mssql_fetch_array($trae_descuentos);
		$res_compenasociado=$compenasociado-$resultado['des_ane_dinero'];
		$descuento_factura=$resultado['des_ane_dinero'];
	}
	else
	{
		$res_compenasociado=$compenasociado;
		$descuento_factura=0;
	}
	$trae_descuentos=$ins_nomina->con_des_nomina($arreglo[$i],$conse_nomina);
	$numero=mssql_num_rows($trae_descuentos);

 $factura = "SELECT mov_nume,fac_val_total,mov_cent_costo FROM movimientos_contables
 INNER JOIN factura on fac_id = mov_nume WHERE mov_compro='$conse_nomina'";
 $dat_fac = mssql_query($factura);
 $datos_fac = mssql_fetch_array($dat_fac);
 $fac_id = $datos_fac['mov_nume'];
 $fac_valor = $datos_fac['fac_val_total'];
 $cen_cos = $datos_fac['mov_cent_costo'];
 
 $sum_descuentos = "SELECT SUM(des_monto) descuentos FROM descuentos d
 INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id WHERE f.fac_id=$fac_id";

$recibo_caja = "SELECT trans.trans_fac_num recibo FROM transacciones tra
INNER JOIN transacciones trans ON tra.tran_tran_id = trans.trans_id WHERE tra.trans_sigla='$conse_nomina'";
$query_recibo = mssql_query($recibo_caja);
$dat_recibo = mssql_fetch_array($query_recibo);

$dat_descuentos = "SELECT SUM(des_nom_valor) valor FROM descuentos_compensacion
WHERE des_nom_nit = $arreglo[$i] AND des_nom_factura=$fac_id AND des_nom_rec_caja = ".$dat_recibo['recibo'];

$des_factura = mssql_query($dat_descuentos);
$datos_descuento = mssql_fetch_array($des_factura);
$valor_descuento = $datos_descuento['valor'];
 $rec_caj_des = mssql_query($sum_descuentos);
 $dat_rec_caja = mssql_fetch_array($rec_caj_des);
 $total_descuentos = $dat_rec_caja['descuentos'];
 $cant_jornadas = $reporte->canJorFac($fac_id);
 $val_jornada = $fac_valor/$cant_jornadas;
 
 $jor_asociado = "select rep_jor_num_jornadas from reporte_jornadas rj 
inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen
where npcc.nit_id = ".$arreglo[$i]." and cen_cos_id = $cen_cos and rep_jor_num_factura = $fac_id";
 $jor_aso = mssql_query($jor_asociado);
 $can_jornadas = mssql_fetch_array($jor_aso);
 $cantidad = $can_jornadas['rep_jor_num_jornadas'];
 $val_facturado = $cantidad*$val_jornada;
 $por_facturado = ($cantidad*100)/$cant_jornadas;
 $por_descontado = $total_descuentos*($por_facturado/100);
 $descuento = $val_facturado-$por_descontado;
 $administracion = $descuento*0.04;
 $educacion = $descuento*0.01;
	 echo "<tr><td>".$dat_asociado['nits_num_documento']."</td>";
	 echo "<td>".$dat_asociado['nombre']."</td>";
	 echo "<td>".number_format($val_facturado,2)."</td>";
	 echo "<td>".number_format($por_descontado,2)."</td>";
	 echo "<td>".number_format($descuento,2)."</td>";
	 echo "<td>".number_format($fabspagado,2)."</td>";
	 echo "<td>".number_format($fonretsindical,2)."</td>";
	 echo "<td>".number_format($vacnompagada,2)."</td>";
	 echo "<td>".number_format($administracion,2)."</td>";
	 echo "<td>".number_format($educacion,2)."</td>";
	 echo "<td>".number_format($segsocialnomcau,2)."</td>";
	 echo "<td>".number_format($retefuente,2)."</td>";
	 echo "<td>".number_format($descuento_factura,2)."</td>";
	 echo "<td>".number_format($valor_descuento,2)."</td>";
	 echo "<td>".number_format($res_compenasociado,2)."</td></tr>";
	 $i++;
  }
  echo "</table>";
?>
<?php
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
$elvalor=$_POST['elvalor'];
$valores=explode("-",$elvalor,4);
$adi_otr_id=$valores[0];
$con_adi_otrosi=$ins_contrato->ConTodDatAdioOtrosi($adi_otr_id);
$row=mssql_fetch_array($con_adi_otrosi);
$con_pol_o_impuestos=$ins_contrato->ConPolOImpPorAdicion($valores[3],$adi_otr_id);
$con_pol_o_impuestos_informativos=$ins_contrato->ConPolOImpInformativaPorAdicion($valores[3],$adi_otr_id);
$res="";
echo "<br>";
if($valores[2]==6)
	$res.="<tr><th>Dinero:</th><td><input type='text' name='dinero' onKeyPress='return permite(event,'num')' value=".number_format($row['adi_otr_valor'])." /></td></tr>";
elseif($valores[2]==7)
	$res.="<tr><th>N&deg; Meses</th><td><input type='text' name='meses' onKeyPress='return permite(event,'num')' value=".$row['adi_otr_meses']." /></td></tr>";
elseif($valores[2]==8)
{
	$res.="<tr><th>Dinero:</th><td><input type='text' name='dinero' onKeyPress='return permite(event,'num')' value=".number_format($row['adi_otr_valor'])." /></td>";
	$res.="<th>N&deg; Meses</th><td colspan='2'><input type='text' name='meses' onKeyPress='return permite(event,'num')' value=".$row['adi_otr_meses']." /></td></tr>";
}
	$res.="<tr><th>Nota: </th><td colspan='7'><textarea cols='100' rows='2' name='nota'>".$row['adi_otr_nota']."</textarea></td></tr>";
	$res.="<tr><th colspan='10'>POLIZAS Y/O IMPUESTOS ASIGNADOS A LA ADICI&Oacute;N</th></tr>";
while($res_pol_o_impuestos=mssql_fetch_array($con_pol_o_impuestos))
{
	$res.="<tr><th>Aseguradora</th><td>".$res_pol_o_impuestos['nits_nombres']."</td>";
	$res.="<th>Poliza o Impuesto</th><td>".$res_pol_o_impuestos['con_nombre']."</td>";
	$res.="<th>Valor</th><td>".number_format($res_pol_o_impuestos['con_por_con_porcentaje'])."</td>";
    $res.="<th>Tipo</th><td>DESCONTABLE</td>";
	$res.="<th>Observacion</th><td>".$res_pol_o_impuestos['con_por_con_observacion']."</td></tr>";
}

while($res_pol_o_impuestos_informativos=mssql_fetch_array($con_pol_o_impuestos_informativos))
{
	$res.="<tr><th>Aseguradora</th><td>".$res_pol_o_impuestos_informativos['nits_nombres']."</td>";
	$res.="<th>Poliza o Impuesto</th><td>".$res_pol_o_impuestos_informativos['con_nombre']."</td>";
	$res.="<th>Valor</th><td>".number_format($res_pol_o_impuestos_informativos['con_por_con_porcentaje'])."</td>";
    $res.="<th>Tipo</th><td>INFORMATIVO</td>";
    $res.="<th>Observacion</th><td>".$res_pol_o_impuestos_informativos['con_por_con_observacion']."</td></tr>";
}
echo $res;
?>
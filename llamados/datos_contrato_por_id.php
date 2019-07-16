<?php
include_once('../clases/contrato.class.php');
include_once('../clases/tipo_contrato_prestacion.class.php');
//echo "el contrato es: ".$_POST['elidcon'];
$id_contrato=$_POST['elidcon'];
$ins_contrato=new contrato();
$ins_tip_contrato=new tipo_contrato_prestacion();
$con_est_contrato=$ins_contrato->con_est_contrato();
$con_dat_contrato=$ins_contrato->consultar_un_contrato_externo2($id_contrato);
$res_dat_contrato=mssql_fetch_array($con_dat_contrato);
$con_tip_contrato=$ins_tip_contrato->con_nom_tip_con_prestacion($res_dat_contrato['tip_con_pre_id']);
error_reporting(E_ALL);
$html="";

$html='<tr><td colspan="4"><h4>DATOS CONTRATO</h4></td></tr>';
$html.='<tr><td colspan="4"><b>Prestaci&oacute;n De Servicios De Anestesia - '.$con_tip_contrato.'</b></td></tr>';
$html.='<tr><td colspan="4"><hr /></td></tr>';
$html.='<tr><td>Consecutivo<input type="text" name="con_num_consecutivo" value="'.$res_dat_contrato['con_hos_consecutivo'].'" disabled="disabled"/></td>';
$html.='<td></td><td></td><td></td></tr>';
$html.='<tr><td>Vigencia Contrato </td><td><input name="con_vigencia" type="text" onFocus="NoSumar();" onBlur="Sumar();" onKeyPress="return permite(event,num)" value="'.$res_dat_contrato['con_vigencia'].'" disabled="disabled"/> MESES</td>';
$html.='<td>Valor Contrato</td><td> $<input name="con_valor" type="text" onFocus="Sumar();" onBlur="NoSumar();" onKeyPress="return permite(event,num)" value="'.number_format($res_dat_contrato['con_valor']).'" disabled="disabled"/></td></tr>';
$html.='<tr>';
if($res_dat_contrato['tip_con_pre_id'] == 1)
{
    $html.='<td>Valor Factura Mensual</td>';
    $html.='<td><input type="text" name="con_cuo_mensual" value="'.$res_dat_contrato['con_val_fac_mensual'].'" disabled="disabled"/></td>';
}
$html.='<td>Valor Hora Diurna</td><td><input type="text" name="con_jor_val_hor_trabajada" value="'.$res_dat_contrato['con_val_hor_trabajada'].'" disabled="disabled" onKeyPress="return permite(event,num)"/></td>';
//$html.='<tr><td>Valor Hora Nocturna</td><td><input type="text" name="con_val_hor_nocturna" id="con_val_hor_nocturna" disabled="disabled" value="'.$res_dat_contrato['con_val_hor_nocturna'].'" /></td>';
$html.='<td>Dias habiles facutra</td><td><input type="text" name="ven_fac" id="ven_fac" value="'.$res_dat_contrato['con_fac_vencimiento'].'" onkeypress="return permite(event,num)" disabled="disabled"/></td>';
$html.='</tr>';
$html.='</tr>';
$html.='<tr><td>Fecha Inicial</td><td><input type="text" name="con_fec_inicial" id="con_fec_inicial" readonly="readonly" value="'.$res_dat_contrato['con_fec_inicio'].'" disabled="disabled"/></td>';
$html.='<td>Fecha Final</td><td><input type="text" name="con_fec_fin" id="con_fec_fin" readonly="readonly" value="'.$res_dat_contrato['con_fec_fin'].'" disabled="disabled"/></td></tr>';
$html.='<tr>';
$html.='<td>Estado Contrato</td>';
$html.='<td><select name="con_estado" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">';
$html.='<option value="" >--Seleccione--</option>';
while($row=mssql_fetch_array($con_est_contrato))
{
    if($res_dat_contrato['est_con_id']==$row['est_con_id'])
    {
        $html.='<option value="'.$row['est_con_id'].'" selected="selected">'.$row['est_con_nombre'].'</option>';
    }
    else
    {
        $html.='<option value="'.$row['est_con_id'].'">'.$row['est_con_nombre'].'</option>';
    }
} 
$html.='</select></td><td>Fecha Legalizacion</td><td><input type="text" name="fec_legalizado" id="fec_legalizado" readonly="readonly" value="'.$res_dat_contrato['con_fec_leg'].'" disabled="disabled"/></td>';
$html.='</tr>';
$html.='<tr>';
$html.='<td colspan="4"><center><b>Observaciones</b></center><textarea name="observa" id="observa" rows="2" cols="100" disabled="disabled">'.$res_dat_contrato['con_observacion'].'</textarea></td>';
$html.='</tr>';
echo $html;
?>
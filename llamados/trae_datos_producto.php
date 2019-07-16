<?php
@include_once('./clases/producto.class.php');
@include_once('../clases/producto.class.php');
@include_once('./clases/tipo_producto.class.php');
@include_once('../clases/tipo_producto.class.php');
@include_once('./clases/cuenta.class.php');
@include_once('../clases/cuenta.class.php');
$ins_cuenta=new cuenta();
$ins_tip_producto=new tipo_producto();
$ins_producto=new producto();
$producto=$_POST['id'];
$res="";
$res_dat_producto=$ins_producto->ConDatProPorId($producto);
$con_tod_tip_producto=$ins_tip_producto->cons_tipo_producto();
$con_tod_cuentas=$ins_cuenta->con_cue_menores(2365);
$res.='<tr><th>Nombre:</th><td><input type="text" name="pro_nombre" id="pro_nombre" required value="'.$res_dat_producto['pro_nombre'].'" disabled/></td>';
$res.='<th>Iva:</th><td><input type="text" name="pro_iva" id="pro_iva" value="'.$res_dat_producto['pro_iva'].'" size="3" disabled/>%</td></tr>';
$res.='<tr><th>Cuenta retenci&oacute;n</th><td><select name="pro_cue_retencion" id="pro_cue_retencion" disabled><option value="">--</option>';
while($res_tod_cuentas=mssql_fetch_array($con_tod_cuentas))
{
	if($res_tod_cuentas['cue_id']==$res_dat_producto['pro_retencion'])
		$res.='<option value="'.$res_tod_cuentas['cue_id'].'" selected="selected">'.$res_tod_cuentas['cue_nombre'].'</option>';
	else
		$res.='<option value="'.$res_tod_cuentas['cue_id'].'">'.$res_tod_cuentas['cue_nombre'].'</option>';
}
$res.='</td>';
$res.='<th>Tipo producto</th><td><select name="tip_pro_id" id="tip_pro_id" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled><option value="">--</option>';
while($res_tod_tip_producto=mssql_fetch_array($con_tod_tip_producto))
{
	if($res_tod_tip_producto['tip_pro_id']==$res_dat_producto['tip_pro_id'])
		$res.='<option value="'.$res_tod_tip_producto['tip_pro_id'].'" selected="selected">'.$res_tod_tip_producto['tip_pro_nombre'].'</option>';
	else
		$res.='<option value="'.$res_tod_tip_producto['tip_pro_id'].'">'.$res_tod_tip_producto['tip_pro_nombre'].'</option>';
}
$res.='</td></tr>';
$res.='<tr><td colspan="4"><input type="button" id="mod" name="mod" value="Modificar" onClick="habilitar();"/> || <input type="submit" id="gua" name="gua" value="Guardar" disabled/></td></tr>';
echo $res;
?>
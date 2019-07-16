<?php
@include_once('../clases/factura.class.php');
@include_once('clases/factura.class.php');
$ins_factura=new factura();
$fac_consecutivo=$_POST['fac_conse'];
//echo "la fac eso: ".$fac_consecutivo;
$res_dat_con_id=$ins_factura->ConContratoPorFactura($fac_consecutivo);
if($res_dat_con_id!="")
{
    $con_leg_des_por_contrato=$ins_factura->ConLegDescPorContrato($res_dat_con_id,11);
    $num_fil_datos=mssql_num_rows($con_leg_des_por_contrato);
    $res_val_descontar=$ins_factura->ValDesLegPorContrato($res_dat_con_id);
}
$res="";

if($num_fil_datos>0)
{
    $res.="<tr><th>C. COSTO</th>";
    $res.="<th>VALOR DESCONTADO</th>";
    $res.="<th>FACTURA</th>";
    $res.="<th>VALOR FACTURA</th>";
    $res.="<th>MES DE SERVICIO</th>";
    //$res.="<th>TIPO DESCUENTO</th>";
    $res.="<th>CONSECUTIVO CONTRATO</th>";
    $res.="<th>VIGENCIA</th>";
    $res.="<th>FECHA INICIO</th>";
    $res.="<th>FECHA FIN</th></tr>";
    $suma_descontado=0;
    while($res_leg_des_por_contrato=mssql_fetch_array($con_leg_des_por_contrato))
    {
        $res.="<tr><td>".$res_leg_des_por_contrato['cen_cos_nombre']."</td>";
        $res.="<td style='text-align:right'>".number_format($res_leg_des_por_contrato['valor_descontado'])."</td>";
        $suma_descontado+=$res_leg_des_por_contrato['valor_descontado'];
        $res.="<td>".$res_leg_des_por_contrato['fac_consecutivo']."</td>";
         $res.="<td style='text-align:right'>".number_format($res_leg_des_por_contrato['fac_val_total'])."</td>";
         $res.="<td>".$res_leg_des_por_contrato['fac_mes_servicio']."/".$res_leg_des_por_contrato['fac_ano_servicio']."</td>";
        //$res.="<td>".$res_leg_des_por_contrato['tip_des_nombre']."</td>";
        $res.="<td>".$res_leg_des_por_contrato['con_hos_consecutivo']."</td>";
        $res.="<td>".$res_leg_des_por_contrato['con_vigencia']."</td>";
        $res.="<td>".$res_leg_des_por_contrato['con_fec_inicio']."</td>";
        $res.="<td>".$res_leg_des_por_contrato['con_fec_fin']."</td></tr>";
    }
    $res.="<tr><th>TOTAL DESCONTADO</th><th style='text-align:right'>".number_format($suma_descontado)."</th>/tr>";
    $res.="<tr><th>VALOR A DESCONTAR</th><th style='text-align:right'>".number_format($res_val_descontar)."</th>/tr>";
    $res.="<tr><th>DIFERENCIA</th><th style='text-align:right;color:red;'>".number_format($suma_descontado-$res_val_descontar)."</th>/tr>";
}
else
{
    $res.="<tr><th>TOTAL DESCONTADO</th><th style='text-align:right'>".number_format(0)."</th>/tr>";
    $res.="<tr><th>VALOR A DESCONTAR SEGUN CONTRATO</th><th style='text-align:right'>".number_format($res_val_descontar)."</th>/tr>";
    $res.="<tr><th>DIFERENCIA</th><th style='text-align:right;color:red;'>".number_format($res_val_descontar)."</th>/tr>";
    
}
echo $res;
?>
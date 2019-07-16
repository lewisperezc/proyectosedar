<?php
include_once('../clases/contrato.class.php');
include_once('../clases/varios.class.php');
$ins_varios=new varios();
$ins_contrato=new contrato();
$con_con_activos=$ins_contrato->ConsultarContratosActivos(2,2);
header('Content-type:application/vnd.ms-excel');
header("Content-Disposition:attachment;filename=ContratosActivos");
header("Pragma:no-cache");
header("Expires:0");
?>
<style>
.resaltada
{
    background:#708D9E;
}
</style>
<table border="1">
    <?php
    while($res_con_activos=mssql_fetch_array($con_con_activos))
    {
        
        /*$con_adi_otr_por_contrato=$ins_contrato->ConAdiOtrPorContrato($res_con_activos['con_id']);
        $dif_fec_con_fec_adicion=$ins_varios->fecha_diff_function(date('d-m-Y'),$res_con_activos['con_fec_fin']);
        $dif_meses_adicion=$ins_varios->suma_fechas_dias($fecha,$diferencia);*/
        
        $num_meses=$ins_contrato->ConCanMesAdiOtrPorContrato($res_con_activos['con_id']);//CONSULTAR EL NUMERO DE MESES SI TIENE ADICIÓN POR TIEMPO
        
        
        $sum_fechas=$ins_varios->sumar_fecha_a_fecha($res_con_activos['con_fec_fin'],$num_meses);
        
        //echo "La nueva fecha es: ".$res_con_activos['con_id']."___"."Fec fin: ".$res_con_activos['con_fec_fin']."___"."Num meses: ".$num_meses."___".$sum_fechas."<br>";
        
        
        $diferencia=$ins_varios->fecha_diff_function(date('d-m-Y'),$sum_fechas);
        
        if($diferencia>0)//SI LA DIFERENCIA ES MAYOR A 0, EL CONTRATO ESTÁ ACTIVO.
        {
        $i=0;
        ?>
        <tbody>
        <tr>
            <th class="resaltada">NIT</th>
            <th class="resaltada">RAZÓN SOCIAL</th>
            <th class="resaltada">CONSECUTIVO CONTRATO</th>
            <th class="resaltada">VIGENCIA(MESES)</th>
            <th class="resaltada">VALOR CONTRATO</th>
            <!--<th>VALOR LEGALIZACIÓN</th>-->
            <th class="resaltada">FECHA INICIO</th>
            <th class="resaltada">FECHA FIN</th>
        </tr>
        </tbody>
        <tr>
            <td><?php echo $res_con_activos['nits_num_documento'] ?></td>
            <td><?php echo $res_con_activos['nits_nombres'] ?></td>
            <td><?php echo $res_con_activos['con_hos_consecutivo'] ?></td>
            <td><?php echo $res_con_activos['con_vigencia'] ?></td>
            <td><?php echo number_format($res_con_activos['con_valor']); ?></td>
            <!--<td><?php //echo $res_con_activos['val_legalizacion'] ?></td>-->
            <td><?php echo $res_con_activos['con_fec_inicio'] ?></td>
            <td><?php echo $res_con_activos['con_fec_fin'] ?></td>
        </tr>
            <?php
            $i=1;
            if($i==1)
            {
                //INICIO MOSTRAR LAS ADICIONES SI TIENE
                ?>
                <tr>
                    <th colspan="7" class="resaltada">ADICIONES Y/O OTROSIS</th>
                </tr>
                <tr>
                    <th class="resaltada">TIPO</th>
                    <th class="resaltada">MESES</th>
                    <th class="resaltada">VALOR</th>
                    <th class="resaltada">FECHA INICIO</th>
                    <th class="resaltada">FECHA FIN</th>
                    <th colspan="2" class="resaltada">NOTA</th>
                </tr>
                <?php
                $con_adi_otr_contrato=$ins_contrato->ConAdiOtrPorConActivo($res_con_activos['con_id']);
                while($res_adi_otr_contrato=mssql_fetch_array($con_adi_otr_contrato))
                {
                ?>
                    <tr>
                        <td><?php echo $res_adi_otr_contrato['adi_o_otr_nombre']; ?></td>
                        <td><?php echo $res_adi_otr_contrato['adi_otr_meses']; ?></td>
                        <td><?php echo number_format($res_adi_otr_contrato['adi_otr_valor']); ?></td>
                        <td><?php echo $res_adi_otr_contrato['adi_otr_fec_inicio']; ?></td>
                        <td><?php echo $res_adi_otr_contrato['adi_otr_fec_fin']; ?></td>
                        <td colspan="2"><?php echo $res_adi_otr_contrato['adi_otr_nota']; ?></td>
                    </tr>
                <?php
                }
                //FIN MOSTRAR LAS ADICIONES SI TIENE
                
                $con_afi_por_contrato=$ins_contrato->ConsultarAfiliadosPorContrato(1,$res_con_activos['con_id']);
                ?>
                <tr>
                    <th colspan="7" class="resaltada">AFILIADOS QUE PERTENECEN AL CONTRATO</th><!--colspan="8"-->
                </tr>
                <tr>
                    <th class="resaltada">DOCUMENTO</th><!--colspan="2"-->
                    <th colspan="3" class="resaltada">APELLIDOS</th>
                    <th colspan="3" class="resaltada">NOMBRES</th>
                </tr>
                <?php
                while($res_afi_por_contrato=mssql_fetch_array($con_afi_por_contrato))
                {
                ?>
                    <tr>
                        <td><?php echo $res_afi_por_contrato['nits_num_documento']; ?></td><!--colspan="2"-->
                        <td colspan="2"><?php echo $res_afi_por_contrato['nits_apellidos']; ?></td>
                        <td colspan="4"><?php echo $res_afi_por_contrato['nits_nombres']; ?></td>
                    </tr>
                <?php
                }
            }
            ?>
            <tr><td colspan="7">&nbsp;</td></tr><!--colspan="8"-->
            <tr><td colspan="7">&nbsp;</td></tr><!--colspan="8"-->
            <?php
        }
    }
    ?>
</table>
<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=MovimientosCuenta");
header("Pragma: no-cache");
header("Expires: 0");
include_once('../clases/pabs.class.php');
$ins_fabs=new pabs();

include_once('../clases/varios.class.php');
$ins_varios=new varios();

include_once('../conexion/conexion.php');


$mes_contable=$_POST['mes_contable'];
$ano_contable= $_SESSION['elaniocontable'];
$doc_inicial=$_POST['doc_inicial'];
$doc_final=$_POST['doc_final'];

if($mes_contable<=9)
    $nuevo_mes="0".$mes_contable;
else
    $nuevo_mes=$mes_contable;

$can_dias_mes=$ins_varios->diasMes($nuevo_mes,$ano_contable);

$fecha1='01-01-2016';

$fecha2=$can_dias_mes."-".$nuevo_mes."-".$ano_contable;

$cuenta='25052001';

$tipo_nit="1";

$con_datos=$ins_fabs->ConsolidadoFabsALaFecha($cuenta,$fecha1,$fecha2,$tipo_nit);
?>
<table border="1">
	<tr>
        <th>DOCUMENTO AFILIADO</th>
        <th>APELLIDOS AFILIADO</th>
        <th>NOMBRES AFILIADO</th>
        <th>VALOR</th>
        <th>SIGLA</th>
        <th>TIPO</th>
        <th>FECHA ELABORACION</th>
        <th>MES CONTABLE</th>
        <th>A&Ntilde;O CONTABLE</th>
    </tr>
    <?php
    while($res_datos=mssql_fetch_array($con_datos))
    {
    	if($res_datos['mov_compro']=='CIE-2017')
		{
			if(strlen($res_datos['mov_cuent'])==9)
			{
    ?>
			    <tr>
			        <td><?php echo $res_datos['nits_num_documento']; ?></td>
			        <td><?php echo $res_datos['nits_apellidos']; ?></td>
			        <td><?php echo $res_datos['nits_nombres']; ?></td>
			        <?php
			        $nue_valor=str_replace(".", ",", $res_datos['mov_valor']);
			        ?>
			        <td><?php echo $nue_valor; ?></td>
			        <td><?php echo $res_datos['mov_compro']; ?></td>
			        <td><?php echo $res_datos['mov_tipo']; ?></td>
			        <td><?php echo $res_datos['mov_fec_elabo']; ?></td>
			        <td><?php echo $res_datos['mov_mes_contable']; ?></td>
			        <td><?php echo $res_datos['mov_ano_contable']; ?></td>
			    </tr>
    <?php
			}
		}
		else
		{
	?>
			<tr>
		        <td><?php echo $res_datos['nits_num_documento']; ?></td>
		        <td><?php echo $res_datos['nits_apellidos']; ?></td>
		        <td><?php echo $res_datos['nits_nombres']; ?></td>
		        <?php
		        $nue_valor=str_replace(".", ",", $res_datos['mov_valor']);
		        ?>
		        <td><?php echo $nue_valor; ?></td>
		        <td><?php echo $res_datos['mov_compro']; ?></td>
		        <td><?php echo $res_datos['mov_tipo']; ?></td>
		        <td><?php echo $res_datos['mov_fec_elabo']; ?></td>
		        <td><?php echo $res_datos['mov_mes_contable']; ?></td>
		        <td><?php echo $res_datos['mov_ano_contable']; ?></td>
    		</tr>
	<?php	
		}
    }
    ?>
</table>
<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=MovimientosCuenta");
header("Pragma: no-cache");
header("Expires: 0");
include_once('../clases/pabs.class.php');
$ins_fabs=new pabs();
$mes_contable=$_POST['mes_contable'];
$ano_contable= $_SESSION['elaniocontable'];
$doc_inicial=$_POST['doc_inicial'];
$doc_final=$_POST['doc_final'];
$con_datos=$ins_fabs->ConsolidadoFabsPorMes($mes_contable,$ano_contable,$doc_inicial,$doc_final)
?>
<table border="1">
	<tr>
        <th>DOCUMENTO AFILIADO</th>
        <th>APELLIDOS AFILIADO</th>
        <th>NOMBRES AFILIADO</th>
        <th>NIT PROVEEDOR</th>
        <th>NOMBRE PROVEEDOR</th>
        <th>VALOR</th>
        <th>SIGLA</th>
        <th>MES CONTABLE</th>
        <th>A&Ntilde;O CONTABLE</th>
        <th>USUARIO</th>
    </tr>
    <?php
    $total=0;
    while($res_datos=mssql_fetch_array($con_datos))
    {
    	$con_usuario=$ins_fabs->ConUsuRegFabs($res_datos['reg_com_sigla'],$res_datos['reg_com_mes'],$res_datos['reg_com_ano']);
		$res_usuario=mssql_fetch_array($con_usuario);
		
        $total+=$res_datos['reg_com_valor'];
    ?>
    <tr>
        <td><?php echo $res_datos['nits_num_documento']; ?></td>
        <td><?php echo $res_datos['nits_apellidos']; ?></td>
        <td><?php echo $res_datos['nits_nombres']; ?></td>
        <td><?php echo $res_datos['nit_proveedor']; ?></td>
        <td><?php echo $res_datos['nombre_proveedor']; ?></td>
        <td><?php echo $res_datos['reg_com_valor']; ?></td>
        <td><?php echo $res_datos['reg_com_sigla']; ?></td>
        <td><?php echo $res_datos['reg_com_mes']; ?></td>
        <td><?php echo $res_datos['reg_com_ano']; ?></td>
        <td><?php echo $res_usuario['nits_num_documento']." - ".$res_usuario['nits_apellidos']." ".$res_usuario['nits_nombres']; ?></td>
    </tr>
    <?php
    }
    ?>
    <tr>
    	<th colspan="5" align="right">TOTAL:</th><th><?php echo $total; ?></th>
    </tr>
</table>
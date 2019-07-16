<?php
@include_once('../clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();

$mes=$_POST['mes'];
$anio=$_POST['anio'];

$con_tod_datos=$ins_mov_contable->ConsultarDocumentosDescuadrados($mes,$anio);
$filas=mssql_num_rows($con_tod_datos);
if($filas>0){
header('Content-type: application/vnd.ms-excel;charset=utf-8');
header("Content-Disposition: attachment; filename=Documentos descuadrados");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <tr>
    	<th colspan="8">DOCUMENTOS DESCUADRADOS</th>
    </tr>
    <tr>
    	<th>DOCUMENTO</th>
        <th>FECHA CREACI&Oacute;N</th>
        <th>CUENTA</th>
        <th>NIT</th>
        <th>VALOR</th>
        <th>NATURALEZA</th>
        <th>MES CONTABLE</th>
        <th>A�O CONTABLE</th>
    </tr>
    <?php
    while($res_tod_datos=mssql_fetch_array($con_tod_datos)){
    ?>
    <tr>
        <td><?php echo $res_tod_datos['mov_compro']; ?></td>
        <td><?php echo $res_tod_datos['mov_fec_elabo']; ?></td>
        <td><?php echo $res_tod_datos['mov_cuent']; ?></td>
        <td><?php echo $res_tod_datos['mov_nit_tercero']; ?></td>
        <td><?php echo $res_tod_datos['valor']; ?></td>
        <td><?php echo $res_tod_datos['mov_tipo']; ?></td>
        <td><?php echo $res_tod_datos['mov_mes_contable']; ?></td>
        <td><?php echo $res_tod_datos['mov_ano_contable']; ?></td>
    </tr>
    <?php
    }
    ?>
</table>
<?php
}
else
{
    echo "<script>
    alert('No se encontraron datos para generar el reporte, intentelo de nuevo.');
    window.history.back(1);
    </script>";
}
?>
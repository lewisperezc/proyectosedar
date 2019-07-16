<?php session_start(); ?>
<!doctype html>
<html>
<meta charset="utf-8">
<title>ORDENES DE DESEMBOLSO</title>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
</style>
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
		
function popUp(URL){
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=500,left=240,top=600');");
}
function eliminarOrden(orden)
{
    $.ajax({
        type: "POST",
        url: "../llamados/elim_ordenDesembolso.php",
        data: "orden="+orden,
        success: function(msg){
            alert("Orden eliminada correctamente, por favor regargue su ventana");}
    });
}
</script>
</head>
<body alink="#000000" link="#000000" vlink="#000000">
<?php

include_once('../clases/transacciones.class.php');
$ins_transacciones=new transacciones();
$con_tra_por_anio=$ins_transacciones->ConDatOrdDesembolso($_SESSION['elaniocontable']);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>FECHA</th>
            <th>CONSECUTIVO</th>
            <th>PROVEEDOR</th>
            <th>VALOR</th>
            <th>ELIMINAR</th>
        </tr>
    </thead> 
    <tbody>
    <?php
        while($res_tra_por_anio=mssql_fetch_array($con_tra_por_anio)){
            $est=$res_tra_por_anio['est_tra_id'];
	?>
	<tr class="gradeA"> 
            <td><a href="javascript:popUp('../reportes_PDF/desembolso.php?sigla=<?php echo $res_tra_por_anio['trans_sigla']; ?>&tip=1&tercero=<?php echo $res_tra_por_anio['nit_id']; ?>&mes_cont=<?php echo $res_tra_por_anio['tran_mes_contable']; ?>&fecha=<?php echo $res_tra_por_anio['trans_fec_doc']; ?>')"><?php echo $res_tra_por_anio['trans_fec_doc']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/desembolso.php?sigla=<?php echo $res_tra_por_anio['trans_sigla']; ?>&tip=1&tercero=<?php echo $res_tra_por_anio['nit_id']; ?>&mes_cont=<?php echo $res_tra_por_anio['tran_mes_contable']; ?>&fecha=<?php echo $res_tra_por_anio['trans_fec_doc']; ?>')"><?php echo $res_tra_por_anio['trans_sigla']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/desembolso.php?sigla=<?php echo $res_tra_por_anio['trans_sigla']; ?>&tip=1&tercero=<?php echo $res_tra_por_anio['nit_id']; ?>&mes_cont=<?php echo $res_tra_por_anio['tran_mes_contable']; ?>&fecha=<?php echo $res_tra_por_anio['trans_fec_doc']; ?>')"><?php echo $res_tra_por_anio['nits_nombres']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/desembolso.php?sigla=<?php echo $res_tra_por_anio['trans_sigla']; ?>&tip=1&tercero=<?php echo $res_tra_por_anio['nit_id']; ?>&mes_cont=<?php echo $res_tra_por_anio['tran_mes_contable']; ?>&fecha=<?php echo $res_tra_por_anio['trans_fec_doc']; ?>')"><?php echo number_format($res_tra_por_anio['trans_val_total']); ?></a></td>
            <td><a href="javascript:eliminarOrden(<?php echo $res_tra_por_anio['trans_id'] ?>)"><?php if($est==""){echo "Eliminar orden?";} ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
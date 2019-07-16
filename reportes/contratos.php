<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LISTADO DE CONTRATOS</title>
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
</script>
</head>
<body alink="#000000" link="#000000" vlink="#000000">
<?php
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
$con_tod_contrato=$ins_contrato->ConTodConExterno(2);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th style="color:#000;">CONSECUTIVO</th>
            <th style="color:#000;">NIT</th>
            <th style="color:#000;">HOSPITAL</th>
            <th style="color:#000;">FECHA INICIO</th>
            <th style="color:#000;">FECHA FIN</th>
            <th style="color:#000;">DURACI&Oacute;N</th>
        </tr>
    </thead> 
    <tbody>
         <?php
		 while($res_tod_contrato=mssql_fetch_array($con_tod_contrato)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['con_hos_consecutivo']; ?></a></td>
			<td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['nits_num_documento']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['nits_nombres']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['con_fec_inicio']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['con_fec_fin']; ?></a></td>
            <td><a href="javascript:popUp('../reportes_PDF/sinopsis.php?tipo=1&elidcontrato=<?php echo $res_tod_contrato['con_id']; ?>')"><?php echo $res_tod_contrato['con_vigencia']." MESES"; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>
</table>
</body>
</html>
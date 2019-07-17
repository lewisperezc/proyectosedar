<?php session_start();?>
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script> 


<script type="text/javascript" language="javascript" src="javascript/redireccionar_formulario_div.js"></script>

<style type="text/css" title="currentStyle"> 
@import "librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<?php
@include_once('../clases/digitalizar_documento.class.php');
@include_once('clases/digitalizar_documento.class.php');

$ins_dig_documento=new DigitalizarDocumento();
$con_tod_doc_digitalizados=$ins_dig_documento->ConsultarTodosDocumentosPorRutaArchivo($_SESSION['k_nit_id']);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<center>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>DOCUMENTO</th>
            <th>PROPIETARIO</th>
            <th>PERFIL</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>DIGITALIZADOR</th>
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($res_tod_doc_digitalizados=mssql_fetch_array($con_tod_doc_digitalizados)){
		 ?>
		<tr class="gradeA"> 
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['doc_dig_nombre']; ?></a></td>
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['nom_propietario']." ".$res_tod_doc_digitalizados['ape_propietario']; ?></a></td>
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['per_nombre']; ?></a></td>
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['doc_dig_fecha']; ?></a></td>
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['doc_dig_hora']; ?></a></td>
			<td><a id="btn_ver_datos" href="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_2.php?doc_dig_id=<?php echo $res_tod_doc_digitalizados[doc_dig_id]; ?>')"><?php echo $res_tod_doc_digitalizados['nits_nombres']." ".$res_tod_doc_digitalizados['nits_apellidos']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>		
</table>
</center>
</body>
</html>
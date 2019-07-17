<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 ?>

<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="javascript/redireccionar_formulario_div.js"></script>
<style type="text/css" title="currentStyle"> 
@import "librerias/datatable/demo_page.css";
@import "librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<?php 
include_once('clases/contrato.class.php');
unset($_SESSION['con_pre_id']);
$instancia_contrato = new contrato();
$con_con_externo = $instancia_contrato->consultar_todos_contratos_externos(2);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr>
        	<th>Nit</th>
            <th>Contrato</th> 
            <th>Consecutivo</th>
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_con_externo)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="Javascript:void(0);" onclick="Redireccionar('contenido','formularios/consultar_contratos_externos_terminados_2.php?con_id=<?php echo $resul['con_id']."-".$resul['tip_con_ext_id']; ?>');"><?php echo $resul['nits_num_documento']; ?></a></td>
			<td><a href="Javascript:void(0);" onclick="Redireccionar('contenido','formularios/consultar_contratos_externos_terminados_2.php?con_id=<?php echo $resul['con_id']."-".$resul['tip_con_ext_id']; ?>');"><?php echo $resul['nits_nombres']; ?></a></td>
			<td><a href="Javascript:void(0);" onclick="Redireccionar('contenido','formularios/consultar_contratos_externos_terminados_2.php?con_id=<?php echo $resul['con_id']."-".$resul['tip_con_ext_id']; ?>');"><?php echo $resul['con_hos_consecutivo']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>		
</table>
</body>
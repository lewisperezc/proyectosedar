<!DOCTYPE html>
<html>
    <head>  
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" language="javascript"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
</head>
<?php
unset($_SESSION['id_empleado']);
@include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$con_tip_nit = $instancia_nits->con_dat_nit(2);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
    	<tr> 
        	<th>Empleado</th> 
            <th>Documento</th> 
        </tr>
    </thead>
	<tbody> 
         <?php
		 while($resul=mssql_fetch_array($con_tip_nit)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="consultar_empleado_contenedor.php?emp_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nombres']; ?></a></td> 
			<td><a href="consultar_empleado_contenedor.php?emp_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nits_num_documento']; ?></a></td>
		</tr>
        <?php } ?>
    </tbody>		
</table>
</body>
</html>
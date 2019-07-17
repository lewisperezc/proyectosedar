<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 


<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script>


<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_table.css";
@import "librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$instancia_nits = new nits();

$con_tip_nit = $instancia_nits->con_dat_nit($_SESSION['sel_nit']);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<center>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>NOMBRES</th> 
            <th>DOCUMENTO</th> 
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_tip_nit)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="datos_nits_general.php?nit_id=<?php echo $resul['nit_id']; ?>&tip_nit_id=<?php echo $_SESSION['sel_nit']; ?>"><?php echo $resul['nombres']; ?></a></td>
			<td><a href="datos_nits_general.php?nit_id=<?php echo $resul['nit_id']; ?>&tip_nit_id=<?php echo $_SESSION['sel_nit']; ?>"><?php echo $resul['nits_num_documento']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>		
</table>
</center>
</body>
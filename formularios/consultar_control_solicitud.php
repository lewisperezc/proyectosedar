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
$con_tip_nit = $instancia_nits->con_dat_nit(1);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<center>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>REMITENTE</th> 
            <th>DESTINATARIO</th> 
            <th>FECHA SOLICITUD</th>
            <th>ESTADO</th>
        </tr> 
    </thead> 
    <tbody>
    	<tr class="gradeA"> 
			<td><a href="#">PEPITO PEREZ</a></td>
			<td><a href="#">JUAN GOMEZ</a></td>
			<td><a href="#">08-05-2019</a></td>
			<td><a href="#">RESUELTO</a></td>
		</tr>
		
		<tr class="gradeA"> 
			<td><a href="#">DIEGO RUIZ</a></td>
			<td><a href="#">CRISTIAN GIRALDO</a></td>
			<td><a href="#">09-05-2019</a></td>
			<td><a href="#">PENDIENTE</a></td>
		</tr>
		
		 <?php
		 //while($resul = mssql_fetch_array($con_tip_nit)){
		 ?>
		<!--<tr class="gradeA"> 
			<td><a href="consultar_asociado_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nombres']; ?></a></td>
			<td><a href="consultar_asociado_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nits_num_documento']; ?></a></td>
		</tr>-->
        <?php //} ?>
        
	</tbody>		
</table>
</center>
</body>
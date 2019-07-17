<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits=new nits();
$con_tip_nit = $ins_nits->con_dat_nit(1);
?>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script>
	function valida_campos(){
		var form = document.seleccionar_asociado;
		if(form.nit.value=="NULL" || form.desde.value=="" || form.hasta.value=="")
		alert('Debe Llenar Todos Los Campos Para Poder Realizar La Busqueda!!!');
		else
		form.submit();
	}
</script>
<body alink="#000000" link="#000000" vlink="#000000">
<form method="post" name="seleccionar_asociado" id="seleccionar_asociado" action="reportes_PDF/movimientos_fabs.php">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>Afiliado</th> 
            <th>Documento</th> 
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_tip_nit)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="consultar_asociado_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nombres']; ?></a></td>
			<td><a href="consultar_asociado_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nits_num_documento']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>		
</table>
</form>
</body>
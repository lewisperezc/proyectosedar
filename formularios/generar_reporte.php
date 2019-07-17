<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script> 
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
@import "librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
		
function popUp(URL){
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=850,height=500,left=240,top=112');");
}
</script>

<?php 
@include_once('../clases/varios.class.php');
@include_once('clases/varios.class.php');
$ins_varios = new varios();
$con_cas_uso = $ins_varios->con_arc_tip_reporte($e);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<div class="div_tabla">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th style="color:#000;">CONSECUTIVO</th>
            <th style="color:#000;">REPORTE</th>
        </tr>
    </thead> 
    <tbody>
         <?php
		 while($resul = mssql_fetch_array($con_cas_uso)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="javascript:popUp('<?php echo $resul['for_rep_archivo']; ?>')"><?php echo $resul['for_rep_id']; ?></a></td>
			<td><a href="javascript:popUp('<?php echo $resul['for_rep_archivo']; ?>')"><?php echo $resul['for_rep_nombre']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>
</table>
</div>
</body>
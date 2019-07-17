<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

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

</head>
<body alink="#000000" link="#000000" vlink="#000000">
<?php
include_once('../clases/nits.class.php');
$ins_nits = new nits();
$con_nits = $ins_nits->con_nit_contrato(1,1,2);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>Afiliado</th> 
            <th>Documento</th> 
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_nits)){
		 ?>
		<tr class="gradeA"> 
			<td><a href="devolucion_aportes_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nits_nombres']." ".$resul['nits_apellidos']; ?></a></td>
			<td><a href="devolucion_aportes_2.php?aso_id=<?php echo $resul['nit_id']; ?>"><?php echo $resul['nits_num_documento']; ?></a></td>
		</tr>
        <?php } ?>
	</tbody>		
</table>
</body>
</html>
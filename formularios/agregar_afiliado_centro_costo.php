<?php session_start(); ?>
<!doctype html>
<html>
<meta charset="utf-8">
<title>AGREGAR AFILIADO A CENTRO DE COSTO</title>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>


<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="estilos/screen.css" media="screen"/>


<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";

@import "librerias/datatable/demo_page.css";
@import "librerias/datatable/demo_table.css";

</style>
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
</head>
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits=new nits();
$con_tod_afiliados=$ins_nits->ConProFondo(1);
?>  
<body alink="#000000" link="#000000" vlink="#000000">
<center>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <td>NOMBRES</td>
            <td>DOCUMENTO</td>
        </tr>
    </thead>
    <tbody>
    <?php
        while($res_tod_afiliados=mssql_fetch_array($con_tod_afiliados)){
    ?>
    <tr class="gradeA"> 
            <td><a href="javascript:eliminarOrden(<?php echo $res_tod_afiliados['trans_id'] ?>)">AAA</a></td>
            <td><a href="javascript:eliminarOrden(<?php echo $res_tod_afiliados['trans_id'] ?>)">BBB</a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</center>
</body>
</html>
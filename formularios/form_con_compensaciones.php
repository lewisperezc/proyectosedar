<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
include_once('../clases/factura.class.php');
include_once('../clases/recibo_caja.class.php');
$ano = $_SESSION['elaniocontable'];
$recibo = new rec_caja();
$factura = new factura();
$fact = $factura->fact(1,$ano);
$_SESSION['fac_id'] = $row['fac_id'];
?>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<script language="javascript" type="text/javascript">
 function abrePagCompensacion(URL1,URL2,URL3,URL4)
 {
    //
    day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL1, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
	
	eval("page" + (id+1) + " = window.open(URL2, '" + (id+1) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
	
	eval("page" + (id+2) + " = window.open(URL3, '" + (id+2) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
	
	eval("page" + (id+3) + " = window.open(URL4, '" + (id+3) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
	
 }

</script>
<form name="datConsul" id="datConsul" method="post" action="">
<body alink="#000000" link="#000000" vlink="#000000">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr>
        	<th>FECHA RECIBO</th>
        	<th>VALOR RECIBO</th>
            <th>FACTURA</th>
            <th>C. COSTO</th>
        </tr> 
    </thead> 
    <tbody>
    <?php
    for($i=0;$i<sizeof($fact);$i++)
    {
    ?>
    <!--FUNCION ANTERIOR(ABRE TODOS LOS ARCHIVOS PDF)
    abrePagCompensacion('../reportes_PDF/pago_causacion.php?valor='+<?php //echo $fact[$i][1]; ?>,'../reportes_PDF/ajuste_causacion.php?valor='+<?php //echo $fact[$i][1]; ?>,'../reportes_PDF/repResumen.php?valor='+<?php //echo $fact[$i][1]; ?>+'&elrecibo='+<?php //echo $fact[$i][2]; ?>+'&valfactura='+<?php //echo $fact[$i][4]; ?>+'&conse_recibo='+<?php //echo $fact[$i][3]; ?>,'../reportes_PDF/ord_pago.php?valor='+<?php //echo $fact[$i][1]; ?>+'&elrecibo='+<?php //echo $fact[$i][2]; ?>+'&valfactura='+<?php //echo $fact[$i][4]; ?>+'&conse_recibo='+<?php //echo $fact[$i][3]; ?>,'../reportes_PDF/nom_centro.php?valor='+<?php //echo $fact[$i][1]; ?>+'&elrecibo='+<?php //echo $fact[$i][2]; ?>+'&valfactura='+<?php //echo $fact[$i][4]; ?>+'&conse_recibo='+<?php //echo $fact[$i][3]; ?>)
    -->
    <tr class="gradeA">
    	<td><a href="Javascript:abrePagCompensacion('../reportes_PDF/pago_causacion.php?elrecibo='+<?php echo $fact[$i][2]; ?>+'&valor='+<?php echo $fact[$i][1]; ?>,'../reportes_PDF/repResumen.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/ord_pago.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/nom_centro.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>)"><?php echo $fact[$i][7]; ?></a></td>
    	<td><a href="Javascript:abrePagCompensacion('../reportes_PDF/pago_causacion.php?elrecibo='+<?php echo $fact[$i][2]; ?>+'&valor='+<?php echo $fact[$i][1]; ?>,'../reportes_PDF/repResumen.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/ord_pago.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/nom_centro.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>)"><?php echo number_format($fact[$i][5]); ?></a></td>
    	<td><a href="Javascript:abrePagCompensacion('../reportes_PDF/pago_causacion.php?elrecibo='+<?php echo $fact[$i][2]; ?>+'&valor='+<?php echo $fact[$i][1]; ?>,'../reportes_PDF/repResumen.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/ord_pago.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/nom_centro.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>)"><?php echo $fact[$i][0]; ?></a></td>
        <td><a href="Javascript:abrePagCompensacion('../reportes_PDF/pago_causacion.php?elrecibo='+<?php echo $fact[$i][2]; ?>+'&valor='+<?php echo $fact[$i][1]; ?>,'../reportes_PDF/repResumen.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/ord_pago.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>,'../reportes_PDF/nom_centro.php?valor='+<?php echo $fact[$i][1]; ?>+'&elrecibo='+<?php echo $fact[$i][2]; ?>+'&valfactura='+<?php echo $fact[$i][4]; ?>+'&conse_recibo='+<?php echo $fact[$i][3]; ?>)"><?php echo $fact[$i][6]; ?></a></td>
    </tr>
    <?php
    }
    ?>
    </tbody>		
</table>
</form>
</body>
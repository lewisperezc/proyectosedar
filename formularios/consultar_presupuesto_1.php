<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script>
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script> 

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
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=900,left=240,top=112');");
}

function redireccionar(val,centro,fecha){
	var form=document.consultar_presupuesto_1;
	if(val==1)
	{
		popUp('./formularios/consultar_presupuesto_2.php?variables='+centro+"-"+fecha);
	}
	else
	{
		if(val==2)
		{
			form.action='reportes_EXCEL/presupuesto.php?variables='+centro+"-"+fecha;
			form.submit();
		}
	}
	
}

</script>
<?php
@include_once('../clases/presupuesto.class.php');
@include_once('clases/presupuesto.class.php');
@include_once('../clases/centro_de_costos.class.php');
@include_once('clases/centro_de_costos.class.php');

$ins_presupuesto=new presupuesto();
$con_cen_cos_es_nit=$ins_presupuesto->con_cen_cos_con_presupuesto();
?>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css">
<body alink="#000000" link="#000000" vlink="#000000" bgcolor="#FFFFFF">
<div class="div_tabla">
<form method="post" name="consultar_presupuesto_1" id="consultar_presupuesto_1">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" bgcolor="#FFFFFF" width="100%">
    <thead bgcolor="#FFFFFF"> 
        <tr> 
            <th style="color:#000">Centro De Costo</th>
            <th style="color:#000">Año</th>
            <th style="color:#000">Consultar/Modificar</th>
            <!--<th style="color:#000">Comparar Presupuestado/Gastado</th>-->
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_cen_cos_es_nit)){
		 ?>
		<tr class="gradeA">
			<td><a  href="Javascript:void(0);"><?php echo $resul['cen_cos_nombre']; ?></a></td>
            <td><a href="Javascript:void(0);"><?php echo $resul['cue_por_cen_cos_fecha']; ?></a></td>
            <td><input type="radio" name="opcion" value="1" onclick="redireccionar(this.value,<?php echo $resul['cen_cos_id']; ?>,<?php echo $resul['cue_por_cen_cos_fecha']; ?>)"/></td>
		</tr>
        <?php } ?>
	</tbody>
</table>
</form>
</div>
</body>
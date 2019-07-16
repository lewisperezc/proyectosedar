<?PHP session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado de facturas</title>
<link rel="stylesheet" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="librerias/jquery-ui-1.10.3.custom/development-bundle/themes/base/jquery.ui.all.css">

<link rel="stylesheet" type="text/css" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/themes/base/jquery.ui.all.css"/>
<link rel="stylesheet" type="text/css" href="librerias/jquery-ui-1.10.3.custom/development-bundle/themes/base/jquery.ui.all.css"/>

<script src="../librerias/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.core.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.widget.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.mouse.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.draggable.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.position.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.position.js"></script>

<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>



<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.dialog.js"></script>

<link rel="stylesheet" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/demos/demos.css">
<link rel="stylesheet" href="librerias/jquery-ui-1.10.3.custom/development-bundle/demos/demos.css">

<link rel="stylesheet" type="text/css" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/demos/demos.css"/>
<link rel="stylesheet" type="text/css" href="librerias/jquery-ui-1.10.3.custom/development-bundle/demos/demos.css"/>

<script>
function AbreListadoFacturas(URL)
{
     day = new Date();
	 id = day.getTime();
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
}

function Error()
{
	$(function(){
	$("#dialog-message" ).dialog({
	modal: true,
	buttons: {
	"Aceptar": function() {
	$( this ).dialog( "close" );
	}
	}
	});
	});
}

function Preguntar(mes,tipo,anio)
{
	if(mes=="")
		Error();
	else
	{
		$(function(){
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: {
			"PDF": function() {
			$( this ).dialog( "close" );
				AbreListadoFacturas('../reportes_PDF/listado_facturas.php?elmes='+mes+'&eltipo='+tipo+'&elanio='+anio);
			},
			"EXCEL": function() {
				//alert(anio);
				$( this ).dialog( "close" );
				AbreListadoFacturas('../reportes_EXCEL/listado_facturas.php?elmes='+mes+'&eltipo='+tipo+'&elanio='+anio);
			}
			}
			});
		});
	}
}
</script>
</head>
<body>
<?php
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_mes_contables=new mes_contable();
$tod_mes=$ins_mes_contables->mes();
?>
<center>

<!--MENSAJE DE ERROR-->
<div id="dialog-message" title="Error" style="display:none;">
	<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>Seleccione una opci&oacute;n valida</p>
</div>
<!--HASTA ACA-->
<!--ESTA ES LA VENTANA DE LA ALERTA-->
<div id="dialog-confirm" title="Pregunta" style="display:none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Seleccione el tipo de archivo que desea descargar</p>
</div>
<!--HASTA ACA-->

<form name="listado_facturas" id="listado_facturas" method="post">
<table border="2" bordercolor="#0099CC">
	<tr>
		<th colspan="4">Lisdato de facturas
        </th>
	</tr>
	<tr>
		<th>Por mes</th>
		<th>General</th>
	</tr>
	<tr>
        <td>
        <select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida" onchange="Preguntar(this.value,1,<?php echo $_SESSION['elaniocontable']; ?>);">
        <option value="">--</option>
        <?php
        while($row=mssql_fetch_array($tod_mes))
        { echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>"; }
         ?>
        </select>
        </td>
    	<th><a href="Javascript:void(0);" onclick="Preguntar(500,2,<?php echo $_SESSION['elaniocontable']; ?>);">ver</a></th>
    </tr>
</table>
</form>
</center>
</body>
</html>
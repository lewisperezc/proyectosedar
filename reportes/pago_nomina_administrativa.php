<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consultar Nominas Pagadas</title>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function popUp()
{
	var form=document.laconsulta;
	var num_quincena=form.num_quincena.value;
	var mes=form.mes.value;
	var anio=form.anio.value;
	var doc_inicial=form.doc_inicial.value;
	var doc_final=form.doc_final.value;
	URL='../reportes_PDF/datos_pag_nomina_administrativa.php?num_quincena='+num_quincena+'&mes='+mes+'&anio='+anio+'&doc_inicial='+doc_inicial+'&doc_final='+doc_final;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=600,left=240,top=320');");
}
</script>
</head>
<body>
<?php
@include_once('../clases/mes_contable.class.php');
@include_once('../clases/presupuesto.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('clases/presupuesto.class.php');
$ins_mes_contable=new mes_contable();
$ins_presupuesto=new presupuesto();
$con_meses=$ins_mes_contable->mes();
$res_anios=$ins_presupuesto->obtener_lista_anios();
?>
<form name="laconsulta" id="laconsulta" method="post" action="Javascript:popUp();">
	<table border="1" bordercolor="#0099CC">
    	<tr>
        	<th>Quincena</th>
            <th>Mes Pago</th>
            <th>A&ntilde;o</th>
            <th>Documento - Desde</th>
            <th>Documento - Hasta</th>
        </tr>
        <tr>
        	<td><select name="num_quincena" id="num_quincena" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <option value="1">1</option>
            <option value="2">2</option>
            </select></td>
            <td><select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
       		<?php
			while($dat_meses=mssql_fetch_array($con_meses))
			{
			?>
            <option value="<?php echo $dat_meses['mes_id']; ?>"><?php echo $dat_meses['mes_nombre']; ?></option>
            <?php
			}
	  		?>
      		</select></td>
            <td><select name="anio" id="anio" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <?php
			for($i=0;$i<sizeof($res_anios);$i++)
			{
			?>
            	<option value="<?php echo $res_anios[$i]; ?>"><?php echo $res_anios[$i]; ?></option>
            <?php 
			}
			?>
            </select></td>
            <td><input type="text" name="doc_inicial" id="doc_inicial" onkeypress="return permite(event,'num')" required="required"/></td>
            <td><input type="text" name="doc_final" id="doc_final" onkeypress="return permite(event,'num')" required="required"/></td>
        </tr>
        <tr>
        	<th colspan="5"><input type="submit" name="con" id="con" value="Consultar"/></th>
        </tr>
    </table>
</form>
</body>
</html>
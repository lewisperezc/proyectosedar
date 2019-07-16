<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LISTADO AFILIADOS CREADOS POR MES</title>
</head>
<body>
<?php
@include_once('clases/presupuesto.class.php');
@include_once('../clases/presupuesto.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/credito.class.php');
@include_once('../clases/credito.class.php');

$ins_mes_contable=new mes_contable();
$ins_presupuesto=new presupuesto();
$anios=$ins_presupuesto->obtener_lista_anios();
$tod_mes=$ins_mes_contable->mes();
$ins_credito=new credito();

$act_est_credito=$ins_credito->ActEstDesNomNegativa(2,$_GET['reci_caj'],$_GET['factu']);
?>
<form id="con_afi" name="con_afi" method="post" action="../reportes_PDF/listado_afiliados_creados_por_mes.php">
<center>
	<table border="1" bordercolor="#00CCFF">
    	<tr>
        	<th colspan="4">LISTADO AFILIADOS CREADOS POR MES</th></tr>
        <tr>
        	<th>A&ntilde;o</th>
            <td>
            <select name="ano" id="ano" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <?php
		    for($a=0;$a<sizeof($anios);$a++)
				echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
		    ?>
            </select>
    		</td>
            <th>Mes</th>
            <td>
            <select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
      			<option value="">Seleccione...</option>
     			<?php
	  			while($row = mssql_fetch_array($tod_mes))
		  			echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 			?>
    		</select>
            </td>
        </tr>
        <tr>
        	<td colspan="4"><input type="submit" name="env" id="env" value="Enviar"/></td>
        </tr>
    </table>
</center>
</form>
</body>
</html>
<!--Ord Des ->770,772-->
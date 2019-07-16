<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LISTADO AFILIADOS CREADOS POR MES</title>
</head>
<body>
<?php
@include_once('clases/nits_tipo.class.php');
@include_once('../clases/nits_tipo.class.php');
$ins_tip_nit=new tipo_nit();
$con_tod_tip_nit=$ins_tip_nit->con_tod_tip_nits();
?>
<form id="con_afi" name="con_afi" method="post" action="../reportes_PDF/listado_nits.php">
<center>
	<table border="1" bordercolor="#00CCFF">
    	<tr>
        	<th colspan="4">LISTADO NITS</th>
       	</tr>
        <tr>
            <th>TIPO DE NIT</th>
            <td>
            <select name="tip_nit_id" id="tip_nit_id" required x-moz-errormessage="Seleccione Una Opcion Valida">
      			<option value="">--Seleccione--</option>
     			<?php
	  			while($res_tod_tip_nit=mssql_fetch_array($con_tod_tip_nit))
		  			echo "<option value='".$res_tod_tip_nit['nit_tip_id']."'>".$res_tod_tip_nit['nit_tip_nombre']."</option>";
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
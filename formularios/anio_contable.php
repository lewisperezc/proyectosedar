<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
$ins_mesContable=new mes_contable();
$lis_anos = $ins_mesContable->get_anos();
?>

<script>
function val_creAno()
{
	var preg=confirm("Esta seguro que desea crear el a\u00f1o contable?");
	if(preg)
		document.crear_anio_contable.submit();
}
</script>
<form name="anio_contable" id="anio_contable" method="post" action='control/guardar_act_anoContable.php'>
<center>
<table>
	<tr><td colspan='2'>A&ntilde;o Contable</td></tr>
		<?php
			$cont=0;
			while($row=mssql_fetch_array($lis_anos))
			{
				echo "<tr><td>".$row['ano_con_id']."</td><td><select name='sele".$cont."'>";
				if($row['ano_con_estado']==1)
				{
					echo "<option value='1-".$row['ano_con_id']."' selected>Cerrado</option><option value='2-".$row['ano_con_id']."'>Abierto</option>";
				}
				else
					echo "<option value='1-".$row['ano_con_id']."'>Cerrado</option><option value='2-".$row['ano_con_id']."' selected>Abierto</option>";
				
				echo "</select></td></tr>";
				$cont++;
			}
		?>
    <tr>
    	<input type='hidden' name='cant' id='cant' value='<?php echo $cont; ?>'>
    	<td colspan="2"><input type="submit" class="art-button" name="btn" id="btn" value="Enviar"/></td>
    </tr>
</table>
</center>
</form>

<br><br><br>

<form name="crear_anio_contable" id="crear_anio_contable" method="post" action='control/guardar_act_anoContable.php?crear=1'>
<center>
<table>
    <tr>
    	<td colspan="2"><input type="button" class="art-button" name="btn" id="btn" value="Crear A&ntilde;o Contable" onClick='val_creAno();'/></td>
    </tr>
</table>
</center>
</form>
</body>
</html>
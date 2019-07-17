<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
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
$estados=2;
$lis_anos = $ins_mesContable->ObtenerAniosPorEstado($estados);

if($btn&&isset($_POST['anio']))
{
	$_SESSION['elaniocontable']=$_POST['anio'];
	if($_GET['elaniocont']==1)
	{
		echo "<script>location.reload();</script>";
	}
	elseif($_POST['elaniocont']==2)
		echo "<script>alert('Registro actualizado correctamente.');</script>";
	
}
?>
<form name="anio_activo" id="anio_activo" method="post">
<center>
<table>
	<tr>
		<td>A&ntilde;o activo</td>
    	<td>
        	<select name="anio" id="anio">
        		<?php
			    while($row=mssql_fetch_array($lis_anos))
              	{
              		if($row['ano_con_id']==$ano)
                		echo "<option value='".$row['ano_con_id']."' selected>".$row['ano_con_id']."</option>";
					else
						echo "<option value='".$row['ano_con_id']."'>".$row['ano_con_id']."</option>";
              	}
				?>
    		</select>
            <input type="hidden" name="elaniocont" id="elaniocont" value="2"/>
    	</td>
	</tr>
    <tr>
    	<td colspan="2"><input type="submit" class="art-button" name="btn" id="btn" value="Enviar"/></td>
    </tr>
</table>
</center>
</form>
</body>
</html>
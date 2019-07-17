<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Otrosi - <?php echo $_SESSION['cons']." - ".$_SESSION['nomb']; ?></title>
</head>
<body>
<?php
include_once('../clases/contrato.class.php');
$ins_contrato = new contrato();
$con_adi_contrato = $ins_contrato->con_adi_otr_contrato(2,$_SESSION['sele_contrato']);
?>
<form>
<center>
	<table border="1" bordercolor="#009999" width="100%">
    	<tr>
        	<th colspan="4">DATOS OTROSI</th>
            <tr>
            	<th>Fecha</th>
                <th>Nota</th>
            </tr>
            <?php
            while($res_adi_contrato = mssql_fetch_array($con_adi_contrato)){
			?>
            <tr>
            	<td><?php echo $res_adi_contrato['adi_otr_fecha']; ?></td>
            	<td><?php echo $res_adi_contrato['adi_otr_nota']; ?></td>
            </tr>
            <?php } ?>
    </table>
</center>
</form>
</body>
</html>
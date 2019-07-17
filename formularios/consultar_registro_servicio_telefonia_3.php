<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script>
function enviar(){
	var form = document.form_datos_linea_telefonia;
	form.submit();
}

function enviar2(){
	var form = document.form_estado_linea_telefonia;
	form.submit();
}

</script>
</head>
<body>
<?php
$lin_tel_id = $_POST['select2'];

include_once('../clases/telefonia.class.php');
$ins_telefonia = new telefonia();
$con_tod_pla_por_lin_telefonica = $ins_telefonia->con_tod_pla_por_lin_telefonica($lin_tel_id);

$con_est_lin_telefonia = $ins_telefonia->con_est_lin_tel_seleccionada($lin_tel_id);
$res_est_lin_tel = mssql_fetch_array($con_est_lin_telefonia);

$numero_registros = mssql_num_rows($con_tod_pla_por_lin_telefonica);
?>
<form name="form_estado_linea_telefonia" method="post" action="../control/modificar_estado_linea_telefonia.php">
<center>
	<table border="1">
    	<tr>
        	<th>Estado De La Linea</th>
            <th>Cambiar Estado Linea</th>
        </tr>
    	<tr>
            <td><?php echo $res_est_lin_tel['est_lin_tel_nombres']; ?></td>
            <input type="hidden" name="est_lin_id" id="est_lin_id" value="<?php echo $res_est_lin_tel['est_lin_tel_id']; ?>"/>
            <td><input type="radio" name="cam_est_lin_telefonica" id="cam_est_lin_telefonica" onclick="enviar2();" value="<?php echo $lin_tel_id; ?>"/></td>
       </tr>
    </table>
</center>
</form>
<form name="form_datos_linea_telefonia" method="post" action="../control/modificar_estado_registro_telefonia.php" target="frame3">
<center>
	<table border="1">
    <?php
    if($numero_registros > 0){
	?>
    	<tr>
           <th>Fecha Registro</th>
           <th>Plan</th>
           <th>Valor</th>
           <th>Tipo</th>
           <th>Estado Plan</th>
           <th>Cambiar Estado Plan</th>
        </tr>
        <?php
        while($res_tod_pla_por_lin_telefonica = mssql_fetch_array($con_tod_pla_por_lin_telefonica)){
		?>
        <tr>
        	<td><?php echo $res_tod_pla_por_lin_telefonica['lin_tel_fec_creacion']; ?></td>
            <td><?php echo $res_tod_pla_por_lin_telefonica['pla_tel_nombre']; ?></td>
            <td><?php echo $res_tod_pla_por_lin_telefonica['pla_tel_valor']; ?></td>
            <td><?php if($res_tod_pla_por_lin_telefonica['tip_lin_tel']==1){ echo "FABS"; }
                      elseif($res_tod_pla_por_lin_telefonica['tip_lin_tel']==2){ echo "Pagare"; }
					  elseif($res_tod_pla_por_lin_telefonica['tip_lin_tel']==3){ echo "Gasto"; }
			?>
            </td>
            <td><?php echo $res_tod_pla_por_lin_telefonica['est_reg_tel_nombres']; ?></td>
            <td><input type="radio" name="cam_estado" id="cam_estado" value="<?php echo $res_tod_pla_por_lin_telefonica['est_reg_tel_id']."-".$res_tod_pla_por_lin_telefonica['lin_tel_por_pla_id']; ?>" onclick="enviar();"/></td>
        </tr>
        <?php
		}
	}
	else{
		?>
       <tr><th>No se encontraron planes de telefonia asignados a esta linea.</th></tr>
    <?php } ?>
    </table>
 </center>
</form>
</center>
</body>
</html>
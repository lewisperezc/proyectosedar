<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script>
function enviar()
{
	document.seleccion.submit();
}
</script>
</head>
<body>
<?php
include_once('../clases/contrato.class.php');

$ins_contrato = new contrato();
$con_tod_tip_adicion = $ins_contrato->con_adi_o_otrosi();
?>
<form method="post" name="seleccion" action="agregar_otrosi_adicion_2.php" target="frame2">
	<center>
        <table>
        <tr>
            <th>Agregar</th>
            <td><select name="agregar" id="agregar" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="" onclick="">--Seleccione--</option>
            <?php while($res_tod_tip_adicion = mssql_fetch_array($con_tod_tip_adicion)){ ?>
                    <option value="<?php echo $res_tod_tip_adicion['adi_o_otr_id'] ?>" onclick="enviar();"><?php echo $res_tod_tip_adicion['adi_o_otr_nombre'] ?></option>
            <?php } ?>
            </select></td>
         </tr>
    </table>
    </center>
</form>
</body>
</html>
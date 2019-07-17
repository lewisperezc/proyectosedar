<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/factura.class.php');
@include_once('clases/factura.class.php');
$ins_factura=new factura();
$con_tod_fac_sin_pagar=$ins_factura->ConTodFacSinPagar(2,0);
?>
<script language="javaScript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
function ObtenerDatos(fac_consecutivo)
{
	//alert(fac_consecutivo);
    $.ajax({
    type: "POST",
    url: "../llamados/trae_datos_descuentos_legalizacion.php",
    data: "fac_conse="+fac_consecutivo,
    success: function(msg){
    $("#dat_val_descontado").html(msg);
    }
    });
}
</script>
<form>
    <table border="1">
        <tr>
            <th>INGRESE EL CONSECUTIVO DE LA FACTURA QUE VA A PAGAR:</th>
            <td>
            <input type="text" name="fac_consecutivo" id="fac_consecutivo" list="fac_conse" onchange='ObtenerDatos(this.value);' required="required"/><datalist id="fac_conse">
            <?php
                while($res_tod_fac_sin_pagar=mssql_fetch_array($con_tod_fac_sin_pagar))
                { echo "<option value='".$res_tod_fac_sin_pagar['fac_consecutivo']."'>".$res_tod_fac_sin_pagar['fac_consecutivo']."</option>"; }
            ?>
            </td>
        </tr>
    </table>
    <table id="dat_val_descontado" border="1">
    </table>
</form>
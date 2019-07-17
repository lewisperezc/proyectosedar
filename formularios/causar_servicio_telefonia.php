<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script>
function seleccion()
{
	document.form_sel_tip_nit.submit();
}
</script>
<script src="../librerias/js/validacion_num_letras.js"/></script>
<center>
<form name="form_sel_tip_nit" method="post" action="causar_servicio_telefonia_2.php" target="frame2">
    <center>
    <table border="1">
        <tr>
            <td>
                <select name="tipo_nit" id="tipo_nit" required x-moz-errormessage="Seleccione Una Opcion Valida">
                    <option value="">Seleccione</option>
                       <option value="1" onclick="seleccion();">Afiliado</option>
                       <option value="2" onclick="seleccion();">Empleado</option>
                 </select>
            </td>
        </tr>
    </table>
    </center>
</form>
</center>
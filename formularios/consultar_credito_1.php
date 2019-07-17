<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script>
function enviar()
{
	document.selecciona_tipo_persona.submit();
}
</script>
<form name="selecciona_tipo_persona" method="post" target="frame2" action="consultar_credito_2.php">
	<center>
        <table>
        <tr>
            <td><b>Consulta De Cr&eacute;ditos</b></td>
        </tr>
        <tr>
            <tr>
                <td><select name="sel_tip_persona" id="sel_tip_persona" required x-moz-errormessage="Seleccione Una Opcion Valida">
                    <option value="">--Seleccione--</option>
                    <option value="1" onclick="enviar();">Afiliado</option>
                    <option value="2" onclick="enviar();">Empleado</option>
                    <option value="3" onclick="enviar();">Proveedor</option>
                </select></td>
            </tr>
        </tr>
    </table>
    </center>
</form>
<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script>
function enviar()
{
	document.consaldos.submit();
}
</script>
<form id="consaldos" name="consaldos" method="post" action="consultar_moviminetos1.php" target="frame2">
    <center>
        <table id="conOrd">
        <tr><td colspan="2">estamos consultando moviminetos contables ?</td></tr>
         <tr> <td>Consultar por: </td>
         <td>
             <select name="consaldos" id="consaldos" required x-moz-errormessage="Seleccione Una Opcion Valida">
                <option value="">--Seleccione--</option>
                <option value="1" onclick="enviar();">Modificar movimientos contables</option>
                <option value="2" onclick="enviar();">Consultar saldos documentos contables</option>
             </select>
         </td>
         </tr>
    </table>
    </center>
</form>
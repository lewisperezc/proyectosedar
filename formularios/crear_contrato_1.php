<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('./clases/contrato.class.php');
$instancia_contrato = new contrato();
$consultar = $instancia_contrato->consulta_tipo_contrato();
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function Mostrar(dato)
{
    if(dato==0)
    {
        $("#con_interno").css("display", "none");
        $("#con_externo").css("display", "none");
    }
    if(dato==1)
    {
        $("#con_interno").css("display", "block");
        $("#con_externo").css("display", "none");
    }
    if(dato==2)
    {
        $("#con_interno").css("display", "none");
        $("#con_externo").css("display", "block");
    }
}
</script>
    <div id='tip_contrato'>
    <table>
        <tr>
            <td><select name="sel_tip_contrato" id="sel_tip_contrato" required x-moz-errormessage="Seleccione Una Opcion Valida" onchange="Mostrar(this.value);">
            <option value="">--</option>
            <?php
            while($row=mssql_fetch_array($consultar))
            {
            ?>
                <option value="<?php echo $row['tip_con_id']; ?>" onclick="Mostrar(this.value);"><?php echo $row['tip_con_nombre']; ?></option>
            <?php
            }
            ?>
            </select></td>
        </tr>
    </table>
    </div>
    <div id="con_interno" style="display:none">
        <?php include_once 'crear_empleado_contenedor.php'; ?>
    </div>
    <div id="con_externo" style="display:none">
        <?php include_once 'crear_contrato_prestacion_contenedor.php'; ?>
    </div>
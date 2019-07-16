<?php
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$anios=$ins_presupuesto->obtener_lista_anios();
$mes = new mes_contable();
$tod_mes = $mes->mes();
?>
<form name="cartera" id="cartera" action="../reportes_EXCEL/rep_FabsMensual.php" method="post">
<center>
    <table border="1" bordercolor="#0099CC">
        <tr><td>A&ntilde;o</td><td>Mes</td></tr>
        <tr>
            <td>
            <select name="ano" id="ano">
            <option value="0">Seleccione</option>
                <?php
                for($a=0;$a<sizeof($anios);$a++)
                    echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
                ?>
            </select>
            </td>
            <td>
            <select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
                <option value="0">Seleccione...</option>
                <?php
                while($row = mssql_fetch_array($tod_mes))
                    echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
                ?>
            </select>
            </td>
        </tr>
        <tr>
            <td colspan="4"><input type="submit" value="EXCEL"/></td>
        </tr>
  </table>
 </center>
</form>
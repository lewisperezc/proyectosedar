<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/mes_contable.class.php');
$mes = new mes_contable();
$con_meses = $mes->DatosMesesAniosContables($ano);
?>
<center>
<form method="post" action="reportes_excel/seg_social.php">
    <table>
        <tr>
            <th colspan="4">CONSULTA PAGO SEGURIDAD SOCIAL</th>
        </tr>
        <tr>
            <td>Mes de pago</td>
            <td><input type="hidden" name="consulta_seg_social" id="consulta_seg_social" value="2">
                <select name="mes_pag_seg_social" id="mes_pag_seg_social" required>
                <option value="">--</option>
                <?php
                while($res_meses=mssql_fetch_array($con_meses))
                {
                ?>
                    <option value="<?php echo $res_meses['mes_estado']."-".$res_meses['mes_id']; ?>"><?php echo $res_meses['mes_nombre']; ?></option>
                <?php
                }
                ?>
            </select></td>

            <td>A&ntilde;o de pago</td>
            <td><input type="hidden" name="anio_pag_seg_social" id="anio_pag_seg_social" value="<?php echo $ano; ?>"><b><?php echo $ano; ?></b></td>
            
        </tr>
        <tr>
            <td colspan="4"><input type="submit" value="Generar"></td>
        </tr>
    </table>
</form>
</center>
<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
include_once('../clases/credito.class.php');
$instancia_credito = new credito();
$cre_id = $_SESSION['cre_id'];
$persona_id=$_SESSION['sel_persona'];
$res_dat_tab_amo_credito=$instancia_credito->con_dat_tab_amo_credito($cre_id);
?>
<script>
function VentanaEmergente(tipo,credito_id,persona_id)
{
	//tipo=1 creacion, tipo=2 consulta
	URL='../reportes_PDF/tabla_amortizacion_credito.php?cre_id='+credito_id+'&per_id='+persona_id+'&tip_reporte='+tipo;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=500,left=240,top=112');");
}
</script>
<form name="consultar_tabla_amortizacion_credito">
	<center>
    <table border="1">
      <tr>
          <td><b>N&uacute;mero Cuota</b></td>
          <td><b>Fecha</b></td>
          <td><b>Cuota</b></td>
      <td><b>Capital abonado</b></td>
      <td><b>Intereses</b></td>
      <td><b>Saldo</b></td>
          <td><b>Estado</b></td>
        </tr>
        <?php
        while($row=mssql_fetch_array($res_dat_tab_amo_credito))
    {
    ?>
        <tr>
          <td><?php echo $row['tab_amo_num_cuota']; ?></td>
          <td><?php echo $row['tab_amo_fecha']; ?></td>
          <td><?php echo number_format($row['tab_amo_cuota']); ?></td>
      <td><?php echo number_format($row['tab_amo_cap_abonado']); ?></td>
      <td><?php echo number_format($row['tab_amo_intereses']); ?></td>
      <td><?php echo number_format($row['tab_amo_saldo']); ?></td>
        <?php
          if($row['est_tab_amo_id'] == 2)
      {
    ?>
          <td><font color="#FF0000"><b><?php echo $row['est_tab_amo_nombre']; ?></b></font></td>
        <?php
      }
      else
      {
    ?>
            <td><?php echo $row['est_tab_amo_nombre']; ?></td>
        <?php
      }
    ?>
        </tr>
        <?php
    }
    ?>
        </table>
        <table>
        <tr>
          <td colspan="7">
              <input type="submit" class="art-button" name="atras" onClick="document.consultar_tabla_amortizacion_credito.action = 'consultar_credito_4.php'" value="<< Atras"/>
              <input type="submit" class="art-button" name="volver_al_inicio" onClick="document.consultar_tabla_amortizacion_credito.action = 'consultar_credito_3.php'" value="<< Volver Al Inicio"/>
              <input type="button" class="art-button" name="imp_tab_amortizacion" onClick="VentanaEmergente(2,<?php echo $cre_id; ?>,<?php echo $persona_id; ?>)" value="Imprimir tabla amortizacion"/>
            </td>
        </tr>
    </table>
  </center>
</form>
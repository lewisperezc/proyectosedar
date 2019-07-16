<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Saldos cuenta por Tercero</title>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
		
function popUp(URL){
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=500,left=240,top=600');");
}
</script>
</head>
<?php
include_once('../clases/cuenta.class.php');
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
include_once('../clases/cuenta.class.php');
$ins_cuenta=new cuenta();
$con_tod_cue_1=$ins_cuenta->todCuentas();
$con_tod_cue_2=$ins_cuenta->todCuentas();
$ins_presupuesto=new presupuesto();
$anios=$ins_presupuesto->obtener_lista_anios();
$mes = new mes_contable();
$tod_mes = $mes->mes();

?>
<tr><th>AÑO</th><th>MES</th><th>DESDE</th><th>HASTA</th></tr>
  <tr>
   <td>
    <select name="ano" id="ano">
     <?php
        for($a=0;$a<sizeof($anios);$a++)
        {
            if($anios[$a]==$_SESSION['elaniocontable'])
                echo "<option value='".$anios[$a]."' selected='selected'>".$anios[$a]."</option>";
            else
                echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
        }
    ?>
    </select>
    </td>
    <td>
     <select name="mes" id="mes">
     <option value="">Seleccione...</option>
     <?php
      while($row = mssql_fetch_array($tod_mes))
          echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
     ?>
    </select>
    </td>
    <td><input type="number" name="cue_inicial" id="cue_inicial" list="lacueini" size="40" required pattern="[0-9]+">
        <datalist id="lacueini">
        <?php
        while($res_tod_cue_1=mssql_fetch_array($con_tod_cue_1))
        {
        ?>
        <option value="<?php echo $res_tod_cue_1['cue_id']; ?>" label="<?php echo $res_tod_cue_1['cue_id']." ".$res_tod_cue_1['cue_nombre']; ?>"/>
        <?php } ?>
        </datalist>
    </td>
    <td><input type="number" name="cue_final" id="cue_final" list="lacuefin" size="40" required pattern="[0-9]+">
        <datalist id="lacuefin">
        <?php
        while($res_tod_cue_2=mssql_fetch_array($con_tod_cue_2))
        {
        ?>
        <option value="<?php echo $res_tod_cue_2['cue_id']; ?>" label="<?php echo $res_tod_cue_2['cue_id']." ".$res_tod_cue_2['cue_nombre']; ?>"/>
        <?php } ?>
        </datalist>
     </td>
  </tr>
   <tr>
    <td colspan="4"><input type="button" name="excel" id="excel" value="Excel" onclick="javascript:abreFactura('../reportes_EXCEL/balance_de_prueba_mensual.php')"/>
    <input type="button" name="pdf" id="pdf" value="PDF"  onclick="javascript:abreFactura('../reportes_PDF/balance_prueba.php')"/>
    </td>
   </tr>
  </table>
</html>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('../clases/recibo_caja.class.php');
  include_once('../clases/credito.class.php');
  include_once('../clases/reporte_jornadas.class.php');
?>

<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script>
function ValFormulario(valor)
{
	document.reg_ent_credito.submit();
}

function ObtenerRecibo(nit){
   $.ajax({
   type: "POST",
   url: "../llamados/trae_recibos_caja.php",
   data: "fac_seleccionada="+nit,
   success: function(msg){
     $("#recibo_caja").html(msg);
   }
 });
}
</script>
<?php
$recibo = new rec_caja();
$rec_pagar=$recibo->recibos_caja();
$_SESSION['espago']=$_GET['val'];
?>

<form name="reg_ent_credito" id="reg_ent_credito" method="post" action="mostrar_reporte.php">
  <center>
	<table border="1">
    	<tr>
            <th>Factura a Pagar</th>
			<td>
             <select name="rec_caja" id="rec_caja" required x-moz-errormessage="Seleccione Una Opcion Valida">
              <option value="">--Seleccione--</option>
              <?php
	while($dat_caja = mssql_fetch_array($rec_pagar))
	{
	   if($dat_caja['rec_caj_monto']==NULL)
	      $val_recibo = $dat_caja['fac_val_total'];
	   else
	   	  $val_recibo = $dat_caja['rec_caj_monto'];
	   echo "<option value='".$dat_caja['rec_caj_id']."-".$val_recibo."-".$dat_caja['rec_caj_consecutivo']."-".$dat_caja['fac_id']."' onclick='ObtenerRecibo(this.value);'>".$dat_caja['cen_cos_nombre']." ".$dat_caja['rec_caj_consecutivo']."--".$dat_caja['fac_consecutivo']."</option>";
	}
	?>
    </select>
    </td>
    <th>Recibo de caja</th>
    <td><select name="recibo_caja" id="recibo_caja"><option value="">Seleccione</option>
    </select>
    </td>
    </tr>
    </table>
  </center>
</form>
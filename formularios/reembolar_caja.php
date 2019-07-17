<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('clases/centro_de_costos.class.php');
@include_once('clases/caja_menor.class.php');
@include_once('clases/cuenta.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/centro_de_costos.class.php');
@include_once('../clases/caja_menor.class.php');
@include_once('../clases/concepto.class.php');
@include_once('../clases/mes_contable.class.php');

$caja = new caja_menor();
$centros = $caja->centros_cajas();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$ins_concepto = new concepto();
$con_concepto = $ins_concepto->busca_concepto(1110);
$res_concepto = mssql_fetch_array($con_concepto);
$_SESSION['caj_cen'] = $_POST['caj_cen'];
$_SESSION['mes_sele'] = $_POST['mes_sele'];
?>

<script language="javascript">
  function enviar(){document.for_reem.submit();}
</script>

<script language="javascript">
function valida_blancos()
{
if(document.ree_caj.sigla.selectedIndex==0)
 			{
 			alert('seleccione el tipo de documento para latransaccion ');
 			transaccion.sigla.focus();
			return false;
			}
if(document.transaccion.fecha_fact.value==0)
			{
			alert('digite la fecha de la factura');
			transaccion.fecha_fact.focus();
			return false;
			}
}

function validarMes()
 {
	var cadena = document.for_reem.mes_sele.value;
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	     alert("Mes de solo lectura");
	 else
	 {  
	  document.ree_caj.action = "./control/reembolsar_caja.php";
	  document.ree_caj.submit();
	 }
 }
</script>
<form name="for_reem" id="for_reem" method="post" >
  <center>
   <table id="contenedor">
    <tr>
     <td>
      <table>
       <tr>
        <td>Centro de costo</td>
        <td>
         <select name="caj_cen" id="caj_cen">
           <option value="0">Seleccione...</option>
         <?php
	      while($dat_cen = mssql_fetch_array($centros)) 
		  {
			if($dat_cen['caj_id'] == $_SESSION['caj_cen']) 
			   echo "<option value='".$dat_cen['caj_id']."-".$dat_cen['cen_id']."' selected='selected' onclick='enviar();'>".$dat_cen['cen_nombre']."</option>";
			else 
		      echo "<option value='".$dat_cen['caj_id']."-".$dat_cen['cen_id']."' onclick='enviar();'>".$dat_cen['cen_nombre']."</option>";
		  }
          ?> 
         </select> 
        </td>
       </tr>
      </table>
     </td>
     <td>
       Mes Contable: 
         <select name="mes_sele" id="mes_sele">
          <?php
			while($dat_meses = mssql_fetch_array($meses))
			  echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select>
     </td>
    </tr>
   </table>
  </center>
</form>

<?php
  if($_SESSION['caj_cen'])
   {
	 $caja_id = split("-",$_SESSION['caj_cen']);
	 $_SESSION['res_caja_id'] = $caja_id[0];
	 $_SESSION['cen_cos'] = $caja_id[1];
	 
	 $dat_caja = $caja->buscar_datos_caja($_SESSION['res_caja_id']);
	 $dat_com_caja = mssql_fetch_array($dat_caja);
	 ?>
	 <form name="ree_caj" id="ree_caj" method="post" action="#" >
      <center>
       <table>
        <tr>
         <td>Asignacion caja menor</td>
         <td>Caja menor gastada</td>
         <td>Concepto</td>
         <td>Reembolsar caja menor</td>
        </tr>
        <tr>
         <td><input type="text" name="caj_asig" id="caj_asig" value="<?php echo $dat_com_caja['caj_men_mon_asig']; ?>" /><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>
         <td><input type="text" name="caj_gas" id="caj_gas" onkeypress="return permite(event,'num')"/></td>
         <td><?php echo $res_concepto['con_nombre']; ?></td>
         <input type="hidden" name="concepto_id" id="concepto_id" value="<?php echo $res_concepto['con_id']; ?>"/>
         <td><input name="asig" id="asig" type="button" class="art-button" onclick="validarMes();" value="Reembolsar"/></td>
        </tr>
       </table>
       </center>
      </form>      
   <?php 
   }
?>
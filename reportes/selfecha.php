<?php 
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
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function abreFactura(URL)
 {
	 var ano=$("#ano").val();
	 var mes=$("#mes").val();
	 var cue_ini=$("#cue_inicial").val();
	 var cue_fin=$("#cue_final").val();
	 if(mes=="")
	 	alert('Debe seleccionar el mes');
	 else
	 {
		 if(cue_ini==""||cue_fin=="")
		 	alert('Debe ingresar la cuenta inicial y la cuenta final');
			else
			{
				day = new Date();
	 			id = day.getTime();
				 URL = URL+'?mes_sele='+mes+'&ano_sele='+ano+'&cue_ini='+cue_ini+'&cue_fin='+cue_fin;
	 			eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
			}
	 }
 }
</script>
<?php
/*$var='123456789';
$tam=strlen($var);
echo $var."___".$tam."___".$var[0];
echo "el dato: ".substr($var,0);*/
?>
<form name="imp_archivo" id="imp_archivo" method="post" action="">
 <center>
  <table border="1" bordercolor="#00CCFF">
  <tr><th>A&Ntilde;O</th><th>MES</th><th>DESDE</th><th>HASTA</th></tr>
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
 </center>
</form>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('clases/mes_contable.class.php');
$instancia_credito = new credito();
$con_dat_nits = $instancia_credito->con_aso_emp_credito();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
?>
<script>
	function enviar_lista()
	{
		if(document.con_cre_persona.selectedIndex != 0)
		{
			document.con_cre_persona.selectedIndex == 0;
			document.con_cre_persona.submit();
		}
	}
	
  function validarMes()
 {
	var cadena = document.reg_pabs.mes_sele.value;
	var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	    alert("Mes de solo lectura.");
	 else
	 {  
	  document.reg_pabs.action = "control/registrarPabs.php";
	  document.reg_pabs.submit();
	 }
 }	
</script>
<form method="post" name="con_cre_persona" action="registrar_pago_de_cuota_2.php" target="frame3">
 <center>
 	<table id="contenedor">
  <tr>
   <td>
    <table>
    	<tr>
        <td>
		<select name="persona_id">
    		<option value="0" onclick="enviar_lista();">--Seleccione--</option>
        <?php
		while($row = mssql_fetch_array($con_dat_nits))
		{
		?>
        	<option value="<?php echo $row['nit_id']; ?>" onclick="enviar_lista();"><?php echo $row['nit_nombres']; ?></option>
		<?php
		}
	   ?>
       </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>
            <td><input type="text" name="cre_consecutivo"/></td>
            <td><input type="submit" class="art-button" name="consultar" value="Consultar"/></td>
       </tr>
    </table>
   </td>
   <td>
    Mes Contable: <select name="mes_sele" id="mes_sele">
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
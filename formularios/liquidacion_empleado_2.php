<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<body>
<?php
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/mes_contable.class.php');

$ins_credito = new credito();
$ins_cuenta = new cuenta();
$ins_mov_contables = new movimientos_contables();
$emp_id = $_POST['sel_nit'];
$_SESSION['nit_id'] = $emp_id;
//$con_mov_con_aso = $ins_mov_contables->con_mov_por_nit($emp_id);
$con_mov_con_aso = $ins_mov_contables->con_mov_por_nit_empleado($emp_id);


$res_sum_mov_con = $ins_mov_contables->obt_sum_cue_nit($emp_id);
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
?>
<script language="javascript">
function validarMes()
{
	var form = document.registrar_liquidacion_empleado_2;
	var cadena = document.registrar_liquidacion_empleado_2.mes_sele.value;
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1){
	    alert("Mes de solo lectura.");
		form.mes_sele.focus();
	}
	else
	{	 
	  if(form.liq_emp_observaciones.value==0){
	  alert("Ingrese las observaciones del retiro del empleado.");
	  form.liq_emp_observaciones.focus();
	  }
	  else{
		  var mensaje = confirm("Esta seguro que desea registrar la liquidacion del empleado.?");
		  if(mensaje)
		  document.registrar_liquidacion_empleado_2.submit();
	  }
    }
}
</script>

<form name="registrar_liquidacion_empleado_2" action="../control/guardar_liquidacion_empleado.php" method="post">
	<center>
	    <table>
	    	<tr>
	        	<td><b>Mes Contable:</b>
	            <select name="mes_sele" id="mes_sele">
	          	<?php
	            while($dat_meses = mssql_fetch_array($meses))
	            	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	          	?>  
	      		</select>
	      		<!--<input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/>-->
	      		</td>
	      	</tr>
	      	<tr>
	        	<td><b>Observaciones</b></td>
		  	</tr>
	      	<tr>
	      		<td><textarea name="liq_emp_observaciones"></textarea></td>
	      	</tr>
	        <tr>
	            <th colspan="3">Saldos Cuentas</th>
	        </tr>
	        <tr>
	            <th>Cuenta</th>
	            <th>Saldo</th>
	            <th>Naturaleza</th>
	        </tr>
	        <?php
        $i = 0;
        while($res_mov_con_aso = mssql_fetch_array($con_mov_con_aso))
        {
            $con_cue_por_id = $ins_cuenta->verificar_existe($res_mov_con_aso['mov_cuent']);
            while($res_nom_cuenta = mssql_fetch_array($con_cue_por_id))
            {
        ?>
        
        <tr>
        <input type="hidden" name="cue_id[]" value="<?php echo $res_mov_con_aso['mov_cuent'] ?>"/>
        <input type="hidden" name="naturaleza[]" value="<?php echo $res_mov_con_aso['mov_tipo'] ?>"/>
        <input type="hidden" name="valor[]" value="<?php echo $res_mov_con_aso['mov_valor'] ?>"/>      
            <td><?php echo $res_nom_cuenta['cue_nombre']; ?></td>
            <td><?php echo $res_mov_con_aso['mov_valor']; ?></td>
            <td><?php if($res_mov_con_aso['mov_tipo'] == 1) { echo "Debito"; }else{ echo "Credito"; } ?></td>
        </tr>
        <?php
            }
            $i++;
        }
        ?>
       
        <tr>
            <td colspan="3"><input type="button" class="art-button" value="Realizar Liquidaci&oacute;n Empleado" onclick="validarMes();"/></td>
        </tr>
	    </table>
	</center>
</form>
</body>
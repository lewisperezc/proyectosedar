<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
include_once('../clases/saldos_cuentas.class.php');

include_once('../clases/cuenta.class.php');
$ins_cuenta=new cuenta();

$ins_presupuesto=new presupuesto();
$mes = new mes_contable();
$ins_sal_cuentas=new insercion();
$anios=$ins_presupuesto->obtener_lista_anios();
$tod_mes=$mes->mes();
$tod_mes_2=$mes->mes();
$ano = $_SESSION['elaniocontable'];
?>
<script>
/*
function enviar(eltipo)
{
	var form=document.cartera;
	if(eltipo==1)
		form.action='../reportes_PDF/saldo_cuentaMes.php';
	else
	{
		if(eltipo==2)
			form.action='../reportes_EXCEL/saldo_cuentaMes.php';
	}
	form.submit();
}
*/
</script>

<form name="cartera" id="cartera" action="../reportes_EXCEL/saldo_cuentaMes.php" method="post">
<center>
    <table border="1" bordercolor="#0099CC">
        <tr>
            <th>Mes</th>
            <th>A&ntilde;o</th>
            <th>Cuenta</th>
            <!--<td>Hasta</td>-->
        <tr>
            <td><input type='hidden' name='ano' id='ano' value='<?php echo $ano; ?>'>
            <select name="mes_ini" id="mes_ini" required x-moz-errormessage="Seleccione Una Opcion Valida">
                <option value="">Seleccione...</option>
                <?php
                while($row = mssql_fetch_array($tod_mes))
                    echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
                ?>
            </select>
            </td>
            <td>
            <select name="ano_ini" id="ano_ini" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <?php
            for($a=0;$a<sizeof($anios);$a++)
                echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
            ?>
            </select>
            </td>
            <td>
            <input type="text" name="cuenta_inicial" id="cuenta_inicial" list="cue" required="required" size="50"/>
      		<datalist id="cue">
      		<?php
     		$cuen_cau = $ins_cuenta->busqueda('no');
       		while($dat_cuentas = mssql_fetch_array($cuen_cau))
        		echo "<option value='".$dat_cuentas['cue_id']."' label='".$dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']."'>";
    		?>
      		</datalist>
            	
            	
            <!--<input type="text" name="cuenta_inicial" id="cuenta_inicial" required="required"/>-->
            </td>
            
            <!--<td><input type="text" name="cuenta_final" id="cuenta_final" required="required"/></td>-->
        </tr>
        <tr>
        	<td colspan="4">
            <!--<input type="button" onclick="enviar(1);" value="PDF"/> ||--> 
            <input type="submit" value="EXCEL"/></td>
        </tr>
  </table>
 </center>
</form>
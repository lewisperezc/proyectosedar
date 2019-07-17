<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
@include_once('../clases/credito.class.php');
@include_once('../../clases/credito.class.php');
$ins_credito=new credito();
$con_fac_con_recaudo=$ins_credito->ConsultarFacturasConRecaudo();

@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
$ins_mesContable=new mes_contable();
$lis_anos=$ins_mesContable->get_anos();
$lis_meses=$ins_mesContable->mes();

?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
	function TipoConsulta(tipo)
	{
		if(tipo==0)
		{
			alert('Debe seleccionar una opcion valida.');
			$("#afiliados").css("display",'none');
			$("#empleados").css("display",'none');
		}
		if(tipo==1)
		{
			$("#afiliados").css("display",'table');
			$("#empleados").css("display",'none');
			$("#tipo_nit").val(1);
		}
		if(tipo==2)
		{
			$("#afiliados").css("display",'none');
			$("#empleados").css("display",'table');
			$("#tipo_nit").val(2);
		}
	}
</script>
<center>
	<table>
		<tr>
			<th>CONSULTAR RECAUDO DE CREDITOS</th>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="tipo_nit" id="tipo_nit" value="0" />
				<select name="tipo_consulta" id="tipo_consulta" onchange="TipoConsulta(this.value);">
					<option value="0">--Seleccione--</option>
					<option value="1">Afiliados</option>
					<option value="2">Empleados</option>
				</select>
			</td>
		</tr>
		
		<form id="frm_afiliados" name="frm_afiliados" method="post" action="./reportes_PDF/rep_recaudo.php?tipo_recaudo_credito=1&tipo_nit=1">
		<tr id="afiliados" style="display:none;">
			<th>Factura a consultar</th>
			<td><input type="text" name="fac_seleccionada" id="fac_seleccionada" list="cent" required="required" onchange="recaudo(this.value);"/>
        	<datalist id="cent">
       		<?php
        	while($dat_centro = mssql_fetch_array($con_fac_con_recaudo))
          	echo "<option value='".$dat_centro['fac_id']."' label='".$dat_centro['fac_consecutivo']."'/>";
       		?>
        	</datalist>
      		<td/>
      		
      		<td><input type="submit" value="Ver recaudo afiliados"/></td>
		</tr>
		</form>
		
		<form id="frm_empleados" name="frm_empleados" method="post" action="./reportes_PDF/rep_recaudo.php?tipo_recaudo_credito=2&tipo_nit=2">
		<tr id="empleados" style="display:none;">
			<th>Mes</th>
			<td><select required name="mes_recaudo" id="mes_recaudo"><option value="">--Seleccione--</option>
			<?php
			while($res_meses=mssql_fetch_array($lis_meses))
			{
			?>
				<option value="<?php echo $res_meses['mes_id'] ?>"><?php echo $res_meses['mes_nombre'] ?></option>
			<?php
			}
			?>
			</select></td>
			
			<th>A&ntilde;o</th>
			<td><select required name="anio_recaudo" id="anio_recaudo"><option value="">--Seleccione--</option>
			<?php
			while($res_anios=mssql_fetch_array($lis_anos))
			{
			?>
				<option value="<?php echo $res_anios['ano_con_id'] ?>"><?php echo $res_anios['ano_con_id'] ?></option>
			<?php
			}
			?>
			</select></td>
			<td><input type="submit" value="Ver recaudo empleados"/></td>
		</tr>
	</table>
</form>
</center>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script src="librerias/js/datetimepicker.js"></script>
</head>
<?php
@include_once('../clases/tipo_comprobante.class.php');
@include_once('clases/tipo_comprobante.class.php');
@include_once('../clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
include_once('clases/mes_contable.class.php');

$ins_mes_contable=new mes_contable();
$ins_nits=new nits();
$ins_tip_comprobante=new tipo_comprobantes();

$meses = $ins_mes_contable->mes();

$lis_anos = $ins_mes_contable->get_anos();

$notrae='34,37,50,54,55,56,57,58,61';
$con_dat_tip_comprobante=$ins_tip_comprobante->ConTipComprobante($notrae);
$existe="IS NOT NULL";
$estado_nits='1,2,3,4,5,6';
$con_usuarios=$ins_nits->con_nit_sin_perfil($existe,2,$estado_nits);
?>
<body>
<form enctype="multipart/form-data" method="post" action="control/guardar_documento_digitalizado.php">
	<center>
		<table>
			<tr>
				<th colspan="4">DIGITALIZAR DE DOCUMENTOS</th>
			</tr>
			<tr>
				<th>Documento</th>
				<td><input type="file" name="doc_dig_archivo" id="doc_dig_archivo" required="required" /></td>
				<th>Propietario</th>
				<td><select name="doc_dig_usu_propietario" id="doc_dig_usu_propietario" required="required">
					<option value="">--</option>
				<?php
					while($res_usuarios=mssql_fetch_array($con_usuarios))
					{
				?>
						<option value="<?php echo $res_usuarios['nit_id'].'.'.$res_usuarios['nit_perfil']; ?>"><?php echo $res_usuarios['nombres']; ?>
						</option>
				<?php
					}
				?>
				</select></td>
			</tr>
			<tr>
				<th>Nombre documento</th>
				<td><input type="text" name="doc_dig_nombre" id="doc_dig_nombre" required="required"></td>
				<th>Tipo documento</th>
				<td><select name="doc_dig_tip_compro" id="doc_dig_tip_compro" required="required">
					<option value="">--</option>
				<?php
					while($res_dat_tip_comprobante=mssql_fetch_array($con_dat_tip_comprobante))
					{
				?>
						<option value="<?php echo $res_dat_tip_comprobante['tip_com_id'].'.'.$res_dat_tip_comprobante['tip_com_sigla']; ?>"><?php echo $res_dat_tip_comprobante['tip_com_nombre']; ?>
						</option>
				<?php
					}
				?>
				</select></td>
			</tr>

			<tr>
				<th>Mes contabilidad</th>
				
				<td>
				<select name="doc_dig_mes_contable" id="doc_dig_mes_contable" required="required">
				<option value="0">--</option>
       			<?php
				while($dat_meses=mssql_fetch_array($meses))
				{
			 		echo "<option value='".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";   
				}
	  ?>  
      </select>
				</td>


				<th>A&ntildeo contabilidad</th>
				<td><select name="doc_dig_ano_contable" id="doc_dig_ano_contable" required="required">
					<option value="0">--</option>
				<?php
			       	while($row=mssql_fetch_array($lis_anos))
              		{
                		echo "<option value='".$row['ano_con_id']."'>".$row['ano_con_id']."</option>";
              		}
				?>
				</select></td>
			</tr>

			<tr>
				<th>Fecha documento</th>
				<td><input type="date" name="dog_dig_fec_documento" id="dog_dig_fec_documento" required/>
				<a href="javascript:NewCal('dog_dig_fec_documento','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                </td>
                <td colspan="2"></td>
			</tr>
			<tr>
				<th colspan="4">Observaci&oacute;n</th>
			</tr>
			<tr>
				<th colspan="4"><textarea name="doc_dig_observacion" id="doc_dig_observacion" required="required" placeholder="Escriba la descripci&oacute;n del documento que va a digitalizar..." name=""></textarea></th>
			</tr>
			<tr>
				<th colspan="4"><input type="submit" value="Digitalizar"></th>
			</tr>

		</table>
	</center>
</form>
</body>
</html>
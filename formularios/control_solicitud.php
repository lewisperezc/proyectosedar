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
$estado_nits='1';
$con_usuarios=$ins_nits->con_nit_sin_perfil($existe,2,$estado_nits);
?>
<body>
<form enctype="multipart/form-data" method="post" action="control/guardar_control_solicutud.php">
	<center>
		<table>
			<tr>
				<th colspan="4">CONTROL DE SOLICITUDES</th>
			</tr>
			<tr>
				<th>Documento de soporte</th>
				<td><input type="file" name="con_sol_archivo" id="con_sol_archivo" /></td>
				<th>Destinatario</th>
				<td><select name="con_sol_solicitante" id="con_sol_solicitante" required="required">
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
				<th>Descripci&oacute;n</th>
				<td><input type="text" name="con_sol_descripcion" id="con_sol_descripcion" required="required"></td>
				<th>Fecha documento</th>
				<td><input type="date" name="con_sol_fec_solicitud" id="con_sol_fec_solicitud" required/>
               </td>
			</tr>

			<tr>
				<th colspan="4">Observaci&oacute;n</th>
			</tr>
			<tr>
				<th colspan="4"><textarea name="con_sol_observaciones" id="con_sol_observaciones" required="required" placeholder="Escriba aqui c&oacute;mo se resolvi&oacute; la solicitud..." name=""></textarea></th>
			</tr>
			<tr>
				<th colspan="4"><input type="submit" value="Guardar control de solicitud"></th>
			</tr>

		</table>
	</center>
</form>
</body>
</html>
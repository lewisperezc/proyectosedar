<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script type="text/javascript" language="javascript" src="javascript/redireccionar_formulario_div.js"></script>
<script>
function Preguntar(doc_dig_id)
{
	var a = confirm("Esta seguro que desea eliminar el documento digitalizado?");
	if(a)
		frm_con_doc_digitalizado.submit();
	else
		return false;
}

function Abre()
{
	
	var url=$("#doc_dig_ruta").val();
	window.open('formularios/mostrar_documento_digitalizado.php?laruta='+url);
}
</script>
</head>
<body>
<?php
include_once('../clases/digitalizar_documento.class.php');
$ins_dig_documento=new DigitalizarDocumento();
$res_dat_documento=$ins_dig_documento->ConsultarTodosDocumentosPorId($_GET['doc_dig_id']);


@include_once('../clases/tipo_comprobante.class.php');
$ins_tip_comprobante=new tipo_comprobantes();

$notrae='34,37,50,54,55,56,57,58,61';
$con_dat_tip_comprobante=$ins_tip_comprobante->ConTipComprobante($notrae);
?>
<form enctype="multipart/form-data" method="post" name="frm_con_doc_digitalizado" id="frm_con_doc_digitalizado" action="./control/eliminar_documento_digitalizado.php">
	<center>
		<table>
			<tr>
				<th colspan="4">DATOS DEL DOCUMENTO DIGITALIZADO</th>
			</tr>
			<tr>
				<th colspan="2">Documento</th>
				<input type="hidden" name="doc_dig_id" id="doc_dig_id" value="<?php echo $_GET['doc_dig_id']; ?>" />
				<input type="hidden" name="doc_dig_ruta" id="doc_dig_ruta" value="<?php echo $res_dat_documento['doc_dig_ruta']; ?>" />
				<input type="hidden" name="doc_dig_sigla" id="doc_dig_sigla" value="<?php echo $res_dat_documento['doc_dig_sigla']; ?>" />
				<td colspan="2"><a href="Javascript:void(0)" onclick="Abre();"><?php echo $res_dat_documento['doc_dig_sigla']; ?></a></td>
			</tr>
			<tr>
				<th>Nombre documento</th>
				<td><input type="text" name="doc_dig_nombre" id="doc_dig_nombre" required="required" value="<?php echo $res_dat_documento['doc_dig_nombre']; ?>"></td>
				<th>Tipo documento</th>
				<td><select name="doc_dig_tip_comprobante" id="doc_dig_tip_comprobante" required="required">
					<option value="">--</option>
				<?php
					while($res_dat_tip_comprobante=mssql_fetch_array($con_dat_tip_comprobante))
					{
						if($res_dat_tip_comprobante['tip_com_id']==$res_dat_documento['doc_dig_tip_comprobante'])
						{
					?>
							<option selected value="<?php echo $res_dat_tip_comprobante['tip_com_id'].'.'.$res_dat_tip_comprobante['tip_com_sigla']; ?>"><?php echo $res_dat_tip_comprobante['tip_com_nombre']; ?>
							</option>
					<?php
						}
						else
						{
					?>
							<option value="<?php echo $res_dat_tip_comprobante['tip_com_id'].'.'.$res_dat_tip_comprobante['tip_com_sigla']; ?>"><?php echo $res_dat_tip_comprobante['tip_com_nombre']; ?>
						</option>
					<?php
						}
					}
				?>
				</select></td>
			</tr>
			<tr>
				<th colspan="4">Observaci&oacute;n</th>
			</tr>
			<tr>
				<th colspan="4"><textarea name="doc_dig_observacion" id="doc_dig_observacion" required="required" placeholder="Escriba la descripci&oacute;n del documento que va a digitalizar..." name=""><?php echo $res_dat_documento['doc_dig_descripcion']; ?></textarea></th>
			</tr>
			<tr>
				<th colspan="2"><input type="button" value="&larr; Atras" onclick="Javascript:Redireccionar('contenido','formularios/consultar_documento_digitalizado_1.php')"></th>
				<th colspan="2"><input type="button" value="Eliminar documento" onclick="Preguntar(<?php echo $_GET['doc_dig_id']; ?>);"></th>
			</tr>

		</table>
	</center>
</form>
</body>
</html>
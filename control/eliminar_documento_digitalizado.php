<?php session_start();
include_once('../clases/digitalizar_documento.class.php');
$ins_dig_documento=new DigitalizarDocumento();

$fecha=date('d-m-Y');
$hora=date("G:i:s a");
$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
$ip=$_SERVER['REMOTE_ADDR'];
$aud_doc_dig_evento="REGISTRO ELIMINADO";
$aud_doc_nom_documento=$_POST['doc_dig_nombre'];
$aud_doc_des_documento=$_POST['doc_dig_observacion'];
$aud_doc_rut_documento=$_POST['doc_dig_ruta'];
$aud_doc_sig_documento=$_POST['doc_dig_sigla'];

$res_dat_doc_digitalizado=$ins_dig_documento->ConsultarTodosDocumentosPorId($_POST['doc_dig_id']);
//echo $res_dat_doc_digitalizado['doc_dig_ruta'];
$borra=unlink($res_dat_doc_digitalizado['doc_dig_ruta']);
if($borra)
{
	$gua_auditora=$ins_dig_documento->GuardarAuditoraDocumentoDigitalzado($_SESSION['k_nit_id'],$fecha,$hora,$host,$ip,
  $aud_doc_dig_evento,$aud_doc_nom_documento,$aud_doc_des_documento,$aud_doc_rut_documento,$aud_doc_sig_documento);
	
	$eli_doc_digitalizado=$ins_dig_documento->EliminarDocumentoDigitalizado($_POST['doc_dig_id']);
	if($eli_doc_digitalizado)
		echo "<script>alert('Documento eliminado correctamente.');history.back(-1);</script>";
	else
		echo "<script>alert('Error al eliminar el documento, intentelo de nuevo.');history.back(-1);</script>";	
}
else
	echo "<script>alert('Error al eliminar el documento, intentelo de nuevo.');history.back(-1);</script>";
?>
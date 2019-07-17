<?php session_start();

include_once('../clases/tipo_comprobante.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/digitalizar_documento.class.php');

$ins_nits=new nits();
$ins_dig_documento=new DigitalizarDocumento();
$ins_tip_comprobante=new tipo_comprobantes();

$doc_dig_archivo = $_POST["doc_dig_archivo"];
$tamano = $_FILES["doc_dig_archivo"]['size'];
$tipo = $_FILES["doc_dig_archivo"]['type'];
$archivo = $_FILES["doc_dig_archivo"]['name'];

$doc_dig_usu_propietario=explode('.',$_POST['doc_dig_usu_propietario']);

$per_id=$doc_dig_usu_propietario[0];

$res_nom_propietario=$ins_dig_documento->ConsultarTodosDatosNitPorId($per_id);
$nombre_persona=str_replace(" ","",$res_nom_propietario['nits_nombres'].$res_nom_propietario['nits_apellidos']);

$res_dat_perfil=$ins_dig_documento->ConsultarTodosDatosPerfilPorId($doc_dig_usu_propietario[1]);

$ruta_perfil=$res_dat_perfil['per_rut_digital'];
$sigla_perfil=$res_dat_perfil['per_sigla'];

$doc_dig_nombre=$_POST['doc_dig_nombre'];
$doc_dig_fecha=date('d-m-Y');
$doc_dig_mes=date('m');
$doc_dig_anio=$_SESSION['elaniocontable'];
$doc_dig_hora=date("G:i:s a");


$doc_dig_mes_contable=$_POST['doc_dig_mes_contable'];
$doc_dig_ano_contable=$_POST['doc_dig_ano_contable'];
$dog_dig_fec_documento=$_POST['dog_dig_fec_documento'];


$doc_dig_observacion=$_POST['doc_dig_observacion'];

$doc_dig_tip_comprobante=explode('.',$_POST['doc_dig_tip_compro']);

$res_consecutivo=$ins_tip_comprobante->ConsultarConsecutivoDigital($doc_dig_tip_comprobante[0]);

$destino="";

$nombre_archivo=date('dmY')."__".$nombre_persona."__".$sigla_perfil."__".substr($doc_dig_tip_comprobante[1],0,-1)."__".$res_consecutivo['tip_com_con_digital'];

$extencion=explode("/",$tipo);

if($_FILES["doc_dig_archivo"]['name']!="")
{

	if($extencion[0]=='image')
		$destino = $ruta_perfil.$nombre_archivo.".jpg";

	elseif($tipo=='text/csv')
		$destino = $ruta_perfil.$nombre_archivo.".csv";
			  
	elseif($tipo=='text/plain')
		$destino = $ruta_perfil.$nombre_archivo.".txt";
			   
	elseif($tipo=='application/pdf' || $tipo=='application/force-download' || $tipo=='application/x-download')
		$destino = $ruta_perfil.$nombre_archivo.".pdf";
			   
	elseif($tipo=='application/x-unknown')
		$destino = $ruta_perfil.$nombre_archivo.".docx";

	elseif($tipo=='application/msword')
		$destino = $ruta_perfil.$nombre_archivo.".doc";

	elseif($tipo=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
		$destino = $ruta_perfil.$nombre_archivo.".xlsx";
		   
	elseif($tipo=='application/vnd.ms-excel')
		$destino = $ruta_perfil.$nombre_archivo.".xls";
	
	elseif($tipo=="application/vnd.openxmlformats-officedocument.presentationml.presentation")
		$destino = $ruta_perfil.$nombre_archivo.".pptx";
	
	elseif($tipo=="application/vnd.ms-powerpoint")
		$destino = $ruta_perfil.$nombre_archivo.".ppt";

	$sube=copy($_FILES["doc_dig_archivo"]['tmp_name'],$destino);
	if($sube)
	{
		$guardar_documento_digitalizado=$ins_dig_documento->GuardarDocumentoDigitalizado($doc_dig_nombre,$_SESSION['k_nit_id'],$doc_dig_tip_comprobante[0],$doc_dig_usu_propietario[1],$doc_dig_fecha,$doc_dig_mes,$doc_dig_anio,$doc_dig_hora,$destino,$doc_dig_observacion,$nombre_archivo,$doc_dig_usu_propietario[0],$doc_dig_mes_contable,$doc_dig_ano_contable,$dog_dig_fec_documento);

		if($guardar_documento_digitalizado)
		{
			//ACTUALIZAR CONSECUTIVO DE TIPO COMPROBANTE
			$act_consecutivo=$ins_tip_comprobante->ActualizarConsecutivoDigital($doc_dig_tip_comprobante[0]);

			echo "<script>alert('Documento subido correctamente.');</script>";
			echo "<script>history.back(-1)</script>";
		}
		else
		{
			$borra=unlink($destino);
			echo "<script>alert('Error al subir el documento, intentelo de nuevo.');</script>";
			echo "<script>history.back(-1)</script>";
		}
	}
	else
	{
		echo "<script>alert('Error al subir el documento, intentelo de nuevo.');</script>";
		echo "<script>history.back(-1)</script>";
		
	}
}
else
	echo "<script>history.back(-1);</script>";
?>
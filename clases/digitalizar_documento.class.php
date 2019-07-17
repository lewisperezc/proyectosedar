<?php
@include_once('../conexion/conexion.php');
class DigitalizarDocumento
{

  public function GuardarDocumentoDigitalizado($doc_dig_nombre,$doc_dig_usu_digitalizador,$doc_dig_tip_comprobante,$doc_dig_perfil,$doc_dig_fecha,$doc_dig_mes,$doc_dig_anio,$doc_dig_hora,$doc_dig_ruta,$doc_dig_descripcion,$doc_dig_sigla,$doc_dig_usu_propietario,$doc_dig_mes_contable,$doc_dig_ano_contable,$doc_dig_fec_documento)
  {
    $query="INSERT INTO documentos_digitalizados(doc_dig_nombre,doc_dig_usu_digitalizador,doc_dig_tip_comprobante,doc_dig_perfil,
doc_dig_fecha,doc_dig_mes,doc_dig_anio,doc_dig_hora,doc_dig_ruta,doc_dig_descripcion,doc_dig_sigla,doc_dig_usu_propietario,doc_dig_mes_contable,doc_dig_ano_contable,doc_dig_fec_documento)
            VALUES('$doc_dig_nombre','$doc_dig_usu_digitalizador','$doc_dig_tip_comprobante','$doc_dig_perfil','$doc_dig_fecha','$doc_dig_mes','$doc_dig_anio','$doc_dig_hora','$doc_dig_ruta','$doc_dig_descripcion','$doc_dig_sigla','$doc_dig_usu_propietario','$doc_dig_mes_contable','$doc_dig_ano_contable','$doc_dig_fec_documento')";
    $ejecutar = mssql_query($query);
    if($ejecutar)
      return $ejecutar;
    else
      return false;   
  }

  public function ConsultarTodosDocumentosPorRutaArchivo($nit_id)
  {
    $query="SELECT dd.*,n.nits_nombres,n.nits_apellidos,p.per_nombre,tc.tip_com_sigla,ni.nits_nombres AS nom_propietario,ni.nits_apellidos AS ape_propietario
			FROM documentos_digitalizados dd
			INNER JOIN nits n ON dd.doc_dig_usu_digitalizador=n.nit_id
			INNER JOIN nits ni ON dd.doc_dig_usu_propietario=ni.nit_id
			INNER JOIN perfiles p ON dd.doc_dig_perfil=p.per_id
			INNER JOIN tipo_comprobante tc ON dd.doc_dig_tip_comprobante=tc.tip_com_id";
			//WHERE (dd.doc_dig_usu_digitalizador='$nit_id' OR dd.doc_dig_usu_propietario='$nit_id')
    //echo $query;
    $ejecutar=mssql_query($query);
    if($ejecutar)
      return $ejecutar;
    else
      return false;   
  }

  public function ConsultarTodosDocumentosPorId($doc_dig_id)
  {
    $query="SELECT dd.*,n.nits_nombres,n.nits_apellidos,p.per_nombre,tc.tip_com_sigla,ni.nits_nombres AS 
			nom_propietario,ni.nits_apellidos AS ape_propietario,dd.doc_dig_sigla
			FROM documentos_digitalizados dd
			INNER JOIN nits n ON dd.doc_dig_usu_digitalizador=n.nit_id
			INNER JOIN nits ni ON dd.doc_dig_usu_propietario=ni.nit_id
			INNER JOIN perfiles p ON dd.doc_dig_perfil=p.per_id
			INNER JOIN tipo_comprobante tc ON dd.doc_dig_tip_comprobante=tc.tip_com_id
            WHERE dd.doc_dig_id='$doc_dig_id'";
    //echo $query;
    $ejecutar=mssql_query($query);
    if($ejecutar)
    {
      $res_dat_doc_digital=mssql_fetch_array($ejecutar);
      return $res_dat_doc_digital;
    }
    else
      return false;   
  }

  public function ConsultarTodosDocumentos()
  {
    $query="SELECT dd.*,n.nits_nombres,n.nits_apellidos,p.per_nombre,tc.tip_com_sigla
            FROM documentos_digitalizados dd
            INNER JOIN nits n ON dd.doc_dig_usuario=n.nit_id
            INNER JOIN perfiles p ON dd.doc_dig_perfil=p.per_id
            INNER JOIN tipo_comprobante tc ON dd.doc_dig_tip_comprobante=tc.tip_com_id";
    $ejecutar=mssql_query($query);
    if($ejecutar)
      return $ejecutar;
    else
      return false;   
  }

  public function EliminarDocumentoDigitalizado($doc_dig_id)
  {
    $query="DELETE FROM documentos_digitalizados WHERE doc_dig_id='$doc_dig_id'";
    $ejecutar=mssql_query($query);
    if($ejecutar)
      return $ejecutar;
    else
      return false;   
  }
  
  public function ConsultarTodosDatosPerfilPorId($per_id)
  {
    $query="SELECT * FROM perfiles WHERE per_id='$per_id'";
	//echo $query;
    $ejecutar=mssql_query($query);
    if($ejecutar)
	{
		$res_dat_perfil=mssql_fetch_array($ejecutar);
      	return $res_dat_perfil;
	}
    else
      return false;   
  }
  
  public function ConsultarTodosDatosNitPorId($nit_id)
  {
    $query="SELECT * FROM nits WHERE nit_id='$nit_id'";
    //echo $query; 
    $ejecutar=mssql_query($query);
    if($ejecutar)
	{
		$res_dat_perfil=mssql_fetch_array($ejecutar);
      	return $res_dat_perfil;
	}
    else
      return false;   
  }
  
  public function GuardarAuditoraDocumentoDigitalzado($aud_doc_dig_usuario,$aud_doc_dig_fecha,$aud_doc_dig_hora,$aud_doc_dig_host,$aud_doc_dig_ip,$aud_doc_dig_evento,$aud_doc_nom_documento,$aud_doc_des_documento,$aud_doc_rut_documento,$aud_doc_sig_documento)
  {
 
    $query="INSERT INTO auditoria_documentos_digitalizados(aud_doc_dig_usuario,aud_doc_dig_fecha,aud_doc_dig_hora,aud_doc_dig_host,aud_doc_dig_ip,aud_doc_dig_evento,
    aud_doc_nom_documento,aud_doc_des_documento,aud_doc_rut_documento,aud_doc_sig_documento)
    VALUES('$aud_doc_dig_usuario','$aud_doc_dig_fecha','$aud_doc_dig_hora','$aud_doc_dig_host','$aud_doc_dig_ip','$aud_doc_dig_evento','$aud_doc_nom_documento','$aud_doc_des_documento','$aud_doc_rut_documento','$aud_doc_sig_documento')";
    $ejecutar=mssql_query($query);
    if($ejecutar)
      	return $ejecutar;
    else
      return false;   
  }
  
}
?>
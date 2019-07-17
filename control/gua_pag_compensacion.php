<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  include_once('../conexion/conexion.php');
  include_once('../clases/recibo_caja.class.php');
  $recibo = new rec_caja();
  $fichero = $recibo->act_consecutivo(25);
  $temp = 0;
  
  
  $mes_sele=explode('-',$_POST['mes_sele']);
  $mes = $mes_sele[1];
  
  $centro=1169;
  $sigla=$_POST['fichero'];
  $conse=split("-",$sigla,2);
  $fecha=date('d-m-Y');
  $cant = $_POST['cant'];

	$bas_retencion=0;
	
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	$tip_mov_aud_id=1;
	
	$aud_mov_con_descripcion='CREACION DOCUMENTO PAGO DE COMPENSACION AFILIADOS(TESORERIA)';
	
	
	$temp=0;
  for($i=0;$i<=$_POST['cant'];$i++)
  {
	if(trim($_POST['nit'.$i])!='' && trim($_POST['cue'.$i])!='' && trim($_POST['valor'.$i])!='')
	{
		$asociado[$i]=$_POST['nit'.$i];
		$val_pagar[$i]=$_POST['valor'.$i];
		$cuenta[$i]=$_POST['cue'.$i];
		$tip_cue[$i]=$_POST['tip_cue'.$i];
		
		if(trim($asociado[$i]!="") && trim($val_pagar[$i])!="" && trim($cuenta[$i])!="" && $tip_cue[$i]!="")
		{
			$sql_pagar="EXECUTE insMovimiento '$sigla','$conse[1]','$cuenta[$i]','0','$asociado[$i]','$centro','$val_pagar[$i]','$tip_cue[$i]','$conse[1]','$conse[1]','0','0','$fecha','$mes','$ano','$bas_retencion'";
			$query_pagar = mssql_query($sql_pagar);	
		
			if($query_pagar)
		  		$temp = 1;
		}
	}
  }
  
  $query = "SELECT COUNT(*) cant FROM mov_contable";
  $cant_mov = mssql_query($query);
  $cantidad = mssql_fetch_array($cant_mov);
  $mov = "EXECUTE movContable ".$cantidad['cant'];
  $ins_mov = mssql_query($mov);
  
  
  	//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
	$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
	aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
	aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
	aud_mov_con_descripcion='$aud_mov_con_descripcion'
	WHERE mov_compro='$sigla' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
	AND tip_mov_aud_id IS NULL";
	//echo $que_aud_mov_contable;
	$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
  
  
  
  
  if($temp==1)
  {
	 echo "<script>alert('Pago del centro de costo satisfactoriamente.');</script>";
  }
  else
    echo "<script>alert('Error.');</script>";

?>
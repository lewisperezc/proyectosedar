<?php session_start();
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/moviminetos_contables.class.php');
@include_once('../clases/recibo_caja.class.php');
@include_once('clases/recibo_caja.class.php');
include_once('../clases/comprobante.class.php');
//echo "entra";
$comprobante= new comprobante();
$ins_mov_contables=new movimientos_contables();
$ins_rec_caja=new rec_caja();
//obtener_concecutivo()
$status = "";
// obtenemos los datos del archivo
$tamano = $_FILES["archivo"]['size'];
$tipo = $_FILES["archivo"]['type'];
$archivo=$_FILES["archivo"]['name'];
$archivo=date('d-m-Y');
$prefijo = substr(md5(uniqid(rand())),0,6);
$tip_comprobante=$_POST['tip_comprobante'];
$mes_sele=explode("-",$_POST['mes_sele'],2);

$fecha=$_POST['arc_pla_fecha'];

//echo "los datos son: ".$archivo."___".$tipo."<br>";

$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;
$aud_mov_con_descripcion='CREACION DE DOCUMENTO DE CONTABILIDAD - SUBIR ARCHIVO PLANO';


if($archivo!=""&&($tipo=="application/vnd.ms-excel"||$tipo=="text/csv"))
{
	//guardamos el archivo a la carpeta correspondiente
    $destino =  "../archivos_planos/".$archivo."_".$prefijo.".csv";
	//echo "<br>el destino es: ".$destino."<br>";
    if (copy($_FILES['archivo']['tmp_name'],$destino))
	{
    	$status = "Archivo subido: ".$archivo."_".$prefijo.".csv";
		//DESDE AK INSERTA
		$cont=0;
		$fp = fopen ("../archivos_planos/".$archivo."_".$prefijo.".csv","r");
		$conce = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$mes_sele[1],$tip_comprobante);
		$sig = $comprobante->sig_comprobante($tip_comprobante);
		$comprobante->act_comprobante($_SESSION['elaniocontable'],$mes_sele[1],$tip_comprobante);
		$sigla = $sig.$conce;
		while($data=fgetcsv($fp,1000,";"))
		{
			$concepto=550;//CONCEPTO
			$mes=$mes_sele[1];
			$consecutivo=$cons+1;
			if(trim($data[0])!="")
			{
				if(is_numeric($data[3]))
				{
					$GuaArchPla1=$ins_mov_contables->GuaArcPlano1($sigla,$consecutivo,$fecha,$data[0],$concepto,$data[1],$data[2],$data[3],$data[4],$consecutivo,0,$mes,$_SESSION['elaniocontable']);
					
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$sigla' AND mov_mes_contable='$mes' AND mov_ano_contable='".$_SESSION['elaniocontable']."'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
				}
			}
			if($GuaArchPla1)
			{
				if(trim($data[0])!="")
				{
					if(is_numeric($data[3]))
					{
						$partir_cadena_nit=explode("_",$data[1],2);
						//echo "el dato es: ".$data[1]."<br>";
						//echo "la cadena es: ".$partir_cadena_nit[0]."<br>";
						$GuaArchPla2=$ins_mov_contables->GuaArcPlano2($sigla,$fecha,$partir_cadena_nit[0],$data[2],$data[3],$fecha,$consecutivo,$_SESSION['k_nit_id'],$archivo,$tip_comprobante,$data[6],$mes,$data[5]);
						
						
						//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
						$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
						aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
						aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
						aud_mov_con_descripcion='$aud_mov_con_descripcion'
						WHERE mov_compro='$sigla' AND mov_mes_contable='$mes' AND mov_ano_contable='".$_SESSION['elaniocontable']."'
						AND tip_mov_aud_id IS NULL";
						//echo $que_aud_mov_contable;
						$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
						
					}
				}
			}
		}
			$cont++;
	}
		fclose($fp);
		if($GuaArchPla2)
			$act_consecutivo=$ins_rec_caja->act_consecutivo($tip_comprobante);
		//HASTA AK INSERTA
		else
     	$status = "Error al subir el archivo";
}
else
	$status = "Error al subir archivo";

echo "<script>alert('".$status."');history.back(-1);</script>";
//
?>
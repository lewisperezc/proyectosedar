<?php
include_once('../clases/novedad.class.php');
$ins_novedad=new novedad();
$dir_subida = '../archivos_planos/novedades_administrativas/';

$nombre_archivo=$_FILES['arc_plano']['name'];
$extension=pathinfo($nombre_archivo, PATHINFO_EXTENSION);

if($extension!="")
{
	$persona=str_replace(" ", "",$_POST['nombres_cedula']);
	$nombre_archivo=$persona."_".date('dmY').".".$extension;
	$fichero_subido = $dir_subida . basename($nombre_archivo);
	$ruta=$dir_subida.$nombre_archivo;
	$subida=move_uploaded_file($_FILES['arc_plano']['tmp_name'],$fichero_subido);
}
else
	$ruta="";

$gua_nov_administrativa=$ins_novedad->gua_nov_administrativa($_POST['nit_id'],$_POST['nov_observacion'],$ruta);
if($gua_nov_administrativa)
	echo "<script>alert('Novedad registrada correctamente.');</script>";
else
    echo "<script>alert('Error al registrar la novedad.');</script>";
echo "<script>window.close();</script>";
?>
<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
include_once('../../conexion/conexion.php');
//$ord_com = new orden_compra();
$listadoSelects=array(
"select1"=>"tipo",
"select2"=>"descripcion",
"select3"=>"select_3"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];
if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	 //echo "imprime desde el rcp de select_dependientes_3_niveles_proceso";
	$tabla=$listadoSelects[$selectDestino];
	   echo "<select name='".$selectDestino."' id='".$selectDestino."'>";
	   echo "<option value='0'>Seleccione...</option>";
	   $datos = mssql_num_rows($consulta);
	   if($opcionSeleccionada==1)
	   {
	      $queconcep = "select con_id,con_nombre,for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4,for_cue_afecta5,for_cue_afecta6,
for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,for_cue_afecta12,
for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,for_cue_afecta18,
for_cue_afecta19,for_cue_afecta20
from dbo.conceptos co inner join dbo.formulas fo
on co.form_for_id = fo.for_id";
		  $consulta = mssql_query($queconcep);	  
		  $id = 0;
		  while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['con_id'])
			     echo "<option value = '".$registro['con_id']."' onclick='valida();'>".$registro['con_id']." ".$registro['con_nombre']."</option>";
			  $id = $registro['con_id']; 	 
		   }	  
	   }
	 
	  if($opcionSeleccionada==2)
	   {
	     $queconcepto = "select con_id,con_nombre,for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4,for_cue_afecta5,for_cue_afecta6,
for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,for_cue_afecta12,
for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,for_cue_afecta18,
for_cue_afecta19,for_cue_afecta20
from dbo.conceptos co inner join dbo.formulas fo
on co.form_for_id = fo.for_id";
		  $consulta = mssql_query($queconcepto);	  
		  $id = 0;
	     while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['con_id'])
			   echo "<option value = '".$registro['con_id']."' onclick='valida();'>".$registro['con_id']
			   ." ".$registro['con_nombre']."</option>";
			  $id = $registro['con_id']; 	 
		   }	
	 }
	 
	if($opcionSeleccionada==3)
	 {
	 $queconcepto = "select con_id,con_nombre,for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4,for_cue_afecta5,for_cue_afecta6,
for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,for_cue_afecta12,
for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,for_cue_afecta18,
for_cue_afecta19,for_cue_afecta20
from dbo.conceptos co inner join dbo.formulas fo
on co.form_for_id = fo.for_id";
		  $consulta = mssql_query($queconcepto);	  
		  $id = 0;
	     while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['con_id'])
			   echo "<option value = '".$registro['con_id']."' onclick='valida();'>".$registro['con_id']
			   ." ".$registro['con_nombre']."</option>";
			  $id = $registro['con_id']; 	 
		   }	
	 
	 }
}
?>
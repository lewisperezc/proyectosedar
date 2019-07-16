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
	   $tabla=$listadoSelects[$selectDestino];
	   echo "<select name='".$selectDestino."' id='".$selectDestino."'>";
	   echo "<option value='0'>Seleccione...</option>";
	   $queconcep = "SELECT cue.cue_id, cue.cue_nombre FROM cuentas cue
                     INNER JOIN ciudades ciu ON ciu.ciu_id = cue.cue_ciudad
                     INNER JOIN nits_por_ciudades npc ON npc.ciu_id = cue.cue_ciudad
                     INNER JOIN nits nit ON nit.nit_id = npc.nit_id
                     WHERE nit.nit_id = $opcionSeleccionada";
          //echo $queconcep;
		  $consulta = mssql_query($queconcep);	  
		  $id = 0;
		  while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['cue_id'])
			     echo "<option value = '".$registro['cue_id']."' onclick='valida();'>".$registro['cue_id']." ".$registro['cue_nombre']."</option>";
			  $id = $registro['tip_con_id']; 	 
		   }
}
?>
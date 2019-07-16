<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
include_once('../../conexion/conexion.php');
$listadoSelects_2=array(
"select4"=>"tipo",
"select5"=>"descripcion",
"select6"=>"select_3"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects_2;
	if(isset($listadoSelects_2[$selectDestino])) return true;
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
	$tabla=$listadoSelects_2[$selectDestino];
    $sql = "SELECT ciu_id,ciu_nombre 
			FROM ciudades 
	        WHERE depa_dep_id ='$opcionSeleccionada'";
	$consulta = mssql_query($sql);
	echo "<select name='".$selectDestino."' id='".$selectDestino."' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value=''>Seleccione Ciudad</option>";
	while($registro=mssql_fetch_array($consulta))
	{
		echo "<option value = '".$registro['ciu_id']."'>".$registro['ciu_nombre']."</option>";
	}			
	echo "</select>";
}
?>
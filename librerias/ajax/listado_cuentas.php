<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
include_once('../../conexion/conexion.php');

$listadoSelects=array(
"select2"=>"tipo",
"select3"=>"descripcion",
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
//borrar de aca para abajo

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
    $sql1 = "SELECT cue_nombre FROM cuentas WHERE cue_id = $opcionSeleccionada ";
	$consultas=mssql_query($sql1);
	while($registro=mssql_fetch_array($consultas))
	 echo $registro['cue_nombre']."  ";
	
}

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
    $sqln="SELECT cue_nombre FROM cuentas WHERE cue_id = $opcionSeleccionada";
			$consultar =mssql_query($sqln );
	while($registro=mssql_fetch_array($consultar))
	{
		echo "<input type='text' name='".$selectDestino."' id='".$selectDestino."' value='".$registro['cue_nombre']."'/>";
	}			
	echo "</select>";
}
?>
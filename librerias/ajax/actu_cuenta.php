<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
include_once('../../conexion/conexion.php');

$listadoSelects=array(
"select2"=>"tipo",
"select3"=>"descripcion",
"select4"=>"select_4"
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


///////
if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
    $sql = "SELECT * FROM cuentas WHERE cue_id = $opcionSeleccionada ";
	$consultas=mssql_query($sql);
	while($registro=mssql_fetch_array($consultas))
	{
	$registro['desc_total']=htmlentities($registro['desc_total']);
	echo "-".$registro['cue_nombre']."  ";
	}		
	
}
///////
if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	$tabla=$listadoSelects[$selectDestino];
    $sqln="SELECT * 	
			FROM cuentas 
			WHERE cue_id like('$opcionSeleccionada%') and subdivision ='si'";
			$consultar =mssql_query($sqln );	
	echo "<select name='".$selectDestino."' id='".$selectDestino."' >";
	echo "<option value=''>Seleccione</option>";
	while($registro=mssql_fetch_array($consultar))
	{
		$registro['desc_total']=htmlentities($registro['desc_total']);
		echo "<option value = '".$registro['cue_id']."'>".$registro['cue_id']."</option>";
	}			
	echo "</select>";
}


?>
<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
include_once('../../conexion/conexion.php');
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
    $sql = "select pro_id,pro_nombre
            from productos
            where tip_pro_id  = '$opcionSeleccionada'";
	$consulta = mssql_query($sql);
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido_1(this.id)'>";
	echo "<option value='0' onclick='validar_vacios2();'>Seleccione Producto</option>";
	while($registro=mssql_fetch_array($consulta))
	{
		echo "<option value = '".$registro['pro_id']."' onclick='validar_vacios2();'>".$registro['pro_nombre']."</option>";
	}			
	echo "</select>";
}
?>
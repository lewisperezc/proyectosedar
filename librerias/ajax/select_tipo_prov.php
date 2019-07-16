<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido

$dns = "anestecoop";
$user = "sa";
$pass = "@nestecoop12";
$conexion= odbc_connect($dns, $user, $pass);

$listadoSelects=array(
"select1"=>"tipo",
"select2"=>"descripcion",
"select3"=>"select_9"
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
    $sql = "SELECT tp.tip_prod_id tip_pro, tp.tip_prod_nombre tip_nombre FROM tipo_producto tp INNER JOIN  		            tipo_producto_por_tipo_proveedor tppp ON tp.tip_prod_id = tppp.tip_prod_id INNER JOIN 
	        tipo_proveedor tpro ON tpro.tip_prove_id = tppp.tip_prove_id
	        WHERE tpro.tip_prove_id ='$opcionSeleccionada'";
	echo "<script type=\"text/javascript\">alert(\"\");</script>";
	$consulta = odbc_exec($conexion,$sql) or die(odbc_error($conexion));
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' >";
	echo "<option value='0'>Elige</option>";
	while($registro=odbc_fetch_array($consulta))
	{
		echo odbc_num_rows($consulta);
		$registro['tip_pro']=htmlentities($registro['tip_pro']);
		echo "<option value = '".$registro['tip_pro']."'>".$registro['tip_nombre']."</option>";
	}			
	echo "</select>";
}
?>
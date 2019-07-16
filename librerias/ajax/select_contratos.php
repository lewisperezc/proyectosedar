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
	   $datos = mssql_num_rows($consulta);
	   if($opcionSeleccionada==1)
	   {
	      $queconcep = "select tip_con_id,tip_con_nombre
 		  from dbo.tipo_contrato_prestacion";
		  $consulta = mssql_query($queconcep);	  
		  $id = 0;
		  while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['tip_con_id'])
			     echo "<option value = '".$registro['tip_con_id']."' onclick='valida();'>".$registro['tip_con_nombre']." ".$registro['con_nombre']."</option>";
			  $id = $registro['tip_con_id']; 	 
		   }	  
	   }
	 
	  if($opcionSeleccionada==2)
	   {
	       
		 $queconcep = "select *
		 				from dbo.nits_tipos
						where nit_tip_id = 2
						";
		  $consulta = mssql_query($queconcep);	  
		  $id = 0;
		  while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['nit_tip_id'])
			     echo "<option value = '".$registro['nit_tip_id']."' onclick='valida();'>".$registro['nit_tip_nombre']." ".$registro['con_nombre']."</option>";
			  $id = $registro['nit_tip_id']; 	 
		   }	  
	   }
	 
	if($opcionSeleccionada==3)
	 {
	 $queconcepto = "";
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
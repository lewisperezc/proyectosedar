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
	   echo "<option value=''>Seleccione...</option>";
	   $datos = mssql_num_rows($consulta);
	   if($opcionSeleccionada==1)
	   {
	      $queOrdCom = "SELECT oc.cen_cos_id id, cc.cen_cos_nombre centro,ord_com_id, est_ord_com_id, nit_id
						FROM ordenes_compra oc inner join centros_costo cc on cc.cen_cos_id = oc.cen_cos_id
						ORDER BY 2";
		  $consulta = mssql_query($queOrdCom);	  
		  $id = 0;
		  while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['id'])
			     echo "<option value = '".$registro['id']."' onclick='valida();'>".$registro['centro']."</option>";
			  $id = $registro['id']; 	 
		   }	  
	   }
	 
	  if($opcionSeleccionada==2)
	   {
	     $queOrdCom = "SELECT ord_com_id, est_ord_com_id, oc.nit_id iden, oc.cen_cos_id, ni.nits_nombres nom, ni.nits_apellidos ape FROM ordenes_compra oc inner join nits ni on ni.nit_id=oc.nit_id ORDER BY oc.nit_id";
		  $consulta = mssql_query($queOrdCom);	  
		  $id = 0;
	     while($registro=mssql_fetch_array($consulta))
		   {
			  if($id != $registro['iden'])
			   echo "<option value = '".$registro['iden']."' onclick='valida();'>".$registro['nom']." ".$registro['ape']."</option>";
			  $id = $registro['iden']; 	 
		   }	
	 }
	 
	if($opcionSeleccionada==3)
	 {
	  $queOrdCom = "SELECT oc.ord_com_id orden,oc.est_ord_com_id estado,oc.nit_id iden,oc.cen_cos_id, ni.nits_nombres nom,
	   					   ni.nits_apellidos ape   
					FROM ordenes_compra oc inner join nits ni on ni.nit_id=oc.nit_id
					ORDER BY oc.nit_id";
	  $consulta = mssql_query($queOrdCom);
	  while($registro=mssql_fetch_array($consulta))
		 echo "<option value = '".$registro['orden']."' onclick='valida();'>OC ".$registro['orden']."</option>";
	 }			
	echo "</select>";
}
?>
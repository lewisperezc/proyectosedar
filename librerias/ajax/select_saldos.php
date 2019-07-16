
<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
@include_once('../../conexion/conexion.php');
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
"<script src='../js/datetimepicker.js'/>";
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
					 $queconcepto = "select nit_id,nits_nombres
					from dbo.nits";
		 			 $consulta = mssql_query($queconcepto);	  
		 			 $id = 0;
	     			while($registro=mssql_fetch_array($consulta))
		  				 {
						 if($id != $registro['nit_id'])
			  				 echo "<option value = '".$registro['nit_id']."' onclick='valida();'>".$registro['nit_id']
			   				." ".$registro['nits_nombres']."</option>";
			  				$id = $registro['nit_id']; 	
		  				 }	     
	  			 }	 
	  		if($opcionSeleccionada==2)
	   			{
					 $id = 0;
					 if($id != 0)
		  		?>   
		   			<option value="1"onclick="valida();" > fechas en las que quiere sacar los saldos</option>
			 	<?php 
				$id =1; 	     
	  			}	 
			if($opcionSeleccionada==3)
				 {	 
				 $queconcep = "select *
						from dbo.centros_costo";
		  		$consulta = mssql_query($queconcep);	  
					  $id = 0;
					  while($registro=mssql_fetch_array($consulta))
		 			  {
					  if($id != $registro['cen_cos_id'])
			 		    echo "<option value = '".$registro['cen_cos_id']."' onclick='valida();'>".$registro['cen_cos_nombre']."</option>";
			  $id = $registro['cen_cos_id']; 	  
		   			}	
	 			 }	
	 		if($opcionSeleccionada==4)
	 			{	
				 $queconcep = "select cue_id ,cue_nombre
							from cuentas
							where cue_subdivision ='no'";
		  		$consulta = mssql_query($queconcep);	  
					  $id = 0;
					  while($registro=mssql_fetch_array($consulta))
		 			  {
					  if($id != $registro['cue_id'])
			 		    echo "<option value = '".$registro['cue_id']."' onclick='valida();'>".$registro['cue_id']."".$registro['cue_nombre']."</option>";
			  $id = $registro['cue_id']; 	  
		   			}	
				}
	echo "</select >";
}
?>
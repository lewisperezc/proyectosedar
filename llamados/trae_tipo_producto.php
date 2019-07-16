<?php
include_once('../clases/tipo_producto.class.php');
$tipo_producto = new tipo_producto();
$tip_pro = $tipo_producto->cons_tipo_producto(); 
$i=0;
while($tip_prod = mssql_fetch_array($tip_pro))       
	{
		$res[$i]["id"] = $tip_prod['tip_pro_id'];
		$res[$i]["nombre"] = $tip_prod['tip_pro_nombre'];
		$i++;
	}
echo json_encode($res);
?>
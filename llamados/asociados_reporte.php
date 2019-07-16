<?php
include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');
 
$centro = new centro_de_costos();
$sel_hosp = $_POST['hospital'];
$factura=$_POST['factura'];
$mes_factura="SELECT mov_mes_contable FROM movimientos_contables WHERE mov_compro='FAC-".$factura."'";
$query=mssql_query($mes_factura);
$dat_query=mssql_fetch_array($query);
$asociados = $centro->buscar_asociados($sel_hosp);
$i=0;
while($row = mssql_fetch_array($asociados))
  {
  	$res[$i]["mes_contable"] = $dat_query['mov_mes_contable'];
	$res[$i]["nit_id"] = $row['doc'];
	$res[$i]["nom_aso"] = $row['nombres']." ".$row['apellidos'];
	$res[$i]["estado"] = $row['estado'];
	$res[$i]["aso_num"] = $row['cen_nit_id'];
	$i++;
  }
echo json_encode($res);
?>
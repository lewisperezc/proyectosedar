<?php
session_start();
include_once('../clases/pabs.class.php');
include_once('../clases/tipo_producto.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/transacciones.class.php');

$fabs = new pabs();
$tipo_producto = new tipo_producto();
$nits = new nits();
$transacciones = new transacciones();
$consul = $fabs->cons_PABS($_POST['fabs'],$_POST['mes'],$_SESSION['elaniocontable']);
$veces = $transacciones->exis_orden($_POST['fabs'],$_POST['mes']);
//error_reporting(E_ALL);
$i=0;
while($unarray=mssql_fetch_array($consul))       
{
    $linea_pabs="";
    $tipo_produ="";
    $res[$i]["nit"] = $unarray['reg_com_nit'];
    $lin_pabs = $fabs->lineasPABS();
    while($dat_concep=mssql_fetch_array($lin_pabs))
    {
      if($unarray["reg_com_linea"]==$dat_concep['pabs_id'])
      {
         //echo $unarray["reg_com_linea"]."%%%%%%";
         $linea_pabs.="<option value=".$dat_concep['pabs_id']." selected='selected'>".substr($dat_concep['pabs_nombre'],0,16)."</option>";
      }
      else
      {
        $linea_pabs.="<option value=".$dat_concep['pabs_id'].">".substr($dat_concep['pabs_nombre'],0,16)."</option>"; 
      }
    }
    $res[$i]["linea"] = $linea_pabs;
    $res[$i]["provee"] = $unarray["reg_com_prove"];
    $nom_prove=$nits->cons_nombres_nit($unarray["reg_com_prove"]);
    $dat_provee = mssql_fetch_array($nom_prove);
    $res[$i]["nom_provee"]=$dat_provee['nits_num_documento']." - ".$dat_provee['nombres'];
    $tip_pro = $tipo_producto->cons_tipo_producto();
    while($tip_prod = mssql_fetch_array($tip_pro))
    {
            if($unarray['tip_pro_id']==$tip_prod['tip_pro_id'])
                $tipo_produ.='<option value="'.$tip_prod['tip_pro_id'].'" selected="selected">'.substr($tip_prod['tip_pro_nombre'],0,15).'</option>';
            else
                $tipo_produ.='<option value="'.$tip_prod['tip_pro_id'].'">'.substr($tip_prod['tip_pro_nombre'],0,15).'</option>';
    }
    $res[$i]["tip_producto"] = $tipo_produ;
    $res[$i]["producto"] = $unarray["reg_com_producto"];
    $res[$i]["can_produc"] = $unarray["reg_com_cantidad"];
    $res[$i]["descripcion"] = $unarray["reg_com_descrip"];
    $res[$i]["valor"] = $unarray["reg_com_valor"];
    $res[$i]["doc_nom_asociado"] = $unarray["nits_num_documento"]." - ".$unarray["nits_nombres"]." ".$unarray["nits_apellidos"];
    $res[$i]['nombre_producto']=$unarray["pro_nombre"];
    $res[$i]['veces']=$veces;
    $i++;
} 
echo json_encode($res);
?>
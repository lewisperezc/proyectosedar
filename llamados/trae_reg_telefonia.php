<?php
@include_once('../clases/telefonia.class.php');

$ins_telefonia = new telefonia();

$con_reg_tel_por_nit = $ins_telefonia->con_reg_tel_por_nit_id($id,1);

error_reporting(E_ALL);

//$sql = "SELECT pro_id,pro_nombre FROM productos WHERE tip_pro_id = ".$_POST['id'];
//$qid = mssql_query($sql);
?>
<option value="NULL">--Seleccione--</option>
<?php
$html = "";
if($con_reg_tel_por_nit!=false)
  {
    while($unarray = mssql_fetch_array($con_reg_tel_por_nit))       
       $html .= '<option value="'.$unarray["lin_tel_id"].'">'.$unarray["lin_tel_nombres"].'</option>';
    $html .="";
    echo $html;
  }
?>
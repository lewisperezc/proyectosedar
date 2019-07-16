<?php
include_once('../clases/telefonia.class.php');
include_once('../clases/nits.class.php');
$ins_telefonia = new telefonia();
$nit = new nits();
$nit_id = $_POST['id'];
$cat_pabs = $nit->cant_pabs($nit_id);
$con_lin_tel_por_nit = $ins_telefonia->con_tod_lin_por_nit($nit_id);
error_reporting(E_ALL);

if($con_lin_tel_por_nit!=false)
  {
?>
<option value="0">--Seleccione--</option>
<?php
    while($unarray = mssql_fetch_array($con_lin_tel_por_nit))       
	{
?>
		<option value="<?php echo $unarray['lin_tel_id']; ?>" onclick="val_sel_linea();"><?php echo $unarray['lin_tel_nombres']; ?></option>
<?php
	}  
  }
?>
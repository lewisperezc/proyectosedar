<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);

$si = $_POST['si'];
$idt = $_POST['id'];
$id = $_POST['id2'];

if($si == 1)
{
 $sql = "SELECT che_con_ini,che_consecutivo FROM chequera WHERE che_cue_pertenece = ".$_POST['id']." AND che_consecutivo < che_con_fin";
 echo $sql."<br>";
 $qid = mssql_query($sql);
 $res = mssql_fetch_array($qid);
 if($res['che_consecutivo'] != NULL)
 {
  $html = $res['che_consecutivo']+1; 
  echo "<option value='".$html."'>".$html."</option>";
 }
 else
  {
	  $html = $res['che_con_ini'];
	  echo "<option value='".$html."'>".$html."</option>";
  }
}
?>
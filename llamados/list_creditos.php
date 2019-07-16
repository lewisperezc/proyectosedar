<?php
include_once('../clases/credito.class.php');

$ins_credito=new credito();
$list_creditos=$ins_credito->cre_salNits($_POST['nit'],0);
$html='';

while($row=mssql_fetch_array($list_creditos))
	$html.="<option value='".$row['cre_id']."' label='".$row['cre_id']." ".$row['cre_valor']."'></option>";

echo $html;
?>
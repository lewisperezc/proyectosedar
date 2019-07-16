<?php
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
$elvalor=$_POST['elvalor'];
$con_adi_otrosi=$ins_contrato->ConAdiOtrPorContrato($elvalor);
$res="";
$res.="Adiciones o Otrosi: ";
$res.="<select name='laadiotrosi' id='laadiotrosi' onchange='TraeDatos(this.value);'>";
$res.="<option value=''>Seleccione</option>";
while($row=mssql_fetch_array($con_adi_otrosi))
{
	$res.="<option value=".$row['adi_otr_id']."-".$row['adi_o_otr_id']."-".$row['tip_adi_otr_id']."-".$elvalor.">".$row['adi_otr_fecha']." - ".$row['adi_o_otr_nombre']." - ".$row['tip_adi_nombre']."</option>";
}
$res.="</select>";
echo $res;
?>
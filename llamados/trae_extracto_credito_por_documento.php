<?php
@include_once('../clases/credito.class.php');
@include_once('clases/credito.class.php');
$ins_creditos=new credito();
$nit_documento=$_POST['nit_documento'];

$res="";
if(trim($nit_documento)!=""&&is_numeric($nit_documento))
{
	if($_POST['tipo']==2)//ES PARA LA UNIFICACION DE CREDITOS
	{
		$con_cre_por_nit=$ins_creditos->ConsultarCreditosConSaldo($nit_documento);
	}
	else//ES PARA EL EXTRACTO DE CREDITOS
	{
		$con_cre_por_nit=$ins_creditos->ConsultarCreditosPorNit($nit_documento);	
	}
	
 	while($res_cre_por_nit=mssql_fetch_array($con_cre_por_nit))
	{
		$res.="<option value='".$res_cre_por_nit['cre_id']."' label='".$res_cre_por_nit['cre_id']."'>";
	}
	/*echo "<option value='".$dat_aso['nit_id']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?></datalist>*/       
	echo $res;
}
?>
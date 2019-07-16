<?php
include_once('../clases/credito.class.php');
$ins_credito = new credito();
$nom_id = $_POST['id'];
$con_nit_por_nomina = $ins_credito->con_nit_por_nomina($nom_id);
function calc_interes($credito,$valor)
   {
	 $cred = new credito();
	 $dias = $cred->ult_pago($credito);
 	 $num_dias = mssql_fetch_array($dias);
 	 $resultado = $cred->int_diario($credito);
 	 $res = mssql_fetch_array($resultado);
 	 $interes = $res['cre_interes']+$res['cre_dtf'];
 	 $int_total = ($interes/365)*$num_dias['dias'];
	 $total_int = $valor * ($int_total/100);
	 return $total_int;
   }
   
error_reporting(E_ALL);
$i=0;
if($con_nit_por_nomina!=false)
  {
    while($res_nit_por_nomina = mssql_fetch_array($con_nit_por_nomina))       
	{
		$tie_credito = $ins_credito->con_aso_emp_cre_registrados($res_nit_por_nomina['trans_nit']);
		$cant_datos = mssql_num_rows($tie_credito);
		if($tie_credito!=false && $cant_datos>0)
		{
			$j=0;
			while($row = mssql_fetch_array($tie_credito))
			{
			  if($row['ult_pago']=="")
			     $ult_pago = "NULL";
			  else
			     $ult_pago = $row['ult_pago'];	 
			  $res[$j]["nit"] = $row['nit_cre'];
			  $res[$j]["credito"] = round($row['cre_saldo']);
			  $res[$j]["pago"] = $ult_pago;
			  $res[$j]["interes"] = round(calc_interes($row['cre_saldo'],$row['cre_val_pagado']*(-1)));
			  $res[$j]["valor"] = round($row['cre_val_pagado']*(-1));
			  $j++;
			}
		    if($res!="")	
		 	{ 
		   		$resp[$i]=$res;
		   		$res = "";
		 	}
		}
		$i++;
	}
  }
echo json_encode($resp);
?>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/concepto.class.php');
$conc= new concepto();
$concep=strtoupper($_POST['concep1']);
$descrip=strtoupper($_POST['descrip1']);
$rete=$_POST['reteica'];
$verificar = $conc->verificar_existe($concep);
$result=mssql_num_rows($verificar);
$tipo_concepto=$_POST['select1'];
$i=1;
$j=1;
$id_b = $_POST['id_num'];
$cuenta=$_POST['cuent21']." ".","." ".$_POST['tip1'];//los dos son fijos en el formulario
$id=$_POST['num'];
$cuentas=$_POST['sel_cuen'];//estos son los que captura dependiendo la cantidad ee cuentas que afecte
$tipos=$_POST['sel_tip'];//estos son los que captura dependiendo la cantidad ee cuentas que afecte
$cont=$_POST['cntr'];

if($result > 0)
{
	echo "<script>
			alert('Este concepto numero --$concep -- ya existee-- --- ');
			location.href='../index.php?c=8';
	      </script>";
}
if($concento==0)
{
	//echo "concentoooo es";
$insert=$conc->inser_formulas($id_b."".",".$cuenta." ".",",
$id[$i+1]."".",".$cuentas[$i]." ".","." ".$tipos[$i]." ".","." ".$cont[$i].",",
//",".",".",".",",
$id[$i+2]."".",".$cuentas[$i+1]." ".","." ".$tipos[$i+1]." ".","." ".$cont[$i+1].",",
$id[$i+3]."".",".$cuentas[$i+2]." ".","." ".$tipos[$i+2]." ".","." ".$cont[$i+2].",",
$id[$i+4]."".",".$cuentas[$i+3]." ".","." ".$tipos[$i+3]." ".","." ".$cont[$i+3].",",
$id[$i+5]."".",".$cuentas[$i+4]." ".","." ".$tipos[$i+4]." ".","." ".$cont[$i+4].",",
$id[$i+6]."".",".$cuentas[$i+5]." ".","." ".$tipos[$i+5]." ".","." ".$cont[$i+5].",",
$id[$i+7]."".",".$cuentas[$i+6]." ".","." ".$tipos[$i+6]." ".","." ".$cont[$i+6].",",
$id[$i+8]."".",".$cuentas[$i+7]." ".","." ".$tipos[$i+7]." ".","." ".$cont[$i+7].",",
$id[$i+9]."".",".$cuentas[$i+8]." ".","." ".$tipos[$i+8]." ".","." ".$cont[$i+8].",",
$id[$i+10]."".",".$cuentas[$i+9]." ".","." ".$tipos[$i+9]." ".","." ".$cont[$i+9].",",
$id[$i+11]."".",".$cuentas[$i+10]." ".","." ".$tipos[$i+10]." ".","." ".$cont[$i+10].",",
$id[$i+12]."".",".$cuentas[$i+11]." ".","." ".$tipos[$i+11]." ".","." ".$cont[$i+11].",",
$id[$i+13]."".",".$cuentas[$i+12]." ".","." ".$tipos[$i+12]." ".","." ".$cont[$i+12].",",
$id[$i+14]."".",".$cuentas[$i+13]." ".","." ".$tipos[$i+13]." ".","." ".$cont[$i+13].",",
$id[$i+15]."".",".$cuentas[$i+14]." ".","." ".$tipos[$i+14]." ".","." ".$cont[$i+14].",",
$id[$i+16]."".",".$cuentas[$i+15]." ".","." ".$tipos[$i+15]." ".","." ".$cont[$i+15].",",
$id[$i+17]."".",".$cuentas[$i+16]." ".","." ".$tipos[$i+16]." ".","." ".$cont[$i+16].",",
$id[$i+18]."".",".$cuentas[$i+17]." ".","." ".$tipos[$i+17]." ".","." ".$cont[$i+17].",",
$id[$i+19]."".",".$cuentas[$i+18]." ".","." ".$tipos[$i+18]." ".","." ".$cont[$i+18].",",
$id[$i+20]."".",".$cuentas[$i+19]." ".","." ".$tipos[$i+19]." ".","." ".$cont[$i+19]);
				
$obte=$conc->obtener_max_formula();
$cue = mssql_fetch_array($obte);
$inserta=$conc->insert_concepto($concep,$cue['max_id'],$descrip,$rete,$tipo_concepto);
if ($insert && $inserta){
echo "<script>
	alert('Se guardo correctamente la formula con el concepto numero - $concep - nombre---$descrip---');
     location.href='../index.php?c=8';
</script>";}
else
echo "<script>
		alert('No se pudo guardar formula, intentelo de nuevo.');
		location.href='../index.php?c=8';
	  </script>"; echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=8'>";
}
?>
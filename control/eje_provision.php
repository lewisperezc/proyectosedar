<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 include_once('../clases/moviminetos_contables.class.php');
 include_once('../clases/transacciones.class.php');
 include_once('../clases/nits.class.php');
 include_once('../clases/centro_de_costos.class.php');
 $tran = new transacciones();
 $movimiento = new movimientos_contables();
 $nits = new nits();
 $centro = new centro_de_costos();
 $conce_provi = strtoupper($_POST['provi']);
 $fecha = date('d-m-Y');
 $sigla = "PROV-".$conce_provi;
 $temp=0;
 $mes = split("-",$_POST['mes_sele'],2);
 $ano = $_SESSION['elaniocontable'];
 $empleados = $nits->con_aso_por_id_estado(2,1);
 while($row = mssql_fetch_array($empleados))
   {
	 $nit_id = strtoupper($row['nit_id']);
	 $salario = strtoupper($row['nits_salario']);
	 $cen_nit = $centro->con_cen_cos_nit($row['nit_id']);
	 $cent_nit = mssql_fetch_array($cen_nit);
	 $cent = $cent_nit['cen_cos_id'];
     $transa = $tran->guaTransaccion($sigla,$fecha,$nit_id,$cent,$salario,0,$fecha,0,$_SESSION["k_nit_id"],$fecha,$mes[1],$ano);
	 $transacc = $tran->obtener_concecutivo();
	 $consecu = mssql_fetch_array($transacc);
	 $conse = $consecu['max_id'];
	$mov = $movimiento->guarCam_movimiento($cent,$conse,$sigla,$nit_id,$fecha,$conse,$fecha,0,$salario,$conce_provi,0,$ica,$mes[1],$ano);
	 if(!$mov)
	   $temp=1;
   }
    echo "<script type=\"text/javascript\">alert(\"Se ejecuto la provision satisfactoriamente.\");</script>"; 
	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../formularios/pagina_blanca.php'>";
?>
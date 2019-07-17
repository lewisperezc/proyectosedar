<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/pabs.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/telefonia.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/centro_de_costos.class.php');
$pabs = new pabs();
$tran = new transacciones();
$nit = new nits();
$movimiento = new movimientos_contables();
$ins_telefonia = new telefonia();
$act_conse = $ins_telefonia->act_consecutivo();
$ins_credito = new credito();
$ins_cen_cos = new centro_de_costos();
$fecha = date('d-m-Y');

$j=0;
for($i=0;$i<=$_POST['cuantos'];$i++)
  {
	  
   $cliente[$i] = $_POST['clientes_'.$i];
   $dat_telefono = $ins_telefonia->con_tod_lin_por_nit($cliente[$i]);
   while($row=mssql_fetch_array($dat_telefono))
     { 
	  $linea = $row['lin_tel_id'];
	  $tel_cliente[$j] = $cliente[$i]."==".$_POST['costo_'.$i."_".$linea]."==".$linea."==".$_POST['pago_'.$linea."_".$i]."==".$_POST['centros_'.$linea.$i]."==".$_POST['descripcion_'.$i.'_'.$linea];
	  $j++;
	 }
   }
   
$mes = split("-",$_POST['mes_sele'],2);
for($i=0;$i<sizeof($tel_cliente);$i++)
  {
	 $dat_tele = split("==",$tel_cliente[$i],6);
	 
	 if($dat_tele[3] == 1)//CREDITO
		{
		 $ins_reg_credito = $ins_credito->ins_reg_cre_por_telefonia($dat_tele[0],strtoupper($dat_tele[5]),$dat_tele[1],1,$fecha,13250592,$dat_tele[4]);
		}
		elseif($dat_tele[3] == 2)//PABS
		{
			//Consultar El Proveedor Que Tiene La Linea Telefonia//
			$con_proveedor = $ins_telefonia->con_pla_tel_proveedor($dat_tele[2]);
			/////////////////////////////////////////////////////
			
			$obt_consecutivo = $pabs->obt_consecutivo();
 			$ins_reg_pabs = $pabs->registrar_telefonia_por_pabs($dat_tele[0],513535,$con_proveedor,$dat_tele[2],strtoupper($dat_tele[5]),$dat_tele[1],$fecha,1,$obt_consecutivo);
			
			//$dat_tele[0],513535,378,$dat_tele[2],strtoupper($dat_tele[5]),$dat_tele[1],$fecha,1,$obt_consecutivo
			
			$act_consecutivo = $pabs->act_consecutivo();
			
			$con_cen_cos = $ins_cen_cos->con_cen_cos_pabs(68);
			$res_cen_cos_pabs = mssql_fetch_array($con_cen_cos);
			$resul_cen_cos_pabs = $res_cen_cos_pabs['cen_cos_id'];
			
			$consecutivo = $tran->obtener_concecutivo();
            $cue = mssql_fetch_array($consecutivo);
            $transacciones = $cue['max_id'];
            $conse = $cue['max_id'] + 1;
			$sigla = "Tel_".$conse;
			$nueTran = $tran->guaTransaccion(strtoupper($sigla),$fecha,$con_proveedor,$resul_cen_cos_pabs,$dat_tele[1],0,$fecha,$dat_tele[2],$_SESSION['k_nit_id'],$fecha,$mes[1],$ano);
			
		    if($nueTran)
		    {	  
	        	$transacc = $tran->obtener_concecutivo();
        		$num_tran = mssql_fetch_array($transacc);
				
				$bas_retencion=0;
				
				$mov = $movimiento->guaMovimiento(strtoupper($sigla),$conse,910505050506,910505050506,$dat_tele[0],$resul_cen_cos_pabs,$dat_tele[1],2,$conse,$_SESSION['k_nit_id'],1,$dat_tele[2],1,$mes[1],$bas_retencion);
				if($mov){
					$mov = $movimiento->guaMovimiento(strtoupper($sigla),$conse,13051005,13051005,$dat_tele[0],$resul_cen_cos_pabs,$dat_tele[1],2,$conse,$_SESSION['k_nit_id'],1,$dat_tele[2],1,$mes[1],$bas_retencion);
			}
		  }
		}
  }

?>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 $consultar = strtoupper($_GET['consulta']);
 include_once('../conexion/conexion.php');
 include_once('../clases/pabs.class.php');
 include_once('../clases/transacciones.class.php');
 include_once('../clases/moviminetos_contables.class.php');
 include_once('../clases/nits.class.php');
 $cant = $_SESSION['canti'];
 
 $bas_retencion=0;
 
 for($i=0;$i<$cant;$i++)
     $asociados[$i] = $_POST['asoc'.$i];
 for($i=0;$i<$cant;$i++)
    $total_pabs[$i] = $_POST['cantpabs'.$i];
 $conceptos = $_POST['concep'];
 $descripcion = $_POST['desc'];
 $beneficiarios = $_POST['bene'];
 $tip_pago = $_POST['tip_pag'];
 $valor = $_POST['val'];
 $productos = $_POST['prod'];
 $cantidad = $_POST['cant'];
 $fecha = date('d-m-Y');
 $consecutivo = $_SESSION['conse'];
 if(strtoupper($_POST['mes_sele'])!="")
   $mes = split("-",strtoupper($_POST['mes_sele']),2);
 else
   $mes = split("-",$_SESSION['mes_sele'],2);
 $pabs = new pabs();
 $tran = new transacciones();
 $nit = new nits();
 $movimiento = new movimientos_contables();
 if($consultar!='')
 {
   $consecutivo=$_SESSION['conse'];
   $temp = 0;
   for($i=0;$i<sizeof($asociados);$i++)
   {
      $act_pabs = $pabs->mod_pabs($consecutivo,$asociados[$i],$beneficiarios[$i],$fecha,68,($valor[$i]*$cantidad[$i]));
	  if(!$act_pabs)
	   {
	     $temp = 1;
	   }
	  if($temp==0)
	    echo "<script>alert('Se elimino el PABS Anterior');</script>";
   }
 }
 else
   $act_conse = $pabs->act_consecutivo();
 $i=0;
 while($i < sizeof($asociados))
     {
		 $_SESSION['pro'] = $productos[$i]; 
		if($asociados[$i]=="" || $conceptos[$i]=="" || $descripcion[$i]=="" || $beneficiarios[$i]=="" || $tip_pago[$i]== ""
		    || $valor[$i]=="" || $productos[$i] = "")
			{
				echo "<script> alert('El registro de la fila $i no se pudo guardar, debido a la falta de datos
				                     de aqui en adelante no se guardara ningun registro');</script>";
				break;
			}
		 else
		    {
			   $aso = $asociados[$i];
			   $total_cau = $valor[$i]*$cantidad[$i];
			   $cant_pabs = $nit->cant_pabs($asociados[$i]);
			   for($j=0;$j<=sizeof($asociados);$j++)
			   {
				   if($aso==$asociados[$j])
				      $total_cau = $total_cau + $valor[$j];
			   }
			   if($total_cau> $cant_pabs)
			   {
					echo "<script>alert('El saldo de PABS del Dr.".$aso." es menor al total de las causaciones a nombre de el, no se guardara la causacion a partir de este registro');</script>";
					echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=65'>";
					break;
			   }
			   else
			    {
			     $pab = $pabs->registrar_PABS($asociados[$i],$conceptos[$i],$beneficiarios[$i],$_SESSION['pro'],
                       strtoupper($descripcion[$i]),$tip_pago[$i],($valor[$i]*$cantidad[$i]),$fecha,$cantidad[$i],$consecutivo);
			     $centro = $nit->con_cen_cos_asociado($asociados[$i]);
			     $cen_cos = mssql_fetch_array($centro);
			     if($pab)
			     {
		            echo "<script type=\"text/javascript\">alert(\"Se guardo el PABS registrado!!\");</script>";
				    $nueTran = $tran->guaTransaccion("FABS-".$consecutivo,$fecha,$beneficiarios[$i],68,$valor[$i],0,$fecha,$conse,$_SESSION["k_nit_id"],$fecha,$mes[1],$ano);//SOLO GUARDA EN LA TABLA transacciones
	                $transacc = $tran->obtener_concecutivo();
                    $num_tran = mssql_fetch_array($transacc);
				    $mov = $movimiento->guaMovimiento("FABS-".$consecutivo,$conse,$conceptos[$i],$conceptos[$i],
					        $asociados[$i],68,$valor[$i],2,$conse,$_SESSION["k_nit_id"],1,$_SESSION['pro'][$i],1,$mes[1],$bas_retencion);
	                if($mov)
	                   echo "<script type=\"text/javascript\">alert(\"Se registro El PABS correctamente!!\");</script>";
				 }
				}
		     }
		 $i++;
	 }	 
unset($_SESSION['conse']);
//INICIO LIMPIO LAS SESSIONES//
unset($_SESSION['asoc']);
unset($_SESSION['concep']);
unset($_SESSION['desc']);
unset($_SESSION['bene']);
unset($_SESSION['tip_pag']);
unset($_SESSION['val']);
unset($_SESSION['prod']);
unset($_SESSION['cant']);
//FIN LIMPIO LAS SESSIONES//
 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=65'>";
?>
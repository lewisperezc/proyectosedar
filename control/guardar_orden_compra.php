<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/orden_compra.class.php');
include_once('../clases/producto.class.php');
include_once('../clases/tipo_producto.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/comprobante.class.php');

$ins_tip_producto = new tipo_producto();
$ord_compra=new orden_compra();
$prod = new producto();
$cuenta = new cuenta();
$ins_nits = new nits();
$mov_conta = new movimientos_contables();
$conce = new concepto();
$comprobante= new comprobante();

$bas_retencion=0;

///////////Cogemos los valores
$centro=$_POST['centro'];
$proveedor=$_POST['proveedor'];
$select2=$_POST['select2'];//CUENTA ICA
$can_filas=$_POST['can_filas'];
$i=0;
while($i<=$can_filas)
{
	$referencia[$i]=strtoupper($_POST["ref".$i]);
   	$tipo_pro[$i]=$_POST["select1".$i];
   	$producto[$i]=$_POST['select2'.$i];
   	$descrip[$i]=$_POST['descr'.$i];
   	$iva[$i]=$_POST['iva'.$i];
   	$cantidad[$i]=$_POST['cantidad'.$i];
   	$valor[$i]=(float)$_POST['valor'.$i];
   	$i++;
}

$totalFin=0;
for($k=0;$k<=$can_filas;$k++)
{
    $total[$k]=$cantidad[$k]*$valor[$k];
	$totalFin = $totalFin + $total[$k];
	if($iva[$k]=="on")
	  {
	   $cal_iva = $prod->ivaProducto($producto[$k]);
	   if($cal_iva==""||$cal_iva==0)
	       $iva_pro[$k] = 0;
	   else
	    {
			$ivaTotal = $ivaTotal + ($total[$k]*($cal_iva)/100);
	        $iva_pro[$k] = $total[$k]*($cal_iva)/100;
		}
	  }
	 else
	   $iva_pro[$k] = 0;
	$con_ret = $ins_nits->con_ret_proveedor($proveedor);
	if($con_ret==1)
	{	
	   $val_rete = $prod->reteProducto($producto[$k]);
	   $reteTotal = $reteTotal + ($total[$k]*($val_rete)/100);
	   $rete_pro[$k] = $total[$k]*($val_rete)/100;
	}
	else
	   $rete_pro[$k] = 0;
	$concep_pro = $conce->conceProducto($producto[$k]);
    $formula = $mov_conta->consul_formulas($concep_pro);
	if($formula)
   	{
		$p=0;
        $row = mssql_fetch_array($formula);	
        while($p<=21)
        {
		 $palabras=split(",",$sp);
	     $arre = split(",",$row["for_cue_afecta".$p]);
		 $a = $arre[0];
		 $b = $arre[1];
		 $c = $arre[2]; 
		 $d = $arre[3];
		 if($a != "" && $b != "" && $c != "")
		 	{
			  $_SESSION['matriz'][$p][0]= $a;
			  $_SESSION['matriz'][$p][1]= $b;
			  $_SESSION['matriz'][$p][2]= $c;
			  $_SESSION['matriz'][$p][3]= $d;
			}
		 $p++;	
		}
     }
	 $val_descu_cre=0;
	 $val_descu_deb=0;
	 for($i=0;$i<sizeof($_SESSION['matriz']);$i++)
  	  {
		$cue_con=$_SESSION['matriz'][$i+1][1];
		$naturaleza = $_SESSION['matriz'][$i+1][2];
		$por_cuenta = $cuenta->busPorCuenta($cue_con);
		$porcentaje = mssql_fetch_array($por_cuenta);
		$porce_cue = $porcentaje['cue_porcentage'];
		if($naturaleza==2)
	  		$val_descu_cre = $val_descu_cre + ($total[$k]*($porce_cue)/100);
		else
	  		$val_descu_deb = $val_descu_deb + ($total[$k]*($porce_cue)/100);
  	  }
	$val_descu = 0;
    $val_descu = $val_descu_cre-$val_descu_deb;
	$por_cuenta = $cuenta->busPorCuenta($cue_ica);
	$porcentaje = mssql_fetch_array($por_cuenta);
	$ica = $total[$k]*($porcentaje['cue_porcentage']);
	if($ica<0){$ica = $ica * (-1);}
	$suma = $total[$k]+$iva_pro[$k];
	$val_ord_pro[$k] = $suma-($val_descu+$ica);
   //////////////////
}
   //$cue_centro = $cuenta->cue_centro($_SESSION["centro"]);
   if($proveedor&&$centro&&$totalFin)
  	{
		$gua_orden = $ord_compra->guardar_ordCompra($proveedor,$centro,$totalFin,$cue_ica);
    	$ult_ordCom = $comprobante->cons_comprobante($ano,date(m),21);
      	$sig = $comprobante->sig_comprobante(21);
      	$comprobante->act_comprobante($ano,date(m),4);
		if($gua_orden)
		{
			echo "<script type=\"text/javascript\">alert(\"Se actualizo la orden de compra.\");</script>";
	  		for($i=0;$i<=$can_filas;$i++)
			{
				if($producto[$i]&&$ult_ordCom&&$cantidad[$i]&&$valor[$i]&&$referencia[$i])
				{
		  		  $guar_prod = $prod->guaProducto($producto[$i],$ult_ordCom,$cantidad[$i],$valor[$i],$referencia[$i],$descrip[$i],$iva_pro[$i],$rete_pro[$i]);
		  		  if(!$guar_prod)
		     		$queda = 1;
		  		  $cue_pagar = $ins_tip_producto->cueTipo($tipo_pro[$i]);
		  		  if($cue_pagar)
					{
		 			  $fecha = date('d-m-Y');
					  $sigla = $sig.$ult_ordCom;
					  if(date('m')<9)
				   		$mes = "0".date('m');
					  else
				   		$mes = date('m');   
		 			  $sql ="EXECUTE insMovimiento '$sigla','$ult_ordCom','$cue_pagar','2','".$proveedor."','".$centro."','$val_ord_pro[$i]','2','$ult_ordCom','".$_SESSION['k_nit_id']."','0','2','$fecha','$mes','$ano','$bas_retencion'";
		  		      $query = mssql_query($sql);
		  		
	      		      $sql1 ="EXECUTE insMovimiento '$sigla','$ult_ordCom','13300501','2','".$proveedor."','".$centro."','$val_ord_pro[$i]','1','$ult_ordCom','".$_SESSION['k_nit_id']."','0','2','$fecha','$mes','$ano','$bas_retencion'";
		   			 $query1 = mssql_query($sql1);
					 if($query&&$query1)
	        		 { 
			   			$query = "SELECT COUNT(*) cant FROM mov_contable";
		   	   			$cant_mov = mssql_query($query);
		       			$cantidad = mssql_fetch_array($cant_mov);
		       			if($cantidad['cant'] == 2)
		       			{
			   	  		 $sql= "INSERT INTO paso_saldo_cuentas SELECT uno,ocho,nueve,cuatro,seis,siete,tres FROM dbo.mov_contable";
			   	  		 $ejecutar = mssql_query($sql);
			      		 if($ejecutar)
			       		 {
			        		$sql_2 = "SELECT * FROM paso_saldo_cuentas";
	                		$ejecutar_2 = mssql_query($sql_2);
				    		$sql_2 = "EXECUTE TrunPasSaldos";
	                		$ejecutar_2 = mssql_query($sql_2);
				   		 }
				  		 $mov = "EXECUTE movContable 2";
		          		 $ins_mov = mssql_query($mov);
			   		    }
				     }
					}
			 else  
	 	  		echo "<script type=\"text/javascript\">alert(\"No se pudo generar la orden de compra.\");</script>";
			}
		}
  	  }
	  else
        echo "<script type=\"text/javascript\">alert(\"No se pudo generar la orden de compra.\");</script>";
	}
 $_SESSION["cantidad"]=$cantidad;
 $_SESSION["refe"] = $referencia;
 $_SESSION["tip_pro"] = $tipo_pro;
 $_SESSION["produc"] = $producto;
 $_SESSION["cantidad"] = $_SESSION["cant"];
 $_SESSION["valor"] = $valor;
 $_SESSION["cant"] = $_SESSION["cant_pro"];
	
echo "<script>var a = confirm('Desea imprimir la orden de compra?');
	  		  if(a)
				  document.location = '../reportes_PDF/orden_compra.php';
			  else
				history.back(-1)';
	 </script>";
?> 
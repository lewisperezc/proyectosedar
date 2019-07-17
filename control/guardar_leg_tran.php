<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$_SESSION['regimen_empresa'];
include_once('../clases/regimenes.class.php');
include_once('../clases/pabs.class.php');
$regimen = new regimenes();
$afecto = $regimen->afec_impuesto($_SESSION['regimen_empresa']);
$rtefuente=0;
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>

<script type="text/javascript">
 function abreFactura(URL)
 {
    day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
 }
</script>

<?php
include_once('../clases/transacciones.class.php');//para funcionamiento local
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/orden_compra.class.php');
include_once('../clases/producto.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/pabs.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/comprobante.class.php');
include_once('../clases/orden_desembolso.class.php');

$ins_ord_desembolso=new orden_desembolso();
$ins_rec_caja = new rec_caja();
$orden_compra = new orden_compra();
$transaccion = new transacciones ();
$mov_contable = new movimientos_contables();
$prod=new producto();
$concep = new concepto();
$ins_nits = new nits();
$ins_pabs = new pabs();
$ins_cuenta = new cuenta();
$ins_credito = new credito();
$comprobante= new comprobante();

$gasto = $_POST['cant_gasto'];
$credito = $_POST['cantidad_credito'];
$pabs = $_POST['cant_pabs'];

$bas_retencion=0;

if(isset($_SESSION['orden']))
   $orden = $_SESSION['orden'];
if($orden!="")
{
	$tot_orden = $orden_compra->bus_Ord($orden);
	$tot_ord = mssql_fetch_array($tot_orden);
	$total = $tot_ord['ord_com_val_total'];
}
else
	$total=0;
$centro = $_POST['centro_cost'];
if($centro=="")
{
	$centro=$_SESSION["cen_cos"];
	$descrip_causacion = $_POST["descripcion"];
}
$nit = $_POST['prov'];
if($nit=="")
	$nit=$_SESSION["prov"];
if($nit!="")
{
	$con_nit = $ins_nits->consultar($nit);
	$dat_nit = mssql_fetch_array($con_nit);
	$reg_prov = $dat_nit['reg_id'];
	$dat_impuesto = explode(",",$afecto);
	for($con=0;$con<=sizeof($dat_impuesto);$con++)
	  {
		 if($dat_impuesto[$con]==$reg_prov)
		   $rtefuente = 1;
	  }
}

$iva = $_POST['iva'];
$num_doc = (int)$_POST['num_oc_fa'];
if($num_doc=="")
	$num_doc=$_SESSION["num_oc_fa"];

$cree = $_POST['cree'];
if($cree>0)
 $cantidad+=1;

$valor = $_POST['valor'];
$fecha2 = $_POST["fec_ven"];
if($fecha2=="")
	$fecha2 = $_SESSION["fec_ven"];

$fecha1 = $_POST["fecha_fact"];
if($fecha1==""){
	$fecha1 = $_SESSION["fecha_fact"];
	if($fecha1=="")
		$fecha1=$fecha2;
}

$cantidad = $_POST['cantidad'];
if($cantidad=="")
   $cantidad = $_SESSION["cant"];

$concepto = $_POST['unidad'];
$fecha = date('d-m-Y');
$ejem = $_SESSION['ejemplo'];
if($ejem=="")
	$ejem = $_SESSION["num_tra"];

$productos = $_POST['producto'];

$ica = $_POST['select2'];
$mes = $_POST['mes_sele'];
if($mes=="")
	$mes = $_SESSION['me'];	
$mes_con = split("-",$mes,2);

$conce = $comprobante->cons_comprobante($ano,$mes_con[1],20);
$sig = $comprobante->sig_comprobante(20);
$comprobante->act_comprobante($ano,$mes_con[1],20);
$sigla = $sig.$conce;

$tot_retencion = round($_SESSION['tot_retencion'],0);
$iva_produc=$_SESSION['iva_pro'];
$retencion=round($_SESSION['retencion_pro'],0);
$total_iva = trim($_SESSION['tot_iva']);

//echo "el total iba es: ".$total_iva."<br>";
$rete_pro = "";


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];


if(empty($total_iva))
{
	 for($i=1;$i<=$_POST['cant_gasto'];$i++)
 	 {
   		$referencia[$i] = strtoupper($_POST["ref".$i]);
   		$tipo_pro[$i] = $_POST["select1".$i];
   		$productos[$i] = $_POST['select2'.$i];
   		$descrip[$i] = $_POST['descr'.$i];
   		$_SESSION['iva'][$i] = $_POST['iva'.$i];
   		$_SESSION["cant_pro"][$i] = $_POST['cantidad'.$i];
		$can_pro = $_POST['cantidad'.$i];
   		$valor[$i] = $_POST['valor'.$i];
   		$total=$_POST['valor'.$i];
		$va_pro = $_POST['valor'.$i];
		$total_pro[$i] = $can_pro * $va_pro;
     }
	 
	 for($k=1;$k<=$_POST['cant_gasto'];$k++)
	 {
	 	echo $_SESSION['iva'][$k]."<br>";
	 	echo $rtefuente;
		if($_SESSION['iva'][$k]=="on")
  		{
	   		$cal_iva = $prod->ivaProducto($productos[$k]);
	   		if($cal_iva==""||$cal_iva==0)
	       		$iva_pro[$k] = 0;
	   		else
	    	{
				$total_iva = $total_iva + ($total_pro[$k]*($cal_iva)/100);
	        	$iva_pro[$k] = $total_pro[$k]*($cal_iva)/100;
			}
		}
		else
	   		$iva_pro[$k] = 0;
	  }
	if($tot_iva=="")
	   $tot_iva = 0;	   
}

if($retencion==""&&$nit!=""&&$rtefuente==1)
{
	for($k=1;$k<=$_POST['cant_gasto'];$k++)
	{
	  if($productos[$k]==28)
	  {
	    $rete_pro[$k] = $_POST['val_reteVaca'];
		$dat_rete = $prod->reteProducto($productos[$k],$nit);
		$val_rete = split("-",$dat_rete,2);
		$reteTotal = round($reteTotal + $rete_pro[$k],0);
		$cue_rete[$k] = round($val_rete[1],0);
	  }
	  else
	  {
		$dat_rete = $prod->reteProducto($productos[$k],$nit);
		$val_rete = split("-",$dat_rete,2);
		$reteTotal = round($reteTotal + ($total_pro[$k]*($val_rete[0])/100),0);
		$rete_pro[$k] = round($total_pro[$k]*($val_rete[0])/100,0);
		$cue_rete[$k] = round($val_rete[1],0);
	  }
	  if($rete_pro[$k]=='')
		$rete_pro[$k] = 0;
	}
}
$cantidad=0;
if($reteTotal>0)
   $cantidad+=1;

if($rtefuente==1&&$_SESSION['regimen_empresa']==3)
   $cantidad+=1;
/*******************************************************************************/
if($productos[1]!=0 && $valor[1] != "")
{
	$tip_mov_aud_id=1;
	$aud_mov_con_descripcion='CREACION DE DOCUMENTO DE CONTABILIDAD';
	
	
	if($descrip_causacion!="")
	   $nueTran = $transaccion->guaTransaccion(strtoupper($sigla),$fecha1,$nit,$centro,$total,$tot_iva,$fecha2,$num_doc,$_SESSION['k_nit_id'],$fecha,$mes_con[1],$ano);
	else
	   $nueTran = $transaccion->guaTransaccionCau(strtoupper($sigla),$fecha1,$nit,$centro,$total,$tot_iva,$fecha2,$num_doc,$_SESSION['k_nit_id'],$fecha,$mes_con[1],$descrip_causacion,$ano);   	

if($nueTran)
{ 
   $tran = $transaccion->obtener_concecutivo();
   $num_tran = mssql_fetch_array($tran);
   for($k=1;$k<=$gasto;$k++)
   {
	   $produc = $productos[$k];
	   $concep_pro = $concepto[$k];
	   $val_prod = $valor[$k]*$_SESSION["cant_pro"][$k]; 
	   $iva_prod = $iva_produc[$k];
	   if($iva_prod=="")
	     $iva_prod=$iva_pro[$k];
	   	 
	  if($_SESSION['cant_pro'][$k]!=trim(""))
	  {
		    $num = $transaccion->guaDetallePro(strtoupper($sigla),$fecha,$produc,$_SESSION["cant_pro"][$k],$valor[$k],$iva_prod,$centro,sizeof($productos),$sigla);
	  $concep_pro = $concep->conceProducto($produc);
	  if($rtefuente== 1)
	  {
		  $reteica = $concep->tiene_ica($concep_pro);
		  if($reteica&&$ica!="")
		    $cantidad += 1;
	  }
	  $val_total=0;
      $form = $mov_contable->consul_formulas($concep_pro);
      $i=1;$matriz;
	  if($form)
      {
        $row = mssql_fetch_array($form);	
        while($i<=21)
        {
		 $palabras=split(",",$sp);
	     $arre = split(",",$row["for_cue_afecta".$i]);
		 $a = $arre[0];
		 $b = $arre[1];
		 $c = $arre[2];
		 $d = $arre[3];
		 if($a != "" && $a != "" && $b != "" && $c != "")
		 	{
			  $matriz[$i][0]= $a;
			  $matriz[$i][1]= $b;
			  $matriz[$i][2]= $c;
			  $matriz[$i][3]= $d;
			}
		 $i++;
		}
		$cantidad += sizeof($matriz);
		if($cree>0)
		{
			$total_cree = $total_pro[$k]*($cree/100);
			$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$ica,$concep_pro,$nit,$centro,$total_cree,2,$num_doc,3,
			$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
		}
		if($reteica!=0 && $reteica!=""&&$ica!="")
	    {
			$cuenta = new cuenta();
			$por_cuenta = $cuenta->busPorCuenta($ica);
			$porcentaje = mssql_fetch_array($por_cuenta);
			$rete = $val_prod*($porcentaje['cue_porcentage']);
			$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,23657004,$concep_pro,$nit,$centro,$total_cree,2,$num_doc,3,
			$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
		}
			
		if($reteTotal>0)
		{
		 $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$cue_rete[$k],$concep_pro,$nit,$centro,$rete_pro[$k],2,$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
		}
		for($p=1;$p<sizeof($matriz);$p++)
		{ 
			if(trim($matriz[$p][3]) == "" && $p==1)
	          {  
				$total_mov = $val_prod;//+$iva_prod;
			$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_pro,$nit,$centro,$total_mov, $matriz[trim($p)][2],$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
		        $matriz[trim($p)][4] = $total_mov;
	          }
			else
	         {
	          $cuenta = new cuenta();
		      /*Fila de la matriz*/$cue_afecta=$matriz[$p][3];
			  if($matriz[$p][1] == " ")
			   {
				 $cen_cos = new centro_de_costos();
				 $matriz[$p][1] = $cen_cos->cue_cobrar_cc($centro);
			   }
		      /*Porcentaje de mi mismo*/$espor = $cuenta->busPorCuenta($matriz[$p][1]);
		      $porcen = mssql_fetch_array($espor);
		      if($porcen['cue_porcentage'] == 0)
		       {
		        $val_total = $total_mov;
    	        $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_pro,$nit,$centro,
		        $val_total,$matriz[trim($p)][2],$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
		        $total_mov = $matriz[$i][4];
		       }
		      else
		       { 
		        $val_cue = $matriz[trim($cue_afecta)][4];
				$lacuenta=$porcen['cue_id'];
				$lacadena='2408';
				if(strpos($lacuenta,$lacadena)===false||$iva_pro[$k]==0)
				  {
					 if(empty($total_iva))
						$total_mov=0;
					 else
					  $total_mov=$val_cue*($porcen['cue_porcentage']/100); 
				  }
				else
				   $total_mov=$val_cue*($porcen['cue_porcentage']/100);
		        $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_pro,$nit,$centro,
				$total_mov,$matriz[trim($p)][2],$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
				if(strstr($porcen['cue_nombre'],'IVA')&&$rtefuente==1&&$_SESSION['regimen_empresa']==3)
		          { 
				    if($matriz[trim($p)][2]==1)
					   $nat = 2;
					else
					   $nat = 1;
				    $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_pro,$nit,$centro,
				               $total_mov/2,$nat,$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
				  }
		       }
	         }//cierra el else
		}	 
		   $ultima_cuenta = $matriz[sizeof($matriz)][1];
		   $ultima_naturaleza = $matriz[sizeof($matriz)][2];
		   $bal_concep = $mov_contable->balance($num_doc,$concep_pro);
		   $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$ultima_cuenta,$concep_pro,$nit,$centro,$bal_concep[0],
		                     $ultima_naturaleza,$num_doc,3,$cantidad,$produc,$cantidad,$mes_con[1],$ano,$bas_retencion);
	   }
	 $cantidad_cuentas = 0;
	 $resto_mov = "SELECT COUNT(*) cant FROM mov_contable";
	 $resto_cant_mov = mssql_query($resto_mov);
     $resto_cantidad = mssql_fetch_array($resto_cant_mov);
	 $mov = "EXECUTE movContable ".$resto_cantidad['cant'];
	 $ins_mov = mssql_query($mov);
	 
	 
	 
	//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
	$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
	aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
	aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
	aud_mov_con_descripcion='$aud_mov_con_descripcion'
	WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_con[1]' AND mov_ano_contable='$ano'
	AND tip_mov_aud_id IS NULL";
	//echo $que_aud_mov_contable;
	$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	 
	 
	 
	 if($num && $total!=0)
	   {
	    echo "<script type=\"text/javascript\">alert(\"Se guardo el detalle de la transaccion.\");</script>";
		$actu = $orden_compra->act_ordCom($orden,4);
		if($actu)
		     echo "<script type=\"text/javascript\">alert(\"Se actualizo la orden de compra.\");</script>";
	   }  
	  }
	 }
  }
else
     echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el encabezado, intente nuevamente.\");</script>";
	 $_SESSION['orden'] = "";
	 $_SESSION['ejemplo']="";
	 $_SESSION['me']="";
	 $act_rec_caja = $ins_rec_caja->act_consecutivo(20);
 }





if($_POST['asoc0']!=0 && $_POST['val0']!="")//ENTRA CUANDO ES FABS
{
	
	
	
	//echo "entra";
	$conse = $ins_pabs->obt_consecutivo();
	$modificar = $_GET['modificar'];
	$acutalizo=0;
	if($modificar==1)
	{
		$tip_mov_aud_id=2;
		$aud_mov_con_descripcion='MODIFICACION DE CAUSACION DE FABS';
		
	  $sigla = $_POST['conse'];
	  
	  $fecha=$ins_pabs->ConsultarFechaFabs($mes_con[1],$ano,$sigla);
	  $fecha1=$ins_pabs->ConsultarFechaFabs($mes_con[1],$ano,$sigla);
	  $fecha2=$ins_pabs->ConsultarFechaFabs($mes_con[1],$ano,$sigla);
	  
	  $parSigla=explode('_',$sigla);
	  $elim_tran = $transaccion->borrTransaccion($sigla,$mes_con[1],$ano);
	  $elim_mov = $mov_contable->borr_Movimiento($sigla,$mes_con[1],$ano);
	  $elim_fabs = $ins_pabs->borrFabs($sigla,$mes_con[1],$ano);
	  $num_doc=$parSigla[1];
	  $acutalizo=1;
	}
	else
	{
		$tip_mov_aud_id=1;
		$aud_mov_con_descripcion='CREACION DE CAUSACION DE FABS';
		
	  $conce = $comprobante->cons_comprobante($ano,$mes_con[1],11);
      $sig = $comprobante->sig_comprobante(11);
      $comprobante->act_comprobante($ano,$mes_con[1],11);
	  $sigla = $sig.$conce;
	}

	$cant_pabs=$_POST['cant_pabs'];
	//echo "la cantidad es: ".$cant_pabs."<br>";
	$form = $mov_contable->consul_formulas(702);
	$j=1;$mat_pabs;

	for($i=0;$i<$cant_pabs;$i++)
	{
		//echo "entra aqui <br>";
	 $cant_cuentas=0;
	 $asociado[$i] = $_POST['asoc'.$i];//AFILIADO
	 $concep_pabs[$i] = $_POST['concep_pabs'.$i];//LINEA DE FABS
	 $nit=$_POST['prov'.$i];//PROVEEDOR
	 $tipo_pro[$i] = $_POST['pabs1'.$i];//TIPO PRUDUCTO
	 $prod_pabs[$i] = $_POST['pabs2'.$i];//PRODUCTO
	 //echo $_POST['pabs2'.$i]."<br>";
	 $cant[$i] = $_POST['cant'.$i];//CANTIDAD DE ARTICULOS
	 $descripcion_pabs[$i] = $_POST['descripcion_pabs'.$i];//DESCRIPCION DEL REGISTRO
	 $tipo_pago[$i] = $_POST['tip_pag'.$i];
	 $valor_pabs[$i] = $_POST['val'.$i];//VALOR REGISTRO FABS
	 $total=$cant[$i]*$valor_pabs[$i];//VALOR TOTAL
	 
	 $tiene_iva[$i] = $_POST['iva'.$i];
	 
	 $tot_pagar[$i] = $total;
	 

	if(!empty($asociado[$i])&&!empty($concep_pabs[$i])&&!empty($tipo_pro[$i])&&!empty($prod_pabs[$i])&&!empty($cant[$i])&&!empty($valor_pabs[$i]))
	{
		$rtefuente = 0;
		$con_nit = $ins_nits->consultar($nit);
		$dat_nit = mssql_fetch_array($con_nit);
		$reg_prov = $dat_nit['reg_id'];	
		$dat_impuesto = explode(",",$afecto);
		
	 	$concep_pro = $concep->conceProducto($prod_pabs[$i]);

	 	$reteica = $concep->tiene_ica($concep_pro);
	 	$val_total=0;
	 

		$cantidad=1;
		$gua_pabs=$ins_pabs->guardar_compraPABS($asociado[$i],$fecha1,$prod_pabs[$i],$valor_pabs[$i],$descripcion_pabs[$i],$concep_pabs[$i],$nit,0,$cant[$i],$tipo_pago[$i],$sigla,$mes_con[1],$ano);//GUARDA EN LA TABLA DE pabs
		
		$nueTran = $transaccion->guaTransaccion(strtoupper($sigla),$fecha1,$nit,$centro,$total,$tot_iva,$fecha1,$num_doc,$_SESSION['k_nit_id'],$fecha,$mes_con[1],$ano);// SOLO GUARDA EN transacciones
		if($nueTran)
	  	{
   		$tran = $transaccion->obtener_concecutivo();
   		$num_tran = mssql_fetch_array($tran);
      	$val_prod = $total;
	    $iva_prod = $iva_pro[$i];
	    if(empty($iva_prod))
	       $iva_prod=0;
		
		//$num = $transaccion->guaDetallePro(strtoupper($sigla),$fecha1,$prod_pabs[$i],$cant[$i],$valor_pabs[$i],$iva_prod,$centro,1,$sigla);//CONSULTA EL trans_id DEL DOCUMENTO
	  	$val_total=0;
		//Primera Cuenta
		
		$con_cue_fabs=$ins_pabs->ConDatLinFabPorId($concep_pabs[$i]);
		$res_cue_fabs=mssql_fetch_array($con_cue_fabs);
		$cue_fabs_dos=$res_cue_fabs['pabs_cuenta_dos'];
		$cue_fabs_uno=$res_cue_fabs['pabs_cuenta'];
		
		//CUENTA DEL FABS SEGUN EL CONCEPTO SELECCIONADO
		$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$cue_fabs_dos,$concep_pro,$asociado[$i]."_1",$centro,$total,1,$num_doc,3,$cantidad,$prod_pabs[$i],2,$mes_con[1],$ano,$bas_retencion);
		$cantidad++;
		//Segunda Cuenta, por pagar		
		$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,'61150525',$concep_pro,$asociado[$i]."_1",$centro,$total,2,$num_doc,3,$cantidad,$prod_pabs[$i],2,$mes_con[1],$ano,$bas_retencion);
		$cantidad++;
		
		$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$cue_fabs_uno,$concep_pro,$nit,$centro,$total,1,$num_doc,3,$cantidad,$prod_pabs[$i],2,$mes_con[1],$ano,$bas_retencion);
		$cantidad++;
	  
	
		//$dat_rete = $prod->reteProducto($prod_pabs[$i],$nit);
	
		//$dat_rete=$prod->ConDatProPorId($prod_pabs[$i]);
		$res_rete=$prod->ConDatProPorId($prod_pabs[$i]);
		
		if($res_rete['pro_retencion']!='' || $res_rete['pro_retencion']!=0)
		{
			$con_por_cuenta=$ins_cuenta->busPorCuenta($res_rete['pro_retencion']);
			$res_por_cuenta=mssql_fetch_array($con_por_cuenta);
			$reteTotal=round($valor_pabs[$i] * $res_por_cuenta['cue_porcentage'] / 100);	
		}
		else
		{
			$reteTotal=0;
		}
		
		$cue_rete = $res_rete['pro_retencion'];
		if($reteTotal>0)
		{
	  		//CUENTA DE RETENCION, SI TIENE.
	  		$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$res_rete['pro_retencion'],$concep_pro,$nit,$centro,$reteTotal,2,$num_doc,3,$cant_cuentas,$prod_pabs[$i],$cant_cuentas,$mes_con[1],$ano,$bas_retencion);
			$cant_cuentas++;
		}
	
	
	$balance=$total-$reteTotal;
	
	if($concep_pabs[$i]==9)
	  $cue_fabs = 25051009;
	else
	  $cue_fabs = 23803001;
	$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$cue_fabs,$concep_pro,$nit,$centro,$balance,2,$num_doc,3,$cant_cuentas,$prod_pabs[$i],$cant_cuentas,$mes_con[1],$ano,$bas_retencion);
		
		$cant_cuentas++; 
		}
	 }
	}



	//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
	$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
	aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
	aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
	aud_mov_con_descripcion='$aud_mov_con_descripcion'
	WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_con[1]' AND mov_ano_contable='$ano'
	AND tip_mov_aud_id IS NULL";
	//echo $que_aud_mov_contable;
	$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	
}//HASTA AQUI FABS





if($_POST['asoc_cre1']!=0 && $_POST['val'][1] !="")
{
	$tip_mov_aud_id=1;
	$aud_mov_con_descripcion='CREACION DE DOCUMENTO DE CONTABILIDAD';
	
	$val_credito = $_POST['val'];
	for($i=1;$i<=$credito;$i++)
	{
		$aso_cre = $_POST['asoc_cre'.$i];
		$concep_cre = $_POST['concep_cre'.$i];
		$aso_cre = $_POST['lin_cre'.$i];//Buscar en los creditos de telefonia en el campo valor este id
		$descrip_cre = $_POST['descripcion'.$i];
		$nueTran = $transaccion->guaTransaccion(strtoupper($sigla),$fecha1,$aso_cre,$centro,$val_credito[$i],0,$fecha2,$num_doc,$_SESSION['k_nit_id'],$fecha,$mes_con[1],$ano);
		if($nueTran)
  		{
			$cre_linea = split("_",$ins_credito->creTelefonia($aso_cre),2);
			//$mov_con = $mov_contable->guaMovimiento($sigla,$cre_linea[0],$cre_linea[1],$concep_cre,$aso_cre,$centro,$val_credito[$i],2,$cre_linea[0],3,$cre_linea[0],$cre_linea[0],1,$mes_con[1],$bas_retencion);
			///////////////////////////////////////
			$reteica = $concep->tiene_ica($concep_cre);
			$val_total=0;$cant_cuentas=0;
			$form = $mov_contable->consul_formulas($concep_cre);
			$j=1;$matriz;
			if($form)
			{
			   //echo "<br> el form existe <br>";
			   $row = mssql_fetch_array($form);
			   while($j<=21)
			   {
				$palabras=split(",",$sp);
				$arre = split(",",$row["for_cue_afecta".$j]);
				$a = $arre[0];
				$b = $arre[1];
				$c = $arre[2];
				$d = $arre[3];
				if($a != "" && $b != "" && $c != "")
				 {
				  $matriz[$j][0]= $a;
				  $matriz[$j][1]= $b;
				  $matriz[$j][2]= $c;
				  $matriz[$j][3]= $d;
				 }
				$j++;	
			   }
			}
			$cant_matriz = sizeof($matriz);
		    if($nueTran)
  			 { 
   				$tran = $transaccion->obtener_concecutivo();
   				$num_tran = mssql_fetch_array($tran);
      			$val_prod = $total;
	     		$iva_prod = $iva_pro[$i];
			  $num = $transaccion->guaDetallePro(strtoupper($sigla),$fecha1,$cre_linea[0],1,$val_credito[$i],0,$centro,1,$sigla);
	  			$val_total=0;$cant_cuentas=0;
				$cantidad = $cant_matriz;
				for($p=1;$p<$cant_matriz;$p++)
				 { 
				  if($matriz[$p][3] == "" && $p==1)
				  {
					 $total_mov = $val_prod+$iva_prod[$i];
					$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_cre,$aso_cre,$centro,$val_credito[$i],$matriz[trim($p)][2],$num_doc,3,$cantidad,$cre_linea[0],$cantidad,$mes_con[1],$ano,$bas_retencion);
					$matriz[trim($p)][4] = $total_mov;
				  }
				  else
				  {
				   $cuenta = new cuenta();
				   /*Fila de la matriz*/$cue_afecta=$matriz[$p][3];
				   if($matriz[$p][1] == " ")
				    {
					  $cen_cos = new centro_de_costos();
					  $matriz[$p][1] = $cen_cos->cue_cobrar_cc($centro);
				    }
				   /*Porcentaje de mi mismo*/$espor = $cuenta->busPorCuenta($matriz[$p][1]);
				   $porcen = mssql_fetch_array($espor);
				  if($porcen['cue_porcentage'] == 0)
				   {
					$val_total = $total_mov;
					$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_cre,$aso_cre,$centro,
					$val_total,$matriz[trim($p)][2],$num_doc,3,$cantidad,$cre_linea[0],$cantidad,$mes_con[1],$ano,$bas_retencion);
					$total_mov = $matriz[$i][4];
				   }
				  else
				   { 
					$val_cue = $matriz[trim($cue_afecta)][4];
					$total_mov = $val_cue * ($porcen['cue_porcentage']/100);
					$mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$matriz[trim($p)][1],$concep_cre,$aso_cre,$centro,
					$total_mov,$matriz[trim($p)][2],$num_doc,3,$cantidad,$cre_linea[0],$cantidad,$mes_con[1],$ano,$bas_retencion);
				   }
				  }//cierra el else
			     }	 
			    $ultima_cuenta = $matriz[$cant_matriz][1];
				//echo "la ult cuenta es: ".$cant_matriz."<br>";
			    $ultima_naturaleza = $matriz[$cant_matriz][2];
				//echo "la ult nat es: ".$ultima_naturaleza."<br>";
			    $bal_concep = $mov_contable->balance($num_doc,$concep_cre);
			    $mov_con = $mov_contable->guaMovimiento($sigla,$num_doc,$ultima_cuenta,$concep_cre,$nit,$centro,$bal_concep[0],$ultima_naturaleza,$num_doc,3,$cantidad,$cre_linea[0],$cantidad,$mes_con[1],$ano,$bas_retencion);
			 }
			////////////////////////////////////////
		}
	}

	//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
	$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
	aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
	aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
	aud_mov_con_descripcion='$aud_mov_con_descripcion'
	WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_con[1]' AND mov_ano_contable='$ano'
	AND tip_mov_aud_id IS NULL";
	//echo $que_aud_mov_contable;
	$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
}
//LIMPIAR SESSIONES//
unset($_SESSION['orden']);
unset($_SESSION["cen_cos"]);
unset($_SESSION["prov"]);
unset($_SESSION["num_oc_fa"]);
unset($_SESSION["fec_ven"]);
unset($_SESSION["fecha_fact"]);
unset($_SESSION["cant"]);
unset($_SESSION['ejemplo']);
unset($_SESSION["num_tra"]);
unset($_SESSION['tot_retencion']);
unset($_SESSION['iva_pro']);
unset($_SESSION['retencion_pro']);
unset($_SESSION['tot_iva']);
unset($_SESSION["cant_pro"]);
unset($_SESSION['me']);
unset($_SESSION['iva']);
/////////////////////
echo "<script language='javascript'>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".strtoupper($sigla)."&mes=".$mes_con[1]."');</script>";
echo "<script>history.back(-1);</script>";
//history.back(-1);
?>
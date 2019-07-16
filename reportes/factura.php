<?php
  session_start(); 
  //$ano = $_SESSION['elaniocontable'];
  include_once('../clases/reporte_jornadas.class.php');
  include_once('../clases/contrato.class.php');
  include_once('../clases/factura.class.php');
  include_once('../librerias/php/funciones.php');
  include_once('../clases/nits.class.php');
  include_once('../clases/recibo_caja.class.php');
  //consecutivo de la factura
  $fac = new factura();
  $ins_rec_caja = new rec_caja();
  $reporte = new reporte_jornadas();
  $con = new contrato();
  $cen_cos = new centro_de_costos();
  $nit = new nits();	
  $_SESSION['val_unitario'] = $_GET['sum_tot_jornadas'];
  $_SESSION['val_total'] = $_GET['sum_tot_jornadas'];
  $vie_factura = $_GET['fac'];
  $fec_impresion = date('d-m-Y');
  $descripcion = $_GET['descr'];
  $mes_servi = $_GET['mes_ser'];
  $mes_contable=$_GET['mes_con'];
  $num_jornadas=$_GET['jor'];
  $per_facturacion=$_GET['per_fac'];
  $anio_servicio=$_GET['ano_serv'];
  $ano = $_SESSION['elaniocontable'];
  //echo "el anio es: ".$anio_servicio;
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" type="text/javascript">
 function imprimir(opcion,url)
 {
	 if(opcion==1)
	 {
	   url='../reportes_PDF/factura_febrero.php';
	   abreFactura(url);
	 }
	 if(opcion==2) 
	   abreFactura(url);
 }
 
 function abreFactura(URL)
 {
    day=new Date();
    id=day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left=340,top=362');");	
 }
</script>
<script language="JavaScript" src ="../librerias/js/jquery.js"></script>

<form name="fta" id="fta" action="#" method="post">
      <?php 
      /********************CIERRE ENCABEZADO*******************/
	  $rep_jornadas=$_SESSION["conse"];
	  if($rep_jornadas=="")
	  	$rep_jornadas = $_POST['imp_fac'];
	  $reporte_jornadas = $rep_jornadas;
	  $exis_factura = $reporte->fac_reporte($rep_jornadas);
	  $tipo = $_SESSION["tip_sele"];
  	  if($exis_factura!="")
		{
		  //echo "entra por el if 2 <br>";
		  $datFactura = $reporte->repJornadas($rep_jornadas);
		  $dat_rep_sele = mssql_fetch_array($datFactura);
		  $dat_conse = $fac->datFactura($dat_rep_sele['rep_jor_num_factura']);
		  $datos = mssql_fetch_array($dat_conse);
		  $_SESSION['consecutivo']=$datos['fac_consecutivo'];
		  $_SESSION['val_unitario']=$datos['fac_val_unitario'];
		  $_SESSION['val_total']=$datos['fac_val_total'];
		  $_SESSION['descripcion'] = $datos['fac_descripcion'];
		  $dat_hospital = $nit->consul_nits($datos['fac_nit']);
		  $datos_hos = mssql_fetch_array($dat_hospital);
		  $_SESSION['nits_nombres']=$datos_hos['nits_nombres'];
		  $_SESSION['nits_num_documento']= $datos_hos['nits_num_documento'];
		  $_SESSION['nits_dir_residencia']=$datos_hos['nits_dir_residencia'];
		  $_SESSION['nits_tel_residencia']=$datos_hos['nits_tel_residencia'];
		}
		else
		{
		  //echo "entra por el else 2 <br>";
		  $rep_jornadas = $_SESSION["conse"];
		  if($_POST["centro"]!="" && $_POST["centro"]>0)
		   { 
			$cen_costo = $_POST["centro"];
			$centro_costo = $_POST["centro"];
		   }
		   elseif($_POST["centro1"]!="" && $_POST["centro1"]>0)
		   { 
			$cen_costo = $_POST["centro1"];
			$centro_costo = $_POST["centro1"];
		   }
		   elseif($_POST["centro_cos"]!="" && $_POST["centro_cos"]>0)
		   {
			 $cen_costo=$_POST["centro_cos"];
			 $centro_costo=$_POST["centro_cos"];
		   }
		  if($centro_costo=="")
		   {
			 $cen_costo=$_SESSION["hospital"];
			 $centro_costo=$_SESSION["hospital"];
		   }
		  

		  /*********BUSCAR CONSECUTIVO FACTURA**************/
		   $mes_factura = explode("-",$fec_impresion,3);
		   if(sizeof($mes_factura)>1)
		   {
		   	if($mes_factura[1]==1 || $mes_factura[1]==01)
				{
				 $_SESSION['consecutivo'] = $fac->buscar_consecutivo($centro);
				  $act_resolu = $fac->actConse_resolucion($centro);
				  ?>
                  <input type="hidden" name="tipofactura" id="tipofactura" value="1"/>
                  <?php
				}
				else
				{
				  $_SESSION['consecutivo'] = $fac->buscar_consecutivo($centro);
				  $act_resolu = $fac->actConse_resolucion($centro);
				   ?>
                  <input type="hidden" name="tipofactura" id="tipofactura" value="2"/>
                  <?php
				}
			}
			else
			{
				$mes_factura = explode("/",$fec_impresion,3);
				if($mes_factura[1]==1 || $mes_factura[1]==01)
				{
				 $_SESSION['consecutivo'] = $fac->buscar_consecutivo($centro);
				  $act_resolu = $fac->actConse_resolucion($centro);
				   ?>
                  <input type="hidden" name="tipofactura" id="tipofactura" value="1"/>
                  <?php
				}
				else
				{
				  $_SESSION['consecutivo'] = $fac->buscar_consecutivo($centro);
				  $act_resolu = $fac->actConse_resolucion($centro);
				   ?>
                  <input type="hidden" name="tipofactura" id="tipofactura" value="2"/>
                  <?php
				}
			}
		  /*************************************************/
		  
		  $rep_sele = $reporte->repJornadas($reporte_jornadas);
		  $dat_rep_sele = mssql_fetch_array($rep_sele);
		  $mes_facturar = split('/',$dat_rep_sele['rep_jor_mes'],2);
		  if($mes_facturar[0]==1)
			 $nombre_mes = "Enero";
		  elseif($mes_facturar[0]==2)
			 $nombre_mes = "Febrero";
		  elseif($mes_facturar[0]==3)
			 $nombre_mes = "Marzo";
		  elseif($mes_facturar[0]==4)
			 $nombre_mes = "Abril";
		  elseif($mes_facturar[0]==5)
			 $nombre_mes = "Mayo";
		  elseif($mes_facturar[0]==6)
			 $nombre_mes = "Junio";
		  elseif($mes_facturar[0]==7)
			 $nombre_mes = "Julio";	 
		  elseif($mes_facturar[0]==8)
			 $nombre_mes = "Agosto";
		  elseif($mes_facturar[0]==9)
			 $nombre_mes = "Septiembre";
		  elseif($mes_facturar[0]==10)
			 $nombre_mes = "Octubre";
		  elseif($mes_facturar[0]==11)
			 $nombre_mes = "Noviembre";
		  elseif($mes_facturar[0]==12)
			 $nombre_mes = "Diciembre";
		  
		  if($fec_impresion)
		    $_SESSION['fecha']= $fec_impresion;
		  else
		  {
		  	$fecha = date('d-m-Y');
		  	$_SESSION['fecha']= $fecha;
		  }
		  
		  //tipo de contrato y sus datos
		  $centro_cos = $cen_cos->buscar_centros($centro_costo);
		  $centro = mssql_fetch_array($centro_cos);
		  $_SESSION['ciudad'] = $centro['ciu_nombre'];
		  $contra = $con->contrato($centro['cen_cos_nit']);
		  $dat_contrato =  mssql_fetch_array($contra);
		  
		  if($dat_contrato['con_val_hor_trabajada'] != "" && $dat_contrato['con_val_hor_nocturna'])
		  {
			$can_reporte = $reporte->canJornadas($cen_costo,$mes_contable,$ano);
			 if($can_reporte<1000)
			   {
				 $_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
				 $_SESSION['val_total']=$_GET['sum_tot_jornadas'];
			   }
			   else
			   {
				 $_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
				 $_SESSION['val_total']=$_GET['sum_tot_jornadas'];
			   }
			$_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
			$_SESSION['val_total']=$_GET['sum_tot_jornadas'];
			$_SESSION['descripcion'] = $descripcion;
			$_SESSION['dias'] = $dat_contrato['dias'];  
		  }
		  else
		  {
			if($tipo==0 && $vie_factura==1)
			{
			  $i=0;
			  $val_unitario = $_GET['sum_tot_jornadas']/*[$rep_jornadas]*/;
			  if($val_unitario!="")
			  {
				$_SESSION['val_unitario']=$val_unitario;
				$val_total = $val_unitario;
				$_SESSION['val_total']=$val_unitario;
			  }
			  else
			  {
				$_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
				$val_total = $_GET['sum_tot_jornadas']; //$val_unitario*($dat_contrato['con_val_hor_trabajada']*6);
				$_SESSION['val_total']=$val_total;
			  }
			  $_SESSION['descripcion'] = $descripcion;
			  $_SESSION['dias'] = $dat_contrato['dias'];
			}
			elseif($dat_contrato['tip_con_pre_id']==1)
			{
			  $val_unitario = $_GET['sum_tot_jornadas'];
			  $_SESSION['val_unitario']=$val_unitario;
			  $val_total = $_GET['sum_tot_jornadas'];
			  $_SESSION['val_total']=$val_total;
			  $_SESSION['descripcion'] = $descripcion;
			  $_SESSION['dias'] = $dat_contrato['dias'];
			}
			elseif($dat_contrato['tip_con_pre_id'] == 2)
			{
			 $val_unitario = $dat_contrato['con_val_hor_trabajada'];
			 $can_reporte = $reporte->canJornadas($cen_costo,$mes_contable,$ano);
			 $val_total = ($dat_contrato['con_val_hor_trabajada'] * 6) * $can_reporte;
			 $_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
			 $_SESSION['val_total']=$_GET['sum_tot_jornadas'];
			 $_SESSION['descripcion'] = $descripcion;
			 $_SESSION['dias'] = $dat_contrato['dias'];
			}
			elseif($dat_contrato['tip_con_pre_id'] == 3)
			{
			   $can_reporte = $reporte->canJornadas($cen_costo,$mes_contable,$ano);
			   $_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
			   $_SESSION['val_total']=$_GET['sum_tot_jornadas'];
			   $_SESSION['descripcion'] = $descripcion;
			   $_SESSION['dias'] = $dat_contrato['dias'];
			}
		  }
		   $dat_hospital = $nit->consul_nits($centro['cen_cos_nit']);
		   $datos_hos = mssql_fetch_array($dat_hospital);
		   $_SESSION['nits_nombres']=$datos_hos['nits_nombres'];
		   $_SESSION['nits_num_documento']= $datos_hos['nits_num_documento'];
		   $_SESSION['nits_dir_residencia']=$datos_hos['nits_dir_residencia'];
		   $_SESSION['nits_tel_residencia']=$datos_hos['nits_tel_residencia'];
		  }

		
		if($_SESSION['val_unitario']==""||$_SESSION['val_total']=="")
		{
			$_SESSION['val_unitario']=$_GET['sum_tot_jornadas'];
			$_SESSION['val_total']=$_GET['sum_tot_jornadas'];
		}
			
		  ?>
		    <table id="encabezado" border="1">
       			<tr>
        			<th colspan="3">FACTURA DE VENTA No. <?php echo $_SESSION['consecutivo']; ?></th>
       			</tr>
      		</table>
		    
			<table id="dat_encabe" border="1">
			 <tr>
			   <th>FEHCA: </th>
			   <td><?php echo  $_SESSION['fecha']; ?></td>
			   <th>CIUDAD: </th>
			   <td><?php echo $_SESSION['ciudad']; ?></td>
			 </tr>
			 <tr>
			   <th>CLIENTE <?php echo $_SESSION['nits_nombres']; ?></th>
			   <th>NIT: </th>
			   <td><?php echo  $_SESSION['nits_num_documento']; ?></td>
			 </tr>
			 <tr>
			   <th>DIRECCION: </th>
			   <td><?php echo $_SESSION['nits_dir_residencia']; ?></td>
			   <th>TEL:</th>
			   <td><?php echo $_SESSION['nits_tel_residencia']; ?></td>
			 </tr>
			 <?php
			 if($dat_contrato['dias']=='' || $dat_contrato['dias']==0)
			 {
			 	$query_2="SELECT * FROM variables WHERE var_id=8";
				$ejecutar_2=mssql_query($query_2);
		  		$res_dias=mssql_fetch_array($ejecutar_2);
				$_SESSION['dias']=$res_dias['var_valor'];
			 }
			 ?>
			 <tr>
			   <td colspan="4"><?php echo "LA FACTURA VENCE PASADOS ".$_SESSION['dias']." DIAS CALENDARIO"; ?></td>
			 </tr>
			</table>
			<table id="dat_factura" border="1">
			 <tr>
			  <th>DESCRIPCION</th>
			  <th>VALOR UNITARIO</th>
			  <th>VALOR TOTAL</th>
			 </tr>
			 <tr>
			 <?php
			 if(trim($_SESSION['descripcion'])=="")
			 	$_SESSION['descripcion']=$_GET['descr'];
			 ?>
			  <td><?php echo $_SESSION['descripcion']; ?></td>
			  <td><?php echo number_format(round($_SESSION['val_unitario'])); ?></td>
			  <td><?php echo number_format(round($_SESSION['val_total'])); ?></td>
			 </tr>
			</table>
		  <?php 
		  //cuando este guardando la factura tengo que hacer el   $act_conse = $fac->act_consecutivo();
		  $nit = $datos_hos['nit_id'];
		  //falta el centro, falta la descripcion, falta el valor unitario, valor total, nit
		  
		   if(trim($_SESSION['descripcion'])=="")
			 	$_SESSION['descripcion']=$_GET['descr'];
		  
		  if($exis_factura=="")
		  {
		  	if($num_jornadas==0)
		  		$num_jornadas=$_SESSION['val_total'];
			$gua_factura=$fac->guardar_factura($centro_costo,$_SESSION['descripcion'],$_SESSION['val_unitario'],$_SESSION['val_total'],$_SESSION['consecutivo'],$_SESSION["centro"],$mes_contable,$ano,$centro['cen_cos_nit'],$reporte_jornadas,$mes_contable,1,$fec_impresion,$mes_servi,$num_jornadas,$per_facturacion,$anio_servicio);
		  }
			
	$_SESSION['num_factura'] = "";
	$_SESSION['contrato_id']=$dat_contrato['con_id'];
?>
  <input type="button" name="regresar" id="regresar" value="&larr; Regresar" onclick="javascript:location.href='../index.php?c=32'"/>
  <input type="button" name="factura" id="factura" value="Imprimir Factura" onclick="javascript:imprimir(1,'../reportes_PDF/factura_pdf.php')"/>
  <input type="button" name="nomina" id="nomina" value="Imprimir Causacion compensacion"  onclick="javascript:imprimir(2,'../reportes_PDF/causacion_nomina.php')"/>
  </form>
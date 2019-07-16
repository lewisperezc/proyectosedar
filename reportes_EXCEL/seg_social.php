<?php session_start();
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();


$tipo_proceso=$_POST['consulta_seg_social'];


$mes_pago=explode('-', $_POST['mes_pag_seg_social']);
$ano=$_POST['anio_pag_seg_social'];


if($mes_pago[1]==1)
{
	$mes_servicio=11;
    $anio_servicio=$ano-1;
}
elseif($mes_pago[1]==2)
{
	$mes_servicio=12;
    $anio_servicio=$ano-1;
}
else
{
	$mes_servicio=$mes_pago[1]-2;
    $anio_servicio=$ano;
}

if($tipo_proceso==1)//ES PAGO
{

  
  $usuario=$_SESSION['k_nit_id'];
  $pag_seg_soc_fecha=date('d-m-Y');
  $pag_seg_soc_hora=date("G:i:s a");
  
 

  $estado_nit=1;
  $tipo_nit=1;
  $tipo_ejecucion=1;
  //$eli_seg_soc_pagada=$ins_mov_contable->EliminarSeguridadSocialPagada($mes_pago[1],$ano);

  $gua_seg_social=$ins_mov_contable->GuardarSeguridadSocial($mes_servicio,$anio_servicio,$estado_nit,$tipo_nit,$pag_seg_soc_fecha,$pag_seg_soc_hora,$mes_pago[1],$ano,$usuario);

  //$con_pag_seg_social=$ins_mov_contable->ObtenerPagoSeguridadSocial($mes_pago[1],$ano);
}
/*elseif($tipo_proceso==2)//ES CONSULTA
{
  $con_pag_seg_social=$ins_mov_contable->ObtenerPagoSeguridadSocial($mes_pago[1],$ano);
}*/

$con_pag_seg_social=$ins_mov_contable->ObtenerPagoSeguridadSocial($mes_pago[1],$ano);

$num_filas=mssql_num_rows($con_pag_seg_social);

//echo "aaa";


if($num_filas>0)
{
  header('Content-type: application/vnd.ms-excel');
  header("Content-Disposition: attachment; filename=Pagsegsocial");
  header("Pragma: no-cache");
  header("Expires: 0");

  echo "<table border=1> ";
    echo "<tr>";
      echo "<th>CEDULA</th>";
      echo "<th>APELLIDOS</th>";
      echo "<th>NOMBRES</th>";
      echo "<th>VALOR MONTO FIJO</th>";
      echo "<th>% FABS</th>";
      echo "<th>TIPO SEGURIDAD SOCIAL</th>";
      echo "<th>% SEGURIDAD SOCIAL</th>";
      echo "<th>FACTURADO</th>";
      echo "<th>FABS</th>";
      echo "<th>APORTES</th>";
      echo "<th>VACACIONES</th>";
      echo "<th>ADMON</th>";
      echo "<th>EDUCACION</th>";
      echo "<th>DESPUES DE DEDUCCIONES</th>";
	  
	  echo "<th>VAL FDO RECREACION</th>";
	  echo "<th>VAL DIETAS</th>";
	  echo "<th>VAL FDP RETIRO SINDICAL</th>";
	  
      echo "<th>IBC</th>";
      echo "<th>PAGO FONDOS</th>";
      echo "<th>% FDO SOLIDARIDAD</th>";
      echo "<th>VAL FDO SOLIDARIDAD</th>";
      echo "<th>TOTAL PAGO FONDOS</th>";
    echo "</tr> ";
  while($res_pag_seg_social=mssql_fetch_array($con_pag_seg_social))
  {
    echo "<tr>";
      echo "<td>".$res_pag_seg_social['nits_num_documento']."</td>";
      echo "<td>".$res_pag_seg_social['nits_apellidos']."</td>";
      echo "<td>".$res_pag_seg_social['nits_nombres']."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_mon_fijo'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_por_fabs'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_tip_seg_social'])."</td>";
      echo "<td>".str_replace('.',',',$res_pag_seg_social['pag_seg_soc_por_seg_social'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_facturado'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_fabs'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_aportes'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_vacaciones'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_administracion'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_educacion'])."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_des_deduc'])."</td>";
	  
	  echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_pag_fon_recrea'])."</td>";
	  echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_pag_dietas'])."</td>";
	  echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_val_pag_fon_ret'])."</td>";
	  
      //echo "<td>".round($res_pag_seg_social['pag_seg_soc_ibc'],-3)."</td>";
      
      echo "<td>".floatval(round($res_pag_seg_social['pag_seg_soc_gra_tot_ibc'],-3))."</td>";
	  
      echo "<td>".floatval(round($res_pag_seg_social['pag_seg_soc_pag_fondos'],-2))."</td>";
      echo "<td>".floatval($res_pag_seg_social['pag_seg_soc_por_fon_solid'])."</td>";
      echo "<td>".floatval(round($res_pag_seg_social['pag_seg_soc_val_fon_solid'],-2))."</td>";
      echo "<td>".floatval(round($res_pag_seg_social['pag_seg_soc_tot_pag_fondos'],-2))."</td>";
    echo "</tr> ";
  }
  echo "</table> ";
}
else
{
  echo "<script>alert('No se encontraron datos para mostrar, intentelo de nuevo.');history.back(-1);</script>";
}
 
 
?>
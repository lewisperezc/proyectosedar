<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

//INICIO OBTENGO EL INTERES QUE MANEJA LA LINEA
include_once('../clases/credito.class.php');
$inst_credito = new credito();
if(isset($_SESSION['cre_linea']))
	$interes_credito = $_SESSION['cre_linea'];
else
	$interes_credito = $_POST['cre_linea'];

$consulta = $inst_credito->busPorCuenta($interes_credito);
$result = mssql_fetch_array($consulta);
//FIN OBTENGO EL INTERES QUE MANEJA LA LINEA
//INICIO CAPTURO LO QUE VIENE DEL FORMULARIO ANTERIOR
	$_SESSION['cre_linea'] = $_POST['cre_linea'];
	$_SESSION['cre_cen_cos'] = $_POST['cre_cen_cos'];
	$_SESSION['cre_observacion'] = $_POST['cre_observacion'];
	$_SESSION['cre_valor'] = str_replace(',','',$_POST['cre_valor']);
	$_SESSION['cre_dtf'] = $_POST['cre_dtf'];
	//
	$_SESSION['cre_men'] = $_POST['cre_men'];
	//
	$_SESSION['cre_num_cuotas'] =  $_POST['cre_num_cuotas'];
	$_SESSION['cre_tip_descuento'] = $_POST['cre_tip_descuento'];
	$_SESSION['cre_codeudor'] = $_POST['cre_codeudor'];
	
	$_SESSION['cre_fec_solicitud'] = $_POST['cre_fec_solicitud'];
	//$_SESSION['cre_fec_desembolso'] = $_POST['cre_fec_desembolso'];
	$_SESSION['cre_fec_pri_pago'] = $_POST['cre_fec_pri_pago'];
	$_SESSION['cre_fec_vencimiento'] = $_POST['cre_fec_vencimiento'];
	$_SESSION['cre_for_liquidacion'] = $_POST['cre_for_liquidacion'];
	$_SESSION['cre_nota'] = $_POST['cre_nota'];
	
	//////////
	$_SESSION['cre_garantia'] = $_POST['cre_garantia'];
	if($_SESSION['cre_garantia']=='')
	$_SESSION['cre_garantia'] = 'NO';
	else
	$_SESSION['cre_garantia'] = 'SI';
	$_SESSION['cre_tip_garantia'] = $_POST['cre_tip_garantia'];
	//INICIO GARANTIA POR CARRO
	$_SESSION['cre_sec_tra_carro'] = $_POST['cre_sec_tra_carro'];
	$_SESSION['cre_num_pla_carro'] = $_POST['cre_num_pla_carro'];
	//FIN GARANTIA POR CARRO
	
	//INICIO GARANTIA POR CASA
	$_SESSION['cre_num_esc_casa'] = $_POST['cre_num_esc_casa'];
	$_SESSION['cre_num_not_casa'] = $_POST['cre_num_not_casa'];
	$_SESSION['cre_fec_con_casa'] = $_POST['cre_fec_con_casa'];
	//FIN GARANTIA POR CASA
	//////////	
	
//FIN CAPTURO LO QUE VIENE DEL FORMULARIO ANTERIOR

?>
<script>
function preguntar()
{
	var a = confirm("Recuerde Que El Credito No Permite Ser Modificado, Esta Seguro que desea Registrarlo?");
	if(a)
	{
		document.tabla_amortizacion.submit();
	}
}
</script>
<?php
function suma_fechas_dias($fecha,$ndias)        
{
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$ao)=split("/", $fecha);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$ao)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$ao) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d-m-Y",$nueva);
      return ($nuevafecha);  
}
function restaFechas($dFecIni, $dFecFin)
{
  $dFecIni = str_replace("-","",$dFecIni);
  $dFecFin = str_replace("-","",$dFecFin);

  ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
  ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

return round(($date2 - $date1) / (60 * 60 * 24));
}
function sumar_meses($fechaini, $meses)
{
 list($af1,$mf1,$df1)=split("-",$fechaini);   
 
 if ($mf1>11)
 {
  $mf1=$mf1-12;
  $df1=$df1+1;
 } 

 $mf1 = $mf1 +1;
 if ($mf1>9)
     $fecha1=$af1."-".$mf1."-".$df1;
 else
 	 $fecha1=$af1."-".'0'.$mf1."-".$df1;
 
 return $fecha1;
}
?>

<?php
	$cre_num_cuotas = $_SESSION['cre_num_cuotas'];
	$cre_valor_1 = str_replace(',','',$_SESSION['cre_valor']);
	$cre_valor_2 = str_replace(',','',$_SESSION['cre_valor']);
	$cre_taza_nom = ($_SESSION['cre_dtf']+$result['cue_porcentage']);
	$interes = 0;
	$fecha = date("d-m-Y");
	if (!isset($cre_num_cuotas)) {$cre_num_cuotas = 1;}
	if (!isset($cre_valor_1)) {$cre_valor_1 = 1;}
	$capital = $cre_valor_1/$cre_num_cuotas;
	$interes = $cre_taza_nom;
	$interes = (($interes/360)/100);
	
	
	$cre_for_liquidacion=$_SESSION['cre_for_liquidacion'];
	//echo "dato: ".$interes."<br>";
?>
<body>
<form name="tabla_amortizacion" method="post" action="../control/guardar_registro_credito.php">
<center>
<table border="1" class="texto_alineado_derecha">
        <tr>
            <td colspan="6" align="center"><font color="#FF0000"><b>Tasa de Amortizacion</b></font>
            <b><?php echo $cre_taza_nom; ?></b></td>
        </tr>
       <tr>
          <td><b>N&uacute;mero Cuota</b></td>
          <td><b>Fecha</b></td>
          <td><b>Cuota</b></td>
		  <td><b>Capital abonado</b></td>
		  <td><b>Intereses</b></td>
		  <td><b>Saldo</b></td>		  	  
      </tr>
	<?php	
	$fecha1 = $_SESSION['cre_fec_solicitud'];
	$interes_pag = 0;
	$tot_cuota=0;
	$tot_cap_abonado=0;
	$tot_interes=0;
	$tot_pagado=0;
		
	    for($i=0;$i<$cre_num_cuotas;$i++)
		{
	?>
	       <tr>
	           <td>
			   		<input type="text" name="num_cuotas[<?php echo $i ?>]" readonly="readonly" value="<?php echo $i+1; ?>" size="4">
	           </td>
	           <td>
			   <input type="text" name="fecha3[<?php echo $i ?>]" readonly="readonly" value="<?php echo $fecha3 = sumar_meses($fecha1, 1); ?>" size="12"/>
			   </td>
	           <td>
	<?php								
			   $fecha2 = $fecha1;
			   $fecha1 = sumar_meses($fecha1, 1);
			   $dias = 30;
					
			   if ($i==0) 
			   {
					$intereses = $interes*$dias;
					
					$interes_pag = $cre_valor_1 * $cre_taza_nom / 100;
					
					$pagar = $capital + $interes_pag;
					$cre_valor_1 = $cre_valor_1-$capital;
					
					$tot_cuota+=$pagar;
	?>
					<input type="text" style="text-align: right" name="pagar[<?php echo $i ?>]" readonly="readonly" value="<?php echo number_format(round($pagar)); ?>"/>
	<?php
			   }
			   else 
			   {
				    $intereses = $interes*$dias;
					
					$interes_pag = $cre_valor_1 * $cre_taza_nom / 100;
					
					$pagar = $capital + $interes_pag;
					$cre_valor_1 = $cre_valor_1-$capital;
					
					$tot_cuota+=$pagar;
	?>		
					<input type="text" style="text-align: right" name="pagar[<?php echo $i ?>]" readonly="readonly" value="<?php echo number_format(round($pagar)); ?>"/>
	<?php
			   }
	?>
			   </td>
			   <td>
	<?php
	
			   $capital = $pagar-$interes_pag;
			   
			   $tot_cap_abonado+=$capital;
			   $tot_interes+=$interes_pag;
	?>
			   <input type="text" style="text-align: right" name="capital[<?php echo $i ?>]" readonly="readonly" value="<?php echo number_format(round($capital)); ?>"/>
			   </td>
	           <td>
	           <input type="text" style="text-align: right" name="interes_pag[<?php echo $i ?>]" readonly="readonly" value="<?php echo number_format(round($interes_pag)); ?>"/>
	           </td>
			   <td>
			   	<input type="text" style="text-align: right" name="cre_valor_1[<?php echo $i ?>]" readonly="readonly" value="<?php echo number_format(round($cre_valor_1)); ?>"/>
	           </td>
	     </tr>
	<?php		
		  	}
	?>
     <tr>
     	<td colspan="2"><b>TOTALES:</b></td>
     	<td style="text-align: right"><b><?php echo number_format($tot_cuota,0); ?></b></td>
     	<td style="text-align: right"><b><?php echo number_format($tot_cap_abonado,0); ?></b></td>
     	<td style="text-align: right"><b><?php echo number_format($tot_interes,0); ?></b></td>
     	<td><b></b></td>
     </tr>   
     <tr>
     	<td colspan="6">
        <input type="submit" class="art-button" name="atras" onclick="document.tabla_amortizacion.action='registrar_credito_3.php'" value="<< Atras"/>
        <input type="button" class="art-button" name="guardar" value="Registrar Credito" onclick="preguntar();"/></td>
     </tr>
</table>
</center>
</form>
</body>
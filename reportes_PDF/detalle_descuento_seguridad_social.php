<?php
//session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/compensacion_nomina.class.php');
include_once('../clases/reporte_jornadas.class.php');
include_once('../clases/nomina.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/credito.class.php');
$ins_mes_contable=new mes_contable();
$ins_varios=new varios();
$ins_concepto=new concepto();
$reporte = new reporte_jornadas();
$ins_nit = new nits();
$saldo = new saldos();
$con_nomina = new compensacion_nomina();
$ins_nomina=new nomina();
$cuenta = new cuenta();
$instancia_factura = new factura();
$ins_credito = new credito();
$valor=$_GET['valor'];//EL ID DE LA FACTURA
$valfactura=$_GET['valfactura'];
$elrecibo=$_GET['elrecibo'];
$conse_recibo=$_GET['conse_recibo'];
$minimo = $ins_nit->sal_minimo();
///////////////////////
$que_1="SELECT rec_caj_id FROM recibo_caja WHERE rec_caj_factura=$valor";
//echo $que_1;
$eje_1=mssql_query($que_1);
$i=0;
while($res_1=mssql_fetch_array($eje_1))
{
	$los_id[$i]=$res_1['rec_caj_id'];
	$i++;
}
//echo $elrecibo;
$posicion=array_search($elrecibo,$los_id);
//echo $posicion;
$que_2="SELECT DISTINCT mov_cent_costo,mov_compro,mov_mes_contable,mov_ano_contable
FROM movimientos_contables WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume=$valor
AND mov_documento NOT LIKE ('%$valor')";
//echo $que_2;
//echo $que_2."<br>";
$eje_2=mssql_query($que_2);
$k=0;
while($res_2=mssql_fetch_array($eje_2))
{
	$los_comprobantes[$k]=$res_2['mov_compro'];
	$los_centros[$k]=$res_2['mov_cent_costo'];
	$k++;
	//echo "Entra por aqui";
}
$la_nomina=$los_comprobantes[$posicion];
$el_centro=$los_centros[$posicion];

if($la_nomina=="")
	$la_nomina=$los_comprobantes[0];

if($el_centro=="")
	$el_centro=$los_centros[0];

//echo "datos: ".$la_nomina."___".$el_centro."<br>";
//$dat_nits="SELECT distinct nit_id FROM dbo.nits_por_cen_costo WHERE cen_cos_id=".$el_centro;//ANTERIOR CONSULTA
$dat_nits="SELECT distinct npcc.nit_id
FROM dbo.nits_por_cen_costo npcc INNER JOIN movimientos_contables mc ON npcc.nit_id=mc.mov_nit_tercero
WHERE cen_cos_id=$el_centro AND mov_cuent=25051001 AND mov_compro='$la_nomina'";//NUEVA CONSULTA
//echo $dat_nits;
/*SELECT distinct npcc.nit_id FROM dbo.nits_por_cen_costo npcc
INNER JOIN movimientos_contables mc ON npcc.nit_id=mc.mov_nit_tercero*
WHERE cen_cos_id= AND mov_cuent=23809501 AND mov_compro=''*/


//echo $dat_nits;
$query_nits = mssql_query($dat_nits);

$nomina = $la_nomina;
$dat_nomina = split("-",$nomina,3);
$conse_nomina = $nomina;
$dat_nom = split("-",$nomina,3);
$conse_nom = $dat_nom[2];

require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/aso_nom_causacion.jpg",0,0,200,'C');
//Arial bold 15 
$this->SetFont('Arial','B',7);
//Movernos a la derecha 
//$this->Cell(10); 
$this->Ln(10); 
}
//Pie de página 
function Footer() 
{ 
//Posici�n: a 1,5 cm del final 
$this->SetY(-15); 
//Arial italic 8 
$this->SetFont('Arial','I',7); 
//N�mero de p�gina 
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 

$pdf=new PDF(); 
//Tipo y tamaño de lertra 
$pdf->SetFont('Arial','B',7);
$contador=0;
$dat_factura = $instancia_factura->datFactura($valor);
$dat_facMes = mssql_fetch_array($dat_factura);
$suma_por_retencion=0;


$sql_factura = "select * from factura inner join centros_costo on cen_cos_id = fac_cen_cos where fac_id=".$valor;
$query_fac = mssql_query($sql_factura);
$dat_factura = mssql_fetch_array($query_fac);
while($row=mssql_fetch_array($query_nits))
{
        $pdf->AddPage();
        $nit=$row['nit_id'];
        $res_nomina=$ins_nomina->trae_datos_nomina($conse_nomina,$nit,2);
        $dat_asociado = mssql_fetch_array($res_nomina);
        //$admon=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'263535');
        $honorarios=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23352501');
        $retefuente=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23650501');
        $compenasociado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'25051001');
        $compenompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'25051001');
        $segsocialnomcau=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'13250594');
        $vacnompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'25051005');
        $fabspagado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'250520101');
        $fonretsindical=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23803009');
        //$pubcontrato=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'61651035');
        $pubcontrato=0;
        $nov = $honorarios + $retefuente + $compenasociado + $vacnompagada + $fabspagado + $fonretsindical + $pubcontrato + $segsocialnomcau;
        $estado = $ins_nit->est_asociado($nit);
        //echo $estado."____".$compenompagada."<br>";
		$datos = $reporte->bus_datCompensacion();
        $dat_compe = mssql_fetch_array($datos);
		$sql_glosa = "SELECT SUM(disGlo_valor) valor FROM distGlosa WHERE disGlo_nit = $nit AND disGlo_compensacion = '$conse_nomina'";	
		//echo $sql_glosa;
        $query_glosa = mssql_query($sql_glosa);
        $dat_glosa = mssql_fetch_array($query_glosa);
		
		
		$dat_descuentos = "SELECT * FROM descuentos_compensacion WHERE des_nom_nit = $nit AND des_nom_factura=$valor AND des_nom_rec_caja = ".$elrecibo;
		//echo $dat_descuentos;
        //echo $dat_descuentos."<br>";
		
        if($estado==1)
        {
        	  $total_participacion=0;	    
              $porcentaje=$nov*($dat_compe['dat_nom_gastos']+$dat_compe['dat_nom_educacion'])/100;//5 DE ADMINISTRACIÓN Y 1 DE EDUCACIÓN = 5
              $novedad=$nov+$porcentaje;
              $factura = "SELECT rec_caj_monto FROM recibo_caja WHERE rec_caj_id=".$elrecibo;
              $dat_fac = mssql_query($factura);
              $datos_fac = mssql_fetch_array($dat_fac);
              $fac_id = $conse_nom;
              $fac_valor = $datos_fac['rec_caj_monto'];//ESTA
              $cen_cos = $el_centro;
              $sum_descuentos="SELECT SUM(des_monto) descuentos FROM descuentos d INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
        WHERE f.fac_id=".$valor." AND (des_tipo not in(1,2) OR des_distribucion IS NOT NULL) AND rc.rec_caj_id=$elrecibo";
              //echo $sum_descuentos;
              $rec_caj_des = mssql_query($sum_descuentos);
              $dat_rec_caja = mssql_fetch_array($rec_caj_des);
              $total_descuentos=$dat_rec_caja['descuentos'];
			  //echo $total_descuentos;
              if($posicion==0)
               {
                      $cant_jornadas=$reporte->canJorFac($valor);		
                      $jor_asociado="select rep_jor_num_jornadas from reporte_jornadas rj inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen where npcc.nit_id = ".$nit." and cen_cos_id = $cen_cos and rep_jor_num_factura=$valor";
               }
               else
               {
                      $que_3="SELECT SUM(rep_jor_con_rec_numero) rep_jornadas FROM rep_jor_con_recibo WHERE fac_id=".$valor." AND rec_caj_consecutivo=".$conse_recibo;
                      $eje_3=mssql_query($que_3);
                      $res_4=mssql_fetch_array($eje_3);
                      $cant_jornadas=$res_4['rep_jornadas'];
                      $jor_asociado="select distinct rep_jor_con_rec_numero as rep_jor_num_jornadas from rep_jor_con_recibo rjcr inner join nits_por_cen_costo npcc on npcc.nit_id=rjcr.nit_id where npcc.nit_id=".$nit." and npcc.cen_cos_id=".$cen_cos." and fac_id=".$valor." and rec_caj_consecutivo=".$conse_recibo;
               }
               $val_jornada=$fac_valor/$cant_jornadas;
               $jor_aso=mssql_query($jor_asociado);
               $can_jornadas=mssql_fetch_array($jor_aso);
               $cantidad=$can_jornadas['rep_jor_num_jornadas'];
               $por_facturado=($cantidad*100)/$cant_jornadas;
			   //echo $total_descuentos."<br>";
               $por_descontado=$total_descuentos*($por_facturado/100);
			   //echo "el per glosa es: ".$dat_glosa['valor']."<br>";
               $val_facturado=$cantidad;
			   //echo $nit."___".$val_facturado."<br>";
			   $descuento=0;
               $descuento=$val_facturado-$por_descontado-$dat_glosa['valor'];
			   //echo "val descuento es: ".$descuento."<br>";

               //////////////////////////////////////////////
              //$descuento = $val_facturado-$por_descontado;
              if($dat_glosa['valor']>0){
                      $pdf->SetFont('Arial','B',7);
                      $pdf->SetY(137);$pdf->SetX(14);$pdf->Cell(18,6,"GLOSA HOSPITAL");
                      $pdf->SetY(137);$pdf->SetX(120);$pdf->Cell(18,6,number_format($dat_glosa['valor'],0),0,0,"R");
              }
			  
    				$administracion = $descuento*($dat_compe['dat_nom_gastos']/100);
    				$admi_extraordinaria=$descuento*($dat_compe['dat_admonExtra']/100);;
            		$educacion = $descuento*($dat_compe['dat_nom_educacion']/100);
				
				
              $sql = "select mc.mov_nume,mc.mov_fec_elabo from movimientos_contables mc inner join
              cuentas cu on mc.mov_cuent=cu.cue_id inner join conceptos con on con.con_id=mc.mov_concepto
              inner join nits nit on nit_id = mov_nit_tercero where mov_compro = '$nomina'
              and mov_nit_tercero like ('$nit')";
              $query_nume = mssql_query($sql);
              $dat_nume = mssql_fetch_array($query_nume);
              if($dat_nume)
                {
                      $dat_nit = $ins_nit->consul_nits($nit);
                      $datos_aso = mssql_fetch_array($dat_nit);

                      $fondos = $con_nomina->con_compensacion($nit,$nomina);
                      $dat_fondos=split("__",$fondos,3);//0=pabs,1=retiro,2=vacaciones
                      $pdf->SetY(66);$pdf->SetX(55);$mes = split(" ",$dat_factura['fac_descripcion']);
                      $impresion = $mes[7]." ".$mes[8]." ".$mes[9];
                      $pdf->Cell(21,10,$dat_factura['fac_consecutivo']);
                      $pdf->SetFont('Arial','B',7);
                      //$pdf->SetY(80);$pdf->SetX(46);$pdf->Cell(21,8,$impresion);//MES DE PAGO
                      $pdf->SetY(80);$pdf->SetX(123);$pdf->Cell(21,8,$dat_nume['mov_fec_elabo']);
                      if(!empty($dat_factura['fac_jornadas']))
                      { $pdf->SetY(80);$pdf->SetX(63); $pdf->Cell(21,8,round($val_facturado/($dat_factura['fac_val_total']/$dat_factura['fac_jornadas']),1));  }
                      //MODIFICADO DESDE DE ACA 05-09-2013//
              /////////////////////////////INICIO OBTENER CUANTOS DIAS EL MES//////////////////////////////
			  //echo "datos: ".$dat_factura['fac_mes_servicio'].":___".$dat_factura['fac_ano_servicio'];
              $res_dat_dias_mes=$ins_varios->diasMes($dat_factura['fac_mes_servicio'],$dat_factura['fac_ano_servicio']);
              /////////////////////////////FIN OBTENER CUANTOS DIAS EL MES//////////////////////////////
              $res_nom_mes=$ins_mes_contable->nomMes($dat_factura['fac_mes_servicio']);
              $pdf->SetY(90);$pdf->SetX(40);$pdf->Cell(21,8,"1 ".substr($res_nom_mes,0,8)."  - ");
              $pdf->SetY(90);$pdf->SetX(57);$pdf->Cell(21,8,$res_dat_dias_mes." ".substr($res_nom_mes,0,8));
              //DIAS DEL MES
          //$can_horas=24*$res_dat_dias_mes;
          //VALOR HORA
          //$val_hora=$dat_factura['fac_val_total']/$can_horas;
          //VALOR JORNADA
          //$valor_jornada=6*$val_hora;
          //CANTIDAD DE JORNADAS TRABAJADAS
          $can_jor_trabajadas=$val_facturado/$dat_factura['fac_val_total'];
          //$can_jor_tra_grupo=$dat_factura['fac_val_total']/$valor_jornada;
          
          			  $pdf->SetFont('Arial','B',7);$pdf->SetY(90);$pdf->SetX(165);$pdf->Cell(21,8,number_format(($can_jor_trabajadas*100),6)."%"); //Jornadas individuales
          			  $pdf->SetY(91);$pdf->SetX(100);$pdf->Cell(21,5,number_format($dat_factura['fac_val_total']));// JOrnadas grupasles
                  	  //MODIFICADO HASTA DE ACA 05-09-2013//    
                      $pdf->SetFont('Arial','B',7);$pdf->SetY(98);$pdf->SetX(67);$pdf->SetFont('Arial','B',7);$pdf->Cell(18,12,$dat_factura['cen_cos_nombre']);
                      $pdf->SetY(68);$pdf->SetX(110);$pdf->SetFont('Arial','B',7);$pdf->Cell(18,6,$datos_aso['nits_num_documento']."--".$datos_aso['nits_apellidos']." ".$datos_aso['nits_nombres']);
                      $i=0;
                      $calculo_porcentajes_descuentos=$val_facturado-$por_descontado-$dat_glosa['valor'];
					  //echo $calculo_porcentajes_descuentos."<br>";
                      //echo $fabspagado."--Facturado".$val_facturado."--Descontado".$por_descontado."<br>";
                      $pdf->SetFont('Arial','B',7);
                      $pdf->SetY(125);$pdf->SetX(14);$pdf->Cell(18,6,"CONTRIBUCION A LA AGREMIACION");
                      $pdf->SetY(125);$pdf->SetX(80);$pdf->Cell(18,6,number_format($val_facturado,0));
                      $pdf->SetY(130);$pdf->SetX(14);$pdf->MultiCell(45,4,"DESCUENTOS LEGALIZACION CONTRATO");
                      $pdf->SetY(130);$pdf->SetX(120);$pdf->Cell(18,6,number_format($por_descontado,0),0,0,"R");
                      $pdf->SetY(140);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO SOCIAL FABS"); 
                      $pdf->SetY(140);$pdf->SetX(96);$pdf->Cell(18,6,number_format($fabspagado,0),0,0,"R");
					  $total_participacion+=$fabspagado;
                      /*if($fabspagado==0)
                      { $fabspagado=1; }
                      echo $fabspagado."<br>";
                      echo "el %: ".$calculo_porcentajes_descuentos."<br>";*/
                      $pdf->SetY(140);$pdf->SetX(142);$pdf->Cell(18,6,number_format(($fabspagado*100)/$calculo_porcentajes_descuentos,0)."%",0,0,"R");
                      $pdf->SetY(140);$pdf->SetX(170);$pdf->Cell(18,6,number_format($dat_fondos[0]),0,0,"R");//TOTAL FONDO FABS
                      $pdf->SetY(144);$pdf->SetX(14);$pdf->Cell(18,6,"RETIRO FONDO SINDICAL");
                      $pdf->SetY(144);$pdf->SetX(96);$pdf->Cell(18,6,number_format($fonretsindical,0),0,0,"R");
					  $total_participacion+=$fonretsindical;
                      $pdf->SetY(144);$pdf->SetX(142);$pdf->Cell(18,6,number_format(($fonretsindical*100)/$calculo_porcentajes_descuentos,0)."%",0,0,"R"); 
                      $pdf->SetY(144);$pdf->SetX(170);$pdf->Cell(18,6,number_format($dat_fondos[1],0),0,0,"R");//TOTAL FONDO RETIRO
                      $pdf->SetY(148);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO DE VACACIONES"); 
                      $pdf->SetY(148);$pdf->SetX(96);$pdf->Cell(18,6,number_format($vacnompagada,0),0,0,"R");
					  $total_participacion+=$vacnompagada;
                      $pdf->SetY(148);$pdf->SetX(142);$pdf->Cell(18,6,number_format(($vacnompagada*100)/$calculo_porcentajes_descuentos,0)."%",0,0,"R");
                      $pdf->SetY(148);$pdf->SetX(170);$pdf->Cell(18,6,number_format($dat_fondos[2],0),0,0,"R");//TOTAL FONDO VACACIONES
                      $pdf->SetY(152);$pdf->SetX(14);$pdf->Cell(18,6,"ADMINISTRACION BASICA"); 
                      $pdf->SetY(152);$pdf->SetX(120);$pdf->Cell(18,6,number_format(($administracion),0),0,0,"R");
                      $pdf->SetY(152);$pdf->SetX(142);$pdf->Cell(18,6,number_format((($administracion)*100)/$calculo_porcentajes_descuentos,1)."%",0,0,"R");
                      //$pdf->SetY(156);$pdf->SetX(14);$pdf->Cell(18,6,"ADMINISTRACION EXTRAORDINARIA");
                      //$pdf->SetY(156);$pdf->SetX(96);$pdf->Cell(18,6,number_format(($admi_extraordinaria),0),0,0,"R");
                      //$pdf->SetY(156);$pdf->SetX(148);$pdf->Cell(18,6,number_format((($admi_extraordinaria)*100)/$calculo_porcentajes_descuentos,1)."%",0,0,"R");
                      $pdf->SetY(156);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO DE EDUCACION");
                      $pdf->SetY(156);$pdf->SetX(120);$pdf->Cell(18,6,number_format(($educacion),0),0,0,"R");
                      $pdf->SetY(156);$pdf->SetX(142);$pdf->Cell(18,6,number_format((($educacion)*100)/$calculo_porcentajes_descuentos,1)."%",0,0,"R");
                      /*$pdf->SetY(156);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO DE EDUCACION"); 
                      $pdf->SetY(158);$pdf->SetX(108);$pdf->Cell(18,6,number_format($educacion,0)); 
                      $pdf->SetY(158);$pdf->SetX(148);$pdf->Cell(18,6,"1%");*/
                      $pdf->SetY(160);$pdf->SetX(14);$pdf->Cell(18,6,"SEGURIDAD SOCIAL"); 
                      $pdf->SetY(160);$pdf->SetX(120);$pdf->Cell(18,6,number_format($segsocialnomcau,0),0,0,"R"); 
                      $pdf->SetY(164);$pdf->SetX(14);$pdf->Cell(18,6,"RETENCION EN LA FUENTE"); 
                      $pdf->SetY(164);$pdf->SetX(120);$pdf->Cell(18,6,number_format($retefuente,0),0,0,"R"); 
                      $des_sql = "SELECT * FROM des_anestecoop WHERE nit_id = $nit AND des_nomina = '$nomina'";
                      $des_query = mssql_query($des_sql);
                      $total = 0;
                      $entra = 0;
                      $j=17;
                      if(mssql_num_rows($des_query)>0)
                      {
                              while($row=mssql_fetch_array($des_query))
                              {
                                      $pdf->SetY(146+(($j-8)*4));$pdf->SetX(14);$pdf->Cell(18,6,$row['des_ane_descripcion']);
                                      $pdf->SetY(146+(($j-8)*4));$pdf->SetX(96);$pdf->Cell(18,6,number_format($row['des_ane_dinero']),0,0,"R");
                                      $total = $total+$row['des_ane_dinero'];
                                      $entra++;
                                      $j++;
                              }
					  }
					  
					  
					$des_factura = mssql_query($dat_descuentos);
        			if(mssql_num_rows($des_factura)>0)
        			{
        				while($row=mssql_fetch_array($des_factura))
            			{
            				$pdf->SetY(172);//renglonesss
							$pdf->SetX(14);//filas ->
							$cuen = $cuenta->getnomCuenta($row['des_nom_cuenta']);
							$pdf->Cell(18,6,substr($cuen,0,30));
							$pdf->SetY(172);//renglonesss
							$pdf->SetX(120);//filas ->
							$pdf->Cell(18,6,number_format($row['des_nom_valor']),0,0,"R");
							$total=$total+$row['des_ane_dinero'];
                			$entra++;
                			$j++;
						}
        			}
					  
					  
                      //$recibo_caja="SELECT trans.trans_fac_num recibo FROM transacciones tra INNER JOIN transacciones trans ON tra.tran_tran_id = trans.trans_id WHERE tra.trans_sigla='$nomina'";
                      //echo $recibo_caja;
                      //$query_recibo = mssql_query($recibo_caja);
                      //$dat_recibo = mssql_fetch_array($query_recibo);
                      
					  ///////////////////INICIO CREDITOS///////////////////	
                      $que_des_creditos="SELECT mc.mov_valor AS credito_dinero,mc.mov_nume,con.con_id,mc.mov_nit_tercero,c.cre_id,cue_nombre,mc.id_mov,c.cre_observacion
                                         FROM movimientos_contables mc INNER JOIN creditos c ON mc.mov_documento='C-'+CAST(c.cre_id AS VARCHAR)
                                         INNER JOIN conceptos con ON c.cue_id=con.con_id
                                         INNER JOIN cuentas cu ON mc.mov_cuent=cu.cue_id
                                         WHERE mov_compro='$nomina' AND mov_nit_tercero='$nit' ORDER BY id_mov ASC";
                                          //echo $que_des_creditos;
                      //echo $que_des_creditos;
                      $eje_des_creditos=mssql_query($que_des_creditos);
                      $temp=0;
                      if(mssql_num_rows($eje_des_creditos)>0)
                      {
                              $par=0;
                              while($row=mssql_fetch_array($eje_des_creditos))
                              {    
									if($row['credito_dinero']>0)                                  
									{
                                      $pdf->SetFont('Arial','B',7);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(14);//filas ->
                                      //$concepto=$ins_concepto->getcon_nombre($row['con_id']);
                                      $pdf->Cell(18,6,$row['cue_nombre']);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(120);//filas ->
                                      $pdf->Cell(18,6,number_format($row['credito_dinero']),0,0,"R");
                                      $pdf->SetFont('Arial','B',6);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(75);//filas ->
                                      $pdf->Cell(18,6,"CREDITO: ".$row['cre_id']);
                                      /*$pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(175);//filas ->
                                      if($temp==$row['cre_id'])
									  {
                                      	$pdf->SetFont('Arial','B',7);
                                      	$pdf->Cell(18,6,number_format($ins_credito->saldo_credito($row['cre_id'])),0,0,"R");
									  }
                                      $temp=$row['cre_id'];
									  */
                                      $pdf->SetFont('Arial','B',6);
                                      if($row['con_id']==302)
                                      {
                                        $pdf->SetY(152+(($j-8)*4));//renglonesss
                                        $pdf->SetX(146);//filas ->
                                        $pdf->Cell(18,6,"DESC: ".$row['cre_observacion']);
                                      }


                                      $total=$total+$row['credito_dinero'];
                                      $entra++;
                                      $j++;
									}
                              }
                      }
                      ///////////////////FIN CREDITOS///////////////////	
                }
              }
              elseif($estado==3)
              {
              		  $total_participacion=0;
				      $pdf->SetY(91);$pdf->SetX(100);$pdf->Cell(21,5,number_format($dat_factura['fac_val_total']));
				      /////////////////////////////INICIO OBTENER CUANTOS DIAS EL MES//////////////////////////////
					  //echo "datos: ".$dat_factura['fac_mes_servicio']."___".$dat_factura['fac_ano_servicio'];
              		  $res_dat_dias_mes=$ins_varios->diasMes($dat_factura['fac_mes_servicio'],$dat_factura['fac_ano_servicio']);
				      /////////////////////////////FIN OBTENER CUANTOS DIAS EL MES//////////////////////////////
				      $res_nom_mes=$ins_mes_contable->nomMes($dat_factura['fac_mes_servicio']);
				      $pdf->SetY(90);$pdf->SetX(40);$pdf->Cell(21,8,"1 ".substr($res_nom_mes,0,8)."  - ");
				  	  $pdf->SetY(90);$pdf->SetX(57);$pdf->Cell(21,8,$res_dat_dias_mes." ".substr($res_nom_mes,0,8));
				  
                      $factura = "SELECT rec_caj_monto FROM recibo_caja WHERE rec_caj_id=".$elrecibo;
                      $dat_fac = mssql_query($factura);
                      $datos_fac = mssql_fetch_array($dat_fac);
                      $dat_nit = $ins_nit->consul_nits($nit);
                      $datos_aso = mssql_fetch_array($dat_nit);
                      $fac_id = $conse_nom;
                      $fac_valor = $datos_fac['rec_caj_monto'];//ESTA
                      $cen_cos = $el_centro;

                      $sum_descuentos="SELECT SUM(des_monto) descuentos FROM descuentos d INNER JOIN
                      recibo_caja rc ON d.des_factura=rc.rec_caj_id
                      INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
                      WHERE f.fac_id=".$valor." AND (des_tipo not in(1,2) OR des_distribucion IS NOT NULL) AND rc.rec_caj_id=$elrecibo";

                 $rec_caj_des = mssql_query($sum_descuentos);
                 $dat_rec_caja = mssql_fetch_array($rec_caj_des);
                 $total_descuentos=$dat_rec_caja['descuentos']; 

                 if($posicion==0)
                 {
                      $cant_jornadas=$reporte->canJorFac($valor);

                      $jor_asociado="select rep_jor_num_jornadas from reporte_jornadas rj inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen where npcc.nit_id = ".$nit." and cen_cos_id = $cen_cos and rep_jor_num_factura=$valor";
                 }
                 else
                 {
                      $que_3="SELECT SUM(rep_jor_con_rec_numero) rep_jornadas FROM rep_jor_con_recibo WHERE fac_id=".$valor." AND rec_caj_consecutivo=".$conse_recibo;
                      $eje_3=mssql_query($que_3);
                      $res_4=mssql_fetch_array($eje_3);
                      $cant_jornadas=$res_4['rep_jornadas'];

                      $jor_asociado="select distinct rep_jor_con_rec_numero as rep_jor_num_jornadas from rep_jor_con_recibo rjcr inner join nits_por_cen_costo npcc on npcc.nit_id = rjcr.nit_id where npcc.nit_id=".$nit." and npcc.cen_cos_id=".$cen_cos." and fac_id=".$valor." and rec_caj_consecutivo=".$conse_recibo;
                      //echo $jor_asociado;
                 }
                  	  $val_jornada = $fac_valor/$cant_jornadas;

                      $jor_aso = mssql_query($jor_asociado);
                      $can_jornadas=mssql_fetch_array($jor_aso);
                      $cantidad = $can_jornadas['rep_jor_num_jornadas'];
					  
                      $val_facturado = $cantidad;

                      $por_facturado = ($cantidad*100)/$cant_jornadas;

                      $por_descontado=$total_descuentos*($por_facturado/100);

                      $descuento = $val_facturado-$por_descontado;
					  
					  $pdf->SetFont('Arial','B',7);$pdf->SetY(90);$pdf->SetX(165);$pdf->Cell(21,8,number_format(($val_facturado*100/$dat_factura['fac_val_total']),2)."%");
					  
					if($dat_glosa['valor']>0){
                    	$pdf->SetFont('Arial','B',7);
                      	$pdf->SetY(137);$pdf->SetX(14);$pdf->Cell(18,6,"GLOSA HOSPITAL");
                      	$pdf->SetY(137);$pdf->SetX(120);$pdf->Cell(18,6,number_format($dat_glosa['valor'],0),0,0,"R");
              		}
					  
					  
					  
					  ///////////////////INICIO CREDITOS///////////////////	
                      $que_des_creditos="SELECT mc.mov_valor AS credito_dinero,mc.mov_nume,con.con_id,mc.mov_nit_tercero,c.cre_id,cue_nombre,mc.id_mov,c.cre_observacion
                                         FROM movimientos_contables mc INNER JOIN creditos c ON mc.mov_documento='C-'+CAST(c.cre_id AS VARCHAR)
                                         INNER JOIN conceptos con ON c.cue_id=con.con_id
                                         INNER JOIN cuentas cu ON mc.mov_cuent=cu.cue_id
                                         WHERE mov_compro='$nomina' AND mov_nit_tercero='$nit' ORDER BY id_mov ASC";
                                          //echo $que_des_creditos;
                      //echo $que_des_creditos;
                      $eje_des_creditos=mssql_query($que_des_creditos);
                      $temp=0;
                      if(mssql_num_rows($eje_des_creditos)>0)
                      {
                              $par=0;
                              while($row=mssql_fetch_array($eje_des_creditos))
                              {    
									if($row['credito_dinero']>0)                                  
									{
                                      $pdf->SetFont('Arial','B',7);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(14);//filas ->
                                      //$concepto=$ins_concepto->getcon_nombre($row['con_id']);
                                      $pdf->Cell(18,6,$row['cue_nombre']);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(107);//filas ->
                                      $pdf->Cell(18,6,number_format($row['credito_dinero']),0,0,"R");
                                      
                                      $pdf->SetFont('Arial','B',6);
                                      $pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(66);//filas ->
                                      $pdf->Cell(18,6,"CREDITO: ".$row['cre_id']);
                                      /*$pdf->SetY(152+(($j-8)*4));//renglonesss
                                      $pdf->SetX(175);//filas ->
                                      if($temp==$row['cre_id'])
									  {
                                      	$pdf->SetFont('Arial','B',7);
                                      	$pdf->Cell(18,6,number_format($ins_credito->saldo_credito($row['cre_id'])),0,0,"R");
									  }
                                      $temp=$row['cre_id'];
									  */
                                      $pdf->SetFont('Arial','B',6);
                                      if($row['con_id']==302)
                                      {
                                        $pdf->SetY(152+(($j-8)*4));//renglonesss
                                        $pdf->SetX(142);//filas ->
                                        $pdf->Cell(18,6,"DESC: ".$row['cre_observacion']);
                                      }


                                      $total=$total+$row['credito_dinero'];
                                      $entra++;
                                      $j++;
									}
                              }
                      }
                      ///////////////////FIN CREDITOS///////////////////		
					  
					$calculo_porcentajes_descuentos_no_afiliados=$val_facturado-$por_descontado-$dat_glosa['valor'];
					$administracion=$calculo_porcentajes_descuentos_no_afiliados*($dat_compe['dat_admonNoAfi']/100);
					
					//$admi_extraordinaria=$calculo_porcentajes_descuentos_no_afiliados*($dat_compe['dat_admonNoAfiExtraordinaria']/100);
					if($dat_compe['dat_admonAdministacion']==0)
						$educacion=0;
					else
						$educacion=$calculo_porcentajes_descuentos_no_afiliados*($dat_compe['dat_admonAdministacion']/100);
					  
                      $retefuente=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23651501');
                      $fondos = $con_nomina->con_compensacion($nit,$nomina);
                      $dat_fondos=split("__",$fondos,3);//0=pabs,1=retiro,2=vacaciones
                      $pdf->SetY(66);$pdf->SetX(55);$mes = split(" ",$dat_factura['fac_descripcion']);
                      $impresion = $mes[7]." ".$mes[8]." ".$mes[9];
                      $pdf->Cell(21,8,$dat_factura['fac_consecutivo']);
                      $pdf->SetFont('Arial','I',7);
                      $pdf->SetY(80);$pdf->SetX(46);$pdf->Cell(21,8,$impresion);
                      $pdf->SetY(80);$pdf->SetX(120);$pdf->Cell(21,8,$dat_nume['mov_fec_elabo']);
                      $pdf->SetFont('Arial','B',7);$pdf->SetY(97);$pdf->SetX(67);$pdf->SetFont('Arial','B',7);$pdf->Cell(18,14,$dat_factura['cen_cos_nombre']);
                      $pdf->SetY(68);$pdf->SetX(110);$pdf->SetFont('Arial','B',7);$pdf->Cell(18,6,$datos_aso['nits_num_documento']."--".$datos_aso['nits_nombres']." ".$datos_aso['nits_apellidos']);
                      
					  //echo "la base es: ".$calculo_porcentajes_descuentos_no_afiliados."<br>";
                      $pdf->SetY(125);$pdf->SetX(14);$pdf->Cell(18,6,"CONTRIBUCION A LA AGREMIACION"); 
                      $pdf->SetY(125);$pdf->SetX(75);$pdf->Cell(18,6,number_format($val_facturado,0),0,0,"R"); 
                      $pdf->SetY(130);$pdf->SetX(14);$pdf->Cell(18,6,"DESCUENTOS LEGALIZACION CONTRATO");		
                      $pdf->SetY(130);$pdf->SetX(120);$pdf->Cell(18,6,number_format($por_descontado,0),0,0,"R");
                      $pdf->SetY(145);$pdf->SetX(14);$pdf->Cell(18,6,"ADMINISTRACION BASICA");
                      $pdf->SetY(145);$pdf->SetX(120);$pdf->Cell(18,6,number_format(($administracion),0),0,0,"R"); 
					  
					  //echo "la admin es: ".$administracion."___".$educacion."<br>";
					  
                      $pdf->SetY(145);$pdf->SetX(142);$pdf->Cell(18,6,number_format((($administracion)*100)/$calculo_porcentajes_descuentos_no_afiliados,1)."%",0,0,"R");
                      $pdf->SetY(155);$pdf->SetX(14);$pdf->Cell(18,6,"EDUCACION");
                      $pdf->SetY(155);$pdf->SetX(120);$pdf->Cell(18,6,number_format(($educacion),0),0,0,"R"); 
                      $pdf->SetY(155);$pdf->SetX(142);$pdf->Cell(18,6,number_format((($educacion)*100)/$calculo_porcentajes_descuentos_no_afiliados,1)."%",0,0,"R");
                      /*$pdf->SetY(155);$pdf->SetX(14);$pdf->Cell(18,6,"EDUCACION"); 
                      $pdf->SetY(155);$pdf->SetX(108);$pdf->Cell(18,6,number_format($educacion,0)); */
                      $pdf->SetY(166);$pdf->SetX(14);$pdf->Cell(18,6,"RETENCION EN LA FUENTE");
                      $pdf->SetY(166);$pdf->SetX(120);$pdf->Cell(18,6,number_format($retefuente,0),0,0,"R");
                      //echo $retefuente+$administracion+$educacion."<br>";
                      $pdf->SetY(166);$pdf->SetX(142);$pdf->Cell(18,6,number_format(($retefuente*100)/($calculo_porcentajes_descuentos_no_afiliados-$administracion-$educacion-$admi_extraordinaria),1)."%",0,0,"R");
                      $total=$compenasociado-$administracion-$admi_extraordinaria-$educacion;
               }
               $pdf->SetFont('Arial','B',7);
               //if($compenasociado>0)
               //{
               $pdf->SetY(257);$pdf->SetX(102);$pdf->Cell(18,6,number_format(($total_participacion),0));
               $pdf->SetY(257);$pdf->SetX(153);$pdf->Cell(18,6,number_format(($compenasociado),0));
                //EL PORCENTAJE DE RETENCI�N QUE SE LE DESCONT� AL DR.
                /*$pdf->SetFont('Arial','B',7);
                $suma_por_retencion=$compenasociado+$segsocialnomcau+$retefuente;
                $por_retencion=($retefuente*100)/$suma_por_retencion;
                $pdf->SetY(174);$pdf->SetX(14);$pdf->Cell(18,6,"BASE DE RETENCI�N");
                $pdf->SetY(178);$pdf->SetX(14);$pdf->Cell(18,6,"($ ".number_format($suma_por_retencion,0).")");
                $pdf->SetY(182);$pdf->SetX(14);$pdf->Cell(18,6,"PORCENTAJE DE RETENCI�N");
                $pdf->SetY(182);$pdf->SetX(148);$pdf->Cell(18,6,number_format($por_retencion,5)."%",0,0,"R");*/
               //}
               $contador++;
}
$pdf->Output();
?>
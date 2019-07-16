<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');

require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$varios=new varios();
$ano = $_POST["ano"];
$mes = $_POST["mes"];
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];
$fec_ini = "01-".$mes."-".$ano;
$fec_fin = "30-".$mes."-".$ano;

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/proveedores_por_vencimiento_mes.jpg",0,0,300,'C');
//Arial bold 15
$this->SetFont('Arial','B',15);
$this->SetFont('Arial','B',15);
//Movernos a la derecha
//$this->Cell(10);
$this->Ln(10);
}

//Pie de p�gina 
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
//Tipo y tama�o de lertra 
$pdf->AddPage('L');
$pdf->SetFont('Arial','B',10);
$pdf->SetY(26);$pdf->SetX(225);$pdf->Cell(21,8,date('d-m-Y'));
$i=1;$p=0;
$sql = "EXECUTE carteraHospitales '$doc_ini','$doc_fin','$fec_ini','$fec_fin'";
$query = mssql_query($sql);
$pdf->SetFont('Arial','B',6);
$fec_dia = date('d-m-Y');
$antes=0;$treinta=0;$sesenta=0;$noventa=0;$cientochenta=0;$mayor=0;
if($query)
{
	//$sql_rep = "SELECT * FROM reportes";
	$sql_rep='SELECT uno,dos,tres,cinco,seis,siete,ocho,nueve,once,doce,trece,catorce,quince,diesiseis,diesisiete,diesiocho,
	diesinueve,veinte,vientiuno,veintidos,
	SUM(ISNULL(CAST(cuatro AS FLOAT),0))+SUM(ISNULL(CAST(diesisiete AS FLOAT),0)) cuatro,
	SUM(ISNULL(CAST(diez AS FLOAT),0))+SUM(ISNULL(CAST(diesiocho AS FLOAT),0)) diez,
	cuatro-SUM(ISNULL(CAST(diez AS FLOAT),0))+SUM(ISNULL(CAST(diesiocho AS FLOAT),0)) saldo
	FROM reportes
	WHERE cuatro>0
	GROUP BY uno,dos,tres,cinco,seis,siete,ocho,nueve,once,doce,trece,catorce,quince,diesiseis,diesisiete,diesiocho,
	diesinueve,veinte,vientiuno,veintidos,cuatro
	ORDER BY siete,CAST(dos AS DATETIME),cinco';
	//echo "<br>".$sql_rep;
	$query_rep = mssql_query($sql_rep);
	$num_rows = mssql_num_rows($query_rep);
	
	$dias_factura=0;
	$total_valor_factura=0;
	$total_abonos_factura=0;
	while($row=mssql_fetch_array($query_rep))
	{
	  if($row['saldo']>0)
	  {
	  $total_valor_factura=$row['cuatro'];
	  $total_abonos_factura=$row['diez'];
	  
	  $resta=$total_valor_factura-$total_abonos_factura;
	  
	  if($resta>0)
	  {
	  if($p==0)
	  {
	  	$temp = $row['uno'];
		/*Nit Hospital*/$pdf->SetY(40+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['ocho']);
		/*Nombre Hospital*/$pdf->SetY(40+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,$row['siete']);
		$i++;
		$pdf->SetFont('Arial','',6);
		if($row['dos']!="")
		  $dos = $row['dos'];
		else
		  $dos = $row['dos'];
		$dat_fecha = explode("-",$row['dos'],3);
		/*Numero y mes de factura*/$pdf->SetY(40+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['cinco']."        ".$row['doce']);
		/*Fecha Factura*/$pdf->SetY(40+($i*3));$pdf->SetX(27);$pdf->Cell(21,8,$dos);
		/*Fecha de Vencimiento*/$pdf->SetY(40+($i*3));$pdf->SetX(43);$pdf->Cell(21,8,$varios->suma_fechas_dias($row['dos'],$row['seis']));
		/*Valor Factura*/$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format((float)($row['cuatro']),0));
		if($row['diez']>0)
		   $abono=$row['diez'];
		else
		   $abono=0;
		$tot_abonos+=$abono;
		$tot_factura+=$row['cuatro'];
		/*Abono*/$pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format((float)$abono,0));
		/*Saldo*/$pdf->SetY(40+($i*3));$pdf->SetX(280);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
        
        if($row['seis']==0||$row['seis']=="")
            $dias_factura=30;
        else
            $dias_factura=$row['seis'];
        
		$pdf->SetY(40+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,$dias_factura);
		$dias = ($varios->restaFechas($row['dos'],$fec_dia))-$dias_factura;
		$pdf->SetY(40+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,$dias);
        
        /*Dias cartera*/$pdf->SetY(40+($i*3));$pdf->SetX(130);$pdf->Cell(21,8,$dias_factura+$dias);
                //echo "<br> los dias son: ".$dias;
        //$dias=192;
		if($dias<=29)
		{
			$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
			$antes+=($row['cuatro']-$abono);
		}
		elseif($dias>=30 && $dias<=60)
		{
			$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
			$sesenta+=($row['cuatro']-$abono);
		}
		elseif($dias>=61 && $dias<=90)
		{
			$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
			$noventa+=($row['cuatro']-$abono);
		}
		elseif($dias>=91 && $dias<=180)
		{
			$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
			$cientochenta+=($row['cuatro']-$abono);
		}
		
		elseif($dias>180)
		{
			$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
			$mayor+=($row['cuatro']-$abono);
		}
		$i++;
		$p++;
	  }

	  //ES POR AQUI
	  else
	  {	
		if($temp==$row['uno'])
		 {
			if($row['dos']!="")
		  	   $dos = $row['dos'];
			else
			  $dos = $row['dos'];
                        
                        
            $cambiar_caracter=ereg_replace("/","-",$row['dos']);
                        //echo "Los datos: ".$cambiar_caracter;
			$dat_fecha = explode("-",$cambiar_caracter,3);
                        //echo $dat_fecha[1]."<br>";
			$nom_mes = $mes_contable->nomMes(ereg_replace("0","",$dat_fecha[1]));
			$pdf->SetY(40+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['cinco']."        ".$row['doce']);
			$pdf->SetY(40+($i*3));$pdf->SetX(27);$pdf->Cell(21,8,$dos);
			$pdf->SetY(40+($i*3));$pdf->SetX(43);$pdf->Cell(21,8,$varios->suma_fechas_dias($row['dos'],$row['seis']));
			$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format((float)($row['cuatro']),0));
			if($row['diez']>0)
		   	   $abono=$row['diez'];
			else
		   	   $abono=0;
			$tot_abonos+=$abono;
			$tot_factura+=$row['cuatro'];
			$pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format((float)$abono,0));
			
            /*Saldo*/$pdf->SetY(40+($i*3));$pdf->SetX(280);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
            
            if($row['seis']==0||$row['seis']=="")
                $dias_factura=30;
            else
                $dias_factura=$row['seis'];
            
            
			$pdf->SetY(40+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,$dias_factura);
            $dias = ($varios->restaFechas($row['dos'],$fec_dia))-$dias_factura;
            $pdf->SetY(40+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,$dias);
        
            /*Dias cartera*/$pdf->SetY(40+($i*3));$pdf->SetX(130);$pdf->Cell(21,8,$dias_factura+$dias);
                        //echo "los dias: ".$dias."<br>";
			if($dias<=29)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$antes+=($row['cuatro']-$abono);
			}
			elseif($dias>=30 && $dias<=60)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$sesenta+=($row['cuatro']-$abono);
			}
			elseif($dias>=61 && $dias<=90)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$noventa+=($row['cuatro']-$abono);
			}
			elseif($dias>=91 && $dias<=180)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$cientochenta+=($row['cuatro']-$abono);
			}
			
			elseif($dias>180)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$mayor+=($row['cuatro']-$abono);
			}
			
			$i++;
			if($i==$num_rows+2)
			{
				$pdf->SetFont('Arial','B',6);
				$pdf->SetY(40+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,"_____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________");
				$i++;
				$pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format($tot_abonos));
				$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format($tot_factura));
				$pdf->SetY(40+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($tot_factura-$tot_abonos));
				$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($antes));
				$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format($sesenta));
				$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format($noventa));
				$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format($cientochenta));
				$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format($mayor));
				$total_cartera=$antes+$treinta+$sesenta+$noventa+$cientochenta+$mayor;
				$totcargeneral=$totcargeneral+$total_cartera;
				$pdf->SetFont('Arial','B',8);
				$pdf->SetY(40+($i*3));$pdf->SetX(275);$pdf->Cell(21,8,number_format($total_cartera));
				$antes=0;$treinta=0;$sesenta=0;$noventa=0;$cientochenta=0;$mayor=0;$tot_abonos=0;$tot_factura=0;$tot_saldo=0;
				$i++;
			}
		 }
		else
		{
			$pdf->SetFont('Arial','B',6);
			$pdf->SetY(40+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,"_____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________");
			$i++;
		    $pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format($tot_abonos));
			$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format($tot_factura));
			$pdf->SetY(40+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($tot_factura-$tot_abonos));
			$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($antes));
			$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format($sesenta));
			$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format($noventa));
			$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format($cientochenta));
			$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format($mayor));
			$total_cartera = $antes+$treinta+$sesenta+$noventa+$cientochenta+$mayor;
			$totcargeneral=$totcargeneral+$total_cartera;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetY(40+($i*3));$pdf->SetX(275);$pdf->Cell(21,8,number_format($total_cartera));
                        
            //IMPRIMIR CARTERA COOMEVA
			$i=1;
			$pdf->AddPage('L');
			$pdf->SetY(26);$pdf->SetX(222);$pdf->Cell(21,8,date('d-m-Y'));
                        
                        
			$antes=0;$treinta=0;$sesenta=0;$noventa=0;$cientochenta=0;$mayor=0;$tot_abonos=0;$tot_factura=0;$tot_saldo=0;
			$temp = $row['uno'];
			$i+=2;
			$pdf->SetFont('Arial','B',6);
			$pdf->SetY(40+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['ocho']);
			$pdf->SetY(40+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,$row['siete']);
			$i++;
			$pdf->SetFont('Arial','',6);
			if($row['dos']!="")
		  	   $dos = $row['dos'];
			else
			  $dos = $row['dos'];
			
                        $cambiar_caracter=ereg_replace("/","-",$row['dos']);
                        //echo "Los datos: ".$cambiar_caracter;
			$dat_fecha = explode("-",$cambiar_caracter,3);
                        //echo $dat_fecha[1]."<br>";
                        
			$nom_mes = $mes_contable->nomMes(ereg_replace("0","",$dat_fecha[1]));
			$pdf->SetY(40+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['cinco']."        ".$row['doce']);
			$pdf->SetY(40+($i*3));$pdf->SetX(27);$pdf->Cell(21,8,$dos);
			$pdf->SetY(40+($i*3));$pdf->SetX(43);$pdf->Cell(21,8,$varios->suma_fechas_dias($row['dos'],$row['seis']));
			$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format((float)($row['cuatro']),0));
			if($row['diez']>0)
		   	   $abono=$row['diez'];
			else
		   	   $abono=0;
			$tot_abonos += $abono;
			$tot_factura+=$row['cuatro'];
			$pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format((float)$abono,0));
			/*Saldo*/$pdf->SetY(40+($i*3));$pdf->SetX(280);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
            
            if($row['seis']==0||$row['seis']=="")
                $dias_factura=30;
            else
                $dias_factura=$row['seis'];
            
			
            $pdf->SetY(40+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,$dias_factura);
            $dias = ($varios->restaFechas($row['dos'],$fec_dia))-$dias_factura;
            $pdf->SetY(40+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,$dias);
        
            /*Dias cartera*/$pdf->SetY(40+($i*3));$pdf->SetX(130);$pdf->Cell(21,8,$dias_factura+$dias);
            
			if($dias<=29)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$antes+=($row['cuatro']-$abono);
			}
			elseif($dias>=30 && $dias<=60)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$sesenta+=($row['cuatro']-$abono);
			}
			elseif($dias>=61 && $dias<=90)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$noventa+=($row['cuatro']-$abono);
			}
			elseif($dias>=91 && $dias<=180)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$cientochenta+=($row['cuatro']-$abono);
			}
			
			elseif($dias>180)
			{
				$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format((float)($row['cuatro']-$abono),0));
				$mayor+=($row['cuatro']-$abono);
			}
			$i++;
		}
	  }
	  //echo "el centro es: ".$elcentro[$cont]."___".$row['siete']."<br>";
	  if($i>40)
	  {
		$i=1;
		$pdf->AddPage('L');
	  }
	}

}//CIERRO EL IF QUE PREGUNTA SI EL SALDO ES > 0

}
	$pdf->SetFont('Arial','B',6);
	$pdf->SetY(40+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,"_____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________");
	$i++;
	$pdf->SetY(40+($i*3));$pdf->SetX(87);$pdf->Cell(21,8,number_format($tot_abonos));
	$pdf->SetY(40+($i*3));$pdf->SetX(60);$pdf->Cell(21,8,number_format($tot_factura));
	$pdf->SetY(40+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format($tot_factura-$tot_abonos));
	$pdf->SetY(40+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($antes));
	$pdf->SetY(40+($i*3));$pdf->SetX(170);$pdf->Cell(21,8,number_format($sesenta));
	$pdf->SetY(40+($i*3));$pdf->SetX(195);$pdf->Cell(21,8,number_format($noventa));
	$pdf->SetY(40+($i*3));$pdf->SetX(223);$pdf->Cell(21,8,number_format($cientochenta));
	$pdf->SetY(40+($i*3));$pdf->SetX(250);$pdf->Cell(21,8,number_format($mayor));
	$total_cartera = $antes+$treinta+$sesenta+$noventa+$cientochenta+$mayor;
	$totcargeneral=$totcargeneral+$total_cartera;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(40+($i*3));$pdf->SetX(275);$pdf->Cell(21,8,number_format($total_cartera));
	$antes=0;$treinta=0;$sesenta=0;$noventa=0;$cientochenta=0;$mayor=0;$tot_abonos=0;$tot_factura=0;$tot_saldo=0;
	$i+=10;
}
$pdf->SetFont('Arial','B',8);
$pdf->SetY(40+($i*3));$pdf->SetX(243);$pdf->Cell(21,8,"Total cartera general:    ".number_format($totcargeneral));
$pdf->Output();
?>
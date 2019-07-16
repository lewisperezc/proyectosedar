<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/saldos.class.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$mes = $_GET['mes_sele'];
$ano = $_GET['ano_sele'];
$mes_contable = new mes_contable();
$varios = new varios();
$saldos = new saldos();
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/bal_prueba.jpg",0,0,200,'C');
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
$this->Cell(0,3,'Fecha de impresion '.date('d-m-Y h:i:s A'),0,0,'C');
$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C'); 
}
}// fin de la clase 


$pdf=new PDF(); 
//Tipo y tama�o de lertra 
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
$dato = $mes_contable->nomMes($mes)."/".$ano;
$pdf->SetY(4);$pdf->SetX(160);$pdf->Cell(21,8,$dato);
$pdf->SetFont('Arial','B',10);
$cue_inicial=$_GET['cue_ini'];
$cue_final=$_GET['cue_fin'];
$pdf->SetY(50);$pdf->SetX(100);$pdf->Cell(21,8,"A ".$varios->diasMes($mes,$ano)." de ".$mes_contable->nomMes($mes)." del ".$ano);
$sql = "EXECUTE bal_prueba $mes,$ano";
//echo $sql; 
$query = mssql_query($sql);
$query1 = mssql_query($sql);

if($mes==1)
   $columna = 'a'.($ano-1).'a13';
else
   $columna = 'a'.($ano).'a'.($mes-1);
$pdf->SetFont('Arial','',7);
$cuen = "";$val = "";
$cuen_orga = "";$val_orga = "";
$temp = 0;$debito=0;
$credito=0;$saldo_inicial=0;
/*$querySalCuenta = "UPDATE cuentas SET ".$columna."=1 WHERE LEN(cue_id)<7";
$fetQuery = mssql_query($querySalCuenta);*/
if($query1)
{
	//echo "entra al if";
 $balance = "SELECT * FROM reportes WHERE uno BETWEEN '$cue_inicial' AND '$cue_final' AND LEN(uno)<9 ORDER BY uno asc";
 //echo "el primero: ".$balance."<br>";
 $que_balance = mssql_query($balance);
 while($row=mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
	  	 $debito+=$row['tres'];
	  	 $credito+=$row['cinco'];
		 $saldo=$saldos->salInicial($mes,$ano,$row['uno']);
		 $cont=strlen($row['uno']);
		 if($cont==6)
		 {
		 	$saldo_inicial=$saldo;
		 	$deb_final+=$row['tres'];
		 	$cre_final+=$row['cinco'];
		 }
		 while($cont>0)
		  {
		  	$cuen[$temp]=substr($row['uno'],0,$cont);
			$val[$temp]=$saldo;
			$cont-=1;
			$temp++;
		  }
	  }
	}

  $k=0;
  for($l=0;$l<sizeof($cuen);$l++)
	{
	 if($l==0)
	 {
		$cuen_orga[$k] = $cuen[$l];
		$val_orga[$k] = $val[$l];
		$k++;
	 }
	else
	 {
		$temp=0;
		for($p=0;$p<=$k;$p++)
		{
			if($cuen_orga[$p]==$cuen[$l])
			 { 
			  $val_orga[$p] += $val[$l];
			  $temp=1;
			  break;
			 }
		}
		if($temp==0)
		{
	 	 $cuen_orga[$k] = $cuen[$l];
		 $val_orga[$k] = $val[$l];
		 $k++;
		}
	 }
    }
}
if($query)
{
	$balance = "SELECT * FROM reportes WHERE uno BETWEEN '$cue_inicial' AND '$cue_final' AND LEN(uno)<9 ORDER BY uno asc";
	$que_balance = mssql_query($balance);
	$i=1;
	$j=1;
	$k=0;
	$temp=0;
	while($row=mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
		  for($p=0;$p<sizeof($cuen_orga);$p++)
		  {
			if($row['uno']==$cuen_orga[$p])
			 {
				$saldo = $val_orga[$p];
				break;
			 }
		  }
		  $saldo=$saldos->salInicial($mes,$ano,$row['uno']);
		  $pdf->SetY(65+($i*3));$pdf->SetX(75);$pdf->Cell(21,8,number_format($saldo,2),0,0,"R");//SALDOS INICIALES
		  $pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
		  $pdf->SetY(65+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,substr($row['dos'],0,25));
		  $pdf->SetY(65+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format($row['tres'],2),0,0,"R");
		  $pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($row['cinco'],2),0,0,"R");		  
		  if(substr($row['uno'],0,1)==1 || substr($row['uno'],0,1)==5 || substr($row['uno'],0,1)==6)
			{$pdf->SetY(65+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format($saldo+($row['tres']-$row['cinco']),2),0,0,"R");}
		  else
			{$pdf->SetY(65+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format($saldo+($row['cinco']-$row['tres']),2),0,0,"R");}
		  $i++;
		  if($i%67==0)
			{
				$pdf->AddPage();
				$dato = $mes_contable->nomMes($mes)."/".$ano;
				$pdf->SetY(4);$pdf->SetX(160);$pdf->Cell(21,8,$dato);
				$pdf->SetFont('Arial','B',11);
				$pdf->SetY(50);$pdf->SetX(100);$pdf->Cell(21,8,"A ".$varios->diasMes($mes,$ano)." de ".$mes_contable->nomMes($mes)." del ".$ano);
				$pdf->SetFont('Arial','',7);
				$i=1;
			}
	  }
    }
}

if($i%67>0)
{
	$pdf->AddPage();
	$i=1;
}	


$pdf->SetFont('Arial','B',9);
$pdf->SetY(65+($i*4));$pdf->SetX(40);$pdf->Cell(21,8,"SUMAS TOTALES",0,0,"R");
$pdf->SetFont('Arial','B',8);
$pdf->SetY(65+($i*4));$pdf->SetX(75);$pdf->Cell(21,8,number_format($saldo_inicial,2),0,0,"R");
$pdf->SetY(65+($i*4));$pdf->SetX(110);$pdf->Cell(21,8,number_format($deb_final,2),0,0,"R");
$pdf->SetY(65+($i*4));$pdf->SetX(145);$pdf->Cell(21,8,number_format($cre_final,2),0,0,"R");	
$pdf->SetY(65+($i*4));$pdf->SetX(175);$pdf->Cell(21,8,number_format(($deb_final-$cre_final),2),0,0,"R");	
$pdf->Output();
?>
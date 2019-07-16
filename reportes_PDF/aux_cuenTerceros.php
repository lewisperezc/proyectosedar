<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/saldos.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$ins_saldos=new saldos();
$ano = $_SESSION['elaniocontable'];
$mes = $_POST["mes"];
$cue_ini= $_POST["cue_ini"];
$cue_fin =$_POST["cue_fin"];
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/auxliar_cuentas_por_tercero.jpg",0,0,200,'C');
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
$pdf->AddPage();
$pdf->SetFont('Arial','',6);


$sql = "EXECUTE aux_terceros '$cue_ini','$cue_fin','$doc_ini','$doc_fin',$mes,$ano";
$query = mssql_query($sql);
if($query)
{
	$balance = "SELECT SUM(CAST(cinco AS FLOAT)) valor,uno,dos,siete,ocho,trece
	FROM reportes GROUP BY uno,dos,siete,ocho,trece";
	$que_balance = mssql_query($balance);
	$i=1;$j=0;
	$p=0;$num=0;
	$debito=0;$credito=0;$tot_debito=0;$tot_credito=0;
	$num_rows = mssql_num_rows($que_balance);
	while($row = mssql_fetch_array($que_balance))
	{
	  $saldo = $ins_saldos->conSal_cue_tercero($row['uno'],$row['trece'],$mes,$ano);
	  if($p==0)
	  {
		$pdf->SetFont('Arial','B',6);
	  	$temp = $row['uno'];
		$ced = $row['siete'];
		$pdf->SetY(60+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
		$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['dos']);
		$i++;
		$pdf->SetFont('Arial','',6);
		$pdf->SetY(60+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['nueve']);
		$pdf->SetY(60+($i*3));$pdf->SetX(20);$pdf->Cell(21,8,$row['siete']);
		$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['ocho'],0,30));
		$pdf->SetY(60+($i*3));$pdf->SetX(85);$pdf->Cell(21,8,$row['doce']);
		$pdf->SetY(60+($i*3));$pdf->SetX(143);$pdf->Cell(21,8,number_format($row['tres'],2));
		$pdf->SetY(60+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format($saldo,2));
		$debito += $row['tres'];$credito += $row['valor'];
		$tot_debito+=$row['tres']; $tot_credito += $row['valor'];
		$pdf->SetY(60+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($row['valor'],2));
		$i++;
		$p++;
	  }
	  else
	  {	  
		  if($temp==$row['uno'])
		  {
			 //echo "entra"; 
			 if($ced==$row[7])
			 {
				$pdf->SetY(60+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['nueve']);
				$pdf->SetY(60+($i*3));$pdf->SetX(20);$pdf->Cell(21,8,$row['siete']);
				$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['ocho'],0,20));
				$pdf->SetY(60+($i*3));$pdf->SetX(85);$pdf->Cell(21,8,$row['doce']);
				$pdf->SetY(60+($i*3));$pdf->SetX(143);$pdf->Cell(21,8,number_format($row['tres'],2));
				$pdf->SetY(60+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format($saldo,2));
				$debito += $row['tres'];$credito += $row['valor'];
				$tot_debito+=$row['tres']; $tot_credito += $row['valor'];
				$pdf->SetY(60+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($row['valor'],2));
				$i++;
			 }
			 else
			 {
				$pdf->SetFont('Arial','',6);
				$ced = $row['siete'];
				$pdf->SetY(60+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['nueve']);
				$pdf->SetY(60+($i*3));$pdf->SetX(20);$pdf->Cell(21,8,$row['siete']);
				$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['ocho'],0,30));
				$pdf->SetY(60+($i*3));$pdf->SetX(85);$pdf->Cell(21,8,$row['doce']);
				$pdf->SetY(60+($i*3));$pdf->SetX(143);$pdf->Cell(21,8,number_format($row['tres'],2));
				$pdf->SetY(60+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format($saldo,2));
				$debito += $row['tres'];$credito += $row['valor'];
				$tot_debito+=$row['tres']; $tot_credito += $row['valor'];
				$pdf->SetY(60+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($row['valor'],2)); 
				$i++;
			 }
			// $i++;
		  }
		  else
		  {
			$i-=1;
			$pdf->SetFont('Arial','B',6);
			$pdf->SetY(65+($i*3));$pdf->SetX(120);$pdf->Cell(21,8,"Total ________________________________________________");
			$pdf->SetY(65+($i*3));$pdf->SetX(143);$pdf->Cell(21,8,number_format($debito,2));
			$pdf->SetY(65+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($credito,2));
			$i++;
			$debito = 0;$credito = 0;
			$temp = $row['uno'];
			$ced = $row['siete'];
			$i+=2;
			$pdf->SetFont('Arial','B',6);
			   $j++; 
			$pdf->SetY(60+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
			$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['dos']);
			$i++;
			$pdf->SetFont('Arial','',6);
			$pdf->SetY(60+($i*3));$pdf->SetX(1);$pdf->Cell(21,8,$row['nueve']);
			$pdf->SetY(60+($i*3));$pdf->SetX(20);$pdf->Cell(21,8,$row['siete']);
			$pdf->SetY(60+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['ocho'],0,30));
			$pdf->SetY(60+($i*3));$pdf->SetX(85);$pdf->Cell(21,8,$row['doce']);
			$pdf->SetY(60+($i*3));$pdf->SetX(143);$pdf->Cell(21,8,number_format($row['tres'],2));
			$pdf->SetY(60+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format($saldo,2));
			$debito += $row['tres'];$credito += $row['valor'];
			$tot_debito+=$row['tres']; $tot_credito += $row['valor'];
			$pdf->SetY(60+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($row['valor'],2));
			$i++;
		  }
	  } 
	   if($i>66)
		{
		 $pdf->AddPage();
		 $pdf->SetFont('Arial','',6);
		 $i=1;
		} 
	}
    $pdf->SetFont('Arial','B',7);
    $pdf->SetY(65+($i*3));$pdf->SetX(120);$pdf->Cell(21,8,"Totales ________________________________________________");
	$pdf->SetY(65+($i*3));$pdf->SetX(133);$pdf->Cell(21,8,number_format($tot_debito,2));
	$pdf->SetY(65+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($tot_credito,2));	
}

$pdf->Output();
?>
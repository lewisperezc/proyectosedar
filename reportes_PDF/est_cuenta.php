<?php 
session_start();
include_once('../conexion/conexion.php');
$ano = $_SESSION['elaniocontable'];
/*include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');*/
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
/*$nit = new nits();
$mes_contable = new mes_contable();
$ano = $_POST["ano"];
$mes = $_POST["mes"];
$cue_ini= $_POST["cue_ini"];
$cue_fin =$_POST["cue_fin"];
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];
$fec_ini = "01-".$mes."-".$ano;
$fec_fin = "30-".$mes."-".$ano;*/

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/est_cuenta.jpg",0,0,200,'C');
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

/*$sql = "EXECUTE FABS $doc_ini,$doc_fin,'$cue_ini','$cue_fin'";
$query = mssql_query($sql);
if($query)
{
	$balance = "SELECT * FROM reportes ORDER BY siete";
	$que_balance = mssql_query($balance);
	$i=1;$j=0;$p=0;$num=0;
	$debito=0;$credito=0;$saldo=0;
	$num_rows = mssql_num_rows($que_balance);
	$pdf->SetFont('Arial','B',8);
	while($row = mssql_fetch_array($que_balance))
	{	
	  if($p==0)
	  {
	  	$temp = $row['siete'];
		$fecha = split("-",$row['cinco'],3);
		$mes = $fecha[1];$asociado = $nit->consul_nits($temp);$dat_asociados = mssql_fetch_array($asociado);
		$pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
		$i++;$pdf->SetFont('Arial','',7);
		$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['cinco']);$pdf->SetFont('Arial','',6);
		$pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,$row['uno']." ".$row['seis']);$pdf->SetFont('Arial','',7);
		$pdf->SetY(80+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format((float)$row['tres'],0));
		$pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
		$debito += $row['tres'];$saldo += $row['tres'];
		$i++;
		$p++;
	  }
	  else
	  {
		  $fecha = split("-",$row['cinco'],3);
		  if($temp==$row['siete']&&$fecha[1]==$mes)
		  {
			$mes = $fecha[1];$asociado = $nit->consul_nits($row['siete']);$dat_asociados = mssql_fetch_array($asociado);
			$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['cinco']);
			$pdf->SetY(80+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['uno']." ".$row['nueve']." ".$row['cuatro']);
			$pdf->SetY(80+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format((float)$row['dos'],0));
			$credito += $row['dos'];$saldo -= $row['dos'];$i++;
		  }
		  else
		  {
			if($temp!=$row['siete'])
			 {
			    $dat_mes = $mes_contable->nomMes($mes);$i+=2;
				$pdf->SetFont('Arial','B',7);
				$pdf->SetY(80+($i*3));$pdf->SetX(110);$pdf->Cell(21,8,$dat_mes." ...................................".number_format((float)$saldo,0));
			   $pdf->AddPage();
			   $i=1;$saldo=0;$debito=0;$credito=0;
			   $temp = $row['siete'];
				$fecha = split("-",$row['cinco'],3);
				$mes = $fecha[1];$asociado = $nit->consul_nits($temp);$dat_asociados = mssql_fetch_array($asociado);
				$pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
				$i++;$pdf->SetFont('Arial','',7);
				$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['cinco']);$pdf->SetFont('Arial','',6);
				$pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,$row['uno']." ".$row['seis']);$pdf->SetFont('Arial','',7);
				$pdf->SetY(80+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format((float)$row['tres'],0));
				$pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
				$debito += $row['tres'];$saldo += $row['tres'];
			  }
			else
			{  
				$dat_mes = $mes_contable->nomMes($mes);
				$pdf->SetFont('Arial','B',7);
				$i++;
				$pdf->SetY(80+($i*3));$pdf->SetX(110);$pdf->Cell(21,8,$dat_mes." ...................................".number_format((float)$saldo,0));
				$fecha = split("-",$row['cinco'],3);
				$mes = $fecha[1];
				$i+=2;$pdf->SetFont('Arial','',6);
				$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['cinco']);$pdf->SetFont('Arial','',6);
				$pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,$row['uno']." ".$row['seis']);$pdf->SetFont('Arial','',7);
				$pdf->SetY(80+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format((float)$row['tres'],0));
				$debito += $row['tres'];$saldo += $row['tres'];$pdf->SetFont('Arial','B',7);
				$pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
			}
		  }
	  }
	}
}*/
$pdf->Output();
?>
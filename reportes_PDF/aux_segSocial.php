<?php 
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte");
header("Pragma: no-cache");
header("Expires: 0");

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/saldos.class.php');

require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$mes_contable = new mes_contable();
$saldos = new saldos();
//$ano = $_POST["ano"];
//$mes = $_POST["mes"];
//$cue_ini= $_POST["cue_ini"];
//$cue_fin =$_POST["cue_fin"];
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
$this->Image("../imagenes/reportes/est_segSocial.jpg",0,0,200,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion '.(date('d-m-Y')),0,0,'C'); 
}

}// fin de la clase 


$pdf=new PDF(); 
//Tipo y tama�o de lertra 
$pdf->AddPage();
$pdf->SetFont('Arial','',6);

$cue_seg_soc_afiliado='13250594';

$sql="EXECUTE seg_socialContabilidad '$doc_ini','$doc_fin','$cue_ini','$cue_fin','$cue_seg_soc_afiliado'";
$sqlMachete="select count(dos) dato from reportes  where dos like ('CAU-SEG_%')";
$queryMachete=mssql_query($sqlMachete);
$datMachete=mssql_fetch_array($queryMachete);
//echo $sql;
$query = mssql_query($sql);
if($query)
{
	$balance = "SELECT * FROM reportes";
	$que_balance = mssql_query($balance);
	$i=1;$j=0;$p=0;$num=0;
	$debito=0;$credito=0;$saldo=0;$meses='';$pos_mes=1;$deb_mostrar=0;$saldo_total=0;$entra=0;$deb_mostrarUltimo=0;
	$mes_nom = '';
	$num_rows = mssql_num_rows($que_balance);
	$pdf->SetFont('Arial','',8);
	while($row = mssql_fetch_array($que_balance))
	{	
	  if($p==0)
	  {
	  	$temp = $row['uno'];
		$asociado = $nit->consul_nits($temp);
		$dat_asociados = mssql_fetch_array($asociado);

		$pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
		$deb_seg = $saldos->saldos_seguridad($cue_seg_soc_afiliado,$dat_asociados['nit_id'],1,($_SESSION['elaniocontable']-1));
		$cre_seg = $saldos->saldos_seguridad($cue_seg_soc_afiliado,$dat_asociados['nit_id'],2,($_SESSION['elaniocontable']-1));
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(49+(($i+3)*3));$pdf->SetX(125);$pdf->Cell(21,8,"Saldo a ".($_SESSION['elaniocontable']-1)." =".number_format(($deb_seg-$cre_seg)));
		$i++;$saldo_total=$deb_seg-$cre_seg;
		if(strpos($row['dos'],'Cau_seg_') !== false || strpos($row['dos'],'CAU-SEG_') !== false)
		{
			$entra++;
			$concepto = "Pago a los Fondos (Salud, Pension, ARL)";
			$fecha = $row['tres'];
			$valor = $row['cuatro'];
			$debito+=$valor;
			$x=150;
		}
		else
		{
			$concepto = "Descuento de nomina";
			$fecha = $row['tres'];
			$valor = $row['cuatro'];
			$credito+=$valor;
			$x=130;
		}
		$pdf->SetFont('Arial','',8);
		$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$fecha);
		$pdf->SetY(80+($i*3));$pdf->SetX(40);$pdf->Cell(21,8,$concepto);
		$pdf->SetY(80+($i*3));$pdf->SetX($x);$pdf->Cell(21,8,number_format($row['cuatro'],2));
		$p++;
	  }
	  else
	  {
	  	if($row['uno']==$temp)
	  	{
	  		$i++;
	  		if(strpos($row['dos'],'Cau_seg_') !== false || strpos($row['dos'],'CAU-SEG_') !== false)
			{
				$entra++;
				$concepto = "Pago a los Fondos (Salud, Pension, ARL)";
				$mes_nom = $mes_contable->nomMes($row['cinco']-1);
				$fecha =  $row['tres'];
				$valor = $row['cuatro'];
				if($entra==$datMachete['dato'])
					$deb_mostrarUltimo = $row['cuatro']; 
				$deb_mostrar = $debito;
				$debito=0;
				$debito+=$valor;
				$x=150;
			}
			else
			{

				$concepto = "Descuento de nomina Factura ".$row['ocho']." (Mes de ".$mes_contable->nomMes($row['cinco'])." del ".$row['seis'].")";
				$fecha = $row['tres'];
				$valor = $row['cuatro'];
				$mes_pago='Mes de '.$row['tres'].'-'.$row['cinco'];
				$credito+=$valor;
				$x=130;
			}
			$mes_nom1 = $mes_contable->nomMes($row['cinco']);
			if($row['siete']==1)
			{
				$i++;
				$tempi=0;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,"Total Mes ".$mes_nom."       _______________________________________");
				$pdf->SetY(80+($i*3));$pdf->SetX(125);$pdf->Cell(21,8,number_format($credito,2));
				$pdf->SetY(80+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format($deb_mostrar,2));
                $saldo=$deb_mostrar-$credito;
                $saldo_total=$saldo_total+$saldo;
                $pdf->SetY(80+($i*3));$pdf->SetX(180);$pdf->Cell(21,8,number_format($saldo_total));
				$credito=0;
				$i+=2;
			}
			$pdf->SetFont('Arial','',8);
			$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$fecha);
			$pdf->SetY(80+($i*3));$pdf->SetX(40);$pdf->Cell(21,8,$concepto);
			$pdf->SetY(80+($i*3));$pdf->SetX($x);$pdf->Cell(21,8,number_format($valor,2));
			//$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,"Total Mes ".$mes_nom."       _______________________________________");
	  	}
	  	else
	  	{
	  		$pdf->AddPage();
	  		$i=1;
	  		$temp=$row['uno'];
	  		$temp = $row['uno'];
			$asociado = $nit->consul_nits($temp);
			$dat_asociados = mssql_fetch_array($asociado);
			$pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
			$i++;
			if(strpos($row['dos'],'Cau_seg_') !== false || strpos($row['dos'],'CAU-SEG_') !== false)
			{
				if($entra==$datMachete['dato'])
					$deb_mostrar = $row['cuatro'];
				else
					$deb_mostrar = $debito;
				$concepto = "Pago a los Fondos (Salud, Pension, ARL)";
				$fecha = '05-'.$row['cuatro'].'-'.$row['cinco'];
				$x=150;
			}
			else
			{
				$concepto = "Descuento de nomina";
				$fecha = $row['tres'];
				$x=130;

			}
			$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$fecha);
			$pdf->SetY(80+($i*3));$pdf->SetX(40);$pdf->Cell(21,8,$concepto);
			$pdf->SetY(80+($i*3));$pdf->SetX($x);$pdf->Cell(21,8,number_format($row['tres'],2));
	  	}   
	  	 if($i%30==0)
                 {
                    $pdf->AddPage();
                    $i=1;
                 }
	  }
	  //$deb_mostrar = $row['cuatro'];
	}
	$i+=2;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(80+($i*3));$pdf->SetX(125);$pdf->Cell(21,8,number_format($credito,2));
	$pdf->SetY(80+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format($deb_mostrarUltimo,2));
    $saldo=$deb_mostrarUltimo-$credito;
    $saldo_total=$saldo_total+$saldo;
    $pdf->SetY(80+($i*3));$pdf->SetX(180);$pdf->Cell(21,8,number_format($saldo_total));
	$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,"Total Mes ".$mes_nom1."       _______________________________________");
	$i+=2;
        
        $pdf->SetY(85+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,"______________________________________________________________________________________________________________________________");
        $pdf->SetFont('Arial','B',10);
        $pdf->SetY(90+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,"Saldo final:");
        $pdf->SetY(90+($i*3));$pdf->SetX(180);$pdf->Cell(21,8,number_format($saldo_total));
	/*$pdf->SetFont('Arial','B',10);
	$pdf->SetY(80+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,"Total Mes ".$nom_mes."       _____________________________________________");
	$pdf->SetY(80+($i*3));$pdf->SetX(125);$pdf->Cell(21,8,number_format($credito,2));
	$pdf->SetY(80+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format($deb_mostrar,2));
	$credito=0;*/
}

$pdf->Output();
?>
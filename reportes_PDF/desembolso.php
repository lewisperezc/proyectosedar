<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
 include_once('../clases/transacciones.class.php');
 $transaccion = new transacciones();
 $sigla = $_GET['sigla'];
 $tercero=$_GET['tercero'];
 $mes=$_GET['mes_cont'];
 $fecha=$_GET['fecha'];
 $neto=0;
 if($_GET['tip']==1)//CONSULTA
 {
	//echo "entra";
    $par_sigla = split("-",$sigla,3);
    $consecutivo = $par_sigla[2];
    $proveedor=$transaccion->TodDatOrdDesembolso($sigla,$mes,$fecha);
    $dat_transaccion=$transaccion->consulOrden($sigla,$mes,$ano);
    $dat_proveedor=mssql_fetch_array($proveedor);
 }
 else//REGISTRO
 {
 	//echo "entra 2";
	$par_sigla = split("_",$sigla,3);
    $consecutivo = $par_sigla[1];
    $proveedor = $transaccion->TodDatOrdDesembolso($sigla,$mes,$fecha);//Bien
    $dat_transaccion=$transaccion->consulOrden($sigla,$mes,$ano);
    $dat_proveedor = mssql_fetch_array($proveedor);
 }
//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
include('../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../pdf/fonts/courier.afm');
require('../pdf/fpdf.php');
$datacreator = array (
					'Title'=>'Ejemplo PDF',
					'Author'=>'SEDAR',
					'Subject'=>'PDF con Tablas',
					'Creator'=>'SEDAR',
					'Producer'=>'http://'
					);

class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
//$this->Image("imagenes_efren/fondo_anestecoop6.jpg", 0, 0, 400, 'center');
//Arial bold 15
  $this->SetFont('Arial','B',15); 
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresi�n'.(date('d-m-Y')),0,0,'C'); 
} 
}// fin de la clase 

//N�mero de p�gina 
 
	
$pdf->addInfo($datacreator);//muestra los datos del creador en pro piedades del documento

$pdf=new FPDF();
$pdf->AliasNbPages();
//$pdf->AddPage();//adiciona otra hoja 
$pdf->SetFont('Times','',12);
/////
$pdf->AddPage('L','letter');//orientacion y tama�o hoja
$pdf->SetFont('Arial','',9); 
$pdf->Line(10,7,270,7); // Linea Horizontal  inicial del documento(izquierda, superios horizontal, superior) 
$pdf->Image('../imagenes/logo_sedar_original.jpg',10,8,50);//<img src="" width="289" height="289" />
//Salto de l�nea 

$pdf->SetFillColor(232,232,232); 
$pdf->Line(10,31,270,31); // Linea Horizontal 2
// Principal 

$pdf->SetFont('Arial','B',8);$pdf->Text(52,30,"FECHA");$pdf->Text(62,30+$j,$dat_proveedor['trans_fec_doc']);//Bien
$pdf->SetFont('Arial','B',8);$pdf->Text(200,20,"CONSECUTIVO: ".$consecutivo); //Bien
$pdf->SetFont('Arial','B',12);$pdf->Text(120,23,'ORDEN DE DESEMBOLSO');//Bien
         
$pdf->SetFont('Arial','B',8);$pdf->Text(12,37,"PROVEEDOR: ");$pdf->Text(50,37,$dat_proveedor['nombre']." ".$dat_proveedor['nits_apellidos']); //Bien
$pdf->SetFont('Arial','B',8);$pdf->Text(155,37,"NIT: ");$pdf->Text(180,37,$dat_proveedor['nit']); //Bien
$pdf->Line(10,39,270,39); //Linea Horizontal 3
     
$pdf->SetFont('Arial','B',8);
$pdf->Line(10,45,150,45);$pdf->SetFont('Arial','B',8);$pdf->Text(12,49,"FECHA");//Bien
$pdf->Line(10,50,270,50);$pdf->SetFont('Arial','',7);$pdf->Text(12,54,$dat_proveedor['trans_fec_doc']); //Bien

$pdf->Line(25,45,25,100);$pdf->SetFont('Arial','B',8);$pdf->Text(26,49,"NUMERO");//Bien
//$pdf->SetFont('Arial','',7);$pdf->Text(28,54,$dat_proveedor['trans_fac_num']);//Bien
$pdf->Line(40,45,40,100);

$pdf->SetFont('Arial','B',8);$pdf->Text(41,49,"F.VENCE");$pdf->SetFont('Arial','',7);//Bien
$pdf->Text(42,54,$dat_proveedor['trans_fec_vencimiento']);//Bien
$pdf->Line(55,45,55,100);

$pdf->SetFont('Arial','B',8);$pdf->Text(68,49,"DESCRIPCION");
$pdf->Line(105,45,105,100);
$pdf->Line(130,45,130,100);

$pdf->SetFont('Arial','B',8);$pdf->Text(137,49,"IVA");
$pdf->Line(150,39,150,100);$pdf->Text(155,45,"RTEFUENTE");
$pdf->Line(177,39,177,100);$pdf->Text(182,45,"RTEIVA");
$pdf->Line(200,39,200,100);$pdf->Text(206,45,"ICA");
$pdf->Line(218,39,218,100);$pdf->Text(222,45,"DESCUENTOS");
$pdf->Text(247,45,"NETO A PAGAR");
$pdf->Text(107,49,"VALOR NETO");
$pdf->SetFont('Arial','B',12);
$pdf->Text(210,105,'TOTAL PAGADO');
$pdf->Line(10,110,270,110);$pdf->Line(10,100,270,100);
$pdf->Line(270,7,270,110);$pdf->Line(10,134,270,134);
$pdf->Line(10,7,10,110);$pdf->Line(245,39,245,110);
$j=0;$total=0;$val_neto=0;
$pdf->SetFont('Arial','B',7);

$j=0;
while ($dat_movimiento = mssql_fetch_array($dat_transaccion))
{
		$pdf->Text(137,54+$j,number_format($dat_movimiento['ord_com_iva']));//IVA
		$pdf->Text(155,54+$j,number_format($dat_movimiento['ord_com_rete'])); //RETE FUENTE
		//$pdf->Text(185,54+$j,number_format($dat_movimiento[3]));//ReteIva*/
		$pdf->Text(210,54+$j,number_format($dat_movimiento['ord_com_ica']));//ICA
		$val_neto=$dat_movimiento['ord_com_val_total'];
		$pdf->Text(110,54+$j,number_format($val_neto));//El valor neto
		$val_pagar=$val_neto-$dat_movimiento['ord_com_iva']-$dat_movimiento['ord_com_rete'];
		$pdf->Text(249,54+$j,number_format($val_pagar));
		$total = $total+$val_pagar;
		$j+=3;
		$val_neto=0;
}
$pdf->Text(255,105,number_format($total));

//Linea Horizontal separador2(INICIO, X,FINAL,X
$pdf->SetFont('Arial','B',12);
$pdf->Text(12,140,'OBSERVACIONES :');//x,y, nomnbre
$pdf->SetFont('Arial','B',12);
$pdf->Text(54,140,$dat_proveedor['trans_observacion']);//x,y, nomnbre
	 
$pdf->Line(10,154,270,154); // Linea Horizontal inicio cuadro 2(INICIO, X,FINAL,X
$pdf->Line(10,154,10,205); 	//derecha
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,157, 'ELABORADOR POR:');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,172, 'NOMBRE:');
		 
$pdf->Line(12,174,80,174);//linesepador
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,177, 'CARGO:');
		 //////////
$pdf->SetFont('Arial','B',8); 
$pdf->Text(92,157, 'REVISADO POR:');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(120,173, '');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(92,177, 'FECHA:');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(104,177,date('d-m-Y'));
		 ////////
$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,157, 'AUTORIZADO POR:');
		 
$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,173, 'DIRECTOR EJECUTIVO');
		 
/*$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,177, 'Cristina Cardona Botero');*/
		 /////////////
$pdf->Line(10,180,270,180); // Linea Horizontal divicion cuadro 2(INICIO, X,FINAL,X
$pdf->Line(90,154,90,205); 	//primera divicion vertical
$pdf->Line(180,154,180,205); 	//segundadivicion vertical
	  ////
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,183, 'ENTREGA EN TESORERIA:');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,192, 'RECIBE:');
		 
$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,200, 'FECHA:');
		 ///
$pdf->SetFont('Arial','B',8); 
$pdf->Text(92,183, 'APROBACION DE TRANSACCION:');
		  //////
$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,183, 'ENTREGA CONTABILIZACION:');
		 
$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,192, 'RECIBE:');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(182,200, 'FECHA:');
	  
$pdf->Line(270,154,270,205);  //izquierdaaa
$pdf->Line(10,205,270,205); // Linea Horizontal final cuadro 2(INICIO, X,FINAL,X
	 
$pdf->Ln(23); 
$pdf->Ln(20);
$pdf->Output();

?>

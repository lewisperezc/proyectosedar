<?php 
session_start();

function nomMes($mes)
{
    $sql="SELECT mes_nombre FROM mes_contable WHERE mes_id=$mes";
    $query = mssql_query($sql);
    if($query)
    {
        $dat_mes = mssql_fetch_array($query);
        return $dat_mes['mes_nombre'];
    }
}

/*echo ."<br>";
echo ."<br>";
echo ."<br>";*/

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('/clases/mes_contable.class.php');
@include_once('../clases/pabs.class.php');
@include_once('/clases/pabs.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$mes_contable = new mes_contable();
$ins_fabs = new pabs();
$inicio = explode("-",$_POST["cue_ini"],2);
$fin = explode("-",$_POST["cue_fin"],2);
$cue_ini= $inicio[1];
$cue_fin =$fin[1];
$ano=$_SESSION['elaniocontable'];


/*if($cue_ini==1)
    $cue_ini=13;
else
    $cue_ini-=1;*/

$mes_antes = $cue_ini-1;
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];

if($_GET['web']==1)
{
    $mes_contable=1;
    $cue_ini= $_GET['ini'];
    $cue_fin =$_GET['fin'];
    $ano=date('Y');
    $mes_antes = $cue_ini-1;
    $doc_ini = $_GET['ced'];
    $doc_fin = $_GET['ced'];
}
$tip_nit_id=1;
$con_dat_afiliados=$nit->ConAfiPorAuxNucleo($tip_nit_id);
$num_filas=mssql_num_rows($con_dat_afiliados);

$m=0;
while($res_dat_afiliados=mssql_fetch_array($con_dat_afiliados))
{
	$lis_afi_por_nucleo[$m]=$res_dat_afiliados['nits_num_documento'];
	$m++;	
}


if($num_filas>0 && in_array($doc_ini, $lis_afi_por_nucleo))
{

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/est_fabs.jpg",0,0,200,'C');
//Arial bold 15
$this->SetFont('Arial','B',15);
$this->SetFont('Arial','B',15);
//Movernos a la derecha
//$this->Cell(10);
$this->Ln(10);
}

//Pie de pagina 
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

$cuenta_fabs='25052001';

$sql = "EXECUTE FABS $doc_ini,$doc_fin,'$cuenta_fabs'";
//echo $sql;

$query = mssql_query($sql);

$que_num_filas="SELECT count(uno) num_filas FROM rep_fabs";
$eje_num_filas=mssql_query($que_num_filas);
$res_num_filas=mssql_fetch_array($eje_num_filas);

if($query&&$res_num_filas['num_filas']>0)
{
    $balance = "select * from rep_fabs order by CAST(quince AS INT),CAST(diez AS INT),CAST(tres AS DATETIME)";
    //echo $balance;
    $que_balance = mssql_query($balance);
    $i=1;$j=0;$p=0;$num=0;
    $debito=0;$credito=0;$saldo=0;
    $num_rows = mssql_num_rows($que_balance);
    $pdf->SetFont('Arial','B',8);
    
    
    while($row = mssql_fetch_array($que_balance))
    {
      $asoc = $row['siete']."_1";
          $aso_id=$row['siete'];
      $sql="SELECT (SELECT sum(mov_valor) FROM movimientos_contables WHERE mov_cuent IN(
      SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs)
      AND mov_tipo=1 AND mov_nit_tercero='$asoc' AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)-
      (SELECT sum(mov_valor) FROM movimientos_contables WHERE mov_cuent IN(
      SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs)
      AND mov_tipo=2 AND mov_nit_tercero='$asoc' AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12) valor";
      //echo $sql; 
      $query=mssql_query($sql);
      $dat_ante = mssql_fetch_array($query);

      
      //AQUI ESTA EL SALDO INICIAL DEL FABS
      
        //$sal__cue_ter_ano="SELECT * FROM saldo_cuentas_tercero WHERE sal_cue_ter_cuenta='253010061' AND sal_cue_ter_ano='$ano' AND sal_cue_ter_tercero LIKE('$aso_id')";
        $sal__cue_ter_ano="SELECT ISNULL(SUM(mov_valor),0) mov_valor,mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable
		FROM movimientos_contables WHERE mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs) and
        mov_compro like('CIE-2017') and mov_nit_tercero='".$aso_id."_1' AND mov_ano_contable=2018 AND
        mov_mes_contable=9
        GROUP BY mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable";
        //echo $sal__cue_ter_ano."<br>";
        $eje_sal__cue_ter_ano=mssql_query($sal__cue_ter_ano);
        $res_sal__cue_ter_ano=mssql_fetch_array($eje_sal__cue_ter_ano);
      
                
      if($p==0)
      {
        //$saldo1=$ins_fabs->saldo_inicial($ano,$cue_ini,$aso_id);
        //$pdf->SetY(70);$pdf->SetX(170);$pdf->Cell(21,8,number_format($res_sal__cue_ter_ano['sal_cue_ter_valor']));
        if($res_sal__cue_ter_ano['mov_tipo']==2)
            $saldo=$res_sal__cue_ter_ano['mov_valor']*(-1);
        else
            $saldo=$res_sal__cue_ter_ano['mov_valor'];
        $pdf->SetY(70);$pdf->SetX(170);$pdf->Cell(21,8,number_format($saldo));
        //$saldo=$res_sal__cue_ter_ano['sal_cue_ter_valor'];*/
        $saldo+=$dat_ante['valor'];
        $temp = $row['siete'];
        $temp1 = $row['diez'];
        $fecha = split("-",$row['cinco'],3);
        $mes=$row['diez'];
        $asociado = $nit->consul_nits($temp);
        $dat_asociados = mssql_fetch_array($asociado);
        $pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
        $i++;$pdf->SetFont('Arial','',6);
        if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT')||strstr($row['uno'],'DEV-FABS'))
        {
            if(strstr($row['uno'],'PAG-COM'))
                $des="FAC #:".$row['cuatro'];
            else
                $des=$row['seis'];
            $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper(substr($row['cinco'],0,15)));
            $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$des." - ".$row['once']);
            $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
            if(is_numeric($row['uno']))
                $valor=(float)$row['dos']*$row['uno'];
            else
                $valor=(float)$row['dos'];
            $pdf->SetY(80+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format((float)$valor,0));
            $debito+=$valor;$saldo+=$valor;
            $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
        }
        else
        {
            //$pdf->SetY(80+($i*3));$pdf->SetX(20);$pdf->Cell(21,8,substr($row['nueve'],0,20));
            $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper($row['cuatro']));
            if($row['once']==""||$row['once']=="NULL")
                $row['once']=$row['uno'];
            $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$row['seis']." - ".$row['once']);
            $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
            if($row['catorce']==1)
            {
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
                $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                $credito+=$valor;$saldo-=$valor;
                $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
            }
            else
            {
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
                $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                $debito+=$valor;$saldo+=$valor;
                $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
            }
        }
        $i++;
        $p++;
      }
      else
      {
        if($temp==$row['siete']&&$temp1==$row['diez'])
        {
          $i++;$pdf->SetFont('Arial','',6);
          if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT')||strstr($row['uno'],'DEV-FABS'))
            {
                if(strstr($row['uno'],'PAG-COM'))
                    $des="FAC #:".$row['cuatro'];
                else
                    $des=$row['seis'];
                $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper(substr($row['cinco'],0,15)));
                $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$des." - ".$row['once']);
                $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
                $pdf->SetY(80+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format((float)$valor,0));
                $debito+=$valor;$saldo+=$valor;
                $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
            }
            else
            {
              //$pdf->SetY(80+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['nueve'],0,20));
              $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper($row['cuatro']));
              if($row['once']==""||$row['once']=="NULL")
                $row['once']=$row['uno'];
              $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$row['seis']." - ".$row['once']);
              $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
              //echo $row['catorce']."<br>"; 
              if($row['catorce']==1)
                {
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                    $credito+=$valor;$saldo-=$valor;
                    $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                }
                else
                {
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format((float)$valor,0));
                    $debito+=$valor;$saldo+=$valor;
                    $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                }
            }
        }
        else
        {
            if($temp!=$row['siete'])
             {
                $i+=2;
                $pdf->SetFont('Arial','B',7);
                $mes=$row['diez'];
                $pdf->SetY(80+($i*3));$pdf->SetX(130);$pdf->Cell(21,8,"Saldo ".$dat_mes." ......................................................".number_format((float)$saldo,0));
                $pdf->AddPage();
                $i=1;$saldo=0;$debito=0;$credito=0;
                $temp = $row['siete'];
                $temp1 = $row['diez'];
                $sal__cue_ter_ano="SELECT ISNULL(SUM(mov_valor),0) mov_valor,mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable FROM movimientos_contables WHERE mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs)
                and mov_compro like('CIE-2017') and mov_nit_tercero='".$temp."_1' AND mov_ano_contable=2018
                AND mov_mes_contable=9
                GROUP BY mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable";
                //echo $sal__cue_ter_ano."<br>";
                  $eje_sal__cue_ter_ano=mssql_query($sal__cue_ter_ano);
                  $res_sal__cue_ter_ano=mssql_fetch_array($eje_sal__cue_ter_ano);
                if($res_sal__cue_ter_ano['mov_tipo']==2)
                    $saldo=$res_sal__cue_ter_ano['mov_valor']*(-1);
                else
                    $saldo=$res_sal__cue_ter_ano['mov_valor'];
                //echo "El saldo es: ".$saldo."<br>";
                $pdf->SetY(70);$pdf->SetX(170);$pdf->Cell(21,8,number_format($saldo));
                $pdf->SetFont('Arial','B',8);
                $fecha = split("-",$row['cinco'],3);
                $mes=$row['diez']; $dat_mes = $mes_contable->nomMes($row['diez']+1);$asociado = $nit->consul_nits($temp);$dat_asociados = mssql_fetch_array($asociado);
                $pdf->SetY(49+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$dat_asociados['nits_num_documento']."--".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']);
                $i++;$pdf->SetFont('Arial','',6);
                if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT')||strstr($row['uno'],'DEV-FABS'))
                {
                    if(strstr($row['uno'],'PAG-COM'))
                        $des="FAC #:".$row['cuatro'];
                    else
                        $des=$row['seis'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper(substr($row['cinco'],0,15)));
                    $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$des." - ".$row['once']);
                    $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                    $debito+=$valor;$saldo+=$valor;
                    $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                }
                else
                {
                  //$pdf->SetY(80+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['nueve'],0,20));
                  $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper($row['cuatro']));
                  if($row['once']==""||$row['once']=="NULL")
                  $row['once']=$row['uno'];
                  $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$row['seis']." - ".$row['once']);
                  $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
                  if($row['catorce']==1)
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                        $credito+=$valor;$saldo-=$valor;
                        $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                    }
                    else
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                        $debito+=$valor;$saldo+=$valor;
                        $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                    }
                }
              }
            else
            { 
                $i++;$pdf->SetFont('Arial','B',7);
                $dat_mes = $mes_contable->nomMes($row['diez']-1);
                $pdf->SetY(80+($i*3));$pdf->SetX(130);$pdf->Cell(21,8,"Saldo ".$dat_mes." .....................................................".number_format((float)$saldo,0));
                $temp1 = $row['diez'];
                $i+=2;$pdf->SetFont('Arial','',6);
                if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT')||strstr($row['uno'],'DEV-FABS'))
                {
                    if(strstr($row['uno'],'PAG-COM'))
                        $des="FAC #:".$row['cuatro'];
                    else
                        $des=$row['seis'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper(substr($row['cinco'],0,15)));
                    $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$des." - ".$row['once']);
                    $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    $pdf->SetY(80+($i*3));$pdf->SetX(115);$pdf->Cell(21,8,number_format((float)$valor,0));
                    $debito+=$valor;$saldo+=$valor;
                    $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                }
                else
                {
                  //$pdf->SetY(80+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['nueve'],0,20));
                  $pdf->SetY(80+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,strtoupper($row['cuatro']));
                  if($row['once']==""||$row['once']=="NULL")
                    $row['once']=$row['uno'];
                  $pdf->SetY(80+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$row['seis']." - ".$row['once']);
                  $pdf->SetY(80+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,substr($dat_mes = nomMes($row['diez']),0,3)." - ".$row['quince']."   ".$row['tres']);
                  if($row['catorce']==1)
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                        $credito+=$valor;$saldo-=$valor;
                        $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                    }
                    else
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        $pdf->SetY(80+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format((float)$valor,0));
                        $debito+=$valor;$saldo+=$valor;
                        $pdf->SetY(80+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format((float)$saldo,0));
                    }
                }
            }
          }
      }
      if($i>=58)
      {
        $pdf->AddPage();
        $pdf->SetFont('Arial','',6);
        $i=1;
      }
    }
}
else//NO TIENE MOVIMIENTOS, BUSCAMOS EL SALDO INICIAL PARA MOSTRARLO
{
    
    //echo "entra por aca";
    $query_nit_id="SELECT * FROM nits WHERE nits_num_documento BETWEEN '$doc_ini' AND '$doc_fin'";
    $eje_nit_id=mssql_query($query_nit_id);
    $hojas=0;
    while($res_nit_id=mssql_fetch_array($eje_nit_id))
    {
        if($hojas>0)
            $pdf->AddPage();
        
        
        $pdf->SetY(49);$pdf->SetX(35);$pdf->Cell(21,8,$res_nit_id['nits_num_documento']."--".$res_nit_id['nits_nombres']." ".$res_nit_id['nits_apellidos']);
        
        $sal__cue_ter_ano="SELECT ISNULL(SUM(mov_valor),0) mov_valor,mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,
        mov_documento,mov_doc_numer,mov_mes_contable,mov_ano_contable FROM movimientos_contables
        WHERE mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs) and
        mov_compro like('CIE-2017') and mov_nit_tercero='".$res_nit_id['nit_id']."_1' AND mov_ano_contable=2018
        AND mov_mes_contable=9
        GROUP BY mov_compro,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable";
        //echo $sal__cue_ter_ano."<br>";
        $eje_sal__cue_ter_ano=mssql_query($sal__cue_ter_ano);
        $res_sal__cue_ter_ano=mssql_fetch_array($eje_sal__cue_ter_ano);
        
        
        if($res_sal__cue_ter_ano['mov_tipo']==2)
            $saldo=$res_sal__cue_ter_ano['mov_valor']*(-1);
        else
            $saldo=$res_sal__cue_ter_ano['mov_valor'];
        $pdf->SetY(70);$pdf->SetX(170);$pdf->Cell(21,8,number_format($saldo));
        
        
        
        $hojas++;
    }
    
}
$pdf->Output();
}
else
{
	echo "<script>alert('No se encontraron datos, intentelo de nuevo.');history.back(-1);</script>";
}
?>
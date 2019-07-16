<?php session_start();

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

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('/clases/mes_contable.class.php');
$nit = new nits();
$mes_contable = new mes_contable();
$inicio = explode("-",$_POST["cue_ini"],2);
$fin = explode("-",$_POST["cue_fin"],2);
$cue_ini= $inicio[1];
$cue_fin =$fin[1];
$ano=$_SESSION['elaniocontable'];
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
//,$cue_ini,$cue_fin,$ano
$cuenta_fabs='25052001';
$sql = "EXECUTE FABS $doc_ini,$doc_fin,$cuenta_fabs";
//echo $sql;
$query=mssql_query($sql);
if($query)
{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=AuxCueTercero");
		header("Pragma: no-cache");
		header("Expires: 0");
	
    	echo "<table border='1'><tr><th>CEDULA</th><th>NOMBRE</th><th>DESCRIPCION</th><th>CONCEPTO</th><th>MES</th><th>DEBITO</th><th>CREDITO</th><th>SALDO</th></tr>";
    	$balance = "select * from rep_fabs order by quince,CAST(diez AS INT),CAST(tres AS DATETIME)";
        $que_balance = mssql_query($balance);
        $i=1;$j=0;$p=0;$num=0;
        $debito=0;$credito=0;$saldo=0;
        $num_rows = mssql_num_rows($que_balance);

         while($row = mssql_fetch_array($que_balance))
            {
                  $asoc = $row['siete']."_1";
                  $aso_id=$row['siete'];
                  $sql="SELECT (SELECT sum(mov_valor) FROM movimientos_contables
                  WHERE mov_cuent=250520101 AND mov_tipo=2 AND mov_nit_tercero='$asoc'
                  AND mov_ano_contable=$ano-1)-(SELECT sum(mov_valor) FROM movimientos_contables
                  WHERE mov_cuent=250520101 AND mov_tipo=1 AND mov_nit_tercero='$asoc' AND mov_ano_contable=$ano-1)
                  valor";
          
                  $query=mssql_query($sql);
                  $dat_ante = mssql_fetch_array($query);
          //$sal__cue_ter_ano="SELECT * FROM saldo_cuentas_tercero WHERE sal_cue_ter_cuenta='253010061' AND sal_cue_ter_ano='$ano' AND sal_cue_ter_tercero LIKE('$aso_id')";
                  $sal__cue_ter_ano="SELECT * FROM movimientos_contables
                  WHERE mov_cuent like('250520011%') and mov_compro like('CIE-%') AND
                  mov_nit_tercero='".$aso_id."_1'";
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
        $saldo+=$dat_ante['valor'];
        $temp = $row['siete'];
        $temp1 = $row['diez'];
        $fecha = split("-",$row['cinco'],3);
        $mes=$row['diez'];
        $asociado = $nit->consul_nits($temp);$dat_asociados = mssql_fetch_array($asociado);
        echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
        if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT'))
        {
            if(strstr($row['uno'],'PAG-COM'))
                $des="Fac #:".$row['cuatro'];
            else
                $des=$row['seis'];
            echo "<td>".$row['cinco']."</td>";
            echo "<td>".$des." - ".$row['once']."</td>";
            echo "<td>".nomMes($row['diez'])."</td>";
            if(is_numeric($row['uno']))
                $valor=(float)$row['dos']*$row['uno'];
            else
                $valor=(float)$row['dos'];
            echo "<td>".(float)$valor."</td>";
            $debito+=$valor;$saldo+=$valor;
            echo "<td>0</td>";
            echo "<td>".(float)$saldo."</td></tr>";
        }
        else
        {
            echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
            echo "<td>".strtoupper($row['cuatro'])."</td>";
            echo "<td>".$row['seis']." - ".$row['once']."</td>";
            echo "<td>".nomMes($row['diez'])."</td>";
            if($row['catorce']==1)
            {
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
                echo "<td>0</td>";
                echo "<td>".(float)$valor."</td>";
                $credito+=$valor;$saldo-=$valor;
                echo "<td>".(float)$saldo."</td></tr>";
            }
            else
            {
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
                echo "<td>".(float)$valor."</td>";
                $debito+=$valor;$saldo+=$valor;
                echo "<td>0</td>";
               echo "<td>".(float)$saldo."</td></tr>";
            }
        }
        $p++;
      }
      else
      {
        if($temp==$row['siete']&&$temp1==$row['diez'])
        {
          echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
          if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT'))
            {
                if(strstr($row['uno'],'PAG-COM'))
                    $des="Fac #:".$row['cuatro'];
                else
                    $des=$row['seis'];
                echo "<td>".$row['cinco']."</td>";
                echo "<td>".$des." - ".$row['once']."</td>";
                echo "<td>".nomMes($row['diez'])."</td>";
                if(is_numeric($row['uno']))
                    $valor=(float)$row['dos']*$row['uno'];
                else
                    $valor=(float)$row['dos'];
               echo "<td>".(float)$valor."</td>";
                $debito+=$valor;$saldo+=$valor;
                echo "<td>0</td>";
                echo "<td>".(float)$saldo."</td></tr>";
            }
            else
            {
              //$pdf->SetY(80+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['nueve'],0,20));
               echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
                echo "<td>".strtoupper($row['cuatro'])."</td>";
                echo "<td>".$row['seis']." - ".$row['once']."</td>";
                echo "<td>".nomMes($row['diez'])."</td>";
                if($row['catorce']==1)
                {
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    echo "<td>0</td>";
                    echo "<td>".(float)$valor."</td>";
                    $credito+=$valor;$saldo-=$valor;
                    echo "<td>".(float)$saldo."</td></tr>";
                }
                else
                {
                    if(is_numeric($row['uno']))
                        $valor=(float)$row['dos']*$row['uno'];
                    else
                        $valor=(float)$row['dos'];
                    echo "<td>".(float)$valor."</td>";
                    $debito+=$valor;$saldo+=$valor;
                    echo "<td>0</td>";
                   echo "<td>".(float)$saldo."</td></tr>";
                }
            }
        }
        else
        {
            if($temp!=$row['siete'])
             {
                
                $i=1;$saldo=0;$debito=0;$credito=0;
                $temp = $row['siete'];
                $temp1 = $row['diez'];

                 $sal__cue_ter_ano="SELECT * FROM movimientos_contables
                 WHERE mov_cuent like('250520011%') and mov_compro like('CIE-%')
                 AND mov_nit_tercero='".$temp."_1'";
          //echo $sal__cue_ter_ano."<br>";
                  $eje_sal__cue_ter_ano=mssql_query($sal__cue_ter_ano);
                  $res_sal__cue_ter_ano=mssql_fetch_array($eje_sal__cue_ter_ano);
                if($res_sal__cue_ter_ano['mov_tipo']==2)
                    $saldo=$res_sal__cue_ter_ano['mov_valor']*(-1);
                else
                    $saldo=$res_sal__cue_ter_ano['mov_valor'];
                $saldo+=$dat_ante['valor'];
                $fecha = split("-",$row['cinco'],3);
                $mes=$row['diez']; $dat_mes = $mes_contable->nomMes($row['diez']+1);$asociado = $nit->consul_nits($temp);$dat_asociados = mssql_fetch_array($asociado);
               echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
                if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT'))
                {
                     if(strstr($row['uno'],'PAG-COM'))
                    $des="Fac #:".$row['cuatro'];
                        else
                            $des=$row['seis'];
                        echo "<td>".$row['cinco']."</td>";
                        echo "<td>".$des." - ".$row['once']."</td>";
                        echo "<td>".nomMes($row['diez'])."</td>";
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                       echo "<td>".(float)$valor."</td>";
                        $debito+=$valor;$saldo+=$valor;
                        echo "<td>0</td>";
                        echo "<td>".(float)$saldo."</td></tr>";
                }
                else
                {
                       echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
                    echo "<td>".strtoupper($row['cuatro'])."</td>";
                    echo "<td>".$row['seis']." - ".$row['once']."</td>";
                    echo "<td>".nomMes($row['diez'])."</td>";
                    if($row['catorce']==1)
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        echo "<td>0</td>";
                        echo "<td>".(float)$valor."</td>";
                        $credito+=$valor;$saldo-=$valor;
                        echo "<td>".(float)$saldo."</td>";
                    }
                    else
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        echo "<td>".(float)$valor."</td>";
                        $debito+=$valor;$saldo+=$valor;
                        echo "<td>0</td>";
                       echo "<td>".(float)$saldo."</td>";
                    }
                }
              }
            else
            { 
                $temp1 = $row['diez'];
                $i+=2;
                echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
                if(strstr($row['uno'],'PAG-COM')||strstr($row['uno'],'NOT'))
                {
                    if(strstr($row['uno'],'PAG-COM'))
                    $des="Fac #:".$row['cuatro'];
                        else
                            $des=$row['seis'];
                        echo "<td>".$row['cinco']."</td>";
                        echo "<td>".$des." - ".$row['once']."</td>";
                        echo "<td>".nomMes($row['diez'])."</td>";
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                       echo "<td>".(float)$valor."</td>";
                        $debito+=$valor;$saldo+=$valor;
                        echo "<td>0</td>";
                        echo "<td>".(float)$saldo."</td></tr>";
                }
                else
                {
                   echo "<tr><td>".$dat_asociados['nits_num_documento']."</td><td>".$dat_asociados['nits_nombres']." ".$dat_asociados['nits_apellidos']."</td>";
                    echo "<td>".strtoupper($row['cuatro'])."</td>";
                    echo "<td>".$row['seis']." - ".$row['once']."</td>";
                    echo "<td>".nomMes($row['diez'])."</td>";
                    if($row['catorce']==1)
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                       echo "<td>".(float)$valor."</td>";
                       echo "<td>0</td>";
                        $credito+=$valor;$saldo-=$valor;
                        echo "<td>".(float)$saldo."</td>";
                    }
                    else
                    {
                        if(is_numeric($row['uno']))
                            $valor=(float)$row['dos']*$row['uno'];
                        else
                            $valor=(float)$row['dos'];
                        echo "<td>".(float)$valor."</td>";
                        $debito+=$valor;$saldo+=$valor;
                        echo "<td>0</td>";
                       echo "<td>".(float)$saldo."</td>";
                    }
                }
            }
          }
      }
    }
    echo "</table>";
}
?>
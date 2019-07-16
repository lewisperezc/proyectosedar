<?php session_start();
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/bancos.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$banco = new bancos();
$ano = $_SESSION['elaniocontable'];
$fecha=date('d-m-Y');
$cant = $_POST['cant'];
//include("../comunes/libreria_generales.php"); 
echo "
<table border='1'>
      <tr>
        <th>DOCUMENTO</th>
        <th>NOMBRE</th>
        <th>BANCO</th>
        <th>CUENTA BANCARICA</th>
        <th>TIPO DE CUENTA</th>
        <th>CONCEPTO</th>
        <th>VALOR</th>";

for($i=0;$i<$cant;$i++)
  {
      if($_POST['cue'.$i]!= '11100524' && $_POST['cue'.$i]!= '23803004' && !empty($_POST['nit'.$i]))
      {
            $asociado = $nit->consultar($_POST['nit'.$i]);
            $dat_asociado = mssql_fetch_array($asociado);
            echo "<tr><td>".$dat_asociado['nits_num_documento']."</td>";
            echo "<td>".$dat_asociado['nits_nombres']." ".$dat_asociado['nits_apellidos']."</td>";
            $bancos = $banco->datBancos($dat_asociado['nits_ban_id']);
            $dat_banco = mssql_fetch_array($bancos);
            echo "<td>".$dat_banco['banco']."</td>";
            echo "<td>".$dat_asociado['nits_num_cue_bancaria']."</td>";
            if($dat_asociado['tip_cue_ban_id']==1)
              echo "<td>Ahorros</td>";
            else
              echo "<td>Corriente</td>";
            echo "<td>".$_POST['cue'.$i]."</td>";
            echo "<td>".$_POST['valor'.$i]."</td></tr>";
            $suma+=$_POST['valor'.$i];
      }
  }
echo "<tr><td colspan='7'>TOTAL ".number_format($suma)."</td></tr></table>";
?>
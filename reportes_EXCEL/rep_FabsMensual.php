<?php
include_once('../conexion/conexion.php');
include_once('../clases/pabs.class.php');
$pabs = new pabs();
$mes = $_POST["mes"];
$ano = $_POST["ano"];
$sql = $pabs->repFabs($mes,$ano);


    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=AuxCueTercero");
    header("Pragma: no-cache");
    header("Expires: 0");
      echo "<table border='1'><tr><th>Movimiento</th><th>Tercero</th><th>Documento</th><th>Fecha Elaboracion</th><th>Cedula</th><th>Nombres</th><th>Apellidos</th><th>Valor</th><th>Tipo</th></tr>";
      while($row = mssql_fetch_array($sql))
        {
            echo "<tr><td>".$row['id_mov']."</td><td>".$row['mov_nit_tercero']."</td><td>".$row['mov_compro']."</td><td>".$row['mov_fec_elabo']."</td><td>".$row['nits_num_documento']."</td><td>".$row['nits_nombres']."</td><td>".$row['nits_apellidos']."</td><td>".$row['mov_valor']."</td><td>".$row['mov_tipo']."</td></tr>";
        }
        echo "</table>";
?>
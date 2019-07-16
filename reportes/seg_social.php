<?php session_start();
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=portafolio.xls");
header("Pragma: no-cache");
header("Expires: 0");
$ano = $_SESSION['elaniocontable'];
include_once('clases/moviminetos_contables.class.php');
include_once('clases/nits.class.php');
include_once('clases/mes_contable.class.php');
$ins_mov_contable = new movimientos_contables();
$ins_nits = new nits();
$val_minimo = $ins_nits->sal_minimo();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$fecha = date('m');//-1;
$ano = $_SESSION['elaniocontable'];
$cero = 0;
$mes_consulta = $fecha;
$nits = $ins_mov_contable->con_sal_cue_seg_soc_asociado($mes_consulta,$ano,'25300513');
$consul_nit;
  
  echo "<table border=1> ";
echo "<tr>"; ?>
        <td><span class="Estilo4">NUMERO DOCUMENTO</span></td>
        <td><span class="Estilo4">FECHA MOVIMIENTO</span></td>
        <td><span class="Estilo4">DESCRIPCION_ibod</span></td>
		<td><span class="Estilo4">IDTERCERO</span></td>
        <td><span class="Estilo4">NOLOTE</span></td>
        <td><span class="Estilo4">RAZONSOCIAL</span></td>
		<td><span class="Estilo4">DESCRIPCION IT</span></td>
        <td><span class="Estilo4">DESCRIPCION I</span></td>
        <td><span class="Estilo4">IDARTICULO</span></td>
		<td><span class="Estilo4">DESCRIPCIONIA</span></td>
        <td><span class="Estilo4">CANTIDAD</span></td>
        <td><span class="Estilo4">PCOSTO</span></td>
		<td><span class="Estilo4">PVENTA</span></td>
		<td><span class="Estilo4">PIVA</span></td>
        <td><span class="Estilo4">NOLOTE</span></td>
		<td><span class="Estilo4">NODOCUMENTO</span></td>
        <td><span class="Estilo4">NOLOTE</span></td>
		<td><span class="Estilo4">FECHAVENCE</span></td>
		<td><span class="Estilo4">USUARIO</span></td>
		<td><span class="Estilo4">USUARIO</span></td>
       </tr>
<?php 
while($row = odbc_fetch_array($req))
    { ?>
      <tr>
        <td><span class="Estilo4"><?php echo $row['CNSMOV']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['FECHAMOV']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['DESCRIPCION']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['IDTERCERO']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['NOLOTE']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['RAZONSOCIAL']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['DESCRIPCION_ITMO']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['DESCRIPCION_ITAR']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['IDARTICULO']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['DESCRIPCION_IART']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['CANTIDAD']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['PCOSTO']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['PVENTA']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['PIVA']; ?></span></td>
        <td><span class="Estilo4"><?php echo $row['NOLOTE']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['OBSERVACION']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['NODOCUMENTO']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['NOLOTEPEDIDO']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['FECHAVENCE']; ?></span></td>
		<td><span class="Estilo4"><?php echo $row['USUARIOCONF']; ?></span></td>
       </tr> <?php                             
    }
echo "</table> ";
?>

$paciente = $_SESSION["paciente"];
?>
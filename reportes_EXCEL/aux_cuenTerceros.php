<?php session_start();

@include_once('../conexion/conexion.php');
@include_once('conexion/conexion.php');

$ano=$_SESSION['elaniocontable'];
$mes=$_POST["mes"];
$cue_ini=$_POST["cue_ini"];
$cue_fin=$_POST["cue_fin"];
$doc_ini=$_POST["doc_ini"];
$doc_fin=$_POST["doc_fin"];

$sql="EXECUTE aux_terceros '$cue_ini','$cue_fin','$doc_ini','$doc_fin',$mes,$ano";
$query=mssql_query($sql);
if($query)
{
	$query_2="SELECT SUM(CAST(tres AS FLOAT)) debito,SUM(CAST(cinco AS FLOAT)) credito,uno,dos,siete,ocho,trece
FROM reportes
GROUP BY uno,dos,siete,ocho,trece";
	$ejecutar=mssql_query($query_2);
	if($ejecutar)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=AuxCueTercero");
		header("Pragma: no-cache");
		header("Expires: 0");
	?>
    	<table border="1">
            <tr>
            	<th>CUENTA</th>
                <th>NOMBRE</th>
                <th>DOCUMENTO</th>
                <th>NOMBRE</th>
                <th>DEBITO</th>
                <th>CREDITO</th>
            </tr>
            <?php
            while($resultado=mssql_fetch_array($ejecutar))
            {
            ?>
            <tr>
            	<td><?php echo $resultado['uno']; ?></td>
                <td><?php echo $resultado['dos']; ?></td>
                <td><?php echo $resultado['siete']; ?></td>
                <td><?php echo $resultado['ocho']; ?></td>
                <td><?php echo $resultado['debito']; ?></td>
                <td><?php echo $resultado['credito']; ?></td>
            </tr>
        <?php
        }
        ?>
    	</table>
    <?php
	}
	else
		echo "<script>alert('No se encontraron registros relacionados con los datos ingresados, intentelo de nuevo.');history.back(-1);
		</script>";
}
?>
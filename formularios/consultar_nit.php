<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>

<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="estilos/screen.css"  media="screen" />
<script>
function valida_select()
{
	document.sel_tip_nit.submit();
}
</script>
<?php 
@include_once('clases/nits.class.php');
@include_once('conexion/conexion.php');

$instancia_tip_nit = new tipo_nit();
$consulta = $instancia_tip_nit->con_tod_tip_nits();
?>
<form name="sel_tip_nit" method="post">
    <center>
        <table>
        <tr>
            <th>
            <select name="sel_tip_nit" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Tipo NIT</option>
            <?php
            while($row = mssql_fetch_array($consulta))
            {
            ?> 
                <option value="<?php echo $row['nit_tip_id'];?>" onclick="valida_select();"><?php echo $row['nit_tip_nombre'];?></option>
            <?php
            }
            ?>
            </select>
            </th>
        </tr>
    </table>
    </center>
</form>
<?php
$nit_seleccionado=$_POST['sel_tip_nit'];
$_SESSION['sel_nit']=$nit_seleccionado;
session_register('otra');
$otra[99];
if($nit_seleccionado)
{
    $otra[1]=$nit_seleccionado;
    $sqlcas="SELECT * FROM formulario_consultas_por_nit where nit_tip_id='$otra[1]'";
	//echo $sqlcas;
    $concas=mssql_query($sqlcas);
    $filcas=mssql_fetch_array($concas);
    $otra[99]=$filcas['for1_nit_nombre'];
}
if($nit_seleccionado)
{
    include_once($otra[99]);
}
?>
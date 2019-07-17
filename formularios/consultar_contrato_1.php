<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('./clases/contrato.class.php');
$instancia_contrato = new contrato();
$consultar = $instancia_contrato->consulta_tipo_contrato();
?>
<script>
function enviar()
{
    document.sel_tip_contrato.submit();
}
</script>
<head>
<link rel="stylesheet" type="text/css" href="estilos/screen.css" media="screen" />
</head>
<body>
<form name="sel_tip_contrato" method="post">
    <center>
    <select name="sel_tip_contrato" id="sel_tip_contrato" required x-moz-errormessage="Seleccione Una Opcion Valida">
        <option value=''>--Seleccione--</option>
        <?php
        while($row = mssql_fetch_array($consultar))
    {
    ?>
            <option value="<?php echo $row['tip_con_id']; ?>" onclick="enviar();"><?php echo $row['tip_con_nombre']; ?></option>
        <?php
    }
    ?>
    </select>
    </center>
</form>
<?php
$sel_tip_contrato = $_POST['sel_tip_contrato'];
if($sel_tip_contrato == 1)
{
    include_once('consultar_empleado_frame.php');
}
else
{
    if($sel_tip_contrato==2)
    {
        include_once('consultar_contrato_externo_frame.php');
    }
}
?>
</body>
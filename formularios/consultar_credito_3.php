<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/credito.class.php');
$instancia_credito = new credito();

if($_POST['sel_persona'])
	$_SESSION['sel_persona'] = $_POST['sel_persona'];
$id_persona = $_SESSION['sel_persona'];

$con_dat_credito = $instancia_credito->con_dat_credito_1($id_persona);
?>
<script language="javascript" type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function enviar()
{
	document.con_cre_3.submit();
}

function elim_credito(credito)
{
    var r = confirm("Realmente desea eliminar el credito?");
    if (r == true)
    {
        $.ajax({
          type: "POST",
          url: "../llamados/elim_credito.php",
          data: "credito="+credito,
          success: function(msg){
            if(msg==0)
                alert("Credito eliminado correctamente.");
            if(msg==1)
                alert("No se pudo eliminar el credito, intentelo de nuevo.");
            if(msg==2)
          		alert("No se puede eliminar el credito porque ya tiene descuentos registrados.");
          }
          });
    } 
    else {
        txt = "Tarea cancelada.";
    }
}

</script>
<form name="con_cre_3" method="post" action="consultar_credito_4.php">
	<center>
        <table border="1">
        <tr>
            <td colspan="7" align="center"><b>Cr&eacute;ditos Registrados A La Persona</b></td>
        </tr>
        <tr>
            <td><b>Consecutivo</b></td>
            <td><b>Tipo</b></td>
            <td><b>Fecha Solicitud</b></td>
            <td><b>Fecha Vencimiento</b></td>
            <td><b>Valor</b></td>
            <td><b>Ver</b></td>
            <td><b>Eliminar</b></td>
        </tr>
    <?php
        while($row = mssql_fetch_array($con_dat_credito))
        {
    ?>
            <tr>
                <td><?php echo $row['cre_id']; ?></td>
                <td><?php echo $row['con_nombre']; ?></td>
                <td><?php echo $row['cre_fec_solicitud']; ?></td>
                <td><?php echo $row['cre_fec_vencimiento']; ?></td>
                <td><?php echo number_format($row['cre_valor']); ?></td>
                <td><a href="consultar_credito_4.php?cre_id=<?php echo $row['cre_id'] ?>">Ver</a></td>
                <td><input type="radio" name="eli_cre" id="eli_cre" value="<?php echo $row['cre_id']; ?>" onclick="elim_credito(this.value);"></td>
            </tr>
    <?php
        }
    ?>
    </table>
    </center>
</form>
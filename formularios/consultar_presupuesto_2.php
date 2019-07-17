<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../estilos/limpiador.css"/>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css"/>
<title>Presupuesto</title>
<script>
function habilitar(cont)
{
    for (i=0;i<document.forms[0].elements.length;i++) 
	  {
        if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		}
      }
	  	  document.reg_presupuesto.guardar.disabled=false;
		  //document.reg_presupuesto.gastado.disabled=true;
}
</script>
</head>
<body>
<?php
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/presupuesto.class.php');
$ins_cen_costo=new centro_de_costos();
$ins_presupuesto=new presupuesto();
$variables=$_GET['variables'];
$partir=split("-",$variables,2);
$cen_cos_id=$partir[0];
$fecha=$partir[1];
$con_nom_cen_cos=$ins_cen_costo->con_cen_cos_pabs($cen_cos_id);
$res_nom_cen_cos=mssql_fetch_array($con_nom_cen_cos);
$con_tod_datos=$ins_presupuesto->con_tod_pre_por_cen_cos_fecha($cen_cos_id,$fecha);
$filas=mssql_num_rows($con_tod_datos);
?>

<form method="post" name="reg_presupuesto" id="reg_presupuesto" action="../control/modificar_presupuesto.php">
<center>
    <?php
        if($filas>1){
        ?>
        <table border="1">
            <tr>
                <th colspan="3">Presupuesto asignado a <?php echo $res_nom_cen_cos['cen_cos_nombre']; ?> en el año <?php echo $fecha; ?></th>
            </tr>
            <tr>
                <th>Cuenta</th>
                <th>Presupuesto</th>
                <!--<th>Gastado</th>-->
            </tr>
            <?php
            while($res_cuentas=mssql_fetch_array($con_tod_datos)){
            ?>
            <tr>
                <td><?php echo $res_cuentas['cue_id']." - ".$res_cuentas['cue_nombre']; ?>
                <input type="hidden" name="pre_cuenta[]" id="pre_cuenta[]" value="<?php echo $res_cuentas['cue_id']; ?>"/>
                <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>"/>
                <input type="hidden" name="cen_cos_id" id="cen_cos_id" value="<?php echo $cen_cos_id; ?>"/>
                </td>
                <td><input type="text" name="pre_valor[]" id="pre_valor[]" value="<?php echo $res_cuentas['cue_por_cen_cos_presupuesto']; ?>" disabled="disabled" /></td>
                <?php
                //$res_gastado=$ins_presupuesto->con_pre_gastado($centro,$res_cuentas['cue_id'],$pre_fecha);
                }
                ?>
            </tr>
           <tr>
            <td colspan="3">
            
            <input type="button" class="art-button" name="mod" value="Modificar Presupuesto" onclick="habilitar(<?php echo $cont; ?>);"/>
            <input type="submit" class="art-button" name="guardar" value="Guardar Presupuesto" disabled="disabled"/>
            </td>
           </tr>
        </table>
        <?php
        }
        else
            echo "<b>No se encontro ningun presupuesto asignado al centro de costo $res_nom_cen_cos[cen_cos_nombre] en este año</b>";
        ?>
</center>		
</form>
</body>
</html>
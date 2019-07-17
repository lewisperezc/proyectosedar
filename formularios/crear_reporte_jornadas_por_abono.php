<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); } ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Reporte de jornadas</title>
<link rel="stylesheet" type="text/css" href="../estilos/limpiador.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
</head>
<body>
<?php
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/nits.class.php');
@include_once('../clases/centro_de_costos.class.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/factura.class.php');
@include_once('clases/nits.class.php');
@include_once('clases/centro_de_costos.class.php');
@include_once('clases/reporte_jornadas.class.php');
@include_once('clases/factura.class.php');

$nit = new centro_de_costos();
$hospital = $nit->con_cen_por_con_estado(1);
$rep = new reporte_jornadas();
$factura = new factura();

$fac_sel=$_SESSION['fac_seleccionada'];
$con_cen_costo=$factura->datFactura($fac_sel);
$res_cen_costo=mssql_fetch_array($con_cen_costo);

$valor_del_abono=$_GET['valor_del_abono'];
$recibo_consecutivo=$_GET['recibo_consecutivo'];
$_SESSION['mes']=$_GET['mes'];
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript">
function suma_jornadas(valor)
{
	var suma=0;
	for(i=0;i<valor;i++)
	{
	  if($('#num_jornadas'+i).val()!="")
	  	suma += parseInt($('#num_jornadas'+i).val());
	}
	$('#sum_jorn').val(suma);
}
 
function enviar(hosp)
{
	if(document.f1.sel_hos.selectedIndex==0)
    	alert("Debe Seleccionar un hospital");
    else
    	document.f1.submit();  
}

function val_cam(campos,valor_del_abono)
{
	if(document.f1.tip_rep2.value==0)
	{
		alert('Debe seleccionar un tipo de reporte valido!!!');
	}
	else
	{
		for(var i=0;i<campos;i++)
		{
			if(document.getElementById("num_jornadas"+i).value=="")
			{
				alert('Los campos de las jornadas son obligatorios, si el afiliado no reporta jornadas ponga 0');
				return false;
			}
			if(document.f1.val_abo.value!=document.f1.sum_jorn.value)
			{
				alert("La suma de las jornadas debe ser igual al valor del abono!!!");
				return false
			}
		}
		document.f1.submit();
	}
}
</script>
<form name="f1" id="f1" method="post" action="../control/guardar_reporte_jornadas_por_abono.php" >
  <center>
<?php
$asociados=$nit->buscar_asociados($res_cen_costo['fac_cen_cos']);
?>
<table id="reporte" border="1">
    <tr>
        <td colspan="2">Tipo reporte</td>
        <td colspan="2">
            <select name="tip_rep2" id="tip_rep2">
                <option value="0">Seleccione</option>
                <option value="1">Reporte en dinero</option>
                <option value="2">Reporte en jornadas</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
		<th>Identificacion</th>
		<th>Nombre</th>
		<th>Estado</th>
		<th>Novedad</th>
	</tr>
<?php
$canti_aso = mssql_num_rows($asociados);
if($canti_aso>0)
{
	$i=0;
	while($row = mssql_fetch_array($asociados))
	{
		echo "<tr>";
		echo "<td><input type='hidden' name='aso_id[]' id='aso_id[]' value='".$row['nit_id']."'/></td>";
		echo "<td>".$row['doc']."</td>";
		echo "<td>".$row['nombres']." ".$row['apellidos']."</td>";
		echo "<td>".$row['estado']."</td>"; 
		$num_aso[$i] = $row['cen_nit_id']; ?>
		<td>
		<input type="text" name="num_jornadas<?php echo $i; ?>" id="num_jornadas<?php echo $i; ?>" onkeypress="return permite(event, 'num')" onchange="suma_jornadas(<?php echo $canti_aso; ?>);"/></td> 
        <?php
		echo "</tr>";
		$i++;
	} 
	$_SESSION["num_aso"] = $num_aso;
	$_SESSION['i'] = $i;
?>
	<tr>
    	<th colspan="3">Suma Jornadas</th><td><input type="text" readonly name="sum_jorn" id="sum_jorn" size="10"/></td>
    </tr>
	<tr>
		<td colspan="4" align="center">
	    <input type="button" class="art-button" value="Guardar" id="gua" name="gua" onClick="val_cam(<?php echo $i; ?>);" />
        <input type="hidden" name="val_abo" id="val_abo" value="<?php echo $valor_del_abono; ?>"/>
        <input type="hidden" name="can_asociados" id="can_asociados" value="<?php echo $i; ?>"/>
        <input type="hidden" name="rec_consecutivo" id="rec_consecutivo" value="<?php echo $recibo_consecutivo; ?>"/>
        <input type="hidden" name="fact_seleccionada" id="fact_seleccionada" value="<?php echo $fac_sel; ?>"/>
        <input type="hidden" name="centro_de_costo" id="centro_de_costo" value="<?php echo $res_cen_costo['fac_cen_cos']; ?>"/>
		</td>
	</tr>
	<?php
}
else
	echo "<tr><th colspan='4'>No se encontraron afiliado activos en este centro de costo!!!</th></tr>";
?>
	</table>
	</center>
</form>
</body>
</html>
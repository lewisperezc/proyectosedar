<?php
@include_once('../clases/varios.class.php');
@include_once('clases/varios.class.php');

@include_once('../clases/reporte_jornadas.class.php');
@include_once('clases/reporte_jornadas.class.php');

$ins_rep_jornadas=new reporte_jornadas();

$ins_varios=new varios();
$con_dat_descuentos_1=$ins_varios->ConsultarTiposDescuentos();
$con_dat_descuentos_2=$ins_varios->ConsultarTiposDescuentos();

$reporte = $ins_rep_jornadas->buscarReporteJornadas_Factura($_GET['factura']);
$total_afiliados = mssql_num_rows($reporte);
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>

<script>
function Ocultar(tipo)
{
    if(tipo==0)
    {
        alert('Seleccione una opcion valida');
        $("#dis_proporcional").css("display","none");
        $("#dis_afiliados").css("display","none");
    }

    if(tipo==1)
    {
        $("#dis_proporcional").css("display","table");
        $("#dis_afiliados").css("display","none");

    }
    if(tipo==2)
    {
        $("#dis_proporcional").css("display","none");
        $("#dis_afiliados").css("display","table");

    }
}

function sumaGlosa(valor)
{
    var suma=0;
    for(i=0;i<valor;i++)
    {
        if($('#jorna'+i).val()=="")
        {
         $('#jorna'+i).val(0);
        }
        suma += parseInt($('#jorna'+i).val());

    }
    $('#tot_descuento_adicional').val(suma);
}

function GuardarGlosa(tipo)
{
    var mensaje='Todos los campos son obligatorios.';
    if(tipo==1)
    {
        if($('#tip_des_adicional').val()==""||$('#val_des_adicional').val()=="")
            alert(mensaje);
        else
        {
            document.frm_agr_otr_descuento.action='../control/guardar_otro_descuento_compensacion.php?tipo_descuento='+tipo;
            document.frm_agr_otr_descuento.submit();
        }

    }

    if(tipo==2)
    {
        if($('#tip_des_adicional_afiliados').val()=="")
            alert(mensaje);
        else
        {
            document.frm_agr_otr_descuento.action='../control/guardar_otro_descuento_compensacion.php?tipo_descuento='+tipo;
            document.frm_agr_otr_descuento.submit();
        }

    }
    
}

</script>
<center>
<form method="post" name="frm_agr_otr_descuento" id="frm_agr_otr_descuento">
<table border="1">
    <tr>
        <th>Tipo descuento</th>
        <th>
        <select name="tip_descuento" id="tip_descuento" onchange="Ocultar(this.value);">
        <option value="0">-Seleccione-</option>
        <option value="1">Distribucion proporcional</option>
        <option value="2">Distribucion afiliados</option>
        </select>
        </th>
    </tr>
</table>

<table border="1" id="dis_proporcional" style="display:none">
<tr>
    <th colspan="4">AGREGAR DESCUENTO</th>
</tr>
<tr>
	<th>Tipo descuento</th>
    <td>
    <select name="tip_des_adicional" id="tip_des_adicional">
    <option value="">--</option>
    <?php
    while($res_dat_descuentos_1=mssql_fetch_array($con_dat_descuentos_1))
    {
    ?>
    	<option value="<?php echo $res_dat_descuentos_1['tip_des_id']; ?>">
        <?php echo $res_dat_descuentos_1['tip_des_nombre']; ?>
        </opcion>
    <?php
    }
    ?>
    </select>
    </td>
    <th>Valor</th>
    <td><input type="text" name="val_des_adicional" id="val_des_adicional" value="0"></td>	
</tr>
<tr>
    <td colspan="4">
    <input type="hidden" name="fac_des_adicional" id="fac_des_adicional" value="<?php echo $_GET['factura'] ?>">
    <input type="button" name="gua_descuento" value="Guardar" onclick="GuardarGlosa(1);">
    </td>
</tr>
</table>

<table border="1" id="dis_afiliados" style="display:none">
<tr>
    <th>Tipo descuento</th>
    <td colspan="2">
    <select name="tip_des_adicional_afiliados" id="tip_des_adicional_afiliados" required="required">
    <option value="">--</option>
    <?php
    while($res_dat_descuentos_2=mssql_fetch_array($con_dat_descuentos_2))
    {
    ?>
        <option value="<?php echo $res_dat_descuentos_2['tip_des_id']; ?>">
        <?php echo $res_dat_descuentos_2['tip_des_nombre']; ?>
        </opcion>
    <?php
    }
    ?>
    </select>
    </td>
</tr>
<?php
$i=0;
while($row = mssql_fetch_array($reporte))
{
    echo "<tr>";
        echo "<td>";
        echo "<input type='hidden' name='repJor$i' id='repJor$i' value='".$row['rep_jor_id']."'/>";
        echo "<input type='hidden' name='nit$i' id='nit$i' value='".$row['nit_id']."'/>";
        echo "<input type='text' name='identi$i' id='identi$i' readonly value='".$row['nits_num_documento']."'/>";
        echo "</td>";
        echo "<td>";
        echo "<input type='text' name='nombre$i' id='nombre$i' readonly value='".$row['nits_nombres']." ".$row['nits_apellidos']."'/>"; 
        echo "</td>";
        echo "<td>";
        echo "<input type='text' name='jorna$i' required id='jorna$i' value='0' onchange='sumaGlosa(".$total_afiliados.");'/>";
        echo "</td>";
    echo "</tr>";
    $i++;
}

echo "<tr>";
echo "<th colspan='2'>Total descuento</th>";
echo "<td><input type='hidden' name='tot_afiliados' id='tot_afiliados' value='".$total_afiliados."'>";
echo "<input type='text' name='tot_descuento_adicional' id='tot_descuento_adicional' value='0' readonly></td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan='4'><input type='button' name='btn_gua_des_afiliados' id='btn_gua_des_afiliados' value='Guardar' onclick='GuardarGlosa(2);'></td>";
echo "</tr>";
?>


</table>

</center>
</form>
<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Consultar recibo de caja</title>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function MostrarCampos(elvalor)
{
	if($("#salpor").val()==1)
	{
    	$("#porconse").css("display","block");
		$("#porcencos").css("display","none");
		$("#porfac").css("display","none");
    }
	else
	{
		if($("#salpor").val()==2)
		{
			$("#porcencos").css("display","block");
    		$("#porconse").css("display","none");
			$("#porfac").css("display","none");
		}
		else
		{
			if($("#salpor").val()==3)
			{
				$("#porfac").css("display","block");
				$("#porconse").css("display","none");
				$("#porcencos").css("display","none");
			}
			else
			{
				if($("#salpor").val()=="")
				{
					$("#porconse").css("display","none");
					$("#porcencos").css("display","none");
					$("#porfac").css("display","none");
				}
			}
		}
	 }
}

function TraeRecibos(valor,crit){
	var html='';
   $.ajax({
   type: "POST",
   url: "../llamados/trae_recibos_por_usuario.php",
   data: "elvalor="+valor+"&criterio="+crit,
   success: function(msg){
	 $("#losrecibos").css("display","block");
	 $("losrecibos").append(html);
     $("#rec").html(msg);
   }
 });
}

function AbrePDF()
{
	if($("#rec_caja").val()!="")
	   recibo=$("#rec_caja").val();
	else
	   recibo=$("#rec_caja1").val();
	URL='../reportes_PDF/recibo_caja_reporte.php?elid='+recibo;
	day = new Date();
	id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=700,left=240,top=300');");
}
</script>
</head>
<body>
<?php
include_once('../clases/recibo_caja.class.php');

//cen_cos_sec()
$ins_rec_caja=new rec_caja();
$ConDatRecibo1=$ins_rec_caja->ConDatRecibo();
$ConDatRecibo2=$ins_rec_caja->ConDatcentro();
$ConDatRecibo3=$ins_rec_caja->ConDatRecibo();
?>
<form name="elformulario" id="elformulario">
<table border="1">
    	<tr>
        	<th>Consultar por</th>
            <td><select name="salpor" id="salpor" onChange="MostrarCampos(this.value);">
            <option value="">Seleccione</option>
            <option value="1">Consucutivo</option>
            <option value="2">C. Costo</option>
            <option value="3">Factura</option>
            </select></td>
            <!--POR CONSECUTIVO-->
        	<th id="porconse" style="display:none">Recibos
            <input type="text" name="rec_caja" id="rec_caja" list="rec" size="15" required onchange="AbrePDF(this.value);">
            <datalist id="rec">
           <?php while($ResDatRecibo=mssql_fetch_array($ConDatRecibo1))
				echo "<option value='".$ResDatRecibo['rec_caj_id']."' label='".$ResDatRecibo['rec_caj_consecutivo']."'>";
        	?>
          </datalist>
          </th>
          <!--POR CENTRO COSTO-->
          <th id="porcencos" style="display:none">C. Costo
            <input type="text" name="ccosto" id="ccosto" list="centro" size="50" required onchange="TraeRecibos(this.value,1);">
            <datalist id="centro">
           <?php while($res_tod_cen_costos=mssql_fetch_array($ConDatRecibo2))
				echo "<option value='".$res_tod_cen_costos['cen_cos_id']."' label='".$res_tod_cen_costos['cen_cos_nombre']."'>";
        	?>
          </datalist>
          </th>
          <!--POR FACTURA-->
          <th id="porfac" style="display:none">Factura
            <input type="text" name="factura" id="factura" list="fac" size="50" required onchange="TraeRecibos(this.value,2);">
            <datalist id="fac">
           <?php while($res_facturas=mssql_fetch_array($ConDatRecibo3))
				echo "<option value='".$res_facturas['fac_id']."' label='".$res_facturas['fac_consecutivo']."'>";
        	?>
          </datalist>
          </th>
          <th id="losrecibos" style="display:none">Recibos
          	<input type="text" name="rec_caja1" id="rec_caja1" list="rec" size="15" required>
            <datalist id="rec">
            </datalist>
          </th>
        </tr>
        <tr>
        	<td colspan="3"><input type="button" onClick="AbrePDF();" name="rep" value="Ver"/></td>
        </tr>
    </table>
</form>
</body>
</html>
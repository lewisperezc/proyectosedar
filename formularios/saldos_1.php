<?php session_start(); 
$ano = $_SESSION['elaniocontable'];?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 

<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="librerias/datatable/jquery.dataTables.js"></script> 
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/separador.js"></script>
<script src="librerias/js/separador.js"></script>


<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_table.css";
@import "librerias/datatable/demo_table.css";
</style> 
<script>
		$(document).ready(function() {
			$('#todoslossaldos').dataTable();
		} );
</script>
<script>
function MostrarCampos(elvalor)
{
	if($("#salpor").val()==1)
	{
    	$("#porcue").css("display","block");
		$("#pornit").css("display","none");
		$("#porcencos").css("display","none");
		$("#cuenits").css("display","none");
		$("#porcred").css("display","none");
    }
	else
	{
		if($("#salpor").val()==2)
		{
    		$("#porcue").css("display","none");
			$("#pornit").css("display","block");
			$("#cuenits").css("display","block");
			$("#porcencos").css("display","none");
			$("#porcred").css("display","none");
		}
		else
		{
			if($("#salpor").val()==3)
			{
				$("#porcue").css("display","none");
				$("#pornit").css("display","none");
				$("#porcencos").css("display","block");
				$("#cuenits").css("display","none");
				$("#porcred").css("display","none");
			}
			else
			{
				if($("#salpor").val()==4)
				{
					$("#porcue").css("display","none");
					$("#pornit").css("display","none");
					$("#porcencos").css("display","none");
					$("#cuenits").css("display","none");
					$("#porcred").css("display","block");
				}
				else
				{
					if($("#salpor").val()=="")
					{
						$("#porcue").css("display","none");
						$("#pornit").css("display","none");
						$("#porcencos").css("display","none");
						$("#cuenits").css("display","none");
						$("#porcred").css("display","none");
					}
				}
			}
		}
	 }
}


function formatearNumero(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}


function TraerSaldos(elvalor){
   var eltipo=$("#salpor").val();
   var elnit=$("#nit_id").val();
   $.ajax({
   type: "POST",
   url: "llamados/trae_saldos.php",
   data: "elvalor="+elvalor+"&eltipo="+eltipo+"&elnit="+elnit,
   success: function(msg){	
    var myObject = eval('(' + msg + ')');
    
    for (var x = 1 ; x <= 13 ; x++) 
	 {
	 	if(x==1)
	 	{
	 		//Math.round(x);
	 		if(myObject[x].saldo_anterior=='undefined')
	 			$("#saldo_anterior").val(formatearNumero(Math.round(0)));
	 		else
	 			$("#saldo_anterior").val(formatearNumero(Math.round(myObject[x].saldo_anterior)));
	 	}
		if(myObject[x].debito>0)
		  $("#debito"+x).val(formatearNumero(Math.round(myObject[x].debito)));
		else
		  $("#debito"+x).val(formatearNumero(Math.round(0)));
		if(myObject[x].credito>0)
		  $("#credito"+x).val(formatearNumero(Math.round(myObject[x].credito)));
		else
		  $("#credito"+x).val(formatearNumero(Math.round(0)));
		
		if(myObject[x].saldo!=0)
		  $("#saldo"+x).val(formatearNumero(Math.round(myObject[x].saldo)));
		else
		  $("#saldo"+x).val(formatearNumero(Math.round(0)));
	 }
   }
 });
}

function VentanaEmergente(mes)
{
	var tipo=$("#salpor").val();
	if(tipo==1)
	{
		var nit_id=0;
		var cuenta=$("#cuenta").val();
	}
    else
	{
		if(tipo==2)
		{
			var nit_id=$("#nit_id").val();
			var cuenta=$("#cue_nit").val();
		}
		else
		{
			if(tipo==3)
			{
				var nit_id=0;
				var cuenta=$("#ccosto").val();//CENTRO DE COSTO
			}
			else
			{
				if(tipo==4)
				{
					var nit_id=0;
					var cuenta=$("#creditos").val();//CENTRO DE COSTO
				}	
			}
		}
	}
	
	var anio=$("#elanio").val();
	URL='formularios/movimientos_por_mes.php?tipo='+tipo+'&cuenta='+cuenta+'&mes='+mes+'&anio='+anio+'&nit='+nit_id;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=500,left=240,top=112');");
}

function traecuentas(nit)
{
   $.ajax({
   type: "POST",
   url: "llamados/trae_cueNits.php",
   data: "nit="+nit,
   success: function(msg){
     $("#cuen_nit").html(msg);
   }
 });
}

function imp_saldo(html)
{
	var nit = $('#nit_id').val();
	var cuenta= $('#cue_nit').val();
	var eltipo=$("#salpor").val();
	URL='reportes_pdf/cue_tercero.php?nit='+nit+'&cuenta='+cuenta+'&tipo='+eltipo;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=500,left=240,top=112');");	
}

</script> 
</head>
<body>
<?php
@include_once('clases/cuenta.class.php');
@include_once('clases/tipos_saldos.class.php');
@include_once('clases/nits.class.php');
@include_once('clases/centro_de_costos.class.php');
include_once('clases/mes_contable.class.php');
include_once('clases/credito.class.php');
$ins_mes = new mes_contable();
$ins_cen_costo=new centro_de_costos();
$ins_nits=new nits();
$ins_tip_saldos=new TiposSaldos();
$ins_cuenta=new cuenta();
$ins_credito=new credito();
$con_tod_cen_costos=$ins_cen_costo->con_cen_cos_ord_por_hospital();
$con_nom_nits=$ins_nits->ConNomNits();
$ConTodTipSaldos=$ins_tip_saldos->ConTodTipSaldos();
$con_tod_cuentas=$ins_cuenta->todCuentas();
$con_creditos = $ins_credito->con_tod_cre_activos(2);
$meses = $ins_mes->mes();
?>
<form name="lascuentas" id="lascuentas" method="post">
	<center>
		<table border="1">
    	<tr>
        	<th>Saldos por</th>
            <td><select name="salpor" id="salpor" onChange="MostrarCampos(this.value);">
            <option value="">Seleccione</option>
            <?php
            while($ResTodTipSaldos=mssql_fetch_array($ConTodTipSaldos))
			{
			?>
            <option value="<?php echo $ResTodTipSaldos['tip_sal_id']; ?>"><?php echo $ResTodTipSaldos['tip_sal_nombre']; ?></option>
            <?php
			}
			?>
            </select></td>
            <!--POR CUENTA-->
        	<th id="porcue" style="display:none">Cuenta
            <input type="text" name="cuenta" id="cuenta" list="cue" size="50" required onchange="TraerSaldos(this.value);">
            <datalist id="cue">
           <?php while($res_tod_cuentas=mssql_fetch_array($con_tod_cuentas))
				echo "<option value='".$res_tod_cuentas['cue_id']."' label='".$res_tod_cuentas['cue_id']." ".$res_tod_cuentas['cue_nombre']."'>";
        	?>
          </datalist>
          </th>
          <!--POR NIT-->
          <th id="pornit" style="display:none">NIT
            <input type="text" name="nit_id" id="nit_id" list="nit" size="50" required onchange="traecuentas(this.value)">
            <datalist id="nit">
           <?php while($res_nom_nits=mssql_fetch_array($con_nom_nits))
				echo "<option value='".$res_nom_nits['nit_id']."' label='".$res_nom_nits['nits_nombres']." ".$res_nom_nits['nits_apellidos']." - ".$res_nom_nits['nits_num_documento']."'>";
        	?> 
          </datalist>
          </th>
          <th id="cuenits" style="display:none">Cuentas
            <input type="text" name="cue_nit" id="cue_nit" list="cuen_nit" size="50" required onchange="TraerSaldos(this.value);">
            <datalist id="cuen_nit">
            </datalist>
          </th>
          <th id="porcencos" style="display:none">C. Costo
            <input type="text" name="ccosto" id="ccosto" list="centro" size="50" required onchange="TraerSaldos(this.value);">
            <datalist id="centro">
           <?php while($res_tod_cen_costos=mssql_fetch_array($con_tod_cen_costos))
				echo "<option value='".$res_tod_cen_costos['cen_cos_id']."' label='".$res_tod_cen_costos['cen_cos_nombre']."'>";
        	?>
          </datalist>
          </th>
          <th id="porcred" style="display:none">Creditos
            <input type="text" name="creditos" id="creditos" list="credito" size="50" required onchange="TraerSaldos(this.value);">
            <datalist id="credito">
           <?php while($res_tod_creditos=mssql_fetch_array($con_creditos))
				echo "<option value='".$res_tod_creditos['cre_id']."' label='CRE_".$res_tod_creditos['cre_id']."'>";
        	?>
          </datalist>
          </th>
        </tr>
    </table>
    <br>
    <table>
    	<tr><td>Saldo: </td><td><input type="text" name="saldo_anterior" id="saldo_anterior"</td></tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="todoslossaldos">
        <tr><td><b>Mes</b></td><td><b>Debito</b></td><td><b>Credito</b></td><td><b>Saldo</b>
        <input type="hidden" name="elanio" id="elanio" value="<?php echo $_SESSION['elaniocontable']; ?>"/>
        </td></tr>
        <tr class='gradeA' align='center'>
        <?php
		$i=1;
		 while($row=mssql_fetch_array($meses))
		 {
		 	//onblur = 'puntitos(this,this.value.charAt(this.value.length-1),\"llamados/retMoneda.php\");'
			echo "<td><a href='Javascript:void(0);' onClick='Javascript:VentanaEmergente($i);'>".$row['mes_nombre']."</a></td>";
			echo "<td><input type='text' name='debito".$i."' id='debito".$i."' readonly='readonly' /></td>";
			echo "<td><input type='text' name='credito".$i."' id='credito".$i."' readonly='readonly'/></td>";
			echo "<td><input type='text' name='saldo".$i."' id='saldo".$i."' readonly='readonly'/></td></tr>";
			$i++;
		 }
		 
		?>
		<tr><td colspan='4'><input type="button" class="art-button" name="imprimir" id="imprimir" value="Imprimir" onclick="imp_saldo();"></td></tr>
	</table>
	</center>
</form>
</body>
</html>
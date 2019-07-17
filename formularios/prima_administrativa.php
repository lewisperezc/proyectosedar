<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../estilos/alienar_texto.css" media="screen"/>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<script language="javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script language="javascript" src="../librerias/js/separador.js"></script>

<title>Prima de servicios</title>

<script language="javascript" type="application/javascript">

function validar()
{
	var cadena = $("#mes_sele").val();
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
    {
	 	alert("Mes de solo lectura.");
		document.cau_nom_administrativa.mes_sele.focus();
		return false;
	}
	else
	{
		if(document.cau_nom_administrativa.mes_pago.value=="")
		{
			alert('Debe seleccinar el mes de pago');
			document.cau_nom_administrativa.mes_pago.focus();
			return false;
		}
		else
		{
			var mensaje = confirm("Esta seguro que desea causar la nomina?");
			if(mensaje)
			{
				quitarComas();
				document.cau_nom_administrativa.submit();
			}
		}
	}
}

function TipoPagoPrima(tipo)
{
	if(tipo==0)
	{
		$("#primera").css('display','none');
		$("#segunda").css('display','none');
		$("#boton").css('display','none');
		alert('Debe seleccionar una opcion valida');
	}
	else
	{
		if(tipo==1||tipo==2)
		{
			$("#primera").css('display','block');
			$("#boton").css('display','block');
		}
	}
}


function CalcularDatosNomina(tipo_pago,dias_trabajados,nombre_basico,posicion,nombre_total_pagar)
{
	//alert(tipo_pago+'_'+dias_trabajados+'_'+nombre_basico+'_'+nombre_quincena+'_'+nombre_transporte+'_'+aux_transporte+'_'+nombre_salud+'_'+nombre_pension+'_'+nombre_solidaridad+'_'+nombre_val_pagar+'_'+nombre_metas+'_'+posicion);
	var dias_ano=360;
	
	if(tipo_pago==1)//PRIMER SEMESTRE
	{
		var nue_val_prima=($("#"+nombre_basico).val()*$("#"+dias_trabajados).val()/dias_ano)
		$("#"+nombre_total_pagar).val(Math.floor(nue_val_prima));
		$("#"+nombre_total_pagar).val(Math.floor(nue_val_prima));
	}
	if(tipo_pago==2)//SEGUNDO SEMESTRE
	{
		var nue_val_prima=($("#"+nombre_basico).val()*$("#"+dias_trabajados).val()/dias_ano)
		$("#"+nombre_total_pagar).val(Math.floor(nue_val_prima));
	}
}
</script>
</head>
<body alink="#000000" link="#000000" vlink="#000000">
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/moviminetos_contables.class.php');
$ins_nits=new nits();
$ins_mes_contable = new mes_contable();
$ins_mov_contable=new movimientos_contables();
$con_emp_primera=$ins_nits->con_dat_emp_por_tip_estado(2,1,1);
$con_emp_segunda=$ins_nits->con_dat_emp_por_tip_estado(2,1,1);
$con_emp_mensual=$ins_nits->con_dat_emp_por_tip_estado(2,1,2);
$con_dat_nom_administrativa=$ins_nits->con_dat_nom_administrativa();
$res_dat_nom_administrativa=mssql_fetch_array($con_dat_nom_administrativa);
$con_mes=$ins_mes_contable->DatosMesesAniosContables($ano);
$con_mes_de_pago=$ins_mes_contable->DatosMesesAniosContables($ano);
$con_dat_fon_sol_pen_nom_administrativa=$ins_nits->con_dat_fon_sol_pen_nom_administrativa();
$con_per_pago=$ins_nits->con_per_pago();

$sigla='CAU-NOM_ADM_';
$dias_ano=360;
?>
<form method="post" name="cau_nom_administrativa" id="cau_nom_administrativa" action="../control/guardar_prima_administrativa.php">
<center>
	<table border="1">
    	<tr>
            <th colspan="8">PRIMA ADMINISTRATIVA <input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/></th>
        </tr>
        <tr>
			<th>SEMESTRE</th>
			<td>
            <select name="pri_semestre">
            <option value="0" onclick="TipoPagoPrima(this.value)">Seleccione</option>
            <option value="1" onclick="TipoPagoPrima(this.value)">PRIMER SEMESTRE</option>
            <option value="2" onclick="TipoPagoPrima(this.value)">SEGUNDO SEMESTRE</option>
            </select></td>
        	<th>MES CONTABLE</th>
        	<td><select name="mes_sele" id="mes_sele">
       		<?php
			while($dat_meses = mssql_fetch_array($con_mes))
		 	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  		?>
      		</select></td>
            <th>FECHA DE LIQUIDACI&Oacute;N</th>
            <td><input type="text" value="<?php echo date('d-m-Y')?>" name="fec_liquidacion" id="fec_liquidacion" readonly="readonly"/>
            <a href="javascript:NewCal('fec_liquidacion','ddmmyyyy')"><img src="./imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
         </tr>
    </table>
    
    <table id="primera" style="display:none;" border="1">
        <tr>
            <th>DOCUMENTO</th>
            <th>NOMBRES</th>
            <th>DIAS TRAB</th>
            <th>SAL. B&Aacute;SICO</th>
            <th>TOTAL A PAGAR</th>
        </tr>
        <?php
        $pos_pri=0;
        while($res_emp_primera=mssql_fetch_array($con_emp_primera))
        {
        	$con_dia_tra_pri_servicios=$ins_mov_contable->ConsultarDiasTrabajadosPrimaServicios($sigla,$res_emp_primera['nit_id'],10,12,$_SESSION['elaniocontable']);
			$val_dia_trabajados=0;
        	while($res_dia_tra_pri_servicios=mssql_fetch_array($con_dia_tra_pri_servicios))
			{
				$val_dia_trabajados=$val_dia_trabajados+$res_dia_tra_pri_servicios['mov_nume'];
			}
			
			$total_pagar=($res_emp_primera['nits_salario']*$val_dia_trabajados)/$dias_ano;
		?>
	        <tr>
	        	<td><input type="hidden" value="<?php echo $res_emp_primera['nit_id']; ?>" id="pri_emp_id<?php echo $pos_pri; ?>" name="pri_emp_id<?php echo $pos_pri; ?>"/>
	            <input type="hidden" value="<?php echo $res_emp_primera['nits_salario']; ?>" name="pri_emp_salario<?php echo $pos_pri; ?>" id="pri_emp_salario<?php echo $pos_pri; ?>"/>
	        	<input type="text" value="<?php echo $res_emp_primera['nits_num_documento']; ?>" id="pri_emp_documento<?php echo $pos_pri; ?>" name="pri_emp_documento<?php echo $pos_pri; ?>" readonly="readonly" size="12" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_salario<?php echo $pos_pri; ?>',<?php echo $pos_pri; ?>,'pri_emp_tot_pagar<?php echo $pos_pri; ?>');"/></td>
	            <td><input type="text" value="<?php echo $res_emp_primera['nombres']; ?>" id="pri_emp_nombres<?php echo $pos_pri; ?>" name="pri_emp_nombres<?php echo $pos_pri; ?>" size="50" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_salario<?php echo $pos_pri; ?>',<?php echo $pos_pri; ?>,'pri_emp_tot_pagar<?php echo $pos_pri; ?>');"/></td>
	            <td><input type="text" value="<?php echo $val_dia_trabajados; ?>" id="pri_emp_dia_trabajados<?php echo $pos_pri; ?>" name="pri_emp_dia_trabajados<?php echo $pos_pri; ?>" size="3" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_salario<?php echo $pos_pri; ?>',<?php echo $pos_pri; ?>,'pri_emp_tot_pagar<?php echo $pos_pri; ?>');"/></td>
	            <td><input type="text" value="<?php echo $res_emp_primera['nits_salario']; ?>" id="pri_emp_sal_basico<?php echo $pos_pri; ?>" name="pri_emp_sal_basico<?php echo $pos_pri; ?>" readonly="readonly" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_salario<?php echo $pos_pri; ?>',<?php echo $pos_pri; ?>,'pri_emp_tot_pagar<?php echo $pos_pri; ?>');"/></td>
	            <td><input type="text" readonly value="<?php echo round($total_pagar); ?>" id="pri_emp_tot_pagar<?php echo $pos_pri; ?>" name="pri_emp_tot_pagar<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_salario<?php echo $pos_pri; ?>',<?php echo $pos_pri; ?>,'pri_emp_tot_pagar<?php echo $pos_pri; ?>');"/></td>
	        </tr>
        <?php
        $pos_pri++;
		$cantidad_primera=$pos_pri;
		?>
		<input type="hidden" value="<?php echo $cantidad_primera; ?>" id="can_reg_primera" name="can_reg_primera"/>
		<?php
		}
		?>
   </table>
   <table id="boton" style="display:none;width:100%">
   	<tr>
    	<td><input type="button" class="art-button" name="gua_cau_nom_administrativa" value="Causar Nomina" onclick="validar();"/></td>
    </tr>
   </table>
</center>
</form>
</body>
</html>
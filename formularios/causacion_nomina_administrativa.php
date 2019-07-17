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

<title>Nomina administrativa</title>

<script language="javascript" type="application/javascript">
function mostrar(valor)
{
	if(valor==0)
	{
		$("#primera").css("display", "none");
		$("#segunda").css("display","none");
		$("#boton").css("display","none");
		alert('Debe seleccionar una opcion valida');
	}
	if(valor==1)
	{
		$("#primera").css("display", "block");
		$("#segunda").css("display","none");
		$("#boton").css("display","block");
	}
	if(valor==2)
	{
	  $("#primera").css("display","none");
	  $("#segunda").css("display", "block");
	  $("#boton").css("display","block");
	}
}

function validar()
{
	var cadena = $("#mes_sele").val();
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
    {
	 	alert("No se puede ingresar mas datos en este mes!!!");
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

function abreVentana(URL)
{
	day = new Date();
    id = day.getTime();
    //alert(URL);
	eval("page" + id + " = window.open(URL,'"+id+"','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1100,height=300,left = 340,top = 362');");
}
 
function TipoPagoNomina(tipo)
{
	if(tipo==0)
	{
		$("#numero_quincena").css('display','none');
		$("#primera").css('display','none');
		$("#segunda").css('display','none');
		$("#mensual").css('display','none');
		$("#boton").css('display','none');
		alert('Debe seleccionar una opcion valida');
	}
	if(tipo==1)
	{
		$("#numero_quincena").css('display','table');
		$("#mensual").css('display','none');
		$("#boton").css('display','none');
	}
	else
	{
		if(tipo==2)
		{
			$("#numero_quincena").css('display','none');
			$("#primera").css('display','none');
			$("#segunda").css('display','none');
			$("#mensual").css('display','table');
			$("#boton").css('display','table');
		}
	}
}


function CalcularDatosNomina(tipo_pago,dias_trabajados,nombre_basico,nombre_quincena,nombre_transporte,aux_transporte,nombre_salud,nombre_pension,nombre_solidaridad,nombre_val_pagar,nombre_metas,posicion)
{
	
	//alert(tipo_pago+'_'+dias_trabajados+'_'+nombre_basico+'_'+nombre_quincena+'_'+nombre_transporte+'_'+aux_transporte+'_'+nombre_salud+'_'+nombre_pension+'_'+nombre_solidaridad+'_'+nombre_val_pagar+'_'+nombre_metas+'_'+posicion);
	var dias_mes=30;
	
	if(tipo_pago==1)//PRIMERA QUINCENA
	{
		var val_dia_trabajado=$("#"+nombre_basico).val()/dias_mes;
		var val_nue_quincena=val_dia_trabajado*$("#"+dias_trabajados).val();
		
		var val_dia_aux_transporte=aux_transporte/dias_mes;
		var val_nue_aux_transporte=val_dia_aux_transporte*$("#"+dias_trabajados).val();
		
		$("#"+nombre_quincena).val(Math.floor(val_nue_quincena));
		$("#"+nombre_transporte).val(Math.floor(val_nue_aux_transporte));
		var ingresos=parseFloat($("#"+nombre_quincena).val())+parseFloat($("#"+nombre_transporte).val());
		var deducciones=parseFloat($("#"+nombre_salud).val())+parseFloat($("#"+nombre_pension).val())+parseFloat($("#"+nombre_solidaridad).val());
		$("#"+nombre_val_pagar).val(Math.floor(ingresos-deducciones));
	}
	
	if(tipo_pago==2)//SEGUNDA QUINCENA
	{
	    //alert('entra');
		//alert($("#"+nombre_quincena).val());
		//alert($("#"+nombre_basico).val());
		var val_dia_trabajado=$("#"+nombre_basico).val()/dias_mes;
		var val_nue_quincena=val_dia_trabajado*$("#"+dias_trabajados).val();
		
		var val_dia_aux_transporte=aux_transporte/dias_mes;
		var val_nue_aux_transporte=val_dia_aux_transporte*$("#"+dias_trabajados).val();
		
		$("#"+nombre_quincena).val(Math.floor(val_nue_quincena));
		
		if(val_nue_quincena==0)
		  $("#"+nombre_transporte).val(0);
		else
		  $("#"+nombre_transporte).val(Math.floor(val_nue_aux_transporte));
		 
		var ingresos=parseFloat($("#"+nombre_quincena).val())+parseFloat($("#"+nombre_transporte).val())+parseFloat($("#"+nombre_metas).val());
		//alert(ingresos);
		//var deducciones=parseFloat($("#"+nombre_salud).val())+parseFloat($("#"+nombre_pension).val())+parseFloat($("#"+nombre_solidaridad).val());
		$("#"+nombre_val_pagar).val(Math.floor(ingresos));
	}
	
	if(tipo_pago==3)//PAGO MENSUAL
	{
		var val_dia_trabajado=$("#"+nombre_quincena).val()/dias_mes;
		var val_nue_quincena=val_dia_trabajado*$("#"+dias_trabajados).val();
		
		var val_dia_aux_transporte=aux_transporte/dias_mes;
		var val_nue_aux_transporte=val_dia_aux_transporte*$("#"+dias_trabajados).val();
		
		$("#"+nombre_basico).val(Math.floor(val_nue_quincena));
		$("#"+nombre_transporte).val(Math.floor(val_nue_aux_transporte));
		
		var ingresos=parseFloat($("#"+nombre_basico).val())+parseFloat($("#"+nombre_transporte).val())+parseFloat($("#"+nombre_metas).val());
		var deducciones=parseFloat($("#"+nombre_salud).val())+parseFloat($("#"+nombre_pension).val())+parseFloat($("#"+nombre_solidaridad).val());
		$("#"+nombre_val_pagar).val(Math.floor(ingresos-deducciones));
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
$ins_nits=new nits();
$ins_mes_contable = new mes_contable();
$con_emp_primera=$ins_nits->con_dat_emp_por_tip_estado(2,1,1);
$con_emp_segunda=$ins_nits->con_dat_emp_por_tip_estado(2,1,1);
$con_emp_mensual=$ins_nits->con_dat_emp_por_tip_estado(2,1,2);
$con_dat_nom_administrativa=$ins_nits->con_dat_nom_administrativa();
$res_dat_nom_administrativa=mssql_fetch_array($con_dat_nom_administrativa);
$con_mes=$ins_mes_contable->DatosMesesAniosContables($ano);
$con_mes_de_pago=$ins_mes_contable->DatosMesesAniosContables($ano);
$con_dat_fon_sol_pen_nom_administrativa=$ins_nits->con_dat_fon_sol_pen_nom_administrativa();
$con_per_pago=$ins_nits->con_per_pago();
?>
<form method="post" name="cau_nom_administrativa" id="cau_nom_administrativa" action="../control/guardar_causacion_nomina_administrativa.php">
<center>
	<table border="1">
    	<tr>
            <th colspan="7">CAUSACI&Oacute;N DE N&Oacute;MINA <input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/></th>
        </tr>
        <tr>
			<th colspan="7">TIPO DE PAGO
            <select name="per_pag_nomina"><option value="0" onclick="TipoPagoNomina(this.value)">Seleccione</option>
            <?php
            while($res_per_pago=mssql_fetch_array($con_per_pago))
			{
			?>
				<option value="<?php echo $res_per_pago['per_pag_nit_id']; ?>" onclick="TipoPagoNomina(this.value)"><?php echo $res_per_pago['per_pag_nit_nombre']; ?></option>
			<?php	
			}
            ?>
            </select></th>
        </tr>
    	<tr>
        	<th>MES CONTABLE</th>
        	<td><select name="mes_sele" id="mes_sele">
       		<?php
			while($dat_meses = mssql_fetch_array($con_mes))
		 	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  		?>
      		</select></td>
            <th>MES DE PAGO</th>
            <td><select name="mes_pago" id="mes_pago"><option value="">--</option>
            <?php
			while($res_mes_de_pago=mssql_fetch_array($con_mes_de_pago))
		 	echo "<option value='".$res_mes_de_pago['mes_id']."'>".$res_mes_de_pago['mes_id']."</option>";
	  		?>
            </select></td>
            <th>FECHA DE LIQUIDACI&Oacute;N</th>
            <td><input type="text" value="<?php echo date('d-m-Y')?>" name="fec_liquidacion" id="fec_liquidacion" readonly="readonly"/>
            <a href="javascript:NewCal('fec_liquidacion','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
         </tr>
         <tr id="numero_quincena" style="display:none;">
        	<th colspan="7">QUINCENA N&deg;
            <select name="num_quincena"><option value="0" onclick="mostrar(this.value)">Seleccione</option>
            		    <option value="1" onclick="mostrar(this.value)">1</option>
                        <option value="2" onclick="mostrar(this.value)">2</option>
            </select></th>
        </tr>
    </table>
    <table width="100%">
    	<tr><td><hr /></td></tr>
    </table>
    <table id="primera" style="display:none;" border="1">
        <tr>
            <th>DOCUMENTO</th>
            <th>NOMBRES</th>
            <th>DIAS TRAB</th>
            <th>SAL. B&Aacute;SICO</th>
            <th>VAL. QUINCENA</th>
            <th>AUX. TRANSPORTE</th>
            <th>DES. SALUD</th>
            <th>DES. PENSI&Oacute;N</th>
            <th>DES. FDO. SOL PENSIONAL</th>
            <th>TOTAL A PAGAR</th>
            <!--<th>Pagar</th>-->
            <th>NOVEDAD</th>
            <!--<th>Pagar Todos<input type="checkbox" name="seltodos" id="seltodos" onclick="OpcSeleccionada(1);"/></th>-->
        </tr>
        <?php
        $pos_pri=0;
        while($res_emp_primera=mssql_fetch_array($con_emp_primera))
        {
			$con_dat_fon_sol_pen_nom_administrativa=$ins_nits->con_dat_fon_sol_pen_nom_administrativa();
		
			$quincena=$res_emp_primera['nits_salario']/2;
			$minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
			$salud=($res_emp_primera['nits_salario']*$res_dat_nom_administrativa['dat_nom_salud'])/100;
			$pension=($res_emp_primera['nits_salario']*$res_dat_nom_administrativa['dat_nom_pension'])/100;
		
			if($res_emp_primera['nits_salario']<=($minimo*2)&&$res_emp_primera['nits_salario']>0)
				$aux_transporte=$res_dat_nom_administrativa['dat_nom_aux_transporte']/2;
			else
				$aux_transporte=0;

			
			while($res_dat_fon_sol_pen_nom_administrativa=mssql_fetch_array($con_dat_fon_sol_pen_nom_administrativa))
			{
				if($res_emp_primera['nits_salario']>=($minimo*4))
				{
					if($res_emp_primera['nits_salario']>=($minimo*$res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_desde'])&&$res_emp_primera['nits_salario']<($minimo*$res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_hasta']))
					{
						$val_fon_sol_pensional=$res_emp_primera['nits_salario']*($res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_porcentaje']/100);	
					}
				}
				else
					$val_fon_sol_pensional=0;
			}
			
			$tot_pagar=round($quincena+$aux_transporte)-($salud+$pension+$val_fon_sol_pensional);
		?>
	        <tr>
	        	<td><input type="hidden" value="<?php echo $res_emp_primera['nit_id']; ?>" id="pri_emp_id<?php echo $pos_pri; ?>" name="pri_emp_id<?php echo $pos_pri; ?>"/>
	            <input type="hidden" value="<?php echo $res_emp_primera['nits_salario']; ?>" name="pri_emp_salario<?php echo $pos_pri; ?>" id="pri_emp_salario<?php echo $pos_pri; ?>"/>
	        	<input type="text" value="<?php echo $res_emp_primera['nits_num_documento']; ?>" id="pri_emp_documento<?php echo $pos_pri; ?>" name="pri_emp_documento<?php echo $pos_pri; ?>" readonly="readonly" size="12" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $res_emp_primera['nombres']; ?>" id="pri_emp_nombres<?php echo $pos_pri; ?>" name="pri_emp_nombres<?php echo $pos_pri; ?>" size="50" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="15" id="pri_emp_dia_trabajados<?php echo $pos_pri; ?>" name="pri_emp_dia_trabajados<?php echo $pos_pri; ?>" size="3" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $res_emp_primera['nits_salario']; ?>" id="pri_emp_sal_basico<?php echo $pos_pri; ?>" name="pri_emp_sal_basico<?php echo $pos_pri; ?>" readonly="readonly" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $quincena; ?>" id="pri_emp_quincena<?php echo $pos_pri; ?>" name="pri_emp_quincena<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $aux_transporte; ?>" id="pri_emp_aux_transporte<?php echo $pos_pri; ?>" name="pri_emp_aux_transporte<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $salud; ?>" id="pri_emp_salud<?php echo $pos_pri; ?>" name="pri_emp_salud<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><input type="text" value="<?php echo $pension; ?>" id="pri_emp_pension<?php echo $pos_pri; ?>" name="pri_emp_pension<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <th><input type="text" value="<?php echo $val_fon_sol_pensional; ?>" id="pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>" name="pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></th>
	            <td><input type="text" value="<?php echo $tot_pagar; ?>" id="pri_emp_tot_pagar<?php echo $pos_pri; ?>" name="pri_emp_tot_pagar<?php echo $pos_pri; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(1,'pri_emp_dia_trabajados<?php echo $pos_pri; ?>','pri_emp_sal_basico<?php echo $pos_pri; ?>','pri_emp_quincena<?php echo $pos_pri; ?>','pri_emp_aux_transporte<?php echo $pos_pri; ?>',<?php echo ($aux_transporte*2); ?>,'pri_emp_salud<?php echo $pos_pri; ?>','pri_emp_pension<?php echo $pos_pri; ?>','pri_emp_fon_sol_pensional<?php echo $pos_pri; ?>','pri_emp_tot_pagar<?php echo $pos_pri; ?>','NA',<?PHP echo $pos_pri; ?>);"/></td>
	            <td><a href="Javascript:void(0);" onclick="abreVentana('novedades_administrativas.php?nombres=<?php echo $res_emp_primera['nits_num_documento']." - ".$res_emp_primera['nombres']; ?>&nit_id=<?php echo $res_emp_primera['nit_id']; ?>');" title="Registrar Novedad">Registrar Novedad</a></td>
	            <!--<td><input type="checkbox" name="pri_apagar[]" id="pri_apagar[]" value="<?php //echo $res_emp_segunda['nit_id']; ?>"/></td>-->
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
   <table id="segunda" style="display:none;" border="1">
        <tr>
        	<th>DOCUMENTO</th>
            <th>NOMBRES</th>
            <th>DIAS TRAB</th>
            <th>SAL. B&Aacute;SICO</th>
            <th>VAL. QUINCENA</th>
            <th>PRIMA EXTRALEGAL</th>
            <th>AUX. TRANSPORTE</th>
            <th>DES. CR&Eacute;DITOS</th>
            <th>OTROS DESCUENTOS</th>
            <th>TOTAL A PAGAR</th>
            <th>NOVEDAD</th>
            <!--</h1><th>Pagar Todos<input type="checkbox" name="seltodos" id="seltodos" onclick="OpcSeleccionada(2);"/></th>-->
        </tr>
        <?php
        $i=0;
		$pos_seg=0;
        while($res_emp_segunda=mssql_fetch_array($con_emp_segunda))
        {
		$quincena=($res_emp_segunda['nits_salario']/2);
		$bonificacion=($res_emp_segunda['nit_bonificacion']*$res_dat_nom_administrativa['dat_nom_por_metas'])/100;
		$minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
		if($res_emp_segunda['nits_salario']<=($minimo*2)&&$res_emp_segunda['nits_salario']>0)
			$aux_transporte=$res_dat_nom_administrativa['dat_nom_aux_transporte']/2;
		else
			$aux_transporte=0;
		
		$tot_pagar=$quincena+$bonificacion+$aux_transporte;
		?>
        <tr>
        	<td><input type="hidden" value="<?php echo $res_emp_segunda['nit_id']; ?>" name="seg_emp_id<?PHP echo $pos_seg; ?>" id="seg_emp_id<?PHP echo $pos_seg; ?>"/>
            <input type="hidden" value="<?php echo $res_emp_segunda['nits_salario']; ?>" name="seg_emp_salario<?PHP echo $pos_seg; ?>" id="seg_emp_salario<?PHP echo $pos_seg; ?>"/>
			<input type="text" value="<?php echo $res_emp_segunda['nits_num_documento']; ?>" id="seg_emp_documento<?PHP echo $pos_seg; ?>" name="seg_emp_documento<?PHP echo $pos_seg; ?>" readonly="readonly" size="12"/></td>
            <td><input type="text" value="<?php echo $res_emp_segunda['nombres']; ?>" id="seg_emp_nombres<?PHP echo $pos_seg; ?>" name="seg_emp_nombres<?PHP echo $pos_seg; ?>" size="50" /></td>
            <td><input type="text" value="15" id="seg_emp_dia_trabajados<?PHP echo $pos_seg; ?>" name="seg_emp_dia_trabajados<?PHP echo $pos_seg; ?>" size="3" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/></td>
            <td><input type="text" value="<?php echo $res_emp_segunda['nits_salario']; ?>" id="seg_emp_sal_basico<?PHP echo $pos_seg; ?>" name="seg_emp_sal_basico<?PHP echo $pos_seg; ?>" readonly="readonly" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/></td>
            <td><input type="text" value="<?php echo $quincena; ?>" id="seg_emp_quincena<?PHP echo $pos_seg; ?>" name="seg_emp_quincena<?PHP echo $pos_seg; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/></td>
            <td><input type="text" value="<?php echo $bonificacion; ?>" id="seg_emp_bonificacion<?PHP echo $pos_seg; ?>" name="seg_emp_bonificacion<?PHP echo $pos_seg; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/></td>
			<td><input type="text" value="<?php echo $aux_transporte; ?>" id="seg_emp_aux_transporte<?PHP echo $pos_seg; ?>" name="seg_emp_aux_transporte<?PHP echo $pos_seg; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/></td>
            <td><input type="radio" name="creditos<?php echo $pos_seg; ?>" id="creditos<?php echo $pos_seg; ?>" value="<?php echo $res_emp_segunda['nit_id']; ?>" onclick="abreVentana('desc_credito.php?desc=<?php echo $res_emp_segunda['nit_id']; ?>&fac=0&recibo=0')" /></td>
            <td><input type="radio" name="otr_descuentos<?php echo $pos_seg; ?>" id="otr_descuentos<?php echo $pos_seg; ?>" value="<?php echo $res_emp_segunda['nit_id']; ?>" onclick="abreVentana('otros_descuentos_nomina_administrativa.php?desc=<?php echo $res_emp_segunda['nit_id']; ?>&fac=0&recibo=0')" /></td>
            <td><input type="text" value="<?php echo $tot_pagar; ?>" id="seg_emp_tot_pagar<?PHP echo $pos_seg; ?>" name="seg_emp_tot_pagar<?PHP echo $pos_seg; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(2,'seg_emp_dia_trabajados<?php echo $pos_seg; ?>','seg_emp_sal_basico<?php echo $pos_seg; ?>','seg_emp_quincena<?php echo $pos_seg; ?>','seg_emp_aux_transporte<?php echo $pos_seg; ?>',<?php echo ($aux_transporte*2); ?>,'seg_emp_salud<?php echo $pos_seg; ?>','seg_emp_pension<?php echo $pos_seg; ?>','seg_emp_fon_sol_pensional<?php echo $pos_seg; ?>','seg_emp_tot_pagar<?php echo $pos_seg; ?>','seg_emp_bonificacion<?php echo $pos_seg; ?>',<?PHP echo $pos_seg; ?>);"/>
            <?php
            $lasmetasprovision=$res_emp_segunda['nit_bonificacion']-$bonificacion;
			?>
            <input type="hidden" name="lasmetasprovision[]" id="lasmetasprovision[]" value="<?php echo $lasmetasprovision; ?>" class="numeros_alineados"/>
            </td>
            <td><a href="Javascript:void(0);" onclick="abreVentana('novedades_administrativas.php?nombres=<?php echo $res_emp_segunda['nits_num_documento']." - ".$res_emp_segunda['nombres']; ?>&nit_id=<?php echo $res_emp_segunda['nit_id']; ?>');" title="Registrar Novedad">Registrar Novedad</a></td>
            <!--<td><input type="checkbox" name="seg_apagar[]" id="seg_apagar[]" value="<?php //echo $res_emp_segunda['nit_id']; ?>"/></td>-->
        </tr>
        <?php
        $pos_seg++;
        $i++;
		$cantidad_segunda=$pos_seg;
		?>
		<input type="hidden" value="<?php echo $cantidad_segunda; ?>" id="can_reg_segunda" name="can_reg_segunda"/>
		<?php
		}
		?>
   </table>
   
   <!--PAGO MENSUAL-->
   <table id="mensual" style="display:none;" border="1">
        <tr>
            <th>DOCUMENTO</th>
            <th>NOMBRES</th>
            <th>DIAS TRAB</th>
            <th>SAL. B&Aacute;SICO</th>
            <th>PRIMA EXTRALEGAL</th>
            <th>AUX. TRANSPORTE</th>
            <th>DES. SALUD</th>
            <th>DES. PENSI&Oacute;N</th>
            <th>DES. FDO. SOL PENSIONAL</th>
            <th>DES. CR&Eacute;DITOS</th>
            <th>OTROS DESCUENTOS</th>
            <th>TOTAL A PAGAR</th>
            <!--<th>Pagar</th>-->
            <th>NOVEDAD</th>
            <!--<th>Pagar Todos<input type="checkbox" name="seltodos" id="seltodos" onclick="OpcSeleccionada(1);"/></th>-->
        </tr>
        <?php
        //$res_emp_primera
        $pos_men=0;
        while($res_emp_mensual=mssql_fetch_array($con_emp_mensual))
        {
			
		$con_dat_fon_sol_pen_nom_administrativa=$ins_nits->con_dat_fon_sol_pen_nom_administrativa();
		
		$quincena=$res_emp_mensual['nits_salario']/2;
		$bonificacion=($res_emp_mensual['nit_bonificacion']*$res_dat_nom_administrativa['dat_nom_por_metas'])/100;
		$minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
		$salud=($res_emp_mensual['nits_salario']*$res_dat_nom_administrativa['dat_nom_salud'])/100;
		$pension=($res_emp_mensual['nits_salario']*$res_dat_nom_administrativa['dat_nom_pension'])/100;
		
		if($res_emp_mensual['nits_salario']<=($minimo*2)&&$res_emp_mensual['nits_salario']>0)
			$aux_transporte=$res_dat_nom_administrativa['dat_nom_aux_transporte'];
		else
			$aux_transporte=0;

			
		while($res_dat_fon_sol_pen_nom_administrativa=mssql_fetch_array($con_dat_fon_sol_pen_nom_administrativa))
		{
			if($res_emp_mensual['nits_salario']>=($minimo*4))
			{
				if($res_emp_mensual['nits_salario']>=($minimo*$res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_desde'])&&$res_emp_mensual['nits_salario']<($minimo*$res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_hasta']))
				{
					$val_fon_sol_pensional=$res_emp_mensual['nits_salario']*($res_dat_fon_sol_pen_nom_administrativa['dat_sol_pen_nom_adm_porcentaje']/100);	
				}
			}
			else
				$val_fon_sol_pensional=0;
		}
			
		$tot_pagar=($res_emp_mensual['nits_salario']+$aux_transporte+$bonificacion)-($salud+$pension+$val_fon_sol_pensional);
		?>
        <tr>
        	<td><input type="hidden" value="<?php echo $res_emp_mensual['nit_id']; ?>" name="men_emp_id<?PHP echo $pos_men; ?>" id="men_emp_id<?PHP echo $pos_men; ?>"/>
            <input type="hidden" value="<?php echo $res_emp_mensual['nits_salario']; ?>" name="men_emp_salario<?PHP echo $pos_men; ?>" id="men_emp_salario<?PHP echo $pos_men; ?>"/>
        	<input type="text" value="<?php echo $res_emp_mensual['nits_num_documento']; ?>" name="men_emp_documento<?PHP echo $pos_men; ?>" id="men_emp_documento<?PHP echo $pos_men; ?>" readonly="readonly" size="12"/></td>
            <td><input type="text" value="<?php echo $res_emp_mensual['nombres']; ?>" name="men_emp_nombres<?PHP echo $pos_men; ?>" id="men_emp_nombres<?PHP echo $pos_men; ?>" size="50"/></td>
            <td><input type="text" value="30" name="men_emp_dia_trabajados<?PHP echo $pos_men; ?>" id="men_emp_dia_trabajados<?PHP echo $pos_men; ?>" size="3" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><input type="text" value="<?php echo $res_emp_mensual['nits_salario']; ?>" id="men_emp_sal_basico<?PHP echo $pos_men; ?>" name="men_emp_sal_basico<?PHP echo $pos_men; ?>" readonly="readonly" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><input type="text" value="<?php echo $bonificacion; ?>" id="men_emp_bonificacion<?PHP echo $pos_men; ?>" name="men_emp_bonificacion<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><input type="text" value="<?php echo $aux_transporte; ?>" id="men_emp_aux_transporte<?PHP echo $pos_men; ?>" name="men_emp_aux_transporte<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><input type="text" value="<?php echo $salud; ?>" id="men_emp_salud<?PHP echo $pos_men; ?>" name="men_emp_salud<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><input type="text" value="<?php echo $pension; ?>" id="men_emp_pension<?PHP echo $pos_men; ?>" name="men_emp_pension<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <th><input type="text" value="<?php echo $val_fon_sol_pensional; ?>" id="men_emp_fon_sol_pensional<?PHP echo $pos_men; ?>" name="men_emp_fon_sol_pensional<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></th>
            <td><input type="radio" name="creditos<?php echo $i; ?>" id="creditos<?php echo $i; ?>" value="<?php echo $res_emp_mensual['nit_id']; ?>" onclick="abreVentana('desc_credito.php?desc=<?php echo $res_emp_mensual['nit_id']; ?>&fac=0&recibo=0')" /></td>
            <td><input type="radio" name="otr_descuentos<?php echo $pos_men; ?>" id="otr_descuentos<?php echo $pos_men; ?>" value="<?php echo $res_emp_mensual['nit_id']; ?>" onclick="abreVentana('otros_descuentos_nomina_administrativa.php?desc=<?php echo $res_emp_mensual['nit_id']; ?>&fac=0&recibo=0')" /></td>
            <td><input type="text" value="<?php echo $tot_pagar; ?>" id="men_emp_tot_pagar<?PHP echo $pos_men; ?>" name="men_emp_tot_pagar<?PHP echo $pos_men; ?>" size="15" class="numeros_alineados" onchange="CalcularDatosNomina(3,'men_emp_dia_trabajados<?php echo $pos_men; ?>','men_emp_sal_basico<?php echo $pos_men; ?>','men_emp_salario<?php echo $pos_men; ?>','men_emp_aux_transporte<?php echo $pos_men; ?>',<?php echo $aux_transporte; ?>,'men_emp_salud<?php echo $pos_men; ?>','men_emp_pension<?php echo $pos_men; ?>','men_emp_fon_sol_pensional<?php echo $pos_men; ?>','men_emp_tot_pagar<?php echo $pos_men; ?>','men_emp_bonificacion<?php echo $pos_men; ?>',<?PHP echo $pos_men; ?>);"/></td>
            <td><a href="Javascript:void(0);" onclick="abreVentana('novedades_administrativas.php?nombres=<?php echo $res_emp_mensual['nits_num_documento']." - ".$res_emp_mensual['nombres']; ?>&nit_id=<?php echo $res_emp_mensual['nit_id']; ?>');" title="Registrar Novedad">Registrar Novedad</a></td>
            <!--<td><input type="checkbox" name="pri_apagar[]" id="pri_apagar[]" value="<?php //echo $res_emp_segunda['nit_id']; ?>"/></td>-->
            <?php
            $lasmetasprovision=$res_emp_mensual['nit_bonificacion']-$bonificacion;
			?>
            <input type="hidden" name="lasmetasprovision[]" id="lasmetasprovision[]" value="<?php echo $lasmetasprovision; ?>" class="numeros_alineados"/>
        </tr>
        <?php
        $pos_men++;
		$cantidad_mensual=$pos_men;
		?>
		<input type="hidden" value="<?php echo $cantidad_mensual; ?>" id="can_reg_mensual" name="can_reg_mensual"/>
		<?php
		}
		?>
   </table>
   <!--FIN PAGO MENSUAL-->
   
   <table id="boton" style="display:none;width:100%">
   	<tr>
    	<td><input type="button" class="art-button" name="gua_cau_nom_administrativa" value="Causar Nomina" onclick="validar();"/></td>
    </tr>
   </table>
</center>
</form>
</body>
</html>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
//$id=$_POST['agregar'];
$id=3;
$_SESSION['otr_adi']=$id;
$ano=$_SESSION['elaniocontable'];
@include_once('../clases/contrato.class.php');
@include_once('clases/contrato.class.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="../estilos/limpiador.css" media="all">
<link rel="stylesheet" href="estilos/limpiador.css" media="all">
<title>Documento sin t&iacute;tulo</title>
<script language="javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/separador.js"></script>
<script src="librerias/js/separador.js"></script>
<script language="javascript">
function probando(val)
{
        //alert(val);
	var contrato=$("#con_sele").val();
        //alert($("#con_sele").val());
	if(contrato==''||val=='')
        {
		$("#dinero").css("display","none");
		$("#tiempo").css("display", "none");
		$("#nota").css("display", "none");
		$("#boton").css("display", "none");
		alert('Debe Seleccionar Los Dos(2) ITEMS');
	}
	else{
		if(val==6){//Dinero
			$("#dinero").css("display","block");
			$("#nota").css("display", "block");
			$("#boton").css("display", "block");
			$("#tiempo").css("display", "none");
			$("#poliza").css("display", "block");
			$("#agritem").css("display", "block");
		}
		else{
			if(val==7){//Tiempo
			  $("#tiempo").css("display", "block");
			  $("#nota").css("display", "block");
			  $("#boton").css("display", "block");
			  $("#dinero").css("display","none");
			  $("#poliza").css("display", "block");
			  $("#agritem").css("display", "block");
			}
			else{
				if(val==8){
				  $("#tiempo").css("display", "block");
				  $("#dinero").css("display","block");
				  $("#nota").css("display", "block");
				  $("#boton").css("display", "block");
				  $("#poliza").css("display", "block");
				  $("#agritem").css("display", "block");
				  
				}
				else{
					if(val==9){
					  $("#nota").css("display", "block");
					  $("#boton").css("display", "block");
					  $("#dinero").css("display","none");
					  $("#tiempo").css("display", "none");
					  $("#poliza").css("display", "none");
		              $("#agritem").css("display", "none");
					}			
				}
			}
		}
                ObtDatContrato();
	}
}

//OBTIENE LOS DATOS DEL CONTRATO
function ObtDatContrato()
{
    var elcontrato=$("#con_sele").val();
    $.ajax({
     type:'POST',
     url:"llamados/datos_contrato_por_id.php",
     data:"elidcon="+elcontrato,
     success:function(msg){
      $("#dat_con_sel").html(msg);
     }
    });
}
//INICION FUNCION AGREGAR ITEM
function agregar() {
	var pos = $("#poliza > tr").length+1;
	<?php
	$ins_contrato=new contrato();
	$con_tod_aseguradoras=$ins_contrato->con_tip_nit(9);
	$con_poliza=$ins_contrato->con_tip_concepto(122);
	?>
	campo='<tr><th>Aseguradora</th><td><select name="aseg'+pos+'" id="aseg'+pos+'""><option value="NULL">Seleccione</option>';
	<?php
	while($res_tod_aseguradoras=mssql_fetch_array($con_tod_aseguradoras))
	{ ?>
	campo+='<option value="<?php echo $res_tod_aseguradoras['nit_id']; ?>"><?php echo substr($res_tod_aseguradoras['nits_nombres'],0,25); ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td>';
	campo+='<th>Poliza</th><td><select name="polimp'+pos+'" id="polimp'+pos+'"><option value="NULL">Seleccione</option>';
	<?php
	while($res_poliza=mssql_fetch_array($con_poliza))
	{ ?>
	campo+='<option value="<?php echo $res_poliza['con_id']; ?>"><?php echo substr($res_poliza['con_nombre'],0,25); ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td><th>Valor</th><td><input type="text" name="valpolimp'+pos+'" id="valpolimp'+pos+'" onkeypress="mascara(this,cpf);"  onpaste="return false" /></td>';
        campo+='<td>Tipo</td><td><select name="tip_pol_impuesto'+pos+'" id="tip_pol_impuesto'+pos+'"><option value="">--</option><option value="1">DESCONTABLE</option>';
        campo+='<option value="2">INFORMATIVO</option></select></td>';
        campo+='<td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto'+pos+'" id="obs_pol_impuesto'+pos+'"/></td></tr>';
	$("#poliza").append(campo);
	$("#cuantos").val(pos);
}
//FIN FUNCION AGREGAR ITEM
function enviar(){
	var form = document.adi_otr;
	form.action='./control/guardar_otrosi_adicion.php';
	form.submit();
}
</script>
</head>
<body>
<?php
$ins_contrato = new contrato();
$con_tipos=$ins_contrato->con_tip_adi_otrosi($id);
//$con_tipos=$ins_contrato->con_tip_adi_otrosi(3);
$con_con_pre_ser_ane_activo=$ins_contrato->con_con_pre_ser_ane_activo(2,2,1);
$con_tod_aseguradoras=$ins_contrato->con_tip_nit(9);
$con_poliza=$ins_contrato->con_tip_concepto(122);
?>
<form method="post" name="adi_otr" id="adi_otr" action="./control/guardar_otrosi_adicion.php">
<center>
     <table>
    <tr style="display:block">
        <th>Contrato</th>
        <td><select name="con_sele" id="con_sele" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--Seleccione--</option>
            <?php while($res_con_pre_ser_ane_activo = mssql_fetch_array($con_con_pre_ser_ane_activo)){ ?>
            <option value="<?php echo $res_con_pre_ser_ane_activo['con_id']; ?>"><?php echo $res_con_pre_ser_ane_activo['nombres']; ?></option>
            <?php } ?>
            </select>
        </td>
        <th>Agregar</th>
        <td><select id="agr" name="agr" required x-moz-errormessage="Seleccione Una Opcion Valida">
                    <option value="">--Seleccione--</option>
                    <?php while($res_tipos = mssql_fetch_array($con_tipos)){ ?>
                    <option value="<?php echo $res_tipos['tip_adi_otr_id']; ?>" onclick="probando(this.value);"><?php echo $res_tipos['tip_adi_nombre']; ?></option>
                    <?php } ?>
                    </select>
        </td>
    </tr>
    </table>
    <table>
        <tr id="dinero" style="display: none;">
            <th>Dinero:</th><td><input type="text" name="dinero" onkeypress="mascara(this,cpf);"  onpaste="return false" onkeypress="return permite(event,'num')"/></td>
        </tr>
        <tr id="tiempo" style="display: none;">
            <th>N&deg; Meses</th><td><input type="text" name="meses" onKeyPress="return permite(event,'num')"/></td>
            <th>Fecha inicio</th>
            <td><input type="text" name="fec_inicio" id="fec_inicio"/>
            <a href="javascript:NewCal('fec_inicio','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
            <th>Fecha fin</th>
            <td><input type="text" name="fec_fin" id="fec_fin"/>
            <a href="javascript:NewCal('fec_fin','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
             </td>
        </tr>
        <tr id="nota" style="display: none;">
            <th>Nota:</th>
            <td><textarea cols="90" rows="1" name="nota" required="required"></textarea></td>
        </tr>
        <tr id="poliza" style="display: none;">
            <th>Aseguradora</th>
            <td><select name="aseg0" id="aseg0">
            <option value="">--Seleccione--</option>
            <?php while($res_tod_aseguradoras=mssql_fetch_array($con_tod_aseguradoras)){ ?>
    <option value="<?php echo $res_tod_aseguradoras['nit_id']; ?>"><?php echo substr($res_tod_aseguradoras['nits_nombres'],0,25); ?></option>
            <?php } ?>
            </select>
            </td>
            <th>Poliza</th>
            <td><select name="polimp0" id="polimp0">
            <option value="">--Seleccione--</option>
            <?php while($res_poliza=mssql_fetch_array($con_poliza)){ ?>
                    <option value="<?php echo $res_poliza['con_id']; ?>"><?php echo substr($res_poliza['con_nombre'],0,25); ?></option>
            <?php } ?>
            </select></td>
            <th>Valor</th>
            <td><input size="50" type="text" name="valpolimp0" id="valpolimp0" onkeypress="mascara(this,cpf);"  onpaste="return false"/></td>
            <td>Tipo</td>
            <td><select name="tip_pol_impuesto0" id="tip_pol_impuesto0">
            <option value="">--</option>
            <option value="1">DESCONTABLE</option>
            <option value="2">INFORMATIVO</option>
            </select>
            </td>
            <td>Observaci&oacute;n</td><td><input size="50" type="text" name="obs_pol_impuesto0" id="obs_pol_impuesto0"/></td>
        </tr>
        <tr id="agritem" style="display:none;">
            <input type="hidden" name="cuantos" id="cuantos" value="0" />
            <th colspan="4"><a href="#" onclick="agregar();">Agregar</a></th>
        </tr>
        <tr id="boton" style="display:none">
           <td colspan="4"><input type="submit" class="art-button" name="gua_datos" id="gua_datos" value="Guardar"/></td>
        </tr>
    </table>
    
    <!--INICIO DATOS DEL CONTRATO SELECCIONADO!-->
    <table id="dat_con_sel">
    </table>
    <!--FIN DATOS DEL CONTRATO SELECCIONADO!-->
</center>   
</form>
</body>
</html>
<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

	@include_once('conexion/conexion.php');
	@include_once('clases/moviminetos_contables.class.php');
	@include_once('clases/cuenta.class.php');
	@include_once('clases/reporte_jornadas.class.php');
	@include_once('clases/concepto.class.php');
	@include_once('clases/centro_de_costos.class.php');
	@include_once('clases/contrato.class.php');
	@include_once('clases/factura.class.php');
	@include_once('librerias/php/funciones.php');
	@include_once('clases/nits.class.php');
	@include_once('clases/presupuesto.class.php');
	@include_once('clases/mes_contable.class.php');
	@include_once('../clases/nits_tipo.class.php');
	$concepto = new concepto();
	$presupuesto = new presupuesto();
	$no_trae="108,109";
	$con_factura = $concepto->conceptos(118,$no_trae);
	$anos = $presupuesto->obtener_lista_anios();
	$centro = new centro_de_costos();
	$ins_mes = new mes_contable();
	$tip_nit = new tipo_nit();
	$meses = $ins_mes->DatosMesesAniosContables($ano);
	$meses_fac_admin=$ins_mes->DatosMesesAniosContables($ano);
?>
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script language="javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="./librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="librerias/js/separador.js"></script>
<script language="javascript">

function jornadas(mes_seleccionado)
{
	//alert(mes_seleccionado);
	var centro_costo = $("#centro_cos").val();
	var mes_contable = $("#mes").val();
	var ano_contable = $("#ano").val();
	
	var html='';
	var tipo;
	
	$.ajax(
	{
		type: "POST",
		url: "./llamados/jornadas.php",
		data: "centro="+centro_costo+"&mes="+mes_contable+"&ano="+ano_contable,success: function(msg)
		{
			var myObject = eval('('+msg+')');
			/*for (var x = 0 ; x < myObject.length ; x++) 
			{	
			    html+='<tr><td>'+myObject[x].consecutivo+'</td><td>'+myObject[x].cen_cos+'</td>';
			 	html+='<td>'+myObject[x].jornadas+'</td><td><input type="radio" name="imp_fac" id="imp_fac" value="'+myObject[x].consecutivo+'" onClick="enviar(0);"/></td></tr>';
		   	}*/
			$("#tab_jornada").css("display","block");
			$("#btn_gua_factura").css("display","block");
			
			$("#tab_jornada").append(html);
			$("#noJornadas").css("display","none");
			$("#tipo").val(tipo);
		    html+='<tr><td>Fecha Factura (dd-mm-yyyy): </td><td><input type="text" readonly="readonly" name="fec_fac" id="fec_fac" value="<?php echo date('d-m-Y'); ?>"/></td><td>Mes de Servicio: </td><td><input type="text" name="mes_ser" id="mes_ser" value="" list="mes_se"/><datalist id="mes_se">';
		    
		   <?php
		    $mes_servicio = $ins_mes->mes();
			while($row=mssql_fetch_array($mes_servicio))
			{ ?>
			   html+='<option value="<?php echo $row['mes_id']; ?>" label="<?php echo $row['mes_nombre']; ?>">';
	<?php	}
		   ?>
			html+='</td>';
	        html+='<td>Descripcion factura: </td><td><textarea name="desc_factura" id="desc_factura"></textarea></td><td>Valor Factura: </td><td><input type="text" name="val_fac" id="val_fac" onkeypress="mascara(this,cpf);" onpaste="return false" /></td><td>Periodo facturado:</td><td><input type="text" name="per_facturado" id="per_facturado"/></td></tr>';
			$("#tab_noJornada").css("display","block");
			$("#tab_noJornada").append(html);
			$("#tab_jornada").css("display","none");
		}
	}
	);
}
</script>

<script language="javascript">

function enviar(tip)
{
	//tip=1->FACTURACION ANESTESIA
	//tip=2->FACTURACION ADMINISTRATIVOS
	
	var mes;
	var ano;
	
	var centro = $("#centro_cos").val();
	
	if(tip==1)//FACTURACION ANESTESIA
	{
		mes=$("#mes").val().split('-');
		ano=$("#estAno").val();
	}
	else
	{
		if(tip==2)//FACTURACION ADMINISTRATIVOS
		{
			mes=$("#mes_sele").val().split('-');
			ano=$("#estAno").val();
		}
	}
	
    if(mes[0]==1)
    {
    	alert("Mes de solo lectura.");
    	return false;
    }
    else
    {
		quitarPuntos();
		if(tip==0)
		{
			//alert('entra aqui 1!');
		   	document.general.action = './reportes/factura.php?fac=1';
		}
		else
		{
			if(tip==1)
			{
				//alert('entra aqui 2!');
			   	document.general.action = './control/guardar_factura.php?centro_cos='+centro;
			}
			else
			{
				//alert('entra aqui 3!');
				document.general.action = './control/guar_facConcepto.php';
			}
			   
		}
		document.general.submit();
    }
}
</script>
<script language="javascript">
function TipoConcepto(tipo)
{
    if(tipo==0)
    {
        $("#otro_conce").css("display","none");
        $("#anestesia").css("display","none");
    }
    else
    {
              if ($("#concep").val()==101){
                    $("#anestesia").css("display","block");
                    $("#otro_conce").css("display","none");
              }else{
                         $("#anestesia").css("display","none");
                         $("#otro_conce").css("display","block");
                         $("#tipo").val(2);
                  }
    }
}

$(document).ready(function(){
   $("#tip_nit").click(function(evento){
  $.ajax({
		type: "POST",
		url: "./llamados/tipo_nits.php",
		data: "tipo="+$("#tip_nit").val(),	
		success: function(msg){
		  $("#nit").html(msg);
		}
	});    
  });
});
</script>
<form name="general" id="general" method="post" action="#">
 <center>
  <table>
   <tr>
    <td>CONCEPTO</td>
    <td>
     <select name="concep" id="concep" required x-moz-errormessage="Seleccione Una Opcion Valida">
      <option value="0" onclick="TipoConcepto(this.value)">--Seleccione--</option>
      <?php
	   while($row=mssql_fetch_array($con_factura))
	      echo "<option value='".$row['con_id']."' onclick='TipoConcepto(this.value);'>".$row['con_nombre']."</option>";
	  ?>
    </select>
    </td>
   </tr>
  </table>
  
  
  
  <div id="anestesia" style="display:none;">
    <table id="cen_fec">
      <tr>
        <td>Centro de costo</td>
        <td>
		  <?php $cen_cos = $centro->con_cen_por_con_estado(1); ?>
			<select name='centro_cos' id='centro_cos'>
             <option>Seleccione...</option>
          <?php  
			while($row = mssql_fetch_array($cen_cos))
			   echo "<option value='".$row['cen_cos_id']."'>".$row['cen_cos_nombre']."</option>";
		  ?>
            </select> 
        </td>
      </tr>
      <tr>
       <td>A&ntilde;o</td>
       <td>
         <select name="ano" id="ano">
           <option value="0">Seleccione</option>
           <?php
		    for($a=0;$a<sizeof($anos);$a++)
			   echo "<option value='".$anos[$a]."'>".$anos[$a]."</option>";
		   ?>
         </select>
       </td>
       </tr>
      <tr>
        <td>Mes</td>
        <td>
        <select name="mes" id="mes" onchange="jornadas(this.value);">
         <option value="0">Seleccione...</option>
         <?php
		 while($dat_meses = mssql_fetch_array($meses))
			echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  ?>  
       </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes->conAno($ano); ?>'/> 
       </td>
      </tr>
    </table>
     <table id="tab_jornada" style="display:none;">
      <tr id="j0"><td>Reporte Num</td><td>Centro de Costo</td><td>Total Jornadas</td><td>Imprimir Factura</td></tr>
     </table>
     <table id="tab_noJornada" style="display:none;">
     </table>
     <input type="button" id="btn_gua_factura" class="art-button" style="display:none;" name="guardar" value="guardar" onclick="enviar(1);"/>
  </div>
  
  
  
  <div id="otro_conce" style="display: none;">
   <table border="1">
   	 <tr>
        <td>Mes Contable</td>
        <td><select name="mes_sele" id="mes_sele" onchange="consecutivo(this.value,11,'trans_id','llamados/inic_mes.php');">
        <option value=''>Seleccione...</option>
        	<?php
          	while($dat_meses = mssql_fetch_array($meses_fac_admin))
            	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
          	?>  
        </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes->conAno($ano); ?>'/> 
        </td>
        <td>Factura</td>
     </tr>
     <tr>
      <td>Centro de costo</td>
      <td>
       <select name="centro" id="centro">
        <option value="0">Seleccione...</option>
   		<?php $cen_cos = $centro->cen_cos_sec();
		 while($row = mssql_fetch_array($cen_cos))
			echo "<option value='".$row['cen_cos_id']."'>".substr($row['cen_cos_nombre'],0,35)."</option>";
		  ?>
       </select>
      </td>
      <td>Fecha Fac</td>
      <td><input type="text" name="fecha" id="fecha" readonly="readonly" value="<?php echo date('d-m-Y'); ?>" size="10" /></td>
      <td>Descripcion</td>
      <td><textarea name="descripcion" id="descripcion"></textarea></td>
     </tr>
     <tr>  
      <td>Valor Factura</td>
      <td><input type="text" name="val_factura" id="val_factura" size="10" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
      <td>Tipo Tercero</td>
      <td>
       <select name="tip_nit" id="tip_nit" >
        <option value="0">Seleccione...</option>
        <?php
		 $tipos = $tip_nit->con_tod_tip_nits();
		 while($row=mssql_fetch_array($tipos))
		     echo "<option value='".$row['nit_tip_id']."'>".substr($row['nit_tip_nombre'],0,20)."</option>";
		?>
       </select>
      </td>
      <td>Tercero</td>
       <td>
       <select name="nit" id="nit"></select>
       </td>
     </tr>
     <tr>
        <td>Mes de Servicio: </td><td><input type="text" name="mes_servicio" id="mes_servicio" value="" list="mes_se"/><datalist id="mes_se">
        <?php
        $mes_servicio_2 = $ins_mes->mes();
        while($row=mssql_fetch_array($mes_servicio_2))
        {
        ?>
          <option value="<?php echo $row['mes_id']; ?>" label="<?php echo $row['mes_nombre']; ?>">
        <?php
        }
        ?>
        </td>
     </tr>
   </table>
   <input type="button" class="art-button" name="guardar" value="Guardar" onclick="enviar(2);"/>
  </div>
  
  
  
  <input type="hidden" name="tipo" id="tipo" value="" />
 </center>
</form>
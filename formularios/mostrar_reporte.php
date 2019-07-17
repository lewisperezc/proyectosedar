<?php
session_start();

if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
include_once('../clases/mes_contable.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/nits.class.php');
 

 $ano = $_SESSION['elaniocontable'];
//bus_con_reporte
 $mes = new mes_contable();
 $saldos = new saldos();
 $meses = $mes->DatosMesesAniosContables($ano);
 $concepto = new concepto();
 $nit = new nits();
 $_SESSION['temp']="";
 $espago=$_SESSION['espago'];
 
 
 $jornadas=array();
 
 
?>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css"/>
<script language="javascript" src="../librerias/js/jquery.js"></script>
<script language="javascript" src="librerias/js/jquery.js"></script>
<script language="javascript" src="../javascript/obener_nombre_mes.js"></script>
<script language="javascript" src="javascript/obener_nombre_mes.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript">

function Recargar()
{
	window.location.href='../formularios/form_pag_compensaciones.php';
}

function Regresar()
{
	location.href='../index.php?c=34';
}

  function modificar()
  {
    for (i=0;i<document.forms[0].elements.length;i++) 
	  {
        if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
        }
      }
	$("#recon").attr("disabled", "disabled");
  }
  
  function reindex(valor,val_factura,consecutivo,factura)
  {
	var descuentos = document.mos_mod.desc.value;
	var cadena = document.mos_mod.mes_sele.value;
	var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
    {
    	alert("No se puede ingresar mas datos en este mes.");
    	return false;
    }
	if(document.mos_mod.tip_rep.value == 0)
	  {
		alert("Debe seleccionar el tipo de reporte");
		return false;
	  }
	 else
	 {  
	   if(valor == 'Guardar')
	   { 
	     if(document.mos_mod.tip_rep.value==2 && document.mos_mod.total.value > 120)
		 {
			 alert("la suma de las jornadas no puede ser mayor a 120.");
			 return false;
		 }
		 else
		 {
			if(document.mos_mod.tip_rep.value==2)
			   {
				   document.mos_mod.action ="../control/guardar_modificacion.php";
		 	  	   document.mos_mod.submit();
			   }
			else
			{
				/*var suma = document.mos_mod.total.value;
		    	if(suma!=val_factura)
				{
			   		alert("La suma de las jornadas no es igual al valor del recibo de caja."); 
			   		return false;
				}
				else
		    	{*/
			 		document.mos_mod.action ="../control/guardar_modificacion.php";
		     		document.mos_mod.submit();
				//}
			}
		 }
	   }
	   if(valor == 'Pagar Compensacion')
	   {
		/*Aqui se debe hacer que la seguridad social de todos sea menor al valor de lo facturado*/
		  var seg_social=0;
		  for(i=0;i<$("#can_jornadas").val();i++)
		  {
			 if(($("#des_segSocial"+i).val()/2)>$("#nove"+i).val())
				seg_social=1;
		  }
		  /*if(seg_social==0||document.mos_mod.tip_rep.value==2)
		  {*/
			  var a = confirm("Esta seguro que desea pagar la compensacion al afiliado en el mes "+ObtenerNombreMes(cadena[1])+"?");
			  if(a)
			  {
			   if(document.mos_mod.tip_rep.value == 2 && document.mos_mod.total.value > 120)
			   {
				 alert("la suma de las jornadas no puede ser mayor a 120");
				 return false;
			   }
			   else
			   {
				   if(document.mos_mod.tip_rep.value == 2)
				   {
					   document.mos_mod.action ="../control/pagar_compensaciones.php";
					   document.mos_mod.submit();
				   }
				   else
				   {
					 var suma = document.mos_mod.total.value;
					 document.mos_mod.action ="../control/pagar_compensaciones.php";
					 document.mos_mod.submit();
				   }
			   }
		  	 }
		  /*}
		  /*
		  else
		  {
			alert("Debe verificar los descuentos de seguridad social de los Afiliados");
			return false;   
		  }*/
	   }
	  if(valor == 'Reconfirmar Jornadas')
	   {
		 document.mos_mod.action ="../control/reconfirmar_jornadas.php?fac_id="+factura;
		 document.mos_mod.submit();
	   } 
	  return true;
	 }
  }
  
function calcular(jornadas,descuento,jor_nit,pos,tip,tot_jornadas){
   $.ajax({
   type: "POST",
   url: "../llamados/suma.php",
   data: "jornadas="+jornadas+"&descu="+descuento+"&jor_nit="+jor_nit+"&tipo="+tip+"&tot="+tot_jornadas,
   success: function(msg){
     $("#descu"+pos).val(msg);
   }
 });
}
</script>
<script type="text/javascript">
 $(function() {
	$("#pagar").click(function() {
	var add = 0;
	$(".amt").each(function() {
	add += Number($(this).val());
   });
  $("#total").val(add);
  });
 });
 
function abreVentana(URL)
 {
     day = new Date();
	 id = day.getTime();
	 //alert(URL);
	 eval("page" + id + " = window.open(URL,'"+id+"','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1100,height=300,left = 340,top = 362');");
 }
 
function recalcular(i,factura,recibo,mes,ano,facturado)
 {
	//var factura = $("#factura").val();
	$.ajax({
   		type: "POST",
   		url: "../llamados/des_seguridad.php",
   		data: "nit="+$("#nit"+i).val()+"&descu="+$("#des_segSocial"+i).val()+"&novedad="+$("#nove"+i).val()+"&fac="+factura+"&recibo="+recibo+"&mes="+mes+"&ano="+ano+"&factu="+facturado,
   		success: function(msg){$("#des_segSocial"+i).val(msg);}
   	});
	$("#des_segSocial"+i).removeAttr("onclick");
 }
 
 function suma_jornadas()
 { 
 	//alert(document.mos_mod.elements["novedad[]"]);
    var lasnovedades=document.mos_mod.elements["novedad[]"];
	var suma=0;
	var sum_sin=0;
	for(i=0;i<lasnovedades.length;i++)
	{
	  if(lasnovedades[i].value!="")
	  {
		sum_sin=lasnovedades[i].value;
	  	suma += parseInt(sum_sin);
	  }
	}
	$('#lasuma').val(suma);
 }
</script>
<?php
  $j=1;
  include_once('../clases/reporte_jornadas.class.php');
  include_once('../clases/recibo_caja.class.php');
  include_once('../clases/factura.class.php');
  $instancia_factura = new factura();
  $rep_jornadas = new reporte_jornadas();
  $rec_caja = new rec_caja();
  $con_tip_facturacion = $instancia_factura->con_tipo_facturacion();
  
  $val_jor_fac=explode("_",$_POST['jor']);
  
  $conse = $val_jor_fac[0];
  //echo "datos: ".$conse."___".$val_jor_fac[1]."<br>";
  $factura=$_POST['factura'];
  $recibo=$_POST['recibo_caja'];
  $_SESSION['recibo'] = $recibo;
  $lasumajornadas=0;
  $lasumasegsocial=0;
  $lasumaglosa=0;
  $lasumadescuento=0;
  $tot_descuentos=0;
  //echo "la info es: ".$factura;
  if(!$conse)
  {
	   //echo "entra por el if";
	   $j=0;
	   if($recibo)
	   {
	   	 $_SESSION['recibo']=$recibo;
	   }
	   else
	   {
	     $_SESSION['recibo']=$_POST['rec_caja'];
	   }
	   $list = split("-",$_SESSION['recibo']);
	   if($list[0]!="")
	   {
	   	$conse = $rep_jornadas->bus_con_reporte($list[0]);
		$_SESSION['num_recibo']=$list[0];
		$_SESSION['val_recibo']=$list[1];
		$_SESSION['val_recibo1']=$rec_caja->valorRecibo($_SESSION['num_recibo']);
		$_SESSION['conse_recibo']=$list[2];
		$fac_selec = $list[3];
		$_SESSION['factura']=$list[3];
		//echo "la factura es: ".$_SESSION['factura'];
		$tot_descuentos = $rec_caja->totalDescuentos($_SESSION['num_recibo']);
		if($tot_descuentos==0)
			$tot_descuentos = $instancia_factura->legFactura($fac_selec,$_SESSION['num_recibo']);
		$num_factura = $rep_jornadas->reporRecibo($_SESSION['num_recibo']);
			if($num_factura)
			{
			  //echo "entra";
			  $reporte = $rep_jornadas->reporRecibo($_SESSION['num_recibo']);
			  $tot_jor = $rep_jornadas->reporRecibo($_SESSION['num_recibo']);
			  $reconfirmado = $rep_jornadas->recorfimadoPorFactura($factura);
			}
			else
			{
				//echo "entra 2";
				$num_factura = $rep_jornadas->reportesPorFactura($fac_selec);
				$reporte = $rep_jornadas->reportesPorFactura($fac_selec);
				$tot_jor = $rep_jornadas->reportesPorFactura($fac_selec);
				$reconfirmado =  $rep_jornadas->recorfimadoPorFactura($fac_selec);
			}
		}
		else
		{
			//echo "Entra cuando es un adelanto de nomina";
			$list[0]=$list[3];
			$_SESSION['val_factura']=$list[1];
			$_SESSION['val_recibo']=$list[1];
			$_SESSION['fac_sele']=$list[3];
			$_SESSION['factura']=$list[3];
			$fac_selec=$list[3];
			$adelanto=1;
			$conse = $rep_jornadas->bus_con_rep_adelanto($fac_selec);
	
			$tot_descuentos = $rec_caja->totalDescuentos($_SESSION['num_recibo']);
			if($tot_descuentos==0)
				$tot_descuentos = $instancia_factura->legFactura($fac_selec,$_SESSION['num_recibo']);
	
			//$tot_descuentos = $instancia_factura->legFactura($fac_selec,$_SESSION['num_recibo']);
	
			//echo "la factura es: ".$_SESSION['factura'];
			$_SESSION['descu']=$tot_descuentos;
			$num_factura = $rep_jornadas->reportesPorFactura($factura);
	  		$reporte = $rep_jornadas->reportesPorFactura($factura);
	  		$tot_jor = $rep_jornadas->reportesPorFactura($factura);
	  		$reconfirmado =  $rep_jornadas->recorfimadoPorFactura($factura);
	   }
   }
   else
   {
   		//echo "por este dsdds";
	  if($_POST['tipo_consulta']==1)//ES CONSULTA DEL REPORTE DE JORNADAS
		$fac_seleccionada=$val_jor_fac[1];
	  else//ES PARA FAGAR LA FACTURA
   	  	$fac_seleccionada=$_POST['factura'];
   	  //echo "entra por el else";
   	  //echo "la factura es: ".$fac_seleccionada;
	  $num_factura = $rep_jornadas->reportesPorFactura($fac_seleccionada);
	  $reporte = $rep_jornadas->reportesPorFactura($fac_seleccionada);
	  $tot_jor = $rep_jornadas->reportesPorFactura($fac_seleccionada);
	  $reconfirmado =  $rep_jornadas->recorfimadoPorFactura($fac_seleccionada); 
   }
	if($fac_selec!="")
		$ano_factura=$instancia_factura->ano_factura($fac_selec);
	else
		$ano_factura=$instancia_factura->ano_factura($factura);
	$tipos='11';
	
?>

<form name="mos_mod" id="mos_mod" method="post">
 <center>
 	 <input type="hidden" name="val_factura" id="val_factura" value="<?php echo $list[1]; ?>" />
  <?php
    if($j==0)
	{
		$res_des_glosas=$rec_caja->totalDescuentosGlosas($_SESSION['num_recibo'],$tipos);
	  ?>
       <table id="descuento" align="center">
        <tr>
         <th>TOTAL DESCUENTOS</th>
         <?php
		   if($tot_descuentos!="")
			   $val_escribir=$tot_descuentos;
		   else
		     $val_escribir=0;
		 ?>
         <td><input type="text" name="desc" id="desc" value="<?php echo $val_escribir; ?>" disabled="disabled" />
         <input type="hidden" name="desc1" id="desc1" value="<?php echo $val_escribir; ?>" /></td>
         <input type="hidden" name="descuentos_glosas" id="descuentos_glosas" value="<?php echo $res_des_glosas; ?>" /></td>
         <td><input type="button" onclick="abreVentana('agregar_descuento_legalizacion_factura.php?rec_caj_seleccionado=<?php echo $_SESSION['num_recibo']; ?>')" name="btn_agr_des_legalizacion" id="btn_agr_des_legalizacion" value="Agregar descuento legalizacion" /></td>
        </tr>
       </table>
      <?php
	}
	else
	  echo "<input type='hidden' name='desc' id='desc' value='0' disabled='disabled' /></td>";
  ?>
  <table id="reporte" align="center">
   <tr>
    <th colspan="3"><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
    MES CONTABLE:
    </th>
    <td>
       <select name="mes_sele" id="mes_sele">
       <?php
		while($dat_meses=mssql_fetch_array($meses))
		{
			if($dat_meses['mes_estado']==2)
			  echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."' selected='selected'>".$dat_meses['mes_nombre']."</option>";
			else
			 echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";   
		}
	  ?>  
      </select>
     </td>
      <th>TIPO REPORTE</th>
      <td>
       <select name="tip_rep" id="tip_rep">
         <option value="0">--Seleccione--</option>
         <option value="1">Reporte en dinero</option>
         <option value="2">Reporte en jornadas</option>
        </select>
      </td>
      <tr><td colspan="10"><hr /></td></tr>
      <tr>
      <th colspan="10">FACTURA SELECCIONADA
	    <?php
		  $dat_factura=mssql_fetch_array($num_factura);
		  //echo "<b>".$dat_factura['consecu']."</b>";
		  $tot_glosa = $rep_jornadas->totalGlosa($dat_factura['factura']);
		  echo "<input type='hidden' name='factura' id='factura' value='".$dat_factura['consecu']."' />";
		?>
        </th></tr>
   </tr>
   <tr align="center">
	<th>DOCUMENTO</th>
	<th>NOMBRE</th>
	<th>ESTADO</th>
	<th>NOVEDAD</th>
    <?php
    if($j==0)
	{
	  echo "<th>TOTAL DESCUENTO</th>";
	  echo "<th>DESCUENTO SEG SOCIAL</th>";
	  echo "<th>DESCUENTO<br>POR GLOSA</th>";
	  echo "<th>CREDITOS</th>";
	} 
	?>
    <th>CONSECUTIVO</th>
    <th>DESCUENTOS<br /> DE NOMINA</th>
   </tr>  
  <?php
     $i=0;
	 $total_jornadas = 0;
	 $glosa_total = 0;
	 while($tot = mssql_fetch_array($tot_jor))
	     $total_jornadas = $total_jornadas+$tot['num_jor'];
	 while($row = mssql_fetch_array($reporte))
      {
	  echo "<tr align='center'>";
	   echo "<td>";
	   echo "<input type='hidden' name='factura' id='factura' value='".$row['fac_id']."'/>";
  	   echo "<input type='text' name='identificacion[$i]' id='identificacion[$i]' disabled='disabled' value='".$row['nits_num_documento']."' size='10'/>";
       echo "<input type='hidden' name='nit$i' id='nit$i' value='".$row['nit_id']."'/>" ;
	   echo "</td>";
	   echo "<td>";
	   echo "<input type='text' name='nombre[$i]' id='nombre[$i]' disabled='disabled' value='".$row['apellidos']." ".$row['nombres']."' size='35'/>"; echo "</td>";
	   echo "<td>";
	   echo "<input type='text' name='estado[$i]' id='estado[$i]' disabled='disabled' value='".$row['estado']."' size='2'/>";
	   echo "<input type='hidden' name='estado$i' id='estado$i' value='".$row['estado']."'/>";
	   echo "</td>";
	   echo "<td>";
	   if($tot_descuentos=="")
	   	$tot_descuentos=0;
	   if($j==0)
	   {
		  if($row['num_jor']<120)
		  echo "<input type='text' name='novedad[$i]' id='novedad[$i]' value='".$row['num_jor']."' readonly='readonly' class='amt' size='10' onblur='suma_jornadas();'/>";
		  else
		  echo "<input type='text' name='novedad[$i]' id='novedad[$i]' value='".$row['num_jor']."' readonly='readonly' class='amt' size='10' onblur='suma_jornadas();'/>";
		  
		  echo "<input type='hidden' name='nove$i' id='nove$i' value='".$row['num_jor']."' size='10' />";
	   }
	   else
	   {
		  if($row['num_jor']<120)
	      echo "<input type='text' name='novedad[$i]' id='novedad[$i]' disabled='disabled' value='".$row['num_jor']."' class='amt' size='10' onblur='suma_jornadas();'/>";
		  else
		  echo "<input type='text' name='novedad[$i]' id='novedad[$i]' disabled='disabled' value='".$row['num_jor']."' class='amt' size='10' onblur='suma_jornadas();'/>";
		  
		  echo "<input type='hidden' name='nove$i' id='nove$i' value='".$row['num_jor']."' size='10' />";		
	   }
	   echo "</td>";
	   $lasumajornadas=$lasumajornadas+$row['num_jor'];
	   
	   $jornadas[$i] = $row['num_jor'];
	   $val_glosa=$rep_jornadas->consulGlosa($row['nit_id'],$row['jor_id'],$_SESSION['conse_recibo']);
	  if($val_glosa=="")
	    $val_glosa=0;
	  if($j==0 && $tot_descuentos>0)
	  {
		if($row['num_jor']<120)
		{
		 $val_jor = (($_SESSION['val_recibo']-$tot_glosa)/($total_jornadas-$tot_glosa))*($row['num_jor']-$val_glosa);
         $val_ope_des = ($_SESSION['val_recibo']-$tot_glosa)/$tot_descuentos;
         $descuento = $val_jor/$val_ope_des;
		 //echo "entra por el if ".$descuento."<br>";
		}
		else
		{
		    $nue_val_factura=$total_jornadas-$tot_glosa;//VALOR DESPUES DE LA GLOSA
		    $nue_val_jornadas=$row['num_jor']-$val_glosa;
            $por_jornadas=$nue_val_jornadas*100/$nue_val_factura;
            
            //$descuento=$tot_descuentos*$por_jornadas/100;
            /*if($row['nit_id']==1543)
            	echo $row['num_jor']."____".$total_jornadas;*/
			
			$porcentaje=$row['num_jor']*100/$total_jornadas;
			
            $descuento=round($tot_descuentos*$porcentaje/100,1);
		}
	  }
	  
	  echo "<td><input type='text' name='descu".$i."' readonly id='descu".$i."' value='".round($descuento,2)."' size='10'/></td>";
	  $descontar = $nit->des_segSocial($row['nit_id'],$row['fac_mes_servicio'],$row['num_jor'],$row['fac_id'],$ano_factura,$row['estado']);
	  if(!$descontar)
	     $descontar=0;
	  
	  $descontar = round($descontar,-2);

	  //if($row['nit_id']=='1281')
	  	//echo $descontar;
	  //Desde aca
	  /*onClick='recalcular(".$i.",".$_SESSION['factura'].",".$_SESSION['conse_recibo'].",".$row['fac_mes_servicio'].",".$ano_factura.",".$row['num_jor'].")'*/
	  echo "<td><input type='text' name='des_segSocial".$i."' id='des_segSocial".$i."' value='$descontar' size='10' readonly /></td>";
	  echo "<input type='hidden' name='jor_glo".$i."' id='jor_glo".$i."' value='".$row['jor_id']."'/>";
	  echo "<td><input type='text' name='glosa".$i."' readonly='readonly' id='glosa".$i."' value='".$val_glosa."' size='10' /></td>"; ?>
	  		<td><input type="radio" name="creditos<?php echo $i; ?>" id="creditos<?php echo $i; ?>" value="<?php echo $row['nit_id']; ?>" onclick="abreVentana('desc_credito.php?desc=<?php echo $row['nit_id']; ?>&fac=<?php echo $fac_selec; ?>&recibo=<?php echo $_SESSION['num_recibo'] ?>')" /></td> <?php
	  echo "<td>";
          $lasumasegsocial=$lasumasegsocial+$descontar;
          $lasumaglosa=$lasumaglosa+$val_glosa;
          $lasumadescuento=$lasumadescuento+$descuento;
          echo "<input type='text' name='consecutivo' id='consecutivo' disabled='disabled' value='".$row['consecutivo']."' size='8' />";
	  echo "</td>";
	  echo "<td>";
	  ?>
      <input type="radio" name="descuento" id="descuento" onclick="abreVentana('descuento_compensacion.php?desc=<?php echo $row['nit_id']."-".$fac_selec."-".$list[0]; ?>')"/>
      <?php
	  echo "</td>";
	  echo "</tr>";
	  $rep_jor[$i] = $row['jor_id'];
	  $aso_identificacion[$i] = $row['nit_id'];
	  $i++;
      }
         echo "<tr><td colspan='10'><hr></td></tr>";
	 echo "<tr><th colspan='3' style='text-align:right;'>TOTALES:</th><th><input type='text' name='lasuma' id='lasuma' value='".number_format($lasumajornadas)."' size='10' readonly/></th><th style='text-align:center;'><input type='text' name='lasumadescuento' id='lasumadescuento' value='".number_format($lasumadescuento)."' size='10' readonly/></th><th style='text-align:center;'><input type='text' name='lasumasegsocial' id='lasumasegsocial' value='".number_format($lasumasegsocial)."' size='10' readonly/></th><th style='text-align:center;'><input type='text' name='lasumaglosa' id='lasumaglosa' value='".number_format($lasumaglosa)."' size='10' readonly/><input type='hidden' name='lasumaglosa2' id='lasumaglosa2' value='".$lasumaglosa."'/></th><th></th><th></th><th></th></tr>";
	 $_SESSION["rep_jornadas"] = $rep_jor;
	 $_SESSION["aso"] = $aso_identificacion;
	 $_SESSION['num_jor'] = $jornadas;
?>
 <input type="hidden" name="total" id="total"/>
 <input type="hidden" name="jornada" id="jornada" value="<?php echo $_SESSION["rep_jornadas"] ;?>" />
 <input type="hidden" name="val_recibo" id="val_recibo" value="<?php echo $_SESSION['val_recibo'] ;?>" />
 <input type="hidden" name="tot_glosa" id="tot_glosa" value="<?php echo $glosa_total; ?>"  />
 <input type="hidden" name="can_jornadas" id="can_jornadas" value="<?php echo $i; ?>"/>
 </table><br>
 <table align="left">
   <?php
	if($j!=0)//ES CONSULTA DE REPORTE DE JORNADAS
	{
		$factura=$fac_seleccionada;
	  //echo "entra por la consulta de reporte de jornadas: ".$factura."<br>";
	  echo "<tr>";
	  echo "<th><input type='button' value='<< Regresar' onclick='Regresar();'</th>";
      echo "<th>";
	  echo "<input type='button' name='mod_rep_jor' id='mod_rep_jor' value='Modificar' onClick='modificar();'>";
	  echo "</th>";
	  if($_SESSION['val_recibo']=="")
	   	$_SESSION['val_recibo']=0;
      if($reconfirmado==0)
    	echo "<th><input type='button' name='guarda' id='guarda' value='Guardar' onclick='reindex(document.pressed=this.value,".$_SESSION['val_recibo'].",".$conse.",".$factura.");'></th>";
	}
   else
	 {
	 	//echo "entra el pago de factura";
	  echo "<th><input type='button' name='pagar' id='pagar' value='Pagar Compensacion' 
	  		   onclick='reindex(document.pressed=this.value,".$_SESSION['val_recibo'].",".$conse.",".$factura.");'></th>";
	 }
 //&&$espago!=1
if($reconfirmado==0)
  echo "<th><input type='button' name='recon' id='recon' value='Reconfirmar Jornadas' 
	onclick='reindex(document.pressed=this.value,".($_SESSION['val_recibo']+$tot_descuentos).",".$conse.",".$factura.");'></th>";		 
  echo "</tr>";		   
	?>
 </table>
 </center>
</form>
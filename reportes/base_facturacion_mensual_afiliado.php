<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/presupuesto.class.php');
$ins_nit=new nits();
$mes=new mes_contable();
$tod_mes=$mes->mes();
$afiliados_1=$ins_nit->con_tip_nit('1');
$afiliados_2=$ins_nit->con_tip_nit('1');
$presupuesto=new presupuesto();
$anos = $presupuesto->obtener_lista_anios();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function validar(opt)
{
	if(opt==1)//PDF
	{
		form=document.aux_tercero;
		form.action = '../reportes_PDF/base_facturacion_mensual_afiliado.php';
		form.submit();
	}
	else
	{
		if(opt==2)//EXCEL
		{
			form=document.aux_tercero;
			form.action = '../reportes_EXCEL/base_facturacion_mensual_afiliado.php';
			form.submit();
		}
	}
}
  </script>
<form name="aux_tercero" id="aux_tercero" method="post">
 <center>
  <table border="1" bordercolor="#0099CC">
   <tr>
    <td>A&ntilde;o</td><td>Mes</td><td>Documento Inicial</td><!--<td>Documento Final</td>-->
   </tr>
   <tr>
    <td>
     <select name="ano" id="ano" >
      <option value="0">Seleccione...</option>
      <?php
		for($a=0;$a<sizeof($anos);$a++)
		  echo "<option value='".$anos[$a]."'>".$anos[$a]."</option>";
	  ?>
     </select>
    </td>
     <td>
     <select name="mes" id="mes">
      <option value="0">Seleccione...</option>
     <?php
	  while($row = mssql_fetch_array($tod_mes))
		  echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 ?>
    </select>
    </td>
    <td>
    	<input type='text' name='nit_inicio' id='nit_inicio' value='' list='nits_inicio'>
      	<datalist id="nits_inicio">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_1))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    </td>
    <!--<td>
    	<input type='text' name='nit_fin' id='nit_fin' value='' list='nits_fin'>
      	<datalist id="nits_fin">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_2))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    </td>-->
   </tr>
   <tr align="center">
   	<td colspan="2"><input type="button" value="Pdf" onclick="validar(1);"/></td>
   	<td colspan="2"><input type="button" value="Excel" onclick="validar(2);"/></td>
   </tr>
  </table>
 </center>
</form>
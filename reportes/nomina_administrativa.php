<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_nits=new nits();
$ins_mes_contable = new mes_contable();

$con_mes=$ins_mes_contable->mes();
$afiliados_1=$ins_nits->con_tip_nit('2');
$afiliados_2=$ins_nits->con_tip_nit('2');
?>
<form action="nomina_administrativa_genera_pdf.php" name="frm_novedades_administrativas" id="frm_novedades_administrativas" method="post" enctype="multipart/form-data">
<center>
<table border="1" bordercolor="#0099CC">	
	<tr>
			<th>TIPO DE PAGO</th>
			<th>
            <select name="per_pag_nomina" id="per_pag_nomina" required><option value="">Seleccione</option>
            <option value="1" >PRIMERA QUINCENA</option>
            <option value="2" >SEGUNDA QUINCENA</option>
            <option value="3" >MENSUAL</option>
			</select></th>
			
			<th>MES DE PAGO</th>
			
        	<th><select name="mes_sele" id="mes_sele" required><option value="">Seleccione</option>
       		<?php
			while($dat_meses = mssql_fetch_array($con_mes))
		 	echo "<option value='".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  		?>
      		</select></th>
	</tr>
	<tr>
		<th colspan="2">DESDE</th>
		<th colspan="2">HASTA</th>
	</tr>
	<tr>
		<td colspan="2">
    	<input type='text' name='nit_inicio' id='nit_inicio' value='' list='nits_inicio' size="50" required>
      	<datalist id="nits_inicio">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_1))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    	</td>
    	<td colspan="2">
    	<input type='text' name='nit_fin' id='nit_fin' value='' list='nits_fin' size="50" required>
      	<datalist id="nits_fin">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_2))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    	</td>
	</tr>
	
   	<tr>
   		<td colspan="4"><input type="submit" value="Ver" /></td>
   	</tr>
</table>
</center>
</form>
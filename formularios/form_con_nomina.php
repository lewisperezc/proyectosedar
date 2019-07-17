<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script>
</script>
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_page.css";
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
function enviar(tipo,id,lasigla,elid)
{
	form=document.con_cau_nom_administrativa;
	if(tipo==2)
	{
		var lacadena=$("#elmes"+elid).val();
		var ano = $("#estAno").val();
    	elvalor = lacadena.split("-");
    	if(elvalor[0]==1)
    	{
	 		alert("No se puede ingresar mas datos en este mes.");
			$("#elmes"+elid).focus();
			return false;
		}
		else
		{
			var mensaje = confirm("Esta seguro que desea pagar la nomina seleccionada?");
			if(mensaje)
			{
				form.action='../control/guardar_pago_nomina_administrativa.php?lasigla='+lasigla+'&laposicion='+elid;
				form.submit();
			}
		}
	}
	else
	{
		form.action='datos_causacion_nomina_administrativa.php?id='+id;
		form.submit();
	}
}
</script>
<form name="con_cau_nom_administrativa" id="con_cau_nom_administrativa" method="post">
<body alink="#000000" link="#000000" vlink="#000000">
<?php
include_once('../clases/nomina.class.php');
include_once('../clases/mes_contable.class.php');
$ins_nomina=new nomina();
$ins_mes_contable = new mes_contable();
$con_mes=$ins_mes_contable->DatosMesesAniosContables($ano);
$pagar=$_GET['opt'];
if($pagar&&$pagar==4)
{
	$con_nom_causadas=$ins_nomina->con_nom_causadas('CAU-NOM_ADM_',1,$ano);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>SIGLA</th> 
            <th>DOCUMENTO</th>
            <th>VALOR NOMINA</th>
            <th>MES CONTABLE CAUSACI&Oacute;N</th>
            <th>A&Ntilde;O CONTABLE</th>
            <th>QUINCENA</th>
            <th>MES CONTABLE</th>
            <th>PAGAR</th>
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 $i=0;
		 while($resul=mssql_fetch_array($con_nom_causadas))
		 {
		 	$con_mes=$ins_mes_contable->mes();
			$nombre_mes_1=$ins_mes_contable->nomMes($resul['mov_mes_contable'])
		 ?>
		<tr class="gradeA"> 
			<td><input type="hidden" name="com_pag_nomina" id="com_pag_nomina" value="<?php echo $resul['mov_compro']; ?>"/><input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/>
			<?php echo $resul['mov_compro']; ?></td>
			<td><input type="hidden" name="num_pag_nomina" id="num_pag_nomina" value="<?php echo $resul['mov_nume']; ?>"/>
			<?php echo $resul['mov_nume']; ?></td>
            <td><input type="hidden" name="val_pag_nomina" id="val_pag_nomina" value="<?php echo $resul['trans_val_total']; ?>"/>
			<?php echo number_format($resul['trans_val_total']); ?>
            </td>
            <td><?php echo $resul['mov_mes_contable']; ?></td>
            <td><?php echo $resul['mov_ano_contable']; ?></td>
            <?php
            if($resul['mov_concepto']==1)
			{
				$num_quincena='01-15';
			}
			elseif($resul['mov_concepto']==2)
			{
				$num_quincena='16-30';
			}
			?>
            <td><?php echo $num_quincena; ?></td>
            <td>
            <select name="elmes<?php echo $i; ?>" id="elmes<?php echo $i; ?>">
       		<?php
			while($dat_meses=mssql_fetch_array($con_mes))
			{
		 		echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
			}
	  		?>
      		</select>
            </td>
            <td><input type="radio" name="sel_opcion" id="sel_opcion" value="<?php echo $resul['mov_nume']; ?>" onClick="enviar(2,this.value,'<?php echo $resul['mov_compro']; ?>','<?php echo $i; ?>');"/></td>
		</tr>
        <?php
		$i++;
        }
		?>
	</tbody>
</table>
<?php
}
else
{
	//Entra a consultar
	$con_nom_causadas=$ins_nomina->con_nom_causadas_y_pagadas('CAU-NOM_ADM_',$ano);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
        <tr> 
            <th>SIGLA</th> 
            <th>DOCUMENTO</th>
            <th>VALOR NOMINA</th>
            <th>MES CONTABLE</th>
            <th>A&Ntilde;O CONTABLE</th>
            <th>QUIENCENA</th>
            <th>PAGADA</th>
            <th>VER</th>
        </tr> 
    </thead> 
    <tbody> 
         <?php
		 while($resul = mssql_fetch_array($con_nom_causadas))
		 {
		 $nombre_mes=$ins_mes_contable->nomMes($resul['mov_mes_contable'])
		 ?>
		<tr class="gradeA"> 
			<td><input type="hidden" name="com_pag_nomina" id="com_pag_nomina" value="<?php echo $resul['mov_compro']; ?>"/>
			<?php echo $resul['mov_compro']; ?></td>
			<td><input type="hidden" name="num_pag_nomina" id="num_pag_nomina" value="<?php echo $resul['mov_nume']; ?>"/>
			<?php echo $resul['mov_nume']; ?></td>
            <td><input type="hidden" name="val_pag_nomina" id="val_pag_nomina" value="<?php echo $resul['trans_val_total']; ?>"/>
			<?php echo number_format($resul['trans_val_total']); ?>
            </td>
            <?php if($resul['estado_nomina_admin']==""||$resul['estado_nomina_admin']==1){ $estado="NO"; }else{ $estado="SI"; } ?>
            <td><?php echo $resul['mov_mes_contable']." - ".$nombre_mes; ?></td>
            <td><?php echo $resul['mov_ano_contable']; ?></td>
            <?php
            if($resul['mov_concepto']==1)
			{
				$num_quincena='01-15';
			}
			elseif($resul['mov_concepto']==2)
			{
				$num_quincena='16-30';
			}
			?>
            <td><?php echo $num_quincena; ?></td>
            <td><?php echo $estado; ?></td>
            <td><input type="radio" name="sel_opcion" id="sel_opcion" value="<?php echo $resul['mov_nume']; ?>" onClick="enviar(1,this.value,0)"/></td>
		</tr>
        <?php } ?>
	</tbody>
</table>
<?php
}
?>
</body>
</form>

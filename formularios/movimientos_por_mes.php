<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
</head>
<?php
$cuenta=$_GET['cuenta'];
if(strlen($cuenta)==1)
	echo "<script>alert('La cuenta seleccionada es mayor, por lo tanto no tiene movimientos!!!');window.close();</script>";
else
{
	include_once('../clases/saldos_cuentas.class.php');
    include_once('../clases/transacciones.class.php');
	$ins_sal_cuentas=new insercion();
    $ins_transaccion = new transacciones();
	$tipo_consulta=$_GET['tipo'];
	$mes=$_GET['mes'];
	$anio=$_GET['anio'];
	if($tipo_consulta==1)//POR CUENTA
	{
		$con_valores=$ins_sal_cuentas->ConSalPorCueAnio2($cuenta,$mes,$anio);
	}
	elseif($tipo_consulta==2)//POR NIT
	{
		$nit_id=$_GET['nit'];
		$con_valores=$ins_sal_cuentas->ConSalPorNitAni2($nit_id,$cuenta,$mes,$anio);
	}
	elseif($tipo_consulta==3)//POR CUENTA Y NIT
		$con_valores=$ins_sal_cuentas->ConSalPorCenCosAnio2($cuenta,$mes,$anio);
    elseif($tipo_consulta==4)//Creditos
    {
    	$cuenta_filtro='13';
		$con_valores=$ins_sal_cuentas->conSalCredito2($cuenta,$mes,$anio,$cuenta_filtro);
    }
?>
    <body alink="#000000" link="#000000" vlink="#000000">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
        <thead> 
            <tr> 
                <th>Cons Documento</th>
                <th>Fec Elab</th>
                <th>Cuenta</th>
                <th>Documento</th>
                <th>Descripcion</th>
                <th>Nit</th>
                <th>Nombres</th>
                <th>C. Costo</th>
                <th>Debito</th>
                <th>Credito</th>
            </tr> 
        </thead> 
        <tbody> 
             <?php
             while($res_valores=mssql_fetch_array($con_valores))
			 {
             ?>
             	<tr class="gradeA"> 
                	<td><?php echo $res_valores['mov_compro']; ?></td>
                	<td><?php echo $res_valores['mov_fec_elabo']; ?></td>
                	<td><?php echo $res_valores['mov_cuent']; ?></td>
                    <td><?php echo $res_valores['fac_consecutivo']; ?></td>
                    <td><?php echo $ins_transaccion->desTransacciones($res_valores['mov_compro'],$mes,$anio); ?></td>
                	<td><?php echo $res_valores['nits_num_documento']; ?></td>
                    <td><?php echo $res_valores['nits_nombres']." ".$res_valores['nits_apellidos']; ?></td>
                	<td><?php echo $res_valores['cen_cos_nombre']; ?></td>
                    <?php
                    if($res_valores['mov_tipo']==1)
		     {
			?>
                        <td><?php echo number_format($res_valores['mov_valor'],0,',','.'); ?></td>
                        <!--<td><?php //echo $res_valores['mov_valor']; ?></td>-->
                	<td><?php echo "0"; ?></td>
                       <?php
		     }
		    elseif($res_valores['mov_tipo']==2)
			{
			?>
			<td><?php echo "0"; ?></td>
                	<td><?php echo number_format($res_valores['mov_valor'],0,',','.'); ?></td>
                        <?php
			}
		     ?>
            	</tr>
            <?php
            }
			?>
        </tbody>		
    </table>
    </body>
<?php
}
?>
</html>
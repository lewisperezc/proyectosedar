<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PyG Por Centro De Costo</title>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
 function validar_vacios()
  {
		var form = document.PyG_por_cen_costo;
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		if(form.pyg_desde.value == ""){ CamposVacios += "* Desde\n"; form.pyg_desde.focus(); }
		if(form.pyg_hasta.value == ""){ CamposVacios += "* Hasta\n"; form.pyg_hasta.focus(); }
		if(form.pyg_mes.selectedIndex == 0){ CamposVacios += "* Mes\n"; form.pyg_mes.focus(); }
		if(form.pyg_cen_costo.selectedIndex == 0){ CamposVacios += "* Cen Costo\n"; form.pyg_cen_costo.focus(); }
			if (CamposVacios != "")
			{
				alert(Mensaje + CamposVacios);
				return true;
			}
			form.action = '../reportes_PDF/pyg_por_cen_costo.php';
			form.submit();
  }
  </script>
</head>
<body>
<?php
include_once('../clases/estado_nits.class.php');
include_once('../clases/presupuesto.class.php');
include_once('../clases/centro_de_costos.class.php');
$ins_cen_costo=new centro_de_costos();
$con_cen_costo=$ins_cen_costo->cen_cos_sec();
$ins_presupuesto=new presupuesto();
$ins_est_nit=new estado_nits();
$con_estados=$ins_est_nit->con_est_nits();
$res_anios=$ins_presupuesto->obtener_lista_anios();
?>
<form name="PyG_por_cen_costo" id="PyG_por_cen_costo" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">PyG Por Centro De Costo</th>
        </tr>
        <tr>
        	<th>Desde</th><td><input type="text" name="pyg_desde" id="pyg_desde" onkeypress="return permite(event,'num')"/></td>
            <th>Hasta</th><td><input type="text" name="pyg_hasta" id="pyg_hasta" onkeypress="return permite(event,'num')"/></td>
        </tr>
        <tr>
            <th>Mes</th>
            <td><select name="pyg_mes" id="pyg_mes"><option value="0">Seleccione</option>
            <option value="1"><?php echo "01 - Enero"; ?></option><option value="2"><?php echo "02 - Febrero"; ?></option>
            <option value="3"><?php echo "03 - Marzo"; ?></option><option value="4"><?php echo "04 - Abril"; ?></option>
            <option value="5"><?php echo "05 - Mayo"; ?></option><option value="6"><?php echo "06 - Junio"; ?></option>
            <option value="7"><?php echo "07 - Julio"; ?></option><option value="8"><?php echo "08 - Agosto"; ?></option>
            <option value="9"><?php echo "09 - Septiembre"; ?></option><option value="10"><?php echo "10 - Octubre"; ?></option>
            <option value="11"><?php echo "11 - Noviembre"; ?></option><option value="12"><?php echo "12 - Diciembre"; ?></option>
            </select></td>
            <th>Año</th>
        	<td><select id="pyg_anio" name="pyg_anio">
				<?php
				for($i=0;$i<sizeof($res_anios);$i++){
				?>
                	<option value="<?php echo $res_anios[$i]; ?>"><?php echo $res_anios[$i]; ?></option>
                <?php 
				}
				?>
            </select></td>
        </tr>
        <tr>
        	<th>Cen. Costo</th>
            <td><select name="pyg_cen_costo" id="pyg_cen_costo"><option value="0">Seleccione</option>
            <?php
            while($res_cen_costo=mssql_fetch_array($con_cen_costo))
			{
			?>
            	<option value="<?php echo $res_cen_costo['cen_cos_id']; ?>"><?php echo $res_cen_costo['cen_cos_nombre']; ?></option>
            <?php
			}
			?>
            </select></td>
        </tr>
        <tr>
            <!--<th colspan="4">
            <a href="Javascript:void(0)" onclick="validar_vacios(1);">Ver Todos</a>
            -->
            <th colspan="4"><input type="button" name="consultar" value="Consultar" onclick="validar_vacios();"/></th>
        </tr>
    </table>
</form>
</body>
</html>
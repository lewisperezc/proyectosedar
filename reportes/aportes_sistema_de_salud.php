<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listado Cumplea&ntilde;os Del Mes</title>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script>
 function validar_vacios()
  {
		var form = document.aportes_sistema_de_salud;
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		
		if(form.mes.selectedIndex == 0){ CamposVacios += "* Mes\n"; form.mes.focus(); }
			if (CamposVacios != "")
			{
				alert(Mensaje + CamposVacios);
				return true;
			}
			form.action = '../reportes_PDF/aportes_sistema_de_salud.php';
			form.submit();
  }
  </script>
</head>
<body>
<?php

include_once('../clases/estado_nits.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$ins_est_nit=new estado_nits();
$con_estados=$ins_est_nit->con_est_nits();
$res_anios=$ins_presupuesto->obtener_lista_anios();
?>
<form name="aportes_sistema_de_salud" id="aportes_sistema_de_salud" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">Aportes Al Sistema De Salud</th>
        </tr>
        <tr>
                <th>Mes</th><td><select name="mes" id="mes"><option value="0">Seleccione</option>
            <option value="01"><?php echo "01 - Enero"; ?></option><option value="02"><?php echo "02 - Febrero"; ?></option>
            <option value="03"><?php echo "03 - Marzo"; ?></option><option value="04"><?php echo "04 - Abril"; ?></option>
            <option value="05"><?php echo "05 - Mayo"; ?></option><option value="06"><?php echo "06 - Junio"; ?></option>
            <option value="07"><?php echo "07 - Julio"; ?></option><option value="08"><?php echo "08 - Agosto"; ?></option>
            <option value="09"><?php echo "09 - Septiembre"; ?></option><option value="10"><?php echo "10 - Octubre"; ?></option>
            <option value="11"><?php echo "11 - Noviembre"; ?></option><option value="12"><?php echo "12 - Diciembre"; ?></option>
            </select></td>
            <th>A�o</th>
        	<td><select id="anio" name="anio">
				<?php
				for($i=0;$i<sizeof($res_anios);$i++){
				?>
                	<option value="<?php echo $res_anios[$i]; ?>"><?php echo $res_anios[$i]; ?></option>
                <?php 
				}
				?>
                </select></td>
        </tr>
        <!--
        <tr>
        	<th>Cedula</th><td><input type="text" name="emp_documento" id="emp_documento" onkeypress="return permite(event,'num')"/></td>
        	<th>Cen. Costo</th>
            <td><select name="cen_costo" id="cen_costo"><option value="0">Seleccione</option>
            
            </select></td>
        </tr>
        -->
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
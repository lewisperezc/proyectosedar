<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Libro De Bancos</title>
<script>
 function validar_vacios(opcion)
  {
		var form = document.libro_de_bancos;
		//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
		var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
		var CamposVacios = "";
		//VALIDAMOS EL CAMPO NOMBRE
		if(form.mes.selectedIndex == 0)
		{ CamposVacios += "* Mes\n"; form.mes.focus(); }
		//SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
		if (CamposVacios != "")
		{
			alert(Mensaje + CamposVacios);
			return true;
		}
			form.action = '../reportes_PDF/libro_de_bancos.php';
			form.submit();
  }
  </script>
</head>
<body>
<?php

include_once('../clases/estado_nits.class.php');
include_once('../clases/presupuesto.class.php');
$ins_est_nit=new estado_nits();
$anos = new presupuesto();
$con_estados=$ins_est_nit->con_est_nits();
?>
<form name="libro_de_bancos" id="libro_de_bancos" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="6">Libro De Bancos</th>
        </tr>
        <tr>
            <th>Año</th>
             <td>
               <select name="ano" id="ano">
			    <?php
				 echo $ano_banco = $anos->obtener_lista_anios();
				 for($i=0;$i<sizeof($ano_banco);$i++)
				   echo "<option value='".$ano_banco[$i]."'>".$ano_banco[$i]."</option>";
				?>
               </select>
             </td>
        	<th>Mes</th><td><select name="mes" id="mes"><option value="0">Seleccione</option>
            <option value="1"><?php echo "1 - Enero"; ?></option><option value="2"><?php echo "2 - Febrero"; ?></option>
            <option value="3"><?php echo "3 - Marzo"; ?></option><option value="4"><?php echo "4 - Abril"; ?></option>
            <option value="5"><?php echo "5 - Mayo"; ?></option><option value="6"><?php echo "6 - Junio"; ?></option>
            <option value="7"><?php echo "7 - Julio"; ?></option><option value="8"><?php echo "8 - Agosto"; ?></option>
            <option value="9"><?php echo "9 - Septiembre"; ?></option>
            <option value="10"><?php echo "10 - Octubre"; ?></option>
            <option value="11"><?php echo "11 - Noviembre"; ?></option>
            <option value="12"><?php echo "12 - Diciembre"; ?></option>
            <option value="1,2,3,4,5,6,7,8,9,10,11,12"><?php echo "Todo el año"; ?></option>
            </select></td>
            <td><input type="button" name="consultar" value="Consultar" onclick="validar_vacios(1);"/></td>
        </tr>
        <!--
        <tr>
			<th colspan="3"><a href="Javascript:void(0)" onclick="validar_vacios(2);">Ver Todos</a></th>
        </tr>
        -->
    </table>
</form>
</body>
</html>
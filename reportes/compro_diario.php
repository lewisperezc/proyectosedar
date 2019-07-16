<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');

$ins_presupuesto=new presupuesto();
$mes = new mes_contable();
$tod_mes = $mes->mes();
$anios=$ins_presupuesto->obtener_lista_anios();

?>
<form name="comp_diario" id="comp_diario" action="../reportes_PDF/comp_diario.php" method="post" >
 <center>
  <table>
   <tr>
    <td>Año</td><td>Mes</td><td>Dia</td>
   </tr>
   <tr>
    <td>
      <select name="ano" id="ano">
      <option value="0">Seleccione</option>
        <?php
          for($a=0;$a<sizeof($anios);$a++)
            echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
        ?>
    </select>
    </td>
     <td>
     <select name="mes" id="mes">
      <option value="0">Seleccione...</option>
     <?php
	  while($row = mssql_fetch_array($tod_mes))
    {
      if(strlen($row['mes_id'])<2)
        echo "<option value='0".$row['mes_id']."'>".$row['mes_nombre']."</option>";
      else
       echo "<option value='0".$row['mes_id']."'>".$row['mes_nombre']."</option>"; 
    }
	 ?>
    </select>
    </td>
    <td>
     <select name="dia" id="dia">
      <option value="0">Seleccione...</option>
     <?php
	  for($i=1;$i<=31;$i++)
		  echo "<option value='$i'>$i</option>";
	 ?>
    </select>
    </td>
   </tr>
   <tr><input type="submit" value="PDF" /></tr>
  </table>
 </center>
</form>
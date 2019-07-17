<?php
 $opc = $_GET['opc']; 

 include_once("../conexion/conexion.php");
 include_once("../clases/credito.class.php");
 $ano = $_SESSION['elaniocontable'];
 $credito = new credito();
 $nit = $_REQUEST["id"];
 
 if($opc==1)
 { ?>
   <option value="0">--Seleccione--</option> <?php
    $dat_creditos=$credito->cre_salNits($nit,0);
    while($row = mssql_fetch_array($dat_creditos))
    { ?>
      <option value="<?php echo $row['cre_id'];?>"><?php echo $row['cre_id'];?></option>
      <?php
    }
  }
  elseif($opc==2)
  { 
   $sql = "SELECT cre.cen_cos_id id,cc.cen_cos_nombre nombre FROM creditos cre
   		   INNER JOIN centros_costo cc
 		   ON cc.cen_cos_id = cre.cen_cos_id
		   WHERE cre.cre_id = $nit";
   $query = mssql_query($sql);	  
 ?>
   <option value="">--Seleccione--</option> <?php
   while($cre_nit = mssql_fetch_array($query))
    { ?>
      <option value="<?php echo $cre_nit['id']; ?>"><?php echo $cre_nit['nombre']; ?></option>
      <?php
    }
  }
?>

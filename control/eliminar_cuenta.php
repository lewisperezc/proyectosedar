<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/concepto.class.php');
$_SESSION['concepto_eli'] = $_POST['concepto_eli'];
$elim = $_SESSION['concepto_eli'];
$_SESSION['con_update'] = $_POST['con_update'];
$upd_conc=$_SESSION['con_update'];

$concep = new concepto;
$prueba=$_POST['posicio'];
$cuenta=$_POST['cuenta'];
$naturaleza=$_POST['nat'];
$contrapartida=$_POST['contraparti'];
$updat;
$resultado =$prueba." ".","." ".$cuenta." ".","." ".$naturaleza." ".","." ".$contrapartida.",";
$i=1;
$j=1;
if($elim){
	
$conc=$concep->elimi_concepto($elim);
if ($conc){

echo "<script>
	alert('Se elimino el concepto y su respectiva formula  --$elim--');
     
</script>";
 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=26'>";

}
else
echo "<script>
		alert('No se pudo insertar.');
		
	  </script>";
	   echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=26'>";

}
else
	while($i<=sizeof($cuenta))
		{	
		 $updat[$i]=$prueba[$i]." ".","." ".$cuenta[$i]." ".","." ".$naturaleza[$i]." ".","." ".$contrapartida[$i].",";
 		 $i++; 
		}
	if($updat)
  		{
		$upda=$concep->upd_conc($upd_conc,$updat[1],$updat[2],$updat[3],$updat[4],$updat[5],$updat[6],$updat[7],$updat[8],$updat[9],$updat[10],$updat[11],$updat[12],$updat[13],$updat[14],$updat[15],$updat[16],$updat[17],$updat[18],$updat[19],$updat[20]);
		
		//LIMPIAR SESSIONES//
		unset($_SESSION['concepto_eli']);
		unset($_SESSION['con_update']);
		/////////////////////
		
	if($upda)
	  { 
	   echo "<script>alert('se actualizo el concepto y su respectiva formula  --$elim--');</script>";		 
	   echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=26'>";
	  }
	else
      { 
	   echo "<script>alert('No se pudo actualizar.');</script>";
	   echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=26'>";
	  }
  }
?>
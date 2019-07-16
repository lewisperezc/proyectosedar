<?php
    $nit = $_POST['nit'];
    $lista[0] = 3;$lista[1] = 7;$lista[2] = 13;$lista[3] = 17;$lista[4] = 19;$lista[5] = 23;$lista[6] = 29;$lista[7] = 37;
	$lista[8] = 41;$lista[9] = 43;$lista[10] = 47;$lista[11] = 53;$lista[12] = 59;$lista[13] = 67;$lista[14] = 71;
for($i=0;$i<strlen($nit);$i++)
 {
  $temp = $nit[(strlen($nit)-1)-$i];
  $calculo = $calculo + ($temp * $lista[$i]);
 }
$resul = $calculo%11;
if($resul>1)
   echo 11-$resul;
else   
   echo $resul;
?>
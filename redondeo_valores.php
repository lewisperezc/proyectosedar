<?php
$cifra=76749;
$para_ibc=round($cifra,-3);//REDONDEO AL MIL MAS CERCANO PARA EL IBC
$para_los_fondos=round($cifra,-2);//REDONDEO AL CIEN MAS CERCANO PARA EL PAGO A LOS FONDOS
echo $para_ibc."<br>";
echo $para_los_fondos."<br>";
?>
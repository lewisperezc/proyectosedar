<?php
include_once ('conexion/conexion.php');
//echo "los numeros: ".rand(1000,9999);
$dinero = 6637823;
$base = ($dinero - ($dinero * 8 / 100));
/*if (($base * 25 / 100) >= 7140720)
    $base = $base - 7140720;
else*/
    $base = $base * 75 / 100;
/*2014$uvt=$uvt=27485;*/
/*2015*/$uvt = 29753;
$veces = round($base / $uvt, 4);
echo "las veces son: " . $veces . "<br>";
/************************************IMAS******************************************/
if ($veces >= 0 && $veces <= 95)
    $imas = 0;
elseif ($veces >= 96 && $veces <= 150) {
    $operacion = $veces - 95;
    //$resultado=numero de veces que est� el uvt en el ingreso base menos 95 uvt
    echo $operacion . "<br>";
    $res_uvt = ($operacion * 19) / 100;
    echo $res_uvt . "<br>";
    $imas = $res_uvt * $uvt;
    echo $imas;
} elseif ($veces > 150 && $veces <= 360) {
    $operacion = $veces - 150;
    $tot_uvt = $operacion * (28 / 100) + 10;
    $imas = $tot_uvt * $uvt;
    echo "el imas es: " . round($imas);
} elseif ($veces >= 361) {
    $operacion = $veces - 360;
    $res_uvt = $operacion * (33 / 100) + 69;
    $imas = $res_uvt * $uvt;
}
echo "<br>" . $imas;
/**************************************IMAN***************************************/
if ($veces <= 177)
    $sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN ROUND($veces,0)-3 AND ROUND($veces,0)+3";
elseif ($veces > 177 && $veces < 340)
    $sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN ROUND($veces,0)-7 AND ROUND($veces,0)+7";
elseif ($veces > 340)
    $sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN ROUND($veces,0)-17 AND ROUND($veces,0)+17";

//echo $sql;
$query = mssql_query($sql);
$i = 0;
while ($row = mssql_fetch_array($query)) {
    $resul['uvt'][$i] = $row['ima_uvt'];
    $resul['pesos'][$i] = $row['ima_ret_pesos'];
    $menor = $veces - $row['ima_uvt'];
    if ($menor < 0)
        $resul['menor'][$i] = ($menor * -1);
    else
        $resul['menor'][$i] = $menor;
    $i++;
}

$mayor = 0;
$pos = 0;
$temp = 0;
for ($i = 0; $i < sizeof($resul); $i++) {
    $temp = $resul['menor'][$i];
    if ($mayor < $temp) {
        $mayor = $temp;
        $pos = $i;
    }
}
echo "por descontar es:  " . max($imas, $resul['pesos'][$pos]);
?>
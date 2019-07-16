<?php session_start();

function number_to_money($value, $symbol = '$', $decimals = 2)
{
    return $symbol . ($value < 0 ? '-' : '') . number_format(abs($value), $decimals);
}

echo number_to_money($_POST['valor']);
?>
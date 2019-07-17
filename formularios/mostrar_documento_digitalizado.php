<?php
$mi_pdf = $_GET['laruta'];
//echo $mi_pdf;
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
readfile($mi_pdf);
?>


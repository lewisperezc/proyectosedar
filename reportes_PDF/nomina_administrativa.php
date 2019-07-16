<?php
session_start();
require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($_SESSION['informacion_nomina_administrativa']);
$dompdf->set_paper ('a4','');//landscape 
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>
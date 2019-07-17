<?php
	$texto = $_GET["texto"];
	// Devuelvo el XML con la palabra que mostramos (con los '_') y si hay éxito o no
	$xml  = '<?xml version="1.0" standalone="yes"?>';
	$xml .= '<datos>';
	foreach ($datos as $dato) {
		if (strpos(strtoupper($dato), strtoupper($texto)) === 0 OR $texto == "") {
			$xml .= '<pais>'.$dato.'</pais>';
		}
	}
	$xml .= '</datos>';
	header('Content-type: text/xml');
	echo $xml;
?>
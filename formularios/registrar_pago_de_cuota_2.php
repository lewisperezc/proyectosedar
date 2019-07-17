<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css"/>
<script type="text/javascript" src="../librerias/js/validacion_num_letras.js"></script>
<?php
include_once('../clases/credito.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/moviminetos_contables.class.php');
$instancia_credito = new credito();
$concep = new concepto();
$mov_contable = new movimientos_contables();
$_SESSION['persona_id'] = $_POST['persona_id'];
$persona_id = $_SESSION['persona_id'];
$_SESSION['cre_consecutivo'] = $_POST['cre_consecutivo'];
$cre_consecutivo = $_SESSION['cre_consecutivo'];
$_SESSION['mes'] = $_POST['mes_sele'][1];
if($persona_id)
  {
	$con_cre_por_nit = $instancia_credito->cre_nits($persona_id);
	echo "<form name='guar_pag' id='guar_pag' method='post' action='../control/registrar_pago.php'>
		<center>";
		echo "<table>";
		 echo "<tr>";
		  echo "<td>Documento</td>";
		  echo "<td>Tercero</td>";
		  echo "<td>Valor total</td>";
		  echo "<td>Saldo a la fecha</td>";
		  echo "<td>Monto a pagar</td>";
		  echo "<td>Descripcion</td>";
		  echo "<td>Concepto de pago</td>";
		  echo "<td>Pagar</td>";
		 echo "<tr>";
		 $i=0;
		 while($row = mssql_fetch_array($con_cre_por_nit))
		  {
			 $tran_id = $row['trans_id'];
			 $cred_sele[$tran_id]=$row['cre_id'];
			 $cuen_sele[$tran_id] = $row['cue_id'];
			 //
			 $nombre = $row['nits_nombres']." ".$row['nits_apellidos'];
			 $direccion = $row['nits_dir_residencia'];
			 //$valor_pagar = $row['trans_val_total'];
			 //
			 $comprobante = $row['trans_sigla'];
			 $no_trae="0";
			 $conceptos = $concep->conceptos(1,$no_trae);
			 $saldo_doc = $mov_contable->bus_sal_documento($tran_id);
			 if($saldo_doc > 0)
			 {
			   echo "<tr>";
		  	   echo "<td><input type='text' name='num_fac[".$tran_id."]' id ='num_fac[".$tran_id."]' 
			              value = '".$comprobante."' readonly='readonly' /></td>";
		  	   echo "<td><input type='text' name='nit[".$tran_id."]' id='nit[".$tran_id."]' 
			              value='".$row['nits_nombres']." ".$row['nits_apellidos']."' readonly='readonly'/></td>";
		  	   echo "<td><input type='text' name='val_total' id='val_total' 
			            value='".$row['trans_val_total']."' readonly='readonly'></td>";
		  	   echo "<td>";
			       if($row['tran_con_id'] == NULL)
					  echo "<input type='text' name='val_saldo[".$tran_id."]' id='val_saldo[".$tran_id."]' 
			                value='".$row['trans_val_total']."' readonly='readonly' /></td>";
				   else
				    {
					 $saldo_doc = $mov_contable->bus_sal_documento($tran_id);
					 echo "<input type='text' name='val_saldo[".$tran_id."]' id='val_saldo[".$tran_id."]' 
			                value='".$saldo_doc."' readonly='readonly' /></td>";
					}
			   echo "</td>"; ?>
              <td><input type="text" name="val_pagar[<?php echo $tran_id; ?>]" id="val_pagar[<?php echo $tran_id; ?>]" onkeypress="return permite(event,'num')" /></td><?php
			   echo "<td><input name='descr[".$tran_id."]' id='descr[".$tran_id."]' type='text' onkeypress='return permite(event,'car')' /></td>";
			   echo "<td>
			         <select name='concep[".$tran_id."]' id='concep[".$tran_id."]'>
					  <option value='0'>Seleccione...</option>";
					while($concepto = mssql_fetch_array($conceptos))
					    echo "<option value='".$concepto['con_id']."'>".$concepto['con_nombre']."</option>";  
			    echo " </select></td>";
		  	   echo "<td><input type='radio' name='pagar' id='pagar' value='".$tran_id."' /></td>";
		 	  echo "<tr>";
			}
			$i++; 
		  }
		//  
		$_SESSION['nombres'] = $nombre;
		$_SESSION['direccion'] = $direccion;
		$_SESSION['credito'] =  $cred_sele;
		$_SESSION['cuen_credito'] = $cuen_sele;  
		echo "<tr><td colspan='8'><input name='boton' id='boton' type='submit' value='Pagar' /></td></tr>";  
		echo "</table>";
		echo "</center>";
		echo "</form>";
  }
  else
     echo "no paso la persona";
?>
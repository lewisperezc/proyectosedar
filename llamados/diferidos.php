<?php
 include_once('../clases/moviminetos_contables.class.php');
 include_once('../clases/cuenta.class.php');
 $ins_mov_contable = new movimientos_contables();
 $ins_cuenta = new cuenta();
 $i=0;
 $mes = $_POST['mes'];
 if($_POST['tipo']==1)
 {
	 $diferidos = $ins_mov_contable->diferidos_contratos($mes);
	 $html="";
	 while($row=mssql_fetch_array($diferidos))
	  {	
	    $gasto = $ins_cuenta->cue_gasto();
		$html.="<tr><td><input type='hidden' name='mov_id".$i."' id='mov_id".$i."' value='".$row['id_mov']."' /><input type='hidden' name='compro".$i."' id='compro".$i."' value='".$row['mov_compro']."' />".$row['mov_compro']."</td><td><input type='hidden' name='cue_dife".$i."' id='cue_dife".$i."' value='".$row['mov_cuent']."' /><input type='hidden' name='centro".$i."' id='centro".$i."' value='".$row['mov_cent_costo']."' />".$row['mov_cuent']."</td><td><input type='text' name='cue_gasto".$i."' id='cue_gasto".$i."' list='cuen_gasto".$i."' required /><datalist id='cuen_gasto".$i."'>";
		  
		  while($cuenta=mssql_fetch_array($gasto))
			$html.="<option value='".$cuenta['cue_id']."' label='".$cuenta['cue_id']." ".$cuenta['cue_nombre']."'>";
		$html.="</datalist>";
		$html.="<td><input type='hidden' name='val_dife".$i."' id='val_dife".$i."' value='".$row['mov_valor']."' />".$row['mov_valor']."</td><td><input type='hidden' name='cant".$i."' id='cant".$i."' value='".$row['con_vigencia']."' />".$row['con_vigencia']."</td><td><input type='hidden' name='val_diferir".$i."' id='val_diferir".$i."' value='".$row['mov_valor']/$row['con_vigencia']."' />".$row['mov_valor']/$row['con_vigencia']."</td><td><input type='hidden' name='tercero".$i."' id='tercero".$i."' value='".$row['mov_nit_tercero']."' /><input type='hidden' name='fec_ini".$i."' id='fec_ini".$i."' value='".$row['con_fec_inicio']."' />".$row['con_fec_inicio']."</td><td><input type='hidden' name='fec_fin".$i."' id='fec_fin".$i."' value='".$row['con_fec_fin']."' />".$row['con_fec_fin']."</td></tr>";
		$i++;
	  }
	  $html.="<input type='hidden' name='cant_contratos' id='cant_contratos' value='$i'";
 }
 else
 {
	 $diferidos = $ins_mov_contable->diferidos_causacion($mes);
	 $html="";
	 while($row=mssql_fetch_array($diferidos))
	  {	
	    $gasto = $ins_cuenta->cue_gasto();
		$html.="<tr><td><input type='hidden' name='mov_cau".$i."' id='mov_cau".$i."' value='".$row['id_mov']."' /><input type='hidden' name='compro_cau".$i."' id='compro_cau".$i."' value='".$row['mov_compro']."' />".$row['mov_compro']."</td><td><input type='hidden' name='cue_dife_cau".$i."' id='cue_dife_cau".$i."' value='".$row['mov_cuent']."' />".$row['mov_cuent']."</td><td><input type='text' name='cue_gastoCau".$i."' id='cue_gastoCau".$i."' list='cuen_gasto".$i."' size='10' required /><datalist id='cuen_gasto".$i."'>";
		  
		  while($cuenta=mssql_fetch_array($gasto))
			$html.="<option value='".$cuenta['cue_id']."' label='".$cuenta['cue_id']." ".$cuenta['cue_nombre']."'>";
		$html.="</datalist>";
		$html.="<td><input type='text' name='valor_cau".$i."' id='valor_cau".$i."' value='".$row['mov_valor']."' size='10' /><input type='hidden' name='centro_cau".$i."' id='centro_cau".$i."' value='".$row['mov_cent_costo']."' /></td><td><input type='text' name='meses_cau".$i."' id='meses_cau".$i."' onchange='calcular(".$row['mov_valor'].",this.value,$i);' size='10' required /></td><td><input type='text' name='val_dif_cau".$i."' id='val_dif_cau".$i."' size='10' /></td><td><input type='hidden' name='tercero_cau".$i."' id='tercero_cau".$i."' value='".$row['mov_nit_tercero']."' /><input type='text' name='fec_ini_cau".$i."' id='fec_ini_cau".$i."' size='10' required/></td><td><input type='text' name='fec_fin_cau".$i."' id='fec_fin_cau".$i."' size='10' required/></td></tr>";
		$i++;
	  }
	  	  $html.="<input type='hidden' name='cant_causaci' id='cant_causaci' value='$i'";
 }
echo $html;	  
?>
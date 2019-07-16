<?php
  include_once('../clases/saldos.class.php');
  include_once('../clases/nits.class.php');
  include_once('../clases/recibo_caja.class.php');
  include_once('../clases/bancos.class.php');
  include_once('../clases/credito.class.php');
  include_once('../clases/centro_de_costos.class.php');
  $centro=new centro_de_costos();
  $saldos = new saldos();
  $nit = new nits();
  $recibo = new rec_caja();
  $banco = new bancos();
  $inst_credito = new credito();
  $asociado = $nit->con_dat_nit(1);
  $fichero = $recibo->obt_consecutivo(25);
  $con_cen_costos=$centro->cen_cos_contrato();
  $centro = $_POST['centro'];
  $html="";
  
 $i=0;
 while($row = mssql_fetch_array($asociado))
 {
	 $salPagarDeb_fac = split("_",$saldos->saldos_cuenta_centro('25051001',$row['nit_id'],1,$centro),2);
	 $salPagarCre_fac = split("_",$saldos->saldos_cuenta_centro('25051001',$row['nit_id'],2,$centro),2);
	 if($salPagarDeb_fac[0])
	    $salPagarDeb = $salPagarDeb_fac[0];
	 else 
	    $salPagarDeb = 0;
		
	 if($salPagarCre_fac[0])
	    $salPagarCre = $salPagarCre_fac[0];
	 else
	    $salPagarCre = 0;
	 $resta = $salPagarCre-$salPagarDeb;

	 if($salPagarCre-$salPagarDeb>0)
	 {
		 $cons_cue_bancarias = $inst_credito->cuentas_bancarias();
		 $dat_banco = $banco->datBancos($row['nits_ban_id']);
		 $datos_banco = mssql_fetch_array($dat_banco);
		 $html.="<tr id='tr".$i."'><input type='hidden' name='aso_id".$i."' id='aso_id".$i."' value='".$row['nit_id']."' /><input type='hidden' name='num_fac".$i."' id='num_fac".$i."' value='".$salPagarCre_fac[1]."' />";
    	 $html.="<td>".$row['nits_num_documento']."</td><td>".$row['nombres']."</td><td><input name='fichero' id='fichero' type='text' value ='FICH-".$fichero."' readonly='readonly' /></td>";
		 $html.="<td>".$datos_banco['banco']."</td><td>".$row['nits_num_cue_bancaria']."</td><td><input type='text' name='val_pagar".$i."' id='val_pagar".$i."' value='".$resta."' /></td>";
		 $html.="<td><select name='cue_ban".$i."' id='cue_ban".$i."'>";
               while($cuentas = mssql_fetch_array($cons_cue_bancarias))
                  $html.="<option value='".$cuentas['cue_id']."'>".substr($cuentas['cue_nombre'],0,16)."</option>";
         $html.="</select></td>";
		$html.="<td><input type='checkbox' name='pagar".$i."' id='pagar".$i."'/></td></tr>";
   		$i++;
	 }
 }
 $html.="<tr id='".($i+1)."'><td colspan='8'><input type='hidden' name='cant' id='cant' value='".$i."'/></td></tr>";
 echo $html;
?>
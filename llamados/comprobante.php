<?php session_start();

ini_set('memory_limit', '-1');

include_once('../conexion/conexion.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/mes_contable.class.php');
?>
<script type="text/javascript">
 function cambiar_valor(valor,pos)
 {
 	$("#nom_gua"+pos).val(valor);
 }
</script>
<?php
$comprobante = $_POST['compro'];
$mes = $_POST['mes'];
$movimiento = new movimientos_contables();
$nit = new nits();
$centro = new centro_de_costos();
$cuenta = new cuenta();
$concep = new concepto();
$mes_contable = new mes_contable();
$mov_contable = $movimiento->consultar_movimiento_contable($comprobante,$mes,$_SESSION['elaniocontable']);
error_reporting(E_ALL);
$html = "<table id='doc_completo' border='1'><tr><td>Cod. cuenta</td><td>Nombre cuenta</td><td>NIT</td><td>Tercero</td><td>Centro de Costo</td><td>Debito</td><td>Credito</td><td>Eliminar?</td></tr>";
$encabezado="";
$i=0;$p=0;$tot_debito=0;$tot_credito=0;
if($mov_contable!=false)
  {
    while($unarray = mssql_fetch_array($mov_contable))
	{
	  $tipos="3,11,2,1,14,9,7,5,11,13,8";
	  $beneficiarios = $nit->ConProFondo($tipos);
	  $nom_comcepto = $concep->getcon_nombre($unarray['mov_concepto']);
	  $cen = $centro->buscar_centros($unarray['mov_cent_costo']);
	  $nit_mov = $unarray['mov_nit_tercero'];
	  $nom_nit = $nit->consul_nits($nit_mov);
	  if($nom_nit)
	  {
	  	$dat_nit = mssql_fetch_array($nom_nit);
		$documento=$dat_nit['nits_num_documento'];
		$nombres=$dat_nit['nits_nombres'];
		$apellidos=$dat_nit['nits_apellidos'];
		$tercero=$dat_nit['nit_id'];
	  }
	  else
	  {
	    $documento="";
		$nombres="";
		$apellidos="";
		$tercero=$nit_mov;
	  }
	  $dat_centro = mssql_fetch_array($cen);
	  if($unarray['mov_tipo']==1)
	  {
	  	$debito=$unarray['mov_valor'];
		$tot_debito+=$debito;
		$credito=0;
	  }
	  else
	  {
	    $debito=0;
		$credito=$unarray['mov_valor'];
		$tot_credito+=$credito;
	  }
	  if($p==0)
	  	{
			$encabezado .= '<tr id="tr'.$i.'"><td><input type="text" name="compro" id="compro" value="'.$unarray['mov_compro'].'" /><input type="hidden" name="nume" id="nume" value="'.$unarray['mov_nume'].'" /></td><td><input type="text" name="fecha" id="fecha" value="'.$unarray['mov_fec_elabo'].'" /></td></tr>';
			$p++;
		}
	  $nom_cuenta = $cuenta->getnomCuenta($unarray['mov_cuent']);	
	  $html .= '<tr id="tr'.$i.'"><td><input type="text" name="cuenta'.$i.'" id="cuenta'.$i.'" value="'.$unarray['mov_cuent'].'" size="10" list="cuen'.$i.'" /><datalist id="cuen'.$i.'">';
	  $cuen_cau=$cuenta->busqueda('no');
      while($dat_cuentas = mssql_fetch_array($cuen_cau))
      	$html.='<option value="'.$dat_cuentas['cue_id'].'" label="'.$dat_cuentas['cue_id'].' '.$dat_cuentas['cue_nombre'].'">';
	  $html .= '</datalist><input type="hidden" name="conce_gua" id="conce_gua" value="'.$unarray['mov_concepto'].'" /></td><td><input type="text" name="nom_cuenta'.$i.'" id="nom_cuenta'.$i.'" value="'.$nom_cuenta.'" size="10" readonly/></td><td><input type="text" name="docu'.$i.'" id="docu'.$i.'" value="'.$documento.'" size="10" list="nit_id'.$i.'" onchange="cambiar_valor(this.value,'.$i.')"/><datalist id="nit_id'.$i.'">';
	  while($dat_aso = mssql_fetch_array($beneficiarios))
	    $html.='<option value="'.$dat_aso['nit_id'].'" label="'.$dat_aso['nits_num_documento'].' '.$dat_aso['nits_nombres'].' '.$dat_aso['nits_apellidos'].'">';
	  $html .='</datalist></td><td><input type="text" name="nombre'.$i.'" id="nombre'.$i.'" value="'.$nombres.' '.$apellidos.'" size="15" readonly /><input type="hidden" name="nom_gua'.$i.'" id="nom_gua'.$i.'" value="'.$tercero.'" /></td><td><input type="text" name="centro'.$i.'" id="centro'.$i.'" value="'.$dat_centro['cen_cos_nombre'].'" size="10" readonly /><input type="hidden" name="cen_gua'.$i.'" id="cen_gua'.$i.'" value="'.$dat_centro['cen_cos_id'].'" /></td><td><input type="text" id="debito'.$i.'" name="debito'.$i.'" value="'.number_format($debito,0).'" size="10" readonly/></td><td><input type="text" name="credito'.$i.'" id="credito'.$i.'" value="'.number_format($credito,0).'" size="10" readonly/></td><td><input type="radio" name="fil'.$i.'" id="fil'.$i.'" value="'.$i.'" onchange="eliminarCon('.$i.');" disabled="disabled" /></td></tr>';
	   $i++;
	}
	$html.='</table><table id="total"><tr id="tr'.$i.'"><td colspan="5">Total</td><td>'.number_format($tot_debito,2).'</td><td>'.number_format($tot_credito,2).'<input type="hidden" name="can_registros" id="can_registros" value="'.$i.'" /></td></tr></table>';
    $html .="";$encabezado.="";
    echo $encabezado."/-/".$html;
  }
?>
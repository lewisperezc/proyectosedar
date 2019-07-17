<?php
@include_once('../conexion/conexion.php');
include_once('tipo_concepto.class.php');

class concepto
{
    private $con_nombre;
	private $form_for_id;
	private $con_id;private $con_reteica;
	private $for_cue_afecta1;private $for_cue_afecta2;private $for_cue_afecta3;private $for_cue_afecta4;
	private $for_cue_afecta5;private $for_cue_afecta6;private $for_cue_afecta7;private $for_cue_afecta8;
	private $for_cue_afecta9;private $for_cue_afecta10;private $for_cue_afecta11;private $for_cue_afecta12;
	private $for_cue_afecta13;private $for_cue_afecta14;private $for_cue_afecta15;private $for_cue_afecta16;
	private $for_cue_afecta17;private $for_cue_afecta18;private $for_cue_afecta19;private $for_cue_afecta20;
	private $tip_concepto;
	
	
	public function __construct()
  	{
		$this->tip_concepto = new tipo_concepto();
	}
	
	public function con_tip_concepto($id_tip_concepto)
	{
		return $this->tip_concepto->con_tip_concepto($id_tip_concepto);
	}
	
	public function setform_for_id($form_for_id)
	{
	$this-> cform_for_id=$form_for_id;
	}
	
	public function getform_for_id()
	{
	return $this->form_for_id ;
	}
	
	public function setcon_nombre($con_nombre)
	{
	$this->con_nombre =$con_nombre;
	}
	
	public function getcon_nombre($concep)
	{
	  $sql="SELECT con_nombre FROM conceptos WHERE con_id = $concep";
	  //echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	    {
			$nom_concepto = mssql_fetch_array($query);
			$this->con_nombre = $nom_concepto['con_nombre'];
		}
	  return $this->con_nombre;
	}
	
	public function cons_cuenta()//busca debito o credito
    {
	 $sql="SELECT * FROM tipo_cuenta";
	 $con_cuenta=mssql_query($sql);
	 return $con_cuenta;
    }
	
	public function consulta_concepto()
      {	
	    $sqlcuet = "SELECT con_id,con_nombre FROM conceptos";
		$cuet = mssql_query($sqlcuet);
		return $cuet;
     }
	
public function inser_formulas($for_cue_afecta1,$for_cue_afecta2,$for_cue_afecta3,$for_cue_afecta4,
				$for_cue_afecta5,$for_cue_afecta6,$for_cue_afecta7,$for_cue_afecta8,$for_cue_afecta9,$for_cue_afecta10,
				$for_cue_afecta11,$for_cue_afecta12,$for_cue_afecta13,$for_cue_afecta14,$for_cue_afecta15,$for_cue_afecta16,
				$for_cue_afecta17,$for_cue_afecta18,$for_cue_afecta19,$for_cue_afecta20)
	{
	$sqlinser=" INSERT INTO formulas (for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4, for_cue_afecta5,
	             for_cue_afecta6,for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,
				 for_cue_afecta12,for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,
				 for_cue_afecta18,for_cue_afecta19,for_cue_afecta20)				
	            VALUES('$for_cue_afecta1','$for_cue_afecta2','$for_cue_afecta3','$for_cue_afecta4','$for_cue_afecta5',
				'$for_cue_afecta6','$for_cue_afecta7','$for_cue_afecta8','$for_cue_afecta9','$for_cue_afecta10',
				'$for_cue_afecta11','$for_cue_afecta12','$for_cue_afecta13','$for_cue_afecta14','$for_cue_afecta15',
                '$for_cue_afecta16','$for_cue_afecta17','$for_cue_afecta18','$for_cue_afecta19','$for_cue_afecta20')";
	 $inser=mssql_query($sqlinser);
	 return $inser;		
	}
	
public function obtener_max_formula()
	{		
		$quer= "SELECT MAX(for_id) max_id FROM formulas";
		$ejecuta=mssql_query($quer);
		return $ejecuta;
		}
		
public function insert_concepto($con_id,$form_for_id,$con_nombre,$con_reteica,$tipo_concepto)
      {
		$sql="insert into conceptos(con_id,form_for_id,con_nombre,con_reteica,con_tipo) VALUES($con_id,$form_for_id,'$con_nombre',$con_reteica,$tipo_concepto)";
			$res =mssql_query($sql);
		return $res;
	  }	
	
  public function verificar_existe($con_id)
    {	
		$sqlcuet="select con_id,con_nombre from dbo.conceptos where con_id =$con_id";
		$cuet =mssql_query($sqlcuet);
		return $cuet;			
    }
	
	
	public function busca_concepto($id_concep)
	{
		$sql="select con_id,con_nombre,for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4,for_cue_afecta5,for_cue_afecta6,
for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,for_cue_afecta12,
for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,for_cue_afecta18,
for_cue_afecta19,for_cue_afecta20 from dbo.conceptos co inner join dbo.formulas fo on co.form_for_id = fo.for_id
where con_id =$id_concep ";
		$res =mssql_query($sql); 	
		return $res;
	}
	
	
	public function elimi_concepto($concep)
		{
		$sql_eli="declare @var int select @var=form_for_id from dbo.conceptos where con_id=$concep
				  delete from dbo.formulas where for_id=@var";
		$res = mssql_query($sql_eli);
		return $res;
		}
	
	public function upd_conc($id,$for1,$for2,$for3,$for4,$for5,$for6,$fo7,$for8,$for9,$for10,$for11,$for12,$for13,$for14,$for15,$for16,$for17,$for18,$for19,$for20)
	{
	$sql_update="execute Upd_formulas 
	$id,'$for1','$for2','$for3','$for4','$for5','$for6','$fo7','$for8','$for9','$for10','$for11','$for12','$for13',
					'$for14','$for15','$for16','$for17','$for18','$for19','$for20'";			
		$res = mssql_query($sql_update);
		return $res;
	}
	
	public function tiene_ica($concepto)
	{
		$sql = "SELECT con_reteica FROM conceptos WHERE con_id = $concepto";
		//echo $sql;
		$query = mssql_query($sql);
		$cue_ica = mssql_fetch_array($query);
		if($cue_ica['con_reteica'] == 1)
		  return true;
		else
		  return false;  
	}
	
	public function consulta_tip_conceptos()
	{
		$query = "select * from dbo.tipos_conceptos";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function crear_tipo_concepto($nombre,$numero)
	{
		$query = "insert into tipos_conceptos values ('$nombre',$numero)";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function verificar_tipo_concepto($tipo)
     {	
		$sqlcuet="select tip_concep_concecutipo from dbo.tipos_conceptos where tip_concep_concecutipo=$tipo";
		$cuet =mssql_query($sqlcuet);
		return $cuet;
    }
	
//////////////////////////////////////////////////////////////////////////////
	public function gru_notas()
	{
		$sql = "SELECT * FROM tipos_conceptos WHERE tip_concep_id in (118,30,109)";
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false;
	}
	
	public function gru_recibos()
	{
		$sql = "SELECT * FROM tipos_conceptos WHERE tip_concep_id in (109)";
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false; 
	}
	
////////////////////////////////////////////////////////////////////////////////	
	public function gru_facturacion()
	{
		$sql = "SELECT * FROM tipos_conceptos WHERE tip_concep_id = 4";
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false; 
	}
	
	public function gru_pabs()
	{
		$sql = "SELECT * FROM tipos_conceptos WHERE tip_concep_id = 112";
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false; 
	}
	
	public function concep_credito()
	{
		$sql = "SELECT * FROM conceptos WHERE con_tipo = 1";
		$query = mssql_query($sql); 
		if($query)
		 return $query;
		else
		 return false;
	}
	
	public function concep_provision()
	{
		$sql = "SELECT * FROM conceptos WHERE con_tipo = 116";
		$query = mssql_query($sql); 
		if($query)
		 return $query;
		else
		 return false;
	}
	
	public function conceptos($filtro,$no_trae)
	{
		$sql = "SELECT * FROM conceptos WHERE con_tipo in ($filtro) AND con_id NOT IN($no_trae)";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false;
	}
	
	public function ins_por_nomina($compensacion,$aportes,$legalizacion,$gastos,$educacion,$cantidad)
	{
		$sql = "UPDATE datos_nomina SET dat_nom_compensacion = $compensacion,dat_nom_aportes = $aportes,dat_nom_legalizacion = $legalizacion,dat_nom_gastos = $gastos,dat_nom_educacion = $educacion,dat_can_nom_compensacion=$cantidad WHERE dat_nom_id = 1";
		$query = mssql_query($sql);
		if($query)
		 return true;
		else
		 return false;
	}
	
	public function con_por_nomina()
	{
		$sql = "SELECT * FROM datos_nomina";
		$query = mssql_query($sql);
		if($query)
		 return $query;
		else
		 return false;
	}
	
	public function conceProducto($producto)
	{
		$sql = "SELECT con.con_id conce FROM conceptos con INNER JOIN tipo_producto tp ON tp.concepto = con.con_id 
				INNER JOIN productos pro ON pro.tip_pro_id=tp.tip_pro_id WHERE pro.pro_id = $producto";	
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		{
			$dat_con = mssql_fetch_array($query);
			return $dat_con['conce'];
		}
		else
		  return false;
	}
	
	public function concepFacturacion($concepto)
	{
		$sql = "SELECT * FROM conceptos WHERE con_id = $concepto AND con_tipo IN (118)";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
}
?>
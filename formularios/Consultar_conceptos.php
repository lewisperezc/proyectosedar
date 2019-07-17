<?php  
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 ?>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" type="text/javascript" src="librerias/ajax/select_actu_cuenta.js"></script>

<script>
function valida()
{
	document.conOrdCom.submit();
}
</script>
<?php 
 @include('../clases/concepto.class.php');
 //@include('./clases/concepto.class.php');
 //@include('clases/concepto.class.php');
 $concepto = new concepto(); 
 function generaOpciones()
 {
   echo "<select name='concep' id='select1' onChange='cargaContenido(this.id)'>
           <option value=''>Seleccione...</option>
		   <option value='1'>CONSULTAR CONCEPTOS</option>
	       <option value='2'>EDITAR CONCEPTOS</option>
	       <option value='3'>ELIMINAR CONCEPTO</option>
         </select>";
 }
?>
<script language="javascript" src="./librerias/ajax/select_conceptos.js"></script>
<script>
function modificar()
  {
    for (i=0;i<document.forms[1].elements.length;i++) 
	  {
        if (document.forms[1].elements[i].disabled) 
		{
          document.forms[1].elements[i].disabled = false;
		  conOrdCom.posicion.focus();
        }
      }
  }
function eli()
	{
		if(document.proOrdCom.posicion_elimi=="")
		{
			alert("Debe digitar algun valor");
			return false;
		}		
			document.proOrdCom.submit();
	}
	
function enviar()
	{
		document.proOrdCom.submit();
	}
</script>
<form id="conOrdCom" name="conOrdCom" method="post">
 <center>
    <table id="conOrd">
    <tr>
      <td colspan="2">Consulta y modificacion de Conceptos</td>
      
     </tr>
     <tr>
      <td>Consultar por: </td>
      <td><?php generaOpciones(); ?></td>
     </tr>
	 <tr>
	   <td>Numero: </td>
	   <td><select name="dato" size="1" disabled="disabled" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Selecciona opci&oacute;n...</option>
          </select></td>
	 </tr>
   </table>
 </center> 
</form>
<?php
	$consulta = $_POST['concep'];
	$_SESSION['consulta']=$consulta;	
	$captura = $_POST['select2'];
	$_SESSION['captura']=$captura;
	$i=0;
    if($_SESSION['captura'])
	{
?>

<form id="proOrdCom" name="proOrdCom" method="post"   action="control/eliminar_cuenta.php">
 <center>
   <?php
   //1
     if($_SESSION['consulta'] == 1)
	   {
	     include_once('clases/moviminetos_contables.class.php');
         $form = new movimientos_contables();
         $formula =$form->consul_formulas($captura);
		  if($formula)
		  {
		    echo "<table id='ordCom'>";
		    echo "<td colspan='4'>El concepto: <input name='$concep' zise='3'disabled='disabled' id='$concep' type='text'  value='$captura' /> 

tiene las siguientes cuentas</td>";
		    echo "<tr>";
		echo "<td><input name='posicion' disabled='disabled' id='posicion' type='text' value='posicion' /></td>";
		echo "<td><input name='cuenta' disabled='disabled' id='cuenta' type='text' value='cuenta' /></td>";
		echo "<td><input name='naturaleza' disabled='disabled' id='for_cue_afecta2' type='text' value='naturaleza' /></td>";
		echo "<td><input name='contrapartida' disabled='disabled' id='for_cue_afecta2' type='text' value='contrapartida' /></td>"; 
		   echo "</tr>";
      $row = mssql_fetch_array($formula);
       while($i<=21)
       {
	     $arre = split(",",$row["for_cue_afecta".$i]);
		 //echo sizeof($arre)."<br>";
		 $a = $arre[0];
		 $b = $arre[1];
		 $c = $arre[2]; 
		 $d = $arre[3];
		 if($a != "" && $b != "" && $c != "")
		 	{
			   if($c)
			   {
			     $natu =$form->consultar_nat($c);
			    while($tip = mssql_fetch_array($natu))
			  	 {
			      echo "<tr>";
			      echo "<td><input name='posicion' disabled='disabled' id='posicion' type='text' value='$a' /></td>";
			      echo "<td><input name='cuenta' disabled='disabled' id='cuenta' type='text' value='$b' /></td>";
			      echo "<td><input name='naturaleza' disabled='disabled' id='naturaleza' type='text' value='$tip[tip_cue_nombre]' /></td>";
			      echo "<td><input name='contrapartida' disabled='disabled' id='contrapartida' type='text' value='$d' /></td>";
			      echo "</tr>";
				}
			  }
			}
		 $i++;	
		}	
		echo "</table>";
		  }
		 else
	        echo "<script type=\"text/javascript\">alert(\"No se pudo intentelo de nuevo!!\");</script>";    
	 }
	//2
	 elseif( $_SESSION['consulta'] == 2 ) 
	 {
		include_once('clases/moviminetos_contables.class.php');
		$form = new movimientos_contables();
		$formula =$form->consul_formulas($captura);
		if($formula)
		 {
		    echo "<table id='ordCom'>";
			echo "<tr>";
		    echo "<td colspan='4'>El Concepto : <input name='con_update' zise='3' readonly='readonly' id='con_update' type='text' value='$captura' /> se va ha modificar y tiene las siguientes cuentas: 
			</td></tr><tr>";
		    echo "<td><input name='posicion1' disabled='disabled' id='posicion1' type='text' value='posicion' /></td>";
		    echo "<td><input name='cuenta1' disabled='disabled' id='cuenta1' type='text' value='cuenta' /></td>";
		   echo "<td><input name='naturaleza1' disabled='disabled' id='naturaleza1' type='text' value='naturaleza' /></td>";
		   echo "<td><input name='contrapartida1' disabled='disabled' id='for_cue_afecta21' type='text' value='contrapartida' /></td></tr>";
           $row = mssql_fetch_array($formula);
           while($i<=21)
             {
		       $arre = split(",",$row["for_cue_afecta".$i]);
		       $a = $arre[0];
		       $b = $arre[1];
		       $c = $arre[2]; 
		       $d = $arre[3];
		       if($a != "" && $b != "" && $c != "")
		 	    {
			     $matriz[$i][0]= $a;
			     $matriz[$i][1]= $b;
			     $matriz[$i][2]= $c; 
			     $matriz[$i][3]= $d;
			    }
		       $i++;	
		      }
			  
 for($i=1;$i<=sizeof($matriz);$i++)
  {
   echo "<tr>";
   echo "<td><input name='posicio[".$i."]'  id='posicio[".$i."]' type='text' readonly='readonly' value='".$matriz[$i][0]."' /></td>";
   echo "<td><select name='cuenta[".$i."]' id='cuenta[".$i."]'>";
   $cuent =$form->consul_cuenta('no');
   while($naturales=mssql_fetch_array($cuent)) 
	 {
	  if (trim($naturales['cue_id']) == trim($matriz[$i][1]))
	  {
          echo "<option value='".$naturales['cue_id']."' selected='selected'>".$naturales['cue_id'].'--'.$naturales['cue_nombre']."</option>"; 
	  }
	  else	
        echo "<option value='".$naturales['cue_id']."'>".$naturales['cue_id'].'-else-'.$naturales['cue_nombre']."</option>";
	  }
    echo "</select></td>";
	////////////////////////////////////////////////////////////////////////////
	echo "<td><select name='nat[".$i."]' id='nat[".$i."]'>";
    $nat =$form->consul_nat();
	 while($natural = mssql_fetch_array($nat)) 
	   { 
		 if($matriz[$i][2]==$natural['tip_cue_id'])
            echo "<option value='".$natural['tip_cue_id']."' selected='selected'>".$natural['tip_cue_nombre']."</option>"; 
		 else	
            echo "<option value='".$natural['tip_cue_id']."'>".$natural['tip_cue_nombre']."</option>";
	   }
     echo "</select></td>";
	 /////////////////////////////////////////////////////////////////////////////////				
echo "<td><input name='contraparti[".$i."]' readonly='readonly' id='contraparti[".$i."]' type='text' value='".$matriz[$i][3]."' /></td>"; 
   echo "</tr>";
 }

 echo "<tr>
     <td colspan='4'><input type='button' onclick='enviar();'  name='editar' id='editar' value='Guardar' />
	  </td>
    </tr>";
			echo "</table>";
		  }
		 else
	        echo "<script type=\"text/javascript\">alert(\"No se pudo traer las ordenes de compra del centro de costo, intentelo de nuevo!!\");</script>";    
	 } 
	//3 
	 elseif($_SESSION['consulta'] == 3 ) 
	 {
	  include_once('clases/moviminetos_contables.class.php');
$form =new movimientos_contables();
$formula =$form->consul_formulas($captura);
		  if($formula)
		  {
		    echo "<table id='ordCom'>";
		 echo "<td colspan='4'>El concepto<input name='concepto_eli' zise='3' readonly='readonly'  id='concepto' type='text'  value='$captura' /> se va a eliminar</td>";

		    echo "<tr>";
		echo "<td><input name='posicion' disabled='disabled' id='posicion' type='text' value='posicion' /></td>";
		echo "<td><input name='cuenta' disabled='disabled' id='cuenta' type='text' value='cuenta' /></td>";
		echo "<td><input name='naturaleza' disabled='disabled' id='naturaleza' type='text' value='naturaleza' /></td>";
		echo "<td><input name='contrapartida' disabled='disabled' id='for_cue_afecta2' type='text' value='contrapartida' /></td>"; 
		   echo "</tr>";
      $row = mssql_fetch_array($formula);
       while($i<=21)
       {
	     
	     $arre = split(",",$row["for_cue_afecta".$i]);
		
		 $a = $arre[0];
		 $b = $arre[1];
		 $c = $arre[2]; 
		 $d = $arre[3];
		 if($a != "" && $b != "" && $c != "")
		 	{
			  $matriz[$i][0]= $a;
			  $matriz[$i][1]= $b;
			  $matriz[$i][2]= $c; 
			  $matriz[$i][3]= $d;
			  if($c)
			  $natu =$form->consultar_nat($c);
			   while($tip = mssql_fetch_array($natu))
			  			  {
			   echo "<tr>";
			   echo "<td><input name='posicionn' disabled='disabled' id='posicion' type='text' value='$a' /></td>";
			 echo "<td><input name='cuentaa' disabled='disabled' id='cuenta' type='text' value='$b' /></td>";
			 echo "<td><input name='naturalezaa' disabled='disabled' id='naturaleza' type='text' value='$tip[tip_cue_nombre]' /></td>";
			 echo "<td><input name='contrapartidaa' disabled='disabled' id='fcontrapartida' type='text' value='$d' /></td>";
			  echo "</tr>";
			 
			  }
			}
		 $i++;	
		}

 
 echo "<tr>
 
     <td colspan='4'>
	 <input type='button' name='eliminar' id='eliminar' value='eliminar' onClick='eli();'/>
    </tr>";
	echo "</table>";
	}
	else
	echo "<script type=\"text/javascript\">alert(\"No se pudo intentelo de nuevo!!\");</script>"; 
} 
   unset($_SESSION['consulta']);
   unset($_SESSION['captura']);
   ?>
 </center> 
</form>
<?php
   }
?>
 
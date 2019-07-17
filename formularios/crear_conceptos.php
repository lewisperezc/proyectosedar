<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script language="javascript" src="./librerias/js/validacion_num_letras.js"></script>
<link rel="stylesheet" href="../estilos/limpiador.css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" type="text/javascript" src="./librerias/ajax/select_tipo_conceptoss.js"></script>
<!-- para que funcione utilizo select_tipo_conceptoss- porque el de sin ss lo utiliza fredy  -->
<script>function a(){if(document.f1.numero.value == ""){alert('Debes digitar  por lo menos 1!!!');document.f1.numero.focus();}	}</script>
<script>
function valida_blancos()
{
	
		if(document.f2.concep1.value==""){
		alert("digite el numero de concepto a crear");
		document.f2.concep1.focus();		
		return false;
		}
		if(document.f2.descrip1.value==""){
		alert("digite la descripcion del concepto a crear");
		document.f2.descrip1.focus();		
		return false;
		}
		if(document.f2.reteica.selectedIndex==0){
			alert('seleccione si el concepto manejara reteica ');
			document.f2.reteica.focus();
			return false;
		}
		if(document.f2.cuent21.selectedIndex==0){
			alert('seleccione una cuenta base para este concepto ');
			document.f2.cuent21.focus();
			return false;
		}if(document.f2.tip1.selectedIndex==0){
			alert('seleccione la naturaleza de la cuenta base');
			document.f2.tip1.focus();
			return false;
		}else 
			document.f2.submit();
}
</script>
 <?php
include_once('./clases/concepto.class.php');
include_once('./clases/cuenta.class.php');
$cuen = new cuenta();
$cuentt=$cuen->busqueda_T('no');
$tip =new concepto();
$tip1=$tip->cons_cuenta();
$concepto =$tip->consulta_concepto();
$tip =new concepto();
$tip1 =$tip->cons_cuenta();
function genera()
{
   include_once('./clases/concepto.class.php');
   $conc= new concepto();
   $revisar= $conc->consulta_tip_conceptos();
	echo "<select name='select1' id='select1' onChange='cargaContenido1(this.id)' >";
	echo "<option value='0'>Seleccione Tipo Producto</option>";
	 while($cue= mssql_fetch_array($revisar))
		echo "<option value='".$cue['tip_concep_id']."'>".$cue['tip_concep_concecutipo']."--".$cue['tip_concep_nombre']."</option>";
	echo "</select>";
}        
  ?> 
<form name="f1" method="post">
<center>
  <table>
  <tr>
  <th scope="col">DIGITE EL NUMERO DE CUENTAS QUE EL CONCEPTO VA A AFECTAR, POR  DEFECTO YA EXISTE UN CAMPO PARA LA CUENTA BASE  Y SU RESPECTIVA NATURALEZA</th>
  </tr>
  <tr>
  <th scope="col">LO MINIMO QUE PUEDES DIGITAR ES 1</th>
  </tr>
  <tr>
  <td># cuentas
  <input type="text" name="numero"/><input type="submit" class="art-button" value="CREAR CONCEPTO" onclick="a();"/></td>
  </tr>
  </table>
</center>
</form >
<?php
$numero = $_POST['numero'];
if($numero>=1 && $numero<=19)
{
?>
<form name="f2" action="./control/guardar_concepto.php" method="post">
<center>
  
         <table width="200" border="1" id="tabla1" align="center">
    <tr>
            <th scope="col" colspan="4" >LOS COMPOS CON * SON OBLIGATORIOS</th>
            </tr>
       <tr>
    
    <th scope="col" colspan="4">*  SERIES DE CONCEPTOS</th>
    </tr>
  <td colspan="4"><?php genera(); ?>
     </td> 
</tr>  
<!-- tablas  inicio fin-->
 <tr>
   <th scope="col" colspan="4">CONCEPTOS EXISTENTES</th>
   </tr>
  <tr>
    <td colspan="4"><select name="select2" size="1" disabled="disabled" id="select2" >
           <option value="">Seleccione</option>
        </select>    </td> 
 
  
     </td> 
</tr>  
<!-- tablas inicio fin -->
    <tr><tr>
          <th colspan="4" align="center" > CREAR CONCEPTOS</th>
      </tr>
        
       <tr>
    <th scope="col " colspan="2">NUMERO CONCEPTO</th>
    <th scope="col" colspan="2">DESCRIPCION  CONCEPTO </th>
   
     </tr>
      <tr>
    <td colspan="2">*<input type="text" name="concep1" id="concep1" onKeyPress="return permite(event, 'num')"  value=""/></td>
    <td colspan="2">*<input type="text" name="descrip1" id="descrip1" onKeyPress="return permite(event, 'car')" value=""/></td>
   
            </tr>
             <tr>
          <th colspan="2" align="center" >ESTE CONCEPTO TIENE RETE ICA</th>
            <td colspan="2">*
            <select name="reteica"  >
            <option value="0">seleccione</option>
            <option value="1">si</option>
            <option value="2">no</option>
            </select>
            </td>
      </tr>
            <tr>
          <th colspan="4" align="center" >CREAR FORMULAS CONTABLES</th>
      </tr>
            
            <Tr>
             <th scope="col" colspan="2">CUENTA BASE</th>
     <th scope="col" colspan="2">NATURALEZA CUENTA BASE</th></Tr>
     <tr>
     
      <td colspan="2"><input type="text" size="2" value="1" name="id_num" readonly="readonly" />*<select name="cuent21">
           <option value="" >--Seleccione  cuenta base--</option>
       <?php
             while($cue = mssql_fetch_array($cuentt))
             {
              ?>
         <option value="<?php echo $cue['cue_id']; ?>"><?php echo $cue['cue_id']."--".$cue['cue_nombre']; ?></option>
             <?php
             }          ?>
            </select></td>
    <td  colspan="2">*<select name="tip1">
           <option value="0" onclick="ver_documento();">--Seleccione  naturaleza--</option>
       <?php
             while($row = mssql_fetch_array($tip1))
             {
              ?>
         <option value="<?php echo $row['tip_cue_id']; ?>"><?php echo $row['tip_cue_nombre']; ?></option>
             <?php
             }          ?>
            </select></td>
     </tr>
     
<tr>
   <th scope="col" colspan="2" >CUENTA   QUE AFECTA</th>
    <th scope="col" colspan="2">NATURALEZA CUENTA</th>
   
  </tr>
  <tr>
  <th scope="col" > UBICACION           </th>
  <th scope="col" > CUENTA</th>
   
   <th scope="col" colspan="1">SELECCIONE</th>
    <th scope="col" colspan="1">SELECCIONA </th>
   
  </tr>
  
 <tr>  
<?php
$cuent = new cuenta();
$tipp =new concepto();
$j=1;

  for($index = 1; $index <= $numero; $index++)
  {
  $j++;
      $t=$t+$index;
  $c=$c+$index;
  $t =$tipp->cons_cuenta();
  $c= $cuent->busqueda_T('no');
  ?><tr>
         <td colspan="2"><input type="text" size="2" value="<?php echo $j ; ?>" name="num[<?php echo $j ; ?>]" readonly="readonly" />          *<select name="sel_cuen[<?php echo $index; ?>]" id="sel_cuen[<?php echo $index; ?>]">
           <option value="" >--Seleccione  cuenta que afecta--</option>
       <?php
             while($cue = mssql_fetch_array($c))
             {
              ?>
         <option value="<?php echo $cue['cue_id']; ?>"><?php echo $cue['cue_id']."--".$cue['cue_nombre']; ?></option>
             <?php
             }          ?>
            </select></td>
      <td colspan="2">*<select name="sel_tip[<?php echo $index; ?>]" id="sel_tip[<?php echo $index; ?>]">
           <option value="" >--naturaleza--</option>
       <?php
             while($row = mssql_fetch_array($t))
             {
              ?>
         <option value="<?php echo $row['tip_cue_id']; ?>"><?php echo $row['tip_cue_nombre']; ?></option>
             <?php
             } ?>
            </select>
            *<select name="cntr[<?php echo $index; ?>]" id="cntr[<?php echo $index; ?>]">
           <option value="" >-contrapartida-</option>
           <?php
       $num =0;
       $num=$numero+1;
            for($ind =1; $ind <=$num; $ind++)
             {
              ?>
         <option value="<?php echo $ind; ?>"><?php echo $ind; ?></option>
             <?php 
            } ?>
            </select>
            
            </td>
                
            </tr>
            

    <?php
  }
  ?>
    <tr> <td colspan="4" >si no encuentra la cuenta deseada de click en el siguiente botton
    <input type="button" class="art-button" value="crear cuentas" name="Crear cuentas"  onClick="location.href='//192.168.0.53/contabilidad/index.php?c=5'"/></td></tr>
    <td colspan="4"><input type="button" class="art-button" value="guardar" name="guardar"  onclick="valida_blancos();"/>    </td>
<?php
}

else
echo "<script>
      alert('Debe digitar un numero entre 1 y 19.');
           </script>";
?>

</table>
</center>
</form >


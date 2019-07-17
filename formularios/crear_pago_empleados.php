<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<form name="pag_emp" id="pag_emp" action="#" method="post">
 <center>
  <table id="pago">
   <tr>
    <td>
    </td>
   </tr>
  </table>
 </center>
</form>
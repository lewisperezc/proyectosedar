<?php session_start();
  include_once('../clases/nits.class.php');
  include_once('../clases/moviminetos_contables.class.php');
  $nit=new nits();
  $mov_contable=new movimientos_contables();
  $cedulas="";
  $res="";
  $minimo=$mov_contable->con_uvt(1);
  foreach ($_FILES as $key) 
  {
    if($key['error'] == UPLOAD_ERR_OK )
    {//Verificamos si se subio correctamente
      if($key['name']!=""&&$key['type']=="application/vnd.ms-excel")
      {
      	 $destino =  "../../facturacionCon/archivos_planos/".$key['name'];
    	 if (copy($key['tmp_name'],$destino))
    	 	{
          $arc_abierto = fopen ("../../facturacionCon/archivos_planos/".$key['name'],"r");
          $i=0;
          $j=0;
          while($data=fgetcsv($arc_abierto,1000,";"))
          {
            if($i!=0)
            {
              if($data[3]==2&&$data[4]==1)
                $act=2;
              elseif($data[3]==2&&$data[4]==2)
                $act=1;
              elseif($data[3]==1&&$data[4]==1)
                $act=4;
              elseif($data[3]==1&&$data[4]==2)
                $act=3;
              $nit->act_tipSeguridad($data[0],$act);
              $res[$j]["cedula"] = $data[0];
              $res[$j]["ibc"] = $data[2];
              $res[$j]["pensionado"] = $data[3];
              $res[$j]["nit_id"]= $nit->busNit($data[0]);
              $res[$j]["minimo"]=$minimo;
              $cedulas=$cedulas.$res[$j]["nit_id"].",";
              $j++;
            }
            $i++;
          }
        }
        $cedulas=$cedulas."0";
        $sql="UPDATE nits SET nit_est_id=1 WHERE nit_id IN ($cedulas);
              UPDATE nits SET nit_est_id=3 WHERE nit_id NOT IN ($cedulas)";
        $query=mssql_query($sql);
        if($query)
          echo json_encode($res);
        else
          echo "1";
        //
      }
    }
    else
     echo $key['error'];
  }
?>
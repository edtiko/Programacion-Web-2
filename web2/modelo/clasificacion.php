<pre>
<?php 
require_once 'control/autoload.php';
require 'modelo/DataBase.class.php';
// echo $template->render(array());
$con = DataBase::getInstance();
$con->setQuery("select * from estudiante");
$v_studet = $con->loadArray();

foreach($v_studet as $indice => $student){  
  $grupo = 0;
  foreach($v_patrones as $key => $patron){    
    $varxpatron = 0;
    foreach($patron as $valor){   
      if( $student[ $valor['variable'] ] ==  $valor['valor'] ){
        $varxpatron++;    
      }
    }
    $v_studet[$indice]['cant_grupo'.$key] = $varxpatron;
    
    if($grupo < $varxpatron){
      $grupo = $varxpatron;
      $v_studet[$indice]['grupo'] = $key;
    }   
  } 
  $v_patrones[$v_studet[$indice]['grupo']]['cant']++; 
} 
?>
<table border="1">
 <tr>
   <th>Estudent</th>
   <th>grupo</th>
   <th colspan="5"></th>
 </tr>
 <?php foreach($v_studet as $value):?>
 <tr>
   <td><?php echo $value['id_estudiante'];?></td>
   <td><?php echo $value['grupo'];?></td>
   <td><?php echo $value['cant_grupo1'];?></td>
   <td><?php echo $value['cant_grupo2'];?></td>
   <td><?php echo $value['cant_grupo3'];?></td>
   <td><?php echo $value['cant_grupo4'];?></td>
   <td><?php echo $value['cant_grupo5'];?></td>
 </tr>
 <?php endforeach;?>
</table>

<?php
require_once '../modelo/logica_riesgo.php';

if ($_FILES['file']['name'] != '') {

    $name = $_FILES['file']['name'];
    $tname = $_FILES['file']['tmp_name'];
    $type = $_FILES['file']['type'];
    
    if ($type != 'text/plain') {
       echo "Solo es permitido subir archivos txt"; die();
    } 
     $valido= file_validate($tname);
    if(!$valido){
        echo "El archivo no tiene la estructura esperada"; die();
    }
else{
    $variables = getVariables($tname);
    //echo count($variables); die();
    $cont = count($variables);
    for($i=0; $i<$cont; $i++){
        if($i==$cont-1 || $i== $cont-2){
        unset($variables[$i]);
        }
    }
    array_values($variables);
   //  print "<pre>";    print_r($variables); die();
   $ret= LogicaRiesgo::guardarVariables($variables);
   if($ret){
       echo "Se Cargo con Exito el Archivo!";
   }
   else{
       echo "Ocurrieron Problemas al Cargar el Archivo!";
   }
}
}

function check($str) {
    
    $c = 1;
    while ($c) $str = str_replace('  ', ' ', $str, $c);

  
    $words = explode(' ', $str);
    foreach ($words as $key => $word) {
        
        unset($words[$key]);
       
        if (in_array($word, $words)) {
            return false;
        }
    }
    return true;
}
function file_validate($tname){
     $file = fopen($tname, "r");
    $i=0;
    $flag = 0;
   while(!feof($file)){
        $i++;
        $line =  fgets($file);
        $linealto = nl2br($line);
          if(strpos($linealto,"(")){
            $flag= $flag + 1;            
       }
         if(strpos($linealto,")")){
            $flag= $flag + 1;            
       }
      /* if(strpos($linealto,"==")){
            $flag= $flag + 1;            
       }*/
       
   }
   if($flag==2){
       return true;
   }
   else{
       return false;
   }
}
function getVariables($tname){
    $file = fopen($tname, "r");
    $i=0;
    $c=0;
    $x=0;
    $var = array();
    $cant= array();
    while(!feof($file)){
        $i++;
        $line =  fgets($file);
        $linealto = nl2br($line);
        if($i == 4  ){ // se obtienen las cantidades de los grupos
            
        for($j=0; $j<strlen($linealto); $j++){
           if( is_numeric($linealto[$j]) )
        {
               if(is_numeric($linealto[$j+1])){
                  $cant[$c] = $linealto[$j].$linealto[$j+1]; 
                  $j++;
               }
               else{
            $cant[$c] = $linealto[$j];
               }
            $c++;
        }
            
        }
        }
       if($i>3){
  
            $var[$x]=preg_split("/[\s,]+/", strip_tags($linealto), -1, PREG_SPLIT_NO_EMPTY);// se obtienen todas las variables
            //$var2[$x]=$estancia;
       $x++;
       }
        
    }
    return $var;
    fclose($file);
}
?>


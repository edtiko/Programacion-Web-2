<?php
  require 'DataBase.class.php';
  Class LogicaRiesgo {

    public static function newQuery($string_query) {
      $con = DataBase::getInstance();
      $con->setQuery($string_query);
      return $con->loadArray();
    }
    
    public static function cargarFiltros(){
	$programa = LogicaRiesgo::obtenerCampoDeEstudiante("id_programa");
            $facultad = LogicaRiesgo::obtenerCampoDeEstudiante("id_facultad");
            $sede     = LogicaRiesgo::obtenerCampoDeEstudiante("sede_universidad");
            $horario  = LogicaRiesgo::obtenerCampoDeEstudiante("jornada");
            $genero   = LogicaRiesgo::obtenerCampoDeEstudiante("genero");

            return array('genero' => $genero,'programa' => $programa,
                                    'facultad' => $facultad, 'sede' => $sede,
                                    'horario' => $horario, 'genero' => $genero);
}

     public static function guardarVariables($var){
         self::newQuery("delete from patron");
        $cont=0;
        for($i=0; $i<count($var); $i++){
          $nro_grupo= 0;  
            for($j=2; $j<count($var[$i]); $j++){
                $nro_grupo++;
           self::newQuery("insert into patron(grupo, variable, valor) values(".$nro_grupo.",'".self::stripText($var[$i][0])."','".self::stripText($var[$i][$j])."'); ");
           
            }
            $cont++;
        }
        if(count($var)==$cont){
            return true;
        }
        else{
            return false;
        }
    }
	public static function executeQuery($string_query) {
      $con = DataBase::getInstance();
      $con->setQuery($string_query);
      return $con->execute();
    }
	
    public static function obtenerEstudiantes() {
      return self::newQuery("select * from estudiante");
    }

    public static function obtenerEstudiantesPorParametros($parametros) {
      
      return self::newQuery("select * from estudiante");
    }

    public static function obtenerGruposIds() {
      return self::newQuery("select distinct grupo from patron");
    }

    public static function obtenerGrupoPorId($id) {
      return self::newQuery("select * from patron where grupo = '" .$id."'");
    }

    public static function obtenerGrupos() {
      $v_patrones = array();
      $grupos_ids = self::obtenerGruposIds();	  
      foreach($grupos_ids as $patron){ 
        $v_patrones[$patron['grupo']] = self::obtenerGrupoPorId($patron['grupo']);
      }
      return $v_patrones;
    }

    public static function obtenerCampoDeEstudiante($campo) {
      return self::newQuery("select distinct ".$campo." from estudiante");
    }

    public static function patrones() {
      return self::newQuery("select * from patron where variable='nivel_riesgo';");
    }
    
	public static function obtenerNivelRiesgo() {
	  $v = self::patrones();
	  $result = array();
	  foreach($v as $valor){
		$result[$valor['grupo']] = $valor['valor'];
	  }	
      return $result;
    }
	
	public static function obtenerEstudiantesFiltro($v =array()) {
	  $v_filtros = array("id_facultad","id_programa","jornada","sede_universidad","genero");
	  $sql = "select * from estudiante";		  
	  foreach($v_filtros as $param){
		if(!empty($v[$param])){
			$sql.= (preg_match("/estudiante$/", $sql) ? ' WHERE ' : ' AND ') .$param."='".$v[$param]."'";
		}
	  }	
      return self::newQuery($sql);
    }
	
	public static function obtenerEstudiantesSeleccionados($v =array()) {
		$sql = "select * from estudiante";
		if(!empty($v)){
			$sql.= " where id_estudiante in (".implode(",", $v).")";
		}
		return self::newQuery($sql);
	}
	
	public static function clasificacionEstudiantes($v =array()) {
		$v_studet = self::obtenerEstudiantesSeleccionados($v);	
	    $v_patrones	= self::obtenerGrupos();
		
		foreach($v_studet as $indice => $student){	
			$grupo = 0;
			$varporgrupos =array();
			foreach($v_patrones as $key => $patron){		
				$varxpatron = 0;
				foreach($patron as $valor){		
					if( $student[$valor['variable'] != 'nivel_riesgo' && $valor['variable'] ] ==  $valor['valor'] ){
						$varxpatron++;		
					}
				}
				if($grupo < $varxpatron){
					$grupo = $varxpatron;
					$v_studet[$indice]['grupo'] = $key;
				}
				$varporgrupos[$key] = $varxpatron;		
			}
			$v_studet[$indice]["A"] = $varporgrupos;
		}				
		return $v_studet;
    }
	
	public static function almacenarPatron($archivo){
		$fp = fopen($_FILES['uploadFile']['tmp_name'], 'rb');
		while ( ($line = fgets($fp)) !== false) {
			
		}
	}
	
	public static function clasificacionEstudiantesPorGrupo($v =array()) {
		$v_studet = self::obtenerEstudiantesSeleccionados($v);	
	    $v_patrones	= self::obtenerGrupos();
		$v_grupos = array();		
		foreach($v_studet as $indice => $student){	
			$grupo = 0;
			$grupo_key = 0;
			$varporgrupos =array();
			foreach($v_patrones as $key => $patron){		
				$varxpatron = 0;
				foreach($patron as $valor){		
					if($valor['variable'] != null && $valor['variable'] != 'nivel_riesgo' &&
						$student[ $valor['variable'] ] ==  $valor['valor'] ){
						$varxpatron++;		
					}
				}				
				if($grupo < $varxpatron){
					$grupo = $varxpatron;
					$grupo_key = $key;				
				}
				$varporgrupos[$key] = $varxpatron;		
			}
			if(is_array($r = self::obtenerEstudiantesRepetidos($varporgrupos,$grupo))){
				$student["grupos"] = $r;
				$v_grupos["R"][] = $student; 	
			}else{												
				$v_grupos[$grupo_key][] = $student;
			}			
		}	
		return $v_grupos;
    }
	
    private static function obtenerEstudiantesRepetidos($v =array(),$mayor){
    	$repetidos = array_keys($v, $mayor);
    	if(count($repetidos) > 1){
    		return $repetidos;
    	}
    	return false;
    }
    
	public static function guardarEstudiantesSeleccionados($v =array()) {	
		$currentdate = date("Y-m-d");                        
		$modulo = "Clusters";	
		
		$sql = "delete from nivel_desercion where modulo = '".$modulo."' and fecha_analisis = '".$currentdate."'";
		self::executeQuery($sql);
		if(!empty($v)){
			foreach($v as $estudiante){				
				$v_grupo = explode("-",$estudiante);
				if ($v_grupo[0] == "R"){
					$v_grupo[0] = $_POST[$estudiante];
				}
				$sql = "insert into nivel_desercion (id_estudiante,nivel,modulo,fecha_analisis) ";
				$sql.= " values (".$v_grupo[1].",'".$v_grupo[0]."','".$modulo."','".$currentdate."');";			
				self::executeQuery($sql);
			}	
		}		
	}
        
         public static function stripText($text)
  {
    // rewrite critical characters
    // French
    $text = str_replace(array('À', 'Â', 'à', 'â'), 'a', $text);
    $text = str_replace(array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ê', 'ë'), 'e', $text);
    $text = str_replace(array('Î', 'Ï', 'î', 'ï'), 'i', $text);
    $text = str_replace(array('Ô', 'ô'), 'o', $text);
    $text = str_replace(array('Ù', 'Û', 'ù', 'û'), 'u', $text);
    $text = str_replace(array('Ç', 'ç'), 'c', $text);
    // German
    $text = str_replace(array('Ä', 'ä'), 'ae', $text);
    $text = str_replace(array('Ö', 'ö'), 'oe', $text);
    $text = str_replace(array('Ü', 'ü'), 'ue', $text);
    $text = str_replace('ß', 'ss', $text);
    // Spanish
    $text = str_replace(array('Ñ', 'ñ'), 'n', $text);
    $text = str_replace(array('Á', 'á'), 'a', $text);
    $text = str_replace(array('Í', 'í'), 'i', $text);
    $text = str_replace(array('Ó', 'ó'), 'o', $text);
    $text = str_replace(array('Ú', 'ú'), 'u', $text);
    // Polish
    $text = str_replace(array('?', '?'), 'a', $text);
    $text = str_replace(array('?', '?'), 'c', $text);
    $text = str_replace(array('?', '?'), 'e', $text);
    $text = str_replace(array('?', '?'), 'l', $text);
    $text = str_replace(array('?', '?'), 'n', $text);
    $text = str_replace(array('?', '?'), 's', $text);
    $text = str_replace(array('Ó', 'ó'), 'o', $text);
    $text = str_replace(array('?', '?', '?', '?'), 'z', $text);
    
    // strip all non word chars
    $text = preg_replace('/[^a-z0-9_-]/i', ' ', $text);

    // strtolower is not utf8-safe, therefore it can only be done after special characters replacement
    $text = strtolower($text);

    // replace all white space sections with a dash
    $text = preg_replace('/\ +/', '-', $text);

    // trim dashes
    $text = preg_replace('/\-$/', '', $text);
    $text = preg_replace('/^\-/', '', $text);

    return $text;
  }
  }
?>

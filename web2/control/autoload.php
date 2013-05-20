 <?php 
require_once 'lib/Twig/lib/Twig/Autoloader.php';
require 'modelo/logica_riesgo.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('vista/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
    'debug' => 'true'
        ));        
$message = array("message_info"=>null,"message_success"=>null,"message_warning"=>null,"message_error"=>null);
        
if (isset($_GET['action'])) {
    
    switch ($_GET['action']) {
        
        case 'consulta':
            echo $twig->render('consultas.html.twig', LogicaRiesgo::cargarFiltros());
            break;
        
        case 'cargar':
            echo $twig->render('cargar_archivo.html.twig');
            break;
        
        case 'graph':
            echo $twig->render('graficas.html.twig');
            break;
        
        case 'bajo':
            echo $twig->render('riesgo_bajo.html.twig');
            break;
        
        case 'medio':
            echo $twig->render('riesgo_medio.html.twig');
            break;
        
        case 'alto':
            echo $twig->render('riesgo_alto.html.twig');
            break;
        case 'consulta_estudiantes':        	
            $estudiantes   = LogicaRiesgo::obtenerEstudiantesFiltro($_POST);
            if(empty($estudiantes)){
            	 $message["message_error"] = "No se encontraron estudiantes con los parametros ingresados";
            	 echo $twig->render('consultas.html.twig',array_merge($message,LogicaRiesgo::cargarFiltros()));
            }else{
            	$array_template = array('estudiantes' => $estudiantes);            	            	
            	echo $twig->render('consulta_estudiantes.html.twig', $array_template);	
            }                        
            break;
			
		case 'clasificacion1':		
            $estudiantes   = LogicaRiesgo::clasificacionEstudiantes($_POST["seleccionados"]);            
            $array_template = array('estudiantes' => $estudiantes);			
            echo $twig->render('clasificacion.html.twig', $array_template);
            break;
				
		case 'clasificacion':		
            $estudiantes   = LogicaRiesgo::clasificacionEstudiantesPorGrupo($_POST["seleccionados"]);
			$niveles = LogicaRiesgo::obtenerNivelRiesgo();
			$niveles["R"] = "Sin clasificacion";
			ksort($estudiantes);						
            $array_template = array('estudiantes' => $estudiantes,'niveles'=>$niveles);			
            echo $twig->render('clasificacion_por_grupo.html.twig', $array_template);
            break;	
        case 'grafica_grupos_nivel':
            $patrones   = LogicaRiesgo::patrones();
            $array_template = array('patrones' => $patrones);
            echo $twig->render('grafica_grupos_nivel.html.twig', $array_template);
            break;
		case 'guardar_clasificacion':
            LogicaRiesgo::guardarEstudiantesSeleccionados($_POST["estudiantes"]);            
			$message["message_success"] = "Proceso satisfactorio";
            echo $twig->render('consultas.html.twig',array_merge($message, LogicaRiesgo::cargarFiltros()));
            break;	
    }
} else {
    echo $twig->render('index.html.twig');
}

 ?>

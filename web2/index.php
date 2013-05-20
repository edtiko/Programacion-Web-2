<?php

require_once 'vendor/autoload.php';

require_once 'modelo/logica_riesgo.php';

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => 'vista/templates'
));
$message = array("message_info" => null, "message_success" => null, "message_warning" => null, "message_error" => null);
$app->get('/', function() use($app) {
            return $app['twig']->render('index.html.twig');
        })->bind('inicio');

$app->get('/cargar_archivo', function() use($app) {
            return $app['twig']->render('cargar_archivo.html.twig');
        })->bind('cargar');

$app->get('/consultas', function() use($app,$message) {
            $filtros = LogicaRiesgo::cargarFiltros();
            return $app['twig']->render('consultas.html.twig', array_merge($message,$filtros));
        })->bind('consulta');

$app->post('/consulta_estudiantes', function() use($app, $message) {
            $estudiantes = LogicaRiesgo::obtenerEstudiantesFiltro($_POST);
            if (empty($estudiantes)) {
                $message["message_error"] = "No se encontraron estudiantes con los parametros ingresados";
                return $app['twig']->render('consultas.html.twig', array_merge($message, LogicaRiesgo::cargarFiltros()));
            } else {
                $array_template = array('estudiantes' => $estudiantes);
                return $app['twig']->render('consulta_estudiantes.html.twig', $array_template);
            }
        })->bind('consulta_estudiantes');

$app->post('/guardar_clasificacion', function() use($app, $message) {
            $message["message_success"] = "Proceso satisfactorio";
            return $app['twig']->render('consultas.html.twig', array_merge($message, LogicaRiesgo::cargarFiltros()));
        })->bind('guardar_clasificacion');

$app->get('/grafica_grupos_nivel', function() use($app) {
            $patrones = LogicaRiesgo::patrones();
            $array_template = array('patrones' => $patrones);
            return $app['twig']->render('grafica_grupos_nivel.html.twig',array_merge($message,$array_template));
        })->bind('grafica_grupos_nivel');
        
$app->post('/clasificacion', function() use($app,$message) {
            $estudiantes = LogicaRiesgo::clasificacionEstudiantesPorGrupo($_POST["seleccionados"]);
            $niveles = LogicaRiesgo::obtenerNivelRiesgo();
            $niveles["R"] = "Sin clasificacion";
            ksort($estudiantes);
            $array_template = array('estudiantes' => $estudiantes, 'niveles' => $niveles);
            return $app['twig']->render('clasificacion_por_grupo.html.twig', array_merge($message,$array_template));
        })->bind('clasificacion');

 $app->get('/clasificacion1', function() use($app,$message) {
          $estudiantes   = LogicaRiesgo::clasificacionEstudiantes($_POST["seleccionados"]);            
            $array_template = array('estudiantes' => $estudiantes);			
            return $app['twig']->render('clasificacion.html.twig',array_merge($message, $array_template));
        })->bind('clasificacion1');        
        
$app->run();
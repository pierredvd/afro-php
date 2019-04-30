<?php

    // Directories
    define('DS'              , DIRECTORY_SEPARATOR);
    define('DIR_ROOT'        , substr(__DIR__, 0, strrpos(__DIR__, DS)) . DS);
    define('DIR_PUBLIC'      , DIR_ROOT . 'www'   . DS);
    define('DIR_AFRO'        , DIR_ROOT . 'afro'    . DS);
    define('DIR_APP'         , DIR_ROOT . 'app'      . DS);
    define('DIR_APP_CACHE'   , DIR_APP  . 'cache'    . DS);
    define('DIR_APP_CLASS'   , DIR_APP  . 'class'    . DS);
    define('DIR_APP_CONFIG'  , DIR_APP  . 'config'   . DS);
    define('DIR_APP_LANG'    , DIR_APP  . 'lang'     . DS);
    define('DIR_APP_LOG'     , DIR_APP  . 'log'      . DS);
    define('AFRO_VERSION'    , '1.0');

    session_start();
    
    // Initialise l'autoloader
    include(DIR_AFRO.'autoload.php');
    \Afro\Autoload::init(array(DIR_ROOT, DIR_APP_CLASS));

    // Initialise l'autodiagnostic
    \Afro\Autotest::init(DIR_ROOT);

    // Initialise des configurations
    \Afro\Config::init(DIR_APP_CONFIG, \Afro\Router::getHost());

    // Initialise les logs
    \Afro\Log::init(DIR_APP_LOG);


    // Initialise des erreurs
    function onError($type, $message, $file, $line) {
        if (\Afro\Config::get('global.error.log')) {
            \Afro\Log::trace($type . ' - ' . $message . ' (' . $file . ':' . $line . ')');
        }
    }
    \Afro\Error::init('onError', \Afro\Config::get('global.error.show'));

    // Initialise la gestion des langues
    \Afro\Lang::init(DIR_APP_LANG);

    // Intialise les modèles
    \Afro\Model::init(\Afro\Config::get('database'));

    // Intialise les vues
    \Afro\View::init();

    // Resolution d'une route
    \Afro\Router::init('\\App\\Controller\\', \Afro\Config::get('route'));
    $resolve = \Afro\Router::resolve(\Afro\Router::getUri());
    if (!is_null($resolve)) {
        $controller = new $resolve['controller']();                                                   // Apelle le controller
        $view = call_user_func_array(array($controller, $resolve['method']), $resolve['parameters']); // Apelle la methode du controller et recupère sa reponse
        if (!is_a($view, '\\Afro\\View')) {
            throw new \Exception('Controller response must be a View');                               // Verife la class de la réponse
        }         
        $view->render();                                                                              // Lance le rendu de la vue
    }  

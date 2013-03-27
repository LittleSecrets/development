<?php 
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH',
                  realpath(dirname(__FILE__) . '/../dev_application'));
    
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV',
                  (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                             : 'production'));
    
    defined('LIBRARY_PATH')
        || define('LIBRARY_PATH',
                  realpath(dirname(__FILE__) . '/../library'));
    
    
    $paths = LIBRARY_PATH
            .PATH_SEPARATOR.get_include_path();
    
    set_include_path($paths);

    require_once 'Zend/Application.php';
     
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

    $application->bootstrap()
                ->run();
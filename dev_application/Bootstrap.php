<?php
/**
 * Bootstrap Application
 *
 * @category Application
 * @package  Bootstrap
 *
 * @version  1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
     public function __construct($application) {
        parent::__construct($application);
        $front = Zend_Controller_Front::getInstance();        
       
        $router = $front->getRouter();       
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routes'); 
        $router->addConfig($config, 'routes');       
       
    }
    
    protected function _initAutoloader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
    }
    
    protected function _initView() {

        // initialization helper
        $view = new Zend_View();

        // add helpers
        $view->addHelperPath(APPLICATION_PATH .'/views/helpers', 'App_View_Helper');
        $view->addHelperPath('App/View/Helper', 'App_View_Helper');
        $view->addHelperPath(APPLICATION_PATH .'/layouts/helpers', 'App_Layout_Helper');

        // add view in ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        return $view;
    }
    
    protected function _initConfigs() {
        $configs = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production', array('allowModifications'=>true));
        Zend_Registry::set('configs', $configs);
    }
    
    public function _initTranslate()
    {
        try {
            $translate = new Zend_Translate(
                array(
                    'adapter' => 'array',
                    'content' => APPLICATION_PATH .'/../data/translate/en/index.php',
                    'locale'  => 'en'
                )
            );
            Zend_Registry::set('Zend_Translate', $translate);
            $this->translate = $translate;   
            
        } catch (Exception $e) {

        }
    }
}
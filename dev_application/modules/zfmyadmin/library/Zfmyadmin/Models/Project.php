<?php
/**
 * ZfMyAdmin
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license, that is available
 * at this URL: http://zfmyadmin.com/license
 * If you are unable to obtain the licence, please send an email to:
 * license@zfmyadmin.com
 *
 * @package    ZfMyAdmin
 * @subpackage models
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

/**
 * Stores structure and settings of the working project
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Project 
{
    private $_frontController;
    private static $_instance;
    
    protected $_projectSettings;
    protected $_modules;
    protected $_codebaseRoot;
    
    
    
    
    
    const CLASS_SEPARATOR = '_';
    const DEFAULT_VIEWS_DIR = 'views';
    const DEFAULT_VIEW_SCRIPTS_DIR = 'scripts';
    const DEFAULT_CSS_DIR = 'css';
    const DEFAULT_JS_DIR = 'js';
    
    const DEFAULT_ROUTER_FILE = 'application/configs/routes.ini';
    const DEFAULT_ROUTER_VAR = '';
    
   /**
     * Displays Index page
     *
     * @return void
     */
   public static $_phpDocTags = array(
        'package'    => '@package',
        'subpackage' => '@subpackage',
        'copyright'  => '@copyright',   
        'license'    => '@license',
        'version'    => '@version',
        'author'     => '@author' 
  );

  /**
     * Displays Index page
     *
     * @return void
     */
  public static $_phpDocMethodTags = array(        
       'version'    => '@version',
       'author'     => '@author',
       'throws'     => '@throws',
  );

    /**
     * Class constructor
     *
     * @return void
     */
    protected  function __construct($frontController) {
        $this->_frontController = $frontController;
        
    }
    
    
    /**
     * Singleton instance
     *
     * @return Zend_Controller_Front
     */
    
    public static function getInstance($frontController = false)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($frontController);
        }
        return self::$_instance;
    }   
    
    
    /**
     * Returns working project's front controller
     *
     * @return object
     */
    public function getFrontController()
    {        
        return $this->_frontController;
    }

    /**
     * Returns default project settings
     *
     * @return array
     */
    public function getDefaultProjectSettings()
    {        
        $settings = array(
            'root'           => realpath(dirname(__FILE__) . '/../../../../../../'),
            'viewScriptsDir' => self::DEFAULT_VIEWS_DIR.DIRECTORY_SEPARATOR
                .self::DEFAULT_VIEW_SCRIPTS_DIR,   
            'publicDir'      => basename($_SERVER['DOCUMENT_ROOT']),
            'separate_modules_public_dirs' => 1,
            'cssDir'         => self::DEFAULT_CSS_DIR,
            'jsDir'          => self::DEFAULT_JS_DIR,
            'routerFile'     => self::DEFAULT_ROUTER_FILE,
            'routerVar'      => self::DEFAULT_ROUTER_VAR
        );
        return $settings;
    }

    /**
     * Returns current project settings
     *
     * @return array
     */
    public function getProjectSettings()
    {        
        if(empty($this->_projectSettings)){
            $model = new Zfmyadmin_Models_Vars;
            $this->_projectSettings = $model->getProjectSettings(); 
            if(empty($this->_projectSettings)){
                $this->_projectSettings = $this->getDefaultProjectSettings();
            }
            
        } 
        return $this->_projectSettings;
    }

    /**
     * Sets working project settings
     *
     * @return void
     */
    public function setProjectSettings($data)
    {        
        $model = new Zfmyadmin_Models_Vars;
        $model->setProjectSettings($data);
    }

    /**
     * Returns working project's root directory
     *
     * @return string
     */
    public function getRoot()
    {
        $settings = $this->getProjectSettings();
        $root = $settings['root'];
        return $root;
    }

    /**
     * Returns relative path to a file or directory
     * @param string $path Absolute path
     * @return string Relative path
     */
    public function getRelativePath($path)
    {
        $path = str_replace($this->getRoot().DIRECTORY_SEPARATOR, '', $path);
        return $path;
    }

    /**
     * Returns View scripts path relative to the current module
     *
     * @return string
     */
    public function getViewScriptsDir()
    {
        $settings = $this->getProjectSettings();
        if(empty($settings['viewScriptsDir'])){
            $viewScriptsDir = self::DEFAULT_VIEWS_DIR.DIRECTORY_SEPARATOR
                .self::DEFAULT_VIEW_SCRIPTS_DIR;
        } else {
            $viewScriptsDir = $settings['viewScriptsDir'];
        }
        return $viewScriptsDir;
    }

    /**
     * Returns working project's module list
     *
     * @return array 
     */
    public function getModules()
    {   
        if (empty($this->_modules)) { 
            $dirFilter = new Zend_Filter_Dir();
            $realPathFilter = new Zend_Filter_RealPath();            
            foreach ($this->getFrontController()->getControllerDirectory() as $key => $value) {                    

                   $directory = $this->getFrontController()->getModuleDirectory();
                   $directory = $directory.DIRECTORY_SEPARATOR.'..';
                   if (!is_dir($directory.DIRECTORY_SEPARATOR.$key)) {
                       continue;
                   }
                
                   $module = new stdClass();
                   $module->name = $key;
                   $module->pathToModule = $realPathFilter->filter(
                        $dirFilter->filter(
                            $value
                        )                
                    );
                   $module->controllersDir = $realPathFilter->filter($value);
                   $module->viewScriptsPath = $module->pathToModule.DIRECTORY_SEPARATOR
                       .$this->getViewScriptsDir();
                   $module->isActive = true;
                   if($module->name == $this->getFrontController()->getDefaultModule()) {
                      $module->isDefault = true; 
                   } else {
                      $module->isDefault = false;   
                   }
                   
                   
                   $modules[$key] = $module;
            }
            $this->_modules = $modules;
        }       
        
       return $this->_modules;
    }

    /**
     * Gets module object
     * @param string $name Module name     *
     * @return object
     */
    public function getModule($name = false)
    {
        $modules = $this->getModules();
        if(!empty($modules[$name])) {
            return $modules[$name];
        } 
        
        return new stdClass;
    }
    
        /**
     * Gets module object
     * @param string $name Module name     *
     * @return object
     */
    public function isPrefixDefaultModule()
    {
       //return false; 
       return  $this->getFrontController()->getParam('prefixDefaultModule');
    }
    
    
    
    /**
     * Gets path to directory of generatable code templates
     *
     * @return string
     */
    public function getCodebaseRoot()
    {
        if (empty($this->_codebaseRoot)) {
            $modules = $this->getModules();
            $module = $modules["zfmyadmin"];
            $this->_codebaseRoot = $module->pathToModule.DIRECTORY_SEPARATOR.'codebase' ;
        }
        return $this->_codebaseRoot;
    }

    /**
     * Gets path to single generatable code template
     * @param string $name template name
     * @return string
     */
    public function getCodebasePath($name)
    {
        $codebasePath = $this->getCodebaseRoot().DIRECTORY_SEPARATOR.$name.'.txt';
        return $codebasePath;
    }

    /**
     * Gets list of module's controllers
     * @param string $moduleName Module name
     * @return array
     */
    public function getControllers($moduleName) {
        $module = $this->getModule($moduleName);
        
        if(!$module->controllersDir){
            return array();
        }
        
        if(!is_dir($module->controllersDir)){
            return array();
        }   
        
        $dir = dir($module->controllersDir); 
        while ($row = $dir->read()) {
            if (preg_match("%Controller.php$%", $row)) {
                $controller = new stdClass();
                $controller->name = str_replace('Controller.php', '', $row);
                
                $isPrefixDefaultModule = $this->isPrefixDefaultModule();
                if($isPrefixDefaultModule||(!$module->isDefault)){
                    $prefix = ucfirst($module->name).Zfmyadmin_Models_Project::CLASS_SEPARATOR;
                }
                $controller->class = $prefix
                    .$controller->name
                    .'Controller';
                $controllers[$controller->name] = $controller;
            }  
        }
        return $controllers;        
    }
    
    /**
     * Gets conrtoller object
     * @param string $module Module name 
     * @param string $controller Controller name 
     * @return object
     */
    public function getController($moduleName, $controllerName)
    {
        $controllers = $this->getControllers($moduleName);
        if(!empty($controllers[$controllerName])) {            
            return $controllers[$controllerName];
        } 
        
        return new stdClass;
    }
    
    /**
     * Gets list of controllers's actions
     * @param string $moduleName Module name
     * @param string $controllerName Controller name
     * @return array
     */
    public function getActions($moduleName, $controllerName = 'Index') {

        if(empty($controllerName)) {
            $controllerName = 'Index';    
        }
        $module = $this->getModule($moduleName);
        $path = $module->controllersDir.DIRECTORY_SEPARATOR.$controllerName.'Controller.php';
        $controller = $this->getController($moduleName, $controllerName);
        $actions = array(); 
        try {
            include_once $path; 
            $model = new ReflectionClass($controller->class);
            $methods = $model->getMethods();
        } catch (Exception $exc) {
            return $actions;
        }
                       
        foreach ($methods as $value) { 
            if($value->class == $controller->class){
                $pattern = "/\w+Action/";
                if(preg_match($pattern, $value->name)) {
                    $action = trim($value->name);
                    $action = str_replace("Action", '', $action);
                    $actions[$action] = $action;
                }
            }    
            
        }
        return $actions;       
    }
    
   /**
     * Gets module object
     * @param string $name Module name     *
     * @return object
     */
    public function getAction($moduleName, $controllerName, $name = '')
    {
        $actions = $this->getActions($moduleName, $controllerName);
        if(!empty($actions[$name])) {
            return $actions[$name];
        } 
        
        return new stdClass;
    }

    /**
     * Returns relative path to public directory
     *
     * @return string
     */
    public function getPublicDir()
    {
        $settings = $this->getProjectSettings();
        if(empty($settings['publicDir'])){
            $dir = basename($_SERVER['DOCUMENT_ROOT']);
        } else {
            $dir = $settings['publicDir'];
        }
        return $dir;
    }    

    /**
     * Returns relative path to directory containing working project styles
     *
     * @return string
     */
    public function getCssDir()
    {
        $settings = $this->getProjectSettings();
        if(empty($settings['cssDir'])){
            $dir = self::DEFAULT_CSS_DIR;
        } else {
            $dir = $settings['cssDir'];
        }
        return $dir;
    }

    /**
     * Returns relative path to directory containing working project JavaScript files
     *
     * @return string
     */
    public function getJsDir()
    {
        $settings = $this->getProjectSettings();
        if(empty($settings['jsDir'])){
            $dir = self::DEFAULT_JS_DIR;
        } else {
            $dir = $settings['jsDir'];
        }
        return $dir;
    }

    /**
     * Returns absolute path to public directory
     *
     * @return string
     */
    public function getPublicPath()
    {
        $path = $this->getRoot().DIRECTORY_SEPARATOR.$this->getPublicDir();
        return $path;
    }
    
    /**
     * Returns url to public directory
     *
     * @return string
     */
    public function getPublicUrl()
    {
        $path = '';
        return $path;
    }
    
} 
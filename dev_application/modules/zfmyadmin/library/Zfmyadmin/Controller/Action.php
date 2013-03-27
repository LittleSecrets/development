<?php
error_reporting(E_ERROR);

class Zfmyadmin_Controller_Action extends Zend_Controller_Action 
{
    public function preDispatch() { 
        
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'zfmyadmin_public')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'install', 'action'=>'index')
            ));
        }
        
        $realPath = new Zend_Filter_RealPath();
        /*Path to models and forms*/
        $models = $realPath->filter(dirname($this->getFrontController()->getModuleDirectory()));
        
        /*Path to configs*/
        
        $configsPath = $realPath->filter($this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'configs');
        
        /*Path to Zfmyadmin library */
        $library = $realPath->filter($this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'library');
        
        /*include new paths*/
        $paths = $library.PATH_SEPARATOR.$models.PATH_SEPARATOR.get_include_path();
        set_include_path($paths);
        
        /*if used Zend_Application*/
        $bootstrap = $this->getFrontController()->getParam('bootstrap');
        if(!empty ($bootstrap)){
            $application = $bootstrap->getApplication();            
            $optionsZfmyadmin = array(
                'autoloaderNamespaces'=>array('Zfmyadmin'=>'Zfmyadmin_'),
            );
            $options = $application->mergeOptions($application->getOptions(), $optionsZfmyadmin);
            $application->setOptions($options);
        } 
        
        if(file_exists($configsPath.DIRECTORY_SEPARATOR.'zfmyadmin.ini')){
            $zfmyadminConfig = new Zend_Config_Ini($configsPath.DIRECTORY_SEPARATOR.'zfmyadmin.ini', 'zfmyadmin');
            if (!empty($zfmyadminConfig->resources->db->adapter) && !empty($zfmyadminConfig->resources->db->params)) {            
                $db = Zend_Db::factory($zfmyadminConfig->resources->db->adapter, $zfmyadminConfig->resources->db->params);        
                Zend_Db_Table_Abstract::setDefaultAdapter($db);            
            }           
        }
        
        $this->_helper->layout->setLayoutPath($this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'layouts');
        
        $this->project = Zfmyadmin_Models_Project::getInstance($this->getFrontController());
        
        $this->view->addBasePath($this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'partials');
        
        $this->acl = $this->getAcl();
        $this->view->acl = $this->acl;
        
        $this->session = new Zend_Session_Namespace('zfmyadmin'); 
        
        $this->user = $this->session->user;
        
        $this->view->user = $this->session->user;
        if(!empty($this->session->locale)) {
            $this->setTranslate($this->session->locale); 
            $this->view->currentLanguage = $this->session->locale;
        } else {
            $this->setTranslate('en');
            $this->view->currentLanguage = 'en';
        }       
        $this->_flashMessenger = $this->_helper->FlashMessenger;
    }
    
    public function getAcl()
    {
        $acl = new Zend_Acl(); 
        $acl->addRole(new Zend_Acl_Role('demo'))
            ->addRole(new Zend_Acl_Role('restricted'), 'demo')
            ->addRole(new Zend_Acl_Role('developer'),'restricted')
            ->addRole(new Zend_Acl_Role('admin'), 'developer');       
   

        $acl->add(new Zend_Acl_Resource('transactions')); // use functional without save
        $acl->add(new Zend_Acl_Resource('log')); // record to log
        $acl->add(new Zend_Acl_Resource('project')); // record to project 
        $acl->add(new Zend_Acl_Resource('project_settings')); // project settings (paths)
        $acl->add(new Zend_Acl_Resource('users')); // manage users
        $acl->add(new Zend_Acl_Resource('settings')); // change password and user settings
        
        $acl->allow('demo', array('transactions'));
        $acl->allow('restricted', array('settings', 'log'));
        $acl->allow('developer', array('project', 'project_settings'));
        $acl->allow('admin', null, null);

        return $acl;
    }
    
    public function setTranslate($locale = 'en')
    {
       
        $translatePath = $this->getFrontController()->getModuleDirectory('zfmyadmin').DIRECTORY_SEPARATOR
                .'data'.DIRECTORY_SEPARATOR.'locales'; 
        $translate = new Zend_Translate(
            array(
                'adapter' => 'gettext',
                'content' => $translatePath.DIRECTORY_SEPARATOR.$locale.'.mo',
                'locale'  => $locale,
            )
        );

       
        /*Set this translator for forms, navigation ...*/
        Zend_Registry::set('Zend_Translate', $translate);
        $this->translate = $translate;       
    }
    
    public function translate($text)
    {
        $text = $this->translate->_($text);
        return $text;
    }
    
    public function postDispatch()
    {
        if(!empty($this->_flashMessenger)) {
            $this->view->message = $this->_flashMessenger->getMessages();
        }
        
        parent::postDispatch();
    }

    public function setFormDefaultModuleControllerAction($form)
    {
        $form->setModulesList($this->project->getModules());
        $form->getElement('moduleName')->setValue($this->getFrontController()->getDefaultModule());
        $controllers = $this->project->getControllers($this->getFrontController()->getDefaultModule());
        $form->setControllersList($controllers);
        $actions = $this->project->getActions(
            $this->getFrontController()->getDefaultModule(),
            $form->getElement('controllerName')->getValue()
        );
        $form->setActionsList($actions); 
        return $form;
    }
    
    public function setFormModuleControllerAction($form)
    {
        $form->moduleName->setValue($this->getRequest()->getParam('moduleName'));
        $form->setControllersList($this->project->getControllers(
                $this->getRequest()->getParam('moduleName')
            )
         );

        $form->getElement('controllerName')
             ->addValidator(new Zfmyadmin_Validate_ControllerExist(
                    $this->getRequest()->getParam('moduleName')    
                 )
             ); 

        $form->setActionsList($this->project->getActions(
                $this->getRequest()->getParam('moduleName'),
                $this->getRequest()->getParam('controllerName')
            )
        );            

         $form->getElement('actionName')
             ->addValidator(new Zfmyadmin_Validate_ActionExist(
                    $this->getRequest()->getParam('moduleName'),
                    $this->getRequest()->getParam('controllerName')
                 )
             ); 

        return $form;    
    }
    
    public function setFormSettingsModuleControllerAction($form, $data)
    {
        $form->moduleName->setValue($data['moduleName']);
        
        $controllers = $this->project->getControllers($data['moduleName']);
        $form->setControllersList($controllers);
        
        if(!empty($data['controllerName'])) {
            $form->controllerName->setValue($data['controllerName']);
            $actions = $this->project->getActions(
                $data['moduleName'],
                $form->getElement('controllerName')->getValue()
            );
            $form->setActionsList($actions);
            if(!empty($data['actionName'])&&!empty($actions)) {
                $form->actionName->setValue($data['actionName']);
            } else {
                $form->actionName->setValue('');
            }
        } else {
           $form->controllerName->setValue(''); 
           $form->actionName->setValue('');
        }
        $form->setDefaults($data); 
        return $form;
            
    }
}
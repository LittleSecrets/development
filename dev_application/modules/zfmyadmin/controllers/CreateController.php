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
 * @subpackage controllers
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */
include_once dirname(__FILE__).'/../library/Zfmyadmin/Controller/Action.php';

/**
 * Creates project's entities
 * @package    zfmyadmin
 * @subpackage controllers
 */
class Zfmyadmin_CreateController extends Zfmyadmin_Controller_Action 
{

    public function preDispatch() {
        parent::preDispatch();
        if (empty($this->user->id)) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'login')
            ));
        }        
        if (!$this->acl->isAllowed($this->user->role, 'transactions')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        } 
        $this->_helper->layout->setLayout('create-layout'); 
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create.js');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/log-actions.js');
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create.css');
    }


    public function indexAction() {

    }
    /**
     * Creates new module
     *
     * @return void
     */    
    public function moduleAction()
    {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/module.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/module.js');
        $form = new Zfmyadmin_Forms_Create_Module;
        $vars = new Zfmyadmin_Models_Vars;
        $transaction = new Zfmyadmin_Models_Create_Module;
        
        if ($this->getRequest()->isPost()) { 
            if ($form->isValid($this->getRequest()->getParams())) {
                
                $autoChangeData = false;
                if ($form->moduleName->getValue()!=$this->getRequest()->getParam('moduleName')) {
                    $this->view->changeControllerName = $this->translate('Module name changed in accordance to Zend standards, your input was:')
                        .' '.$this->getRequest()->getParam('moduleName');
                }
                
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    
                    $model = new Zfmyadmin_Models_Vars;
                    $userSettings = $model->getUserSettings($this->user);
                    
                    $operations = $transaction->create($data);
                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ){                        
                        $transaction->setSaveToLog(true);
                        $message = $this->translate('Module %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Module %name% not created, TODO not saved(not enough rights)');
                    }                                         
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ) {
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                                                                     
                    }
                    
                    $transaction->commit($operations, $this->user->id, $data);
                    
                    $message = $this->translate->_('Module %name% created');
                    $message = str_replace('%name%', $data['moduleName'], $message);
                    $this->_flashMessenger->addMessage($message);
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array('module'=>'zfmyadmin', 'controller'=>'create', 'action'=>'module')
                    )));
                    
                } else {
                    $operations = $transaction->create($data);
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create module')); 
                
                
            }            
            
        } else {
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_MODULE]
            );
            $form->setDefaults($data);            
        }  
        
        
        /*Prepare data for output for change settings templates on client side*/
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_MODULE],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings); 
        
        $modules = $this->project->getModules();
        $this->view->modules = $modules;        
        foreach ($modules as $module) {
            $modulesData[$module->name] = $module->name;
        }        
        $this->view->modulesJsonData = Zend_Json::encode($modulesData);
        
        $this->view->form = $form;
    }

    /**
     * Creates new controller in a given module
     *
     * @return void
     */
    public function controllerAction()
    {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/controller.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/controller.js');
        $form = new Zfmyadmin_Forms_Create_Controller;
        $form->setModulesList($this->project->getModules());
        $form->getElement('moduleName')->setValue($this->getFrontController()->getDefaultModule());
        $transaction = new Zfmyadmin_Models_Create_Controller;
        $vars = new Zfmyadmin_Models_Vars;
        
        if ($this->getRequest()->isPost()) {            

            $form->getElement('controllerName')
                 ->addValidator(new Zfmyadmin_Validate_ControllerNoExist(
                     $this->getRequest()->getParam('moduleName')    
                    )
                 ); 
            
            if ($form->isValid($this->getRequest()->getParams())) {
                
                $autoChangeData = false;
                if ($form->controllerName->getValue()!=$this->getRequest()->getParam('controllerName')) {
                    $this->view->changeControllerName =
                        $this->translate('Controller name changed in accordance to Zend standards, your input was:')
                        .' '.$this->getRequest()->getParam('controllerName') ;
                }
                
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    
                    $model = new Zfmyadmin_Models_Vars;
                    $userSettings = $model->getUserSettings($this->user);
                    
                    $operations = $transaction->create($data);
                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ) {                        
                        $transaction->setSaveToLog(true);
                        $message = $this->translate('Controller %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Controller %name% not created, TODO not saved(not enough rights)');
                    }                                          
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ){
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                        $message = $this->translate('Controller %name% created');                                              
                    }
                    
                    $data['actionName']='index';
                    $transaction->commit($operations, $this->user->id, $data);
                    
                    
                    $message = str_replace('%name%', $data['controllerName'], $message);
                    $this->_flashMessenger->addMessage($message);                    
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array(
                            'module'     => 'zfmyadmin',
                            'controller' => 'create',
                            'action'     => 'controller',
                        )
                    )));
                    
                } else {
                    $operations = $transaction->create($data);
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create controller')); 
               
                
            }
        } else {
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER]
            );
            $form->setDefaults($data);            
        }  
        /*Prepare data for output for change settings templates on client side*/
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER],    
            $this->user->id            
        );      
        $this->view->settingsJsonData = Zend_Json::encode($settings);       
        
        $this->view->form = $form;
       
    }

    public function actionAction()
    {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/action.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/action.js');
        
        $form = new Zfmyadmin_Forms_Create_Action; 
        $form->setModulesList($this->project->getModules());
        $form->getElement('moduleName')->setValue($this->getFrontController()->getDefaultModule());
        $controllers = $this->project->getControllers($this->getFrontController()->getDefaultModule());
        $form->setControllersList($controllers);
        
        
        if ($this->getRequest()->isGet()) {
            $form->addRouterVars(3);
        }
        $transaction = new Zfmyadmin_Models_Create_Action;
        $vars = new Zfmyadmin_Models_Vars;
        
        if($this->getRequest()->isPost()) {
                                
            $form->moduleName->setValue($this->getRequest()->getParam('moduleName'));
            $form->setControllersList($this->project->getControllers(
                    $this->getRequest()->getParam('moduleName')
                )
             );
  
            $routerVars = (array)$this->getRequest()->getParam('router');
            if (count($routerVars) > 0) {
                $form->addRouterVars($routerVars);
            }
            $form->getElement('controllerName')
                 ->addValidator(new Zfmyadmin_Validate_ControllerExist(
                        $this->getRequest()->getParam('moduleName')    
                     )
                 ); 
            
             $form->getElement('actionName')
                 ->addValidator(new Zfmyadmin_Validate_ActionNoExist(
                        $this->getRequest()->getParam('moduleName'),
                        $this->getRequest()->getParam('controllerName') 
                     )
                 ); 
            
            if ($form->isValid($this->getRequest()->getParams())) {
                $autoChangeData = false;
                if($form->actionName->getValue()!=$this->getRequest()->getParam('actionName')) {
                    $this->view->changeControllerName = 
                        $this->translate('Action name changed in accordance to Zend standards, your input was:')
                        .' '.$this->getRequest()->getParam('actionName') ;
                }
                
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    $model = new Zfmyadmin_Models_Vars;                    
                    $userSettings = $model->getUserSettings($this->user);                    
                                        
                    $operations = $transaction->create($data);                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ) {                        
                        $transaction->setSaveToLog(true);
                        $message = $this->translate('Action %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Action %name% not created, TODO not saved(not enough rights)');
                    }                                         
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ) {
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                        $message = $this->translate('Action %name% created');                                                
                    }

                    $transaction->commit($operations, $this->user->id, $data);
                    
                    
                    $message = str_replace('%name%', $data['actionName'], $message);
                    $this->_flashMessenger->addMessage($message); 
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array(
                            'module'     => 'zfmyadmin',
                            'controller' => 'create',
                            'action'     => 'action',
                        )
                    )));
                    
                } else {
                    $operations = $transaction->create($data);  
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create Action')); 
                       
                
            }            
        
        } else {            
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_ACTION]
            ); 
            if (!empty($data)) {
                $form->moduleName->setValue($data['moduleName']);
                $form->setControllersList($this->project->getControllers($data['moduleName']));
                $form->setDefaults($data);    
            }           
        }  
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_ACTION],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings);  
        $this->view->form = $form;

    }
    
    public function cssAction() {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/css.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/css.js');
        
        $form = new Zfmyadmin_Forms_Create_Css; 
        $form = $this->setFormDefaultModuleControllerAction($form);        
        $transaction = new Zfmyadmin_Models_Create_Css;
        $vars = new Zfmyadmin_Models_Vars;
        
        if($this->getRequest()->isPost()) {

            $form = $this->setFormSettingsModuleControllerAction($form, $this->getRequest()->getParams());
             
            if ($form->isValid($this->getRequest()->getParams())) {
              
               
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    $model = new Zfmyadmin_Models_Vars;                    
                    $userSettings = $model->getUserSettings($this->user);                    
                    $saveSuccesful = '';                    
                    $operations = $transaction->create($data);                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ) {                        
                        $transaction->setSaveToLog(true);
                        $message = $this->translate('Css %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Css %name% not created, TODO not saved(not enough rights)');
                    }                                          
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ) {
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                        $message = $this->translate('Css %name% created');                                                
                    }
                    
                    $transaction->commit($operations, $this->user->id, $data);
                    
                    
                    $message = str_replace(
                        '%name%',
                         $data['moduleName']
                             .DIRECTORY_SEPARATOR.$data['controllerName']
                             .DIRECTORY_SEPARATOR.$data['actionName'],
                         $message
                     );
                    $this->_flashMessenger->addMessage($message); 
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array(
                            'module'     => 'zfmyadmin',
                            'controller' => 'create',
                            'action'     => 'css',
                        )
                    )));
                    
                } else {
                    $operations = $transaction->create($data);  
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create Css'));
                 
            }            
        
        } else {            
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_CSS]
            ); 
            if (!empty($data)) {
                $form = $this->setFormSettingsModuleControllerAction($form, $data);     
            }           
        }  
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_CSS],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings);  
        $this->view->form = $form;        
    }

    
    public function jsAction() {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/js.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/js.js');
        
        $form = new Zfmyadmin_Forms_Create_Js;        
        $form = $this->setFormDefaultModuleControllerAction($form);
        $transaction = new Zfmyadmin_Models_Create_Js;
        $vars = new Zfmyadmin_Models_Vars;
        
        if($this->getRequest()->isPost()) {

            $form = $this->setFormSettingsModuleControllerAction($form, $this->getRequest()->getParams());

            if ($form->isValid($this->getRequest()->getParams())) {
              
               
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    $model = new Zfmyadmin_Models_Vars;                    
                    $userSettings = $model->getUserSettings($this->user);                    
                    $saveSuccesful = '';                    
                    $operations = $transaction->create($data);                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ) {                        
                        $transaction->setSaveToLog(true);
                        
                        $message = $this->translate('Js %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Js %name% not created, TODO not saved(not enough rights)');
                    }                                           
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ) {
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                        $message = $this->translate('Js %name% created');                                                
                    }
                    
                    $transaction->commit($operations, $this->user->id, $data);
                    
                    
                    $message = str_replace(
                        '%name%',
                         $data['moduleName']
                             .DIRECTORY_SEPARATOR.$data['controllerName']
                             .DIRECTORY_SEPARATOR.$data['actionName'],
                         $message
                     );
                    $this->_flashMessenger->addMessage($message); 
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array(
                            'module'     => 'zfmyadmin',
                            'controller' => 'create',
                            'action'     => 'js',
                        )
                    )));
                    
                } else {
                    $operations = $transaction->create($data);  
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create Js')); 
                                 
                
            }            
        
        } else {            
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_JS]
            ); 
            if (!empty($data)) {
                $form = $this->setFormSettingsModuleControllerAction($form, $data);    
            }           
        }  
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_JS],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings);  
        $this->view->form = $form;        
    }


    public function routerAction()
    {
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/router.js');
        
        $form = new Zfmyadmin_Forms_Create_Router();        

        $form = $this->setFormDefaultModuleControllerAction($form);
       
        if ($this->getRequest()->isGet()) {
            $form->addRouterVars(3);
        }
        $transaction = new Zfmyadmin_Models_Create_Router;
        $vars = new Zfmyadmin_Models_Vars;
        
        if($this->getRequest()->isPost()) {
            
            $form = $this->setFormSettingsModuleControllerAction($form, $this->getRequest()->getParams());

            $routerVars = (array)$this->getRequest()->getParam('router');
            if (count($routerVars) > 0) {
                $form->addRouterVars($routerVars);
            }
            
            if ($form->isValid($this->getRequest()->getParams())) {
                $data = $form->getValues();
                $intentionSignature = $data['intentionSignature'];
                $data['intentionSignature'] = '';
                
                
                if ($intentionSignature == md5(serialize($data))) {
                    $model = new Zfmyadmin_Models_Vars;                    
                    $userSettings = $model->getUserSettings($this->user);                    
                                        
                    $operations = $transaction->create($data);                    
                    if (($this->acl->isAllowed($this->user->role, 'log'))
                        &&(!empty($userSettings['commit']['isSaveToLog']))                       
                    ) {                        
                        $transaction->setSaveToLog(true);
                        $message = $this->translate('Router %name% not created(not enough rights), TODO saved to log');
                    } else {
                        $message = $this->translate('Router %name% not created, TODO not saved(not enough rights)');
                    }                                         
                    
                    if (($this->acl->isAllowed($this->user->role, 'project')
                        &&(!empty($userSettings['commit']['isSaveToProject'])))
                    ) {
                        $transaction->setSaveToLog(true);
                        $transaction->setSaveToProject(true);
                        $message = $this->translate('Router %name% created');                                                
                    }
                    
                    $transaction->commit($operations, $this->user->id, $data);
                    
                    $message = str_replace('%name%', $data['routerTitle'], $message);
                    $this->_flashMessenger->addMessage($message); 
                    
                    $this->_redirect($this->_redirect($this->view->url(
                        array(
                            'module'     => 'zfmyadmin',
                            'controller' => 'create',
                            'action'     => 'controller',
                        )
                    )));
                    
                } else {
                    $operations = $transaction->create($data);  
                } 
                $this->view->operations = $operations;  
                $intentionSignature = md5(serialize($data));
                $form->intentionSignature->setValue($intentionSignature);
                
                $session = new Zend_Session_Namespace('zfmyadmin');
                $session->operations = $operations;
                $session->intentionSignature = $intentionSignature;
                $session->intentionData = $data;

                $form->createSubmit->setLabel($this->translate('Create Route')); 
                                   
                
            }            
        
        } else {            
            $data = $vars->getSettingsTemplate(
                $this->user->id, 
                Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST,
                Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_ROUTER]
            ); 
            if (!empty($data)) {
                $form = $this->setFormSettingsModuleControllerAction($form, $data);                
            }           
        }  
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_ROUTER],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings);  
        $this->view->form = $form;

    }
    


    public function formAction()
    {
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/create/form.css');                
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/create/form.js');
        
        $form = new Zfmyadmin_Forms_Create_Form(); 
        $form = $this->setFormDefaultModuleControllerAction($form);
        //$transaction = new Zfmyadmin_Models_Create_Router;
        $vars = new Zfmyadmin_Models_Vars;        
        if($this->getRequest()->isPost()&&$this->getRequest()->getParam('formType') == 'addField') {
            $session = new Zend_Session_Namespace('zfmyadmin');
            $formfields = $session->formfields;
            $formfield = $this->getRequest()->getPost();
            $formfields[] = $formfield;            
            $session->formfields = $formfields;
        } else {
            
        }

        if($this->getRequest()->isPost()) {

        
        } else {
            
        }
        
        $settings = $vars->getAllSettingsTemplates(
            Zfmyadmin_Models_Transaction::$_creatorsName[Zfmyadmin_Models_Operation::CATEGORY_FORM],    
            $this->user->id            
        );
        $this->view->settingsJsonData = Zend_Json::encode($settings);  
        $this->view->form = $form;
        $this->view->field = new Zfmyadmin_Forms_Create_Form_Field;
        $this->view->formfields = $formfields;
        
    }

}
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
 * Manages users and their settings
 * @package    zfmyadmin
 * @subpackage controllers
 */
class Zfmyadmin_UserController extends Zfmyadmin_Controller_Action  
{

    public function preDispatch()
    {
        parent::preDispatch();

        if (empty($this->user->id)&&($this->getRequest()->getActionName()!='login')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'login')
            ));
        }
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/user.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/user.js');
        $this->_helper->layout->setLayout('layout');
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext -> addActionContext('settings-creator-form', 'json')
                     ->initContext('json'); 
    }

    /**
     * Displays user list and managing options
     *
     * @return void
     */
    public function indexAction() 
    {
        if (!$this->acl->isAllowed($this->user->role, 'users')) {
            $this->_redirect($this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            )));
        }
        $model = new Zfmyadmin_Models_User;
        $users = $model->getList();
        $this->view->users = array();
        foreach ($users as $user) {
            $row = new stdClass;
            $row->user = $user;
            $row->form = new Zfmyadmin_Forms_UsersEdit;
            $row->form->setData($user);            
            $vars = new Zfmyadmin_Models_Vars;
            $userSettings = $vars->getUserSettings($user);
            $row->personal = (object)$userSettings['personal'];
            $this->view->users[$user->id] = $row;
        }
        $formCreate = new Zfmyadmin_Forms_UserCreate;
        

        if ($this->getRequest()->isPost()){
            $userId = $this->getRequest()->getParam('userId');
            $form = $this->view->users[$userId]->form;   
            
            if (($this->getRequest()->getParam('formTarget', false)=='edit')&&($form->isValid($this->getRequest()->getParams()))) {
                $data =  $form->getValues();
                $user = $model->getUser($userId);
                $isDelete = $this->getRequest()->getParam('delete');
                if (!empty($isDelete)) { 
                    $message = $this->translate->_('User %name% removed');
                    $message = str_replace('%name%', $user->login, $message);
                    $user->delete();
                    
                } else {
                    $user->role = $data['role'];
                    $user->save();
                    $message = $this->translate->_('Rights for user %name% changed');
                    $message = str_replace('%name%', $user->login, $message);
                }

                $this->_flashMessenger->addMessage($message);
                $this->_redirect($this->view->url(
                    array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'index')
                ));
            }

            if (($this->getRequest()->getParam('formTarget', false)=='create')
                &&($formCreate->isValid($this->getRequest()->getParams()))
            ) {
                $model = new Zfmyadmin_Models_User;
                $data = $formCreate->getValues();
                $data['role'] = Zfmyadmin_Models_User::ROLE_DEVELOPER;
                if (!empty($data['hash'])) {
                    $data['password'] = $data['hash'];
                } else {
                    $data['password'] = md5($data['password']);
                }                
                $user = $model->createRow($data);
                $user ->save();
                
                $message = $this->translate->_('User %name% created');
                $message = str_replace('%name%', $user->login, $message);
                $this->_flashMessenger->addMessage($message);
                
                $this->_redirect($this->view->url(
                    array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'index')
                ));
            };
        }
        $this->view->formCreate = $formCreate;
    }
    
   /**
     * Performs user login
     *
     * @return void
     */
    public function loginAction() 
    {
        $model = new Zfmyadmin_Models_User;
        $model->logout();
        $this->_helper->layout->setLayout('login-layout');
        $form = new Zfmyadmin_Forms_UserLogin;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $user = $model->login($form->getValues());
                if (!empty($user->id)) {
                    $this->_redirect($this->view->url(
                        array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
                    ));
                }
            };
            $this->view->data = $form->getValues();
        }
        $this->view->form = $form;        
    }

    /**
     * Performs user logout
     *
     * @return void
     */
    public function logoutAction() 
    {
        $model = new Zfmyadmin_Models_User;
        $model->logout();
        $this->_redirect($this->view->url(
            array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'login'))
        );
        
    }

    /**
     * Sets user settings
     *
     * @return void
     */
    public function settingsAction() 
    {
        if (!$this->acl->isAllowed($this->user->role, 'settings')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }
        $model = new Zfmyadmin_Models_Vars;
        $settings = $model->getUserSettings($this->user);         
        $form = new Zfmyadmin_Forms_UserSettings;
        if (!empty($settings)) {
            $form->setDefaults($settings);
        }        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $data = $form->getValues();
                $model->setUserSettings($this->user, $data);
                
                $message = $this->translate->_('Your settings changed');
                $this->_flashMessenger->addMessage($message);
                $this->_redirect($this->view->url(
                    array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'settings')
                ));
            };
        }
        $this->view->form = $form;
        
    }
    
    /**
     * Sets user creator forms settings - response json data
     *
     * @return void
     */
    public function settingsCreatorFormAction() 
    {

        if ($this->getRequest()->isPost()) { 
            $data = $this->getRequest()->getParams();
            switch ($data['creatorCategory']) {
                case Zfmyadmin_Models_Operation::CATEGORY_MODULE:
                    $form = new Zfmyadmin_Forms_Create_Module;
                    unset($data['moduleName']);
                    break;
                
                case Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER:
                    $form = new Zfmyadmin_Forms_Create_Controller;
                    $form->setModulesList($this->project->getModules());
                    unset($data['controllerName']);
                    break;

                case Zfmyadmin_Models_Operation::CATEGORY_ACTION:
                    $form = new Zfmyadmin_Forms_Create_Action;
                    $form->setModulesList($this->project->getModules());
                    $form->setControllersList(
                        $this->project->getControllers(
                            $this->getRequest()->getParam('moduleName')
                        )
                    );
                    unset($data['actionName']);
                    break;  
                
                case Zfmyadmin_Models_Operation::CATEGORY_CSS:
                    $form = new Zfmyadmin_Forms_Create_Css;
                    $form->setModulesList($this->project->getModules());
                    $form->setControllersList(
                        $this->project->getControllers(
                            $this->getRequest()->getParam('moduleName')
                        )
                    );
                    $form->setActionsList($this->project->getActions(
                            $this->getRequest()->getParam('moduleName'),
                            $this->getRequest()->getParam('controllerName')
                        )
                    ); 
                    
                    break; 
                
                case Zfmyadmin_Models_Operation::CATEGORY_JS:
                    $form = new Zfmyadmin_Forms_Create_Js;
                    $form->setModulesList($this->project->getModules());
                    $form->setControllersList(
                        $this->project->getControllers(
                            $this->getRequest()->getParam('moduleName')
                        )
                    );
                    $form->setActionsList($this->project->getActions(
                            $this->getRequest()->getParam('moduleName'),
                            $this->getRequest()->getParam('controllerName')
                        )
                    ); 
                    
                    break;     
                case Zfmyadmin_Models_Operation::CATEGORY_ROUTER:
                    $form = new Zfmyadmin_Forms_Create_Router();
                    $form->setModulesList($this->project->getModules());
                    $form->setControllersList(
                        $this->project->getControllers(
                            $this->getRequest()->getParam('moduleName')
                        )
                    );
                    $form->setActionsList($this->project->getActions(
                            $this->getRequest()->getParam('moduleName'),
                            $this->getRequest()->getParam('controllerName')
                        )
                    ); 
                    
                    break;                
               
                default:
                    break;
            } 
            
            if ($form->isValidPartial($data)) {
                switch ($data['settingsType']) {
                    case Zfmyadmin_Models_User::SETTINGS_TEMPLATE_LAST: 
                        
                        break;
                    case Zfmyadmin_Models_User::SETTINGS_TEMPLATE_USER:
                        if(!$this->acl->isAllowed($this->user->role, 'settings')) {
                            $error = $this->translate("You do not have rights to change these settings");
                        }
                        break;
                    case Zfmyadmin_Models_User::SETTINGS_TEMPLATE_PROJECT:
                        if(!$this->acl->isAllowed($this->user->role, 'project_settings')) {
                            $error = $this->translate("You do not have rights to change these settings");
                        }
                        break;
                    
                        
                    case Zfmyadmin_Models_User::SETTINGS_TEMPLATE_ZFMYADMIN:
                        if(!$this->acl->isAllowed($this->user->role, 'project_settings')) {
                            $error = $this->translate("You do not have rights to change these settings");
                        }
                        break;   
                        
                    default:
                        $error = $this->translate("You do not have rights to change these settings");
                        break;
                }
                
                $type = $data['settingsType'];
                $data = $form->getValidValues($data);              
                if (empty($error)) {
                    $model = new Zfmyadmin_Models_User;
                    $result = $model->saveSettingsTemplate(
                        $data,
                        $this->user->id,
                        Zfmyadmin_Models_Transaction::$_creatorsName[$data['creatorCategory']],
                        $type
                    );
                    
                    if (!empty($result)) {
                        $this->view->errorMessage = '';
                        $this->view->newSettings = $data;
                    } else {
                        $this->view->errorMessage = $this->translate('An error occured, settings not saved!');
                    }
                } else {
                    $this->view->errorMessage = $error;
                }             
            }    
        }        
    }

    /**
     * Changes user's password
     *
     * @return void
     */
    public function changePasswordAction()
    {         
        if (!$this->acl->isAllowed($this->user->role, 'settings')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }
        $form = new Zfmyadmin_Forms_ChangePassword;
        if ($this->getRequest()->isPost()){
            if ($form->isValid($this->getRequest()->getParams())) {
                if ($form->getValue('password')==$form->getValue('passwordConfirm')) {
                    $this->user->setTable(new Zfmyadmin_Models_User);
                    $this->user->password = md5($form->getValue('password'));                    
                    $this->user->save();
                    $message = $this->translate->_('Your password changed');
                    $this->_flashMessenger->addMessage($message);
                    $this->_redirect($this->view->url(
                        array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
                    ));
                } else {
                    $form->passwordConfirm->setErrors(array(_('Password not confirmed!')));
                }                 
            };
        }
        $model = new Zfmyadmin_Models_User;
        
        $this->view->form = $form;
    }
    
}
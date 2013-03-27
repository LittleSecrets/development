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
 * Manages project's Index and Project settings pages
 * @package    zfmyadmin
 * @subpackage controllers
 */

class Zfmyadmin_IndexController extends Zfmyadmin_Controller_Action 
{

    public function preDispatch()
    {
        parent::preDispatch();
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/index.css');
        $this->_helper->layout->setLayout('layout');
    }

    /**
     * Displays Index page
     *
     * @return void
     */
    public function indexAction() {
       
    }

    /**
     * Sets general Project settings
     *
     * @return void
     */
    public function projectSettingsAction()
    {
        if (empty($this->user->id)) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'login')
            ));
        }  
        
        if (!$this->acl->isAllowed($this->user->role, 'project_settings')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }
        
        $model = new Zfmyadmin_Models_Vars;
        $settings = $model->getProjectSettings();

        $form = new Zfmyadmin_Forms_ProjectSettings;
        if (empty($settings)) {
            $settings = Zfmyadmin_Models_Project::getInstance()->getDefaultProjectSettings();
        }   
        
        $form->setDefaults($settings);
        if ($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getParams())) {
                $data = $form->getValues();
                $model->setProjectSettings($data, $this->user );                    
                $message = $this->translate->_('Project settings saved');
                $this->_flashMessenger->addMessage($message);
                $this->_redirect($this->_redirect($this->view->url(
                    array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'project-settings')
                )));
            };
        }
        $this->view->form = $form;     
    }
    
    /**
     * Displays Index page
     *
     * @return void
     */
    public function languageAction() {
        
        $code = $this->getRequest()->getParam('code');
        $model = new Zfmyadmin_Models_Vars;
        $availableLanguages = $model->getAvailableLanguages();
        if (array_key_exists($code, $availableLanguages)) {
            $this->session->locale = $code;
        }
        $redirectUrl = $_SERVER['HTTP_REFERER'];
        $this->_redirect($redirectUrl);       
    }

}
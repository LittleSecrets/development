<?php
error_reporting(E_ERROR);
class App_Controller_Action extends Zend_Controller_Action 
{
    public function preDispatch() {
        parent::preDispatch();
        
        $this->view->headScript()->appendFile('/libs/jquery-1.8.0.min.js');
        
        $this->configs = Zend_Registry::get('configs'); 
        $lang = $this->getFrontController()->getRequest()->getParam('lang');
        if(empty($lang)) {
           $lang = $this->configs->lang->default;
           $this->getFrontController()->getRequest()->setParam('lang', $lang);
        } 
        Zend_Registry::set('lang', $lang);        
        $this->lang = $lang;
        $this->view->lang = $lang;        
        $this->view->languages = $this->configs->languages->toArray();
        $this->view->languageDefault = $this->configs->lang->default;
        
        try {
           
            $translatePath = APPLICATION_PATH.DIRECTORY_SEPARATOR.'translate';
            $translate = new Zend_Translate(
                array(
                    'adapter' => 'gettext',
                    'content' => $translatePath.DIRECTORY_SEPARATOR.$lang.'.mo',
                    'locale'  => $locale,
                )
            );
            Zend_Registry::set('Zend_Translate', $translate);
            $this->translate = $translate;   
            
            } catch (Exception $e) {
                //echo 'File not found, no adapter class...' ; die;
                // General failure
            }
        
        
        
        
        
        $bootstrap = $this->getFrontController()->getParam('bootstrap');
        if(!empty ($bootstrap)){
            $application = $bootstrap->getApplication();            
            $options = $application->mergeOptions($application->getOptions(), $this->configs->toArray());
            $application->setOptions($options);
        }
        
        	

        $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/acl.ini', 'acl');    
        $this->acl = $this->setAcl($options);
        $this->view->acl = $this->acl;
        
        
        $this->session = new Zend_Session_Namespace('zfmyadmin');
        $this->user = $this->session->user;        
        $this->view->user = $this->session->user;
        
        $this->_flashMessenger = $this->_helper->FlashMessenger;
        
        /*For development only*/
//        if (empty($this->user->id)&&($this->getRequest()->getActionName()!='login')) {
//            $this->_redirect($this->view->url(array(), 'admin-user-login'));
//        }     
        
    }
    public function postDispatch()
    {
        if(!empty($this->_flashMessenger)) {
            $this->view->message = $this->_flashMessenger->getMessages();
        }
        
        parent::postDispatch();
    }
    public function setAcl($options)
    {
        $acl = new Zend_Acl();
        
        foreach ($options->roles as $name => $parents) {
            if (!$acl->hasRole($name)) {
                if (empty($parents)) {
                    $parents = null;
                } else {
                    $parents = explode(',', $parents);         
                }
                $acl->addRole(new Zend_Acl_Role($name), $parents);
            }
        }
      
        foreach ($options->resources as $name => $parent) {
            if (!$acl->has($name)) {
                if (empty($parent)) {
                    $parent = null;
                    $acl->add(new Zend_Acl_Resource($name));
                } else {
                    $acl->add(new Zend_Acl_Resource($name), $parent);
                }
              
            }
        }
   
        $acl->deny(null, null, null);
        
        foreach ($options->allow as $user => $resources) {
            foreach ($resources as $resource => $privileges) {
               $privileges =  explode(',', $privileges);
               $acl->allow($user, $resource, $privileges);
            }
        }        
        return $acl;
    }
    
        public function translate($text)
        {
            $translate = $this->translate;
            $text = $translate->_($text);
            return $text;
        }

}

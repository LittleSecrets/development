<?php
class Index_IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        
    }
    /**
     * Action of controller
     */
    public function indexAction()
    {

        $this->view->content = '';
    }
    
}
<?php
/**
 * File of controller Index in module blog
 *
 * @package blog
 * @subpackage controllers
 * @copyright zfmyadmin.com
 * @license http://zfmyadmin.com/license     New BSD License
 * @version 1.01
 * @author My Name <email@exemple.com>
 */


/**
 * Class Blog_IndexController of controller in module blog
 *
 * @package blog
 * @subpackage controllers
 */
class Blog_IndexController extends Zend_Controller_Action
{

    /**
     * Service method of controller Index in module blog
     *
     * @version 1.01
     * @author My Name <email@exemple.com>
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Service method of controller Index in module blog
     *
     * @version 1.01
     * @author My Name <email@exemple.com>
     */
    public function preDispatch()
    {
        parent::preDispatch();
     
    }

    /**
     * Service method of controller Index in module blog
     *
     * @version 1.01
     * @author My Name <email@exemple.com>
     */
    public function postDispatch()
    {
        parent::postDispatch();

    }


    /**
     * Action of controller
     *
     * @version 1.01
     * @author My Name <email@exemple.com>
     */
    public function indexAction()
    {
        $model = new Blog;
        $this->view->list = $model->getList();
    }

    /**
     * Action of controller
     *
     * @version
     * @author My Name <email@exemple.com>
     */
    public function postAction()
    {
        $this->view->headLink()->appendStylesheet('/blog/css/index/post.css');
        $this->view->headScript()->appendFile('/blog/js/index/post.js');
        $id = $this->getRequest()->getParam('id');
        if(!empty($id)) {
            $model = new Blog;
            $this->view->post = $model->getPost((int)$id);
        }        
    }

}
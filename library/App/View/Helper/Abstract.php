<?php
class App_View_Helper_Abstract extends Zend_View_Helper_Abstract
{
    protected $_translate;
    
    public function __construct() {
        $this->_translate = Zend_Registry::get('Zend_Translate');
    }

}
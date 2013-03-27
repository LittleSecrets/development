<?php
class App_Form extends Zend_Form 
{
    public function init() {

    }
    public function translate($text)
    {
        $translate = Zend_Registry::get('Zend_Translate');        
        $text = $translate->_($text);
        return $text;
    }
    
}

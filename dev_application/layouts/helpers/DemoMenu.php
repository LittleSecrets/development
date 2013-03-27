<?php

class App_Layout_Helper_DemoMenu extends Zend_View_Helper_Abstract
{
    public function demoMenu($type)
    {
        $pages = new Zend_Config_Ini(APPLICATION_PATH . '/configs/navigation.ini', $type); 
        $container = new Zend_Navigation($pages);
        return $container;
    }
}
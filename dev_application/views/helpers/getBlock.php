<?php
class App_View_Helper_GetBlock extends App_View_Helper_Abstract
{
    public function getBlock($alias, $lang = null)
    {
        if (empty($lang)){
            $lang = Zend_Registry::get('lang');
        }
        $model = new Block;
        $translation = $model->findTranslationByAlias ($alias, $lang);      
        if (!empty($translation->content)) {            
            return $translation->content;
        } else {
           return $this->_translate->_('This block not found');
        }        
    }
}
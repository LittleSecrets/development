<?php

class Zfmyadmin_Filter_ToLowerFirst  implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $value = preg_replace('/^\w/e', "strtolower('$0')", $value);
        return $value;        
    }
}
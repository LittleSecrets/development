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
 * @subpackage library/Validate
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

/**
 * 
 *
 * @package    zfmyadmin
 * @subpackage library/Validate
 */
class Zfmyadmin_Validate_ExtendsControllerClass extends Zfmyadmin_Validate_Abstract
{    
    /**
     * Error codes
     * @const string
     */
    const CLASS_NO_EXIST = 'ClassNoExists';
    const CLASS_NO_EXTENDS_PROPERLY = 'ClassNoExtendsZendControllerAction';
    
  
    public function isValid($value)
    {
        if(class_exists($value, true)){
            if(is_subclass_of($value, 'Zend_Controller_Action')
                    || $value = 'Zend_Controller_Action'
            ) {                
                return true;
            } else {
                $this->_error(self::CLASS_NO_EXTENDS_PROPERLY, $this->translate("Class does not extend Zend_Controller_Action!"));
                return false; 
            }
        } else {
            $this->_error(self::CLASS_NO_EXIST, $this->translate("Class does not exist!"));
            return false; 
        }
        
    }

}
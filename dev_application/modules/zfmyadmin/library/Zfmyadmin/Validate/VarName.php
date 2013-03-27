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

class Zfmyadmin_Validate_VarName extends Zfmyadmin_Validate_Abstract
{    

    /**
     * Error codes
     * @const string
     */    
    const NOT_VALID_VARIABLE_NAME = 'notValidVariableName';
   
    public function isValid($value) {
       $pattern = '/^[a-zA-Z_]+[a-zA-Z0-9_]*$/';
       if (!preg_match($pattern, $value)) {
            $this->_error(self::NOT_VALID_VARIABLE_NAME, $this->translate("'%value%' not correct variable name"));
            return false;
       }
       return true;
       
    }

}
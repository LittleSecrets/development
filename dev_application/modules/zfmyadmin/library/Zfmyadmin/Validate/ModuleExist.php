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

class Zfmyadmin_Validate_ModuleExist extends Zfmyadmin_Validate_Abstract
{ 
    
    protected $_controllersPath; 
    
    /**
     * Error codes
     * @const string
     */    
    const MODULE_NO_EXIST = 'moduleNoExists';

  
    public function isValid($value)
    {
        
        $project = Zfmyadmin_Models_Project::getInstance();
        $module = $project->getModule($value);        
        if (empty($module->name)) {
            $this->_error( self::MODULE_NO_EXIST, $this->translate('Module does not exist!'));
            return false;
        }        
        return true;
    }

}
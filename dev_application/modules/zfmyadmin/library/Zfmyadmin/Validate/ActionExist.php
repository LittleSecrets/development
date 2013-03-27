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
 * Check existens action in the controller.
 *
 * @package    zfmyadmin
 * @subpackage library/Validate
 */

class Zfmyadmin_Validate_ActionExist extends Zfmyadmin_Validate_Abstract
{ 
    
    protected $_actions; 
    
    /**
     * Error codes
     * @const string
     */    
    const ACTION_NO_EXIST = 'actionNoExists';
    
    
    /**
     * Sets validator options
     *
     * @param  string $moduleName
     * @param  string $controllerName
     * @return void
     */    
    public function __construct($moduleName  = '', $controllerName = '')
    {
        $project = Zfmyadmin_Models_Project::getInstance();
        $this->_actions = $project->getActions($moduleName, $controllerName);
    }    
  
    public function isValid($value)
    {
        if (!array_key_exists($value, $this->_actions)) {
            $this->_error(self::ACTION_NO_EXIST, $this->translate("Action does not exist!"));
            return false;
        }        
        return true;
    }

}



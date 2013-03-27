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

class Zfmyadmin_Validate_ControllerExist extends Zfmyadmin_Validate_Abstract
{ 
    
    protected $_controllersPath; 
    
    /**
     * Error codes
     * @const string
     */    
    const CONTROLLER_NO_EXIST = 'fileNoExists';
    
    /**
     * Sets validator options
     *
     * @param  string $moduleName
     * @return void
     */       
    public function __construct($moduleName  = '')
    {
        $project = Zfmyadmin_Models_Project::getInstance();
        $module = $project->getModule($moduleName);        
        $this->_controllersPath = $module->controllersDir;
    }    
  
    public function isValid($value)
    {
        $value.='Controller.php';
        if (!file_exists($this->_controllersPath . DIRECTORY_SEPARATOR . $value)) {
                $this->_error( self::CONTROLLER_NO_EXIST, $this->translate('In this module such controller does not exist!'));
                return false;
            }        
        return true;
    }

}
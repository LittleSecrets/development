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

class Zfmyadmin_Validate_DirExists extends Zfmyadmin_Validate_Abstract
{
    protected $_basePath;
    
    /**
     * Error codes
     * @const string
     */    
    const DIR_NO_EXIST = 'fileExists';
    
    
    /**
     * Sets validator options
     *
     * @param  string $pathType
     * @return void
     */   
    public function __construct($pathType = null)
    {
        $project = Zfmyadmin_Models_Project::getInstance();
        switch ($pathType) {
            case 'root':
                $this->_basePath = $project->getRoot().DIRECTORY_SEPARATOR;
                break;

           case 'public':
                $this->_basePath = $project->getPublicPath().DIRECTORY_SEPARATOR;
                break;

            default:
                $this->_basePath = '';
                break;
        }

    }    
  
    public function isValid($value)
    {
        $value = $this->_basePath.$value;
        if (!is_dir($value)) {
                $this->_error(self::DIR_NO_EXIST, $this->translate('Directory does not exist!'));
                return false;
        }        
        return true;
    }
}
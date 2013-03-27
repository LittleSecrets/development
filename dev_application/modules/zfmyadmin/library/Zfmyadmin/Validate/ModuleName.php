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

class Zfmyadmin_Validate_ModuleName extends Zfmyadmin_Validate_Regex
{    
   
    public function __construct()
    {
        $this->_messageTemplates[parent::NOT_MATCH] = $this->translate('Invalid module name');
        $pattern = "%^[a-z]+?[a-z0-9]*([A-Z]+[a-z0-9]*)*$%";
        parent::__construct($pattern);
        
    }

}
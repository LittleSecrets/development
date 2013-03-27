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
 * @subpackage form/decorators
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

/**
 * Set decorators for router vars elements.
 *
 * @package    zfmyadmin
 * @subpackage form/decorators
 */


class Zfmyadmin_Forms_Decorators_RouterVars extends  Zend_Form_Decorator_Abstract
{
        
    public  function render($content)
    {
        switch ($this->getElement()->getAttrib('data-type')) {
            case 'var-name':
                $html = '<tr data-number="'.$this->getElement()->getAttrib('data-number').'" ><td class="router-var-name">'.$content.'</td>';
                break;
            case 'var-value':
                $html = '<td  class="router-var-value" >'.$content.'</td>
                         <td>
                             <div class="ui-state-default ui-corner-all button-16 router-var-button-delete" title="Удалить переменную" data-number="'.$this->getElement()->getAttrib('data-number').'">
                             <span class="ui-icon ui-icon-circle-minus"></span>
                             </div>
                         </td>                    
                        </tr>';
                break;
        }
        return $html;    
    }
}


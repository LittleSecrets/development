<?php
/**
* %Description%*
*
* @package    zfmyadmin
* @subpackage helpers
* @copyright  http://zfmyadmin.com
* @license    GNU General Public License   http://www.gnu.org/copyleft/gpl.html
* @version    1.0
* @link       http://zfmyadmin.com
* @since      File available since Release 1.1.0
* @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
*/

class Zfmyadmin_View_Helper_OperationStatus  extends Zend_View_Helper_Abstract
{
    public function operationStatus($status)
    {
        switch ($status) {
            case Zfmyadmin_Models_Operation::STATUS_ADD_TO_LOG:
                $class = '';
                break;
            case Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE:
                $class = 'action-info-content-commit';                
                break;
            case Zfmyadmin_Models_Operation::STATUS_ERROR_ADD_TO_FILE:
                $class = 'action-info-content-error';                
                break;            
        }
        return $class;
    }
}

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

class Zfmyadmin_View_Helper_OperationStatusDescription  
    extends Zend_View_Helper_Abstract
{
    public function operationStatusDescription($operation)
    {
        switch ($operation->status) {
            case Zfmyadmin_Models_Operation::STATUS_ADD_TO_LOG:
                $description = ' '.$this->view->translate('Saved to log');
                break;
            case Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE:
                $description = ' '.$this->view->translate('Saved to log, file(s) and code added to project');
                break;
            case Zfmyadmin_Models_Operation::STATUS_ERROR_ADD_TO_FILE:
                $description = ' '.$this->view->translate('Saved to log, failed to save file(s) and code to project');
                break;            

            default:
                $description = $this->view->translate('TODO');
                break;         
        }
        return $description;
    }
    
}

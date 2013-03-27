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

class Zfmyadmin_View_Helper_OperationTypeTitle  extends Zend_View_Helper_Abstract
{
    public function operationTypeTitle($type)
    {
        switch ($type) {
            case Zfmyadmin_Models_Operation::TYPE_CREATE_DIR:
                $title = _('Create directory');
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_FILE:
                $title = _('Create file');
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_CODE:
                $title = _('Create code');
                break;            
        }
        return $title;
    }
}
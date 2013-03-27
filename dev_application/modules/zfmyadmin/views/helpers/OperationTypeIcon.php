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

class Zfmyadmin_View_Helper_OperationTypeIcon  extends Zend_View_Helper_Abstract
{
    public function operationTypeIcon($type)
    {
        switch ($type) {
            case Zfmyadmin_Models_Operation::TYPE_CREATE_DIR:
                $class = 'ui-icon-folder-collapsed';
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_FILE:
                $class = 'ui-icon-disk';
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_CODE:
                $class = 'ui-icon-document';
                break;            
        }
        return $class;
    }
}
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

class Zfmyadmin_View_Helper_OperationLogDesctiption  extends Zend_View_Helper_Abstract
{
    public function operationLogDesctiption($operation)
    {
        $desctiption = '';
        switch ($operation->type) {
            case Zfmyadmin_Models_Operation::TYPE_CREATE_DIR:
                $desctiption .= $this->view->translate('Create directory').' '
                             .$this->view->relativePath($operation->target);
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_FILE:
                $desctiption .= $this->view->translate('Create file').' '
                             .$this->view->relativePath($operation->target);
                break;
            case Zfmyadmin_Models_Operation::TYPE_CREATE_CODE:
                switch ($operation->category) {
                    case Zfmyadmin_Models_Operation::CATEGORY_MODULE:
                       $desctiption .= $operation->description.' '
                                    .$this->view->translate('in file').' '
                                    .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER:
                       $desctiption .= $this->view->translate('Create class').' '
                                    .$operation->description.' '
                                    .$this->view->translate('in file').' '
                                    .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_ACTION:
                       $desctiption .= $this->view->translate('Create action').' '
                                    .$operation->description.'() '
                                    .$this->view->translate('in file').' '
                                    .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_VIEW:
                       $desctiption .= $operation->description.' '
                                    .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_METHOD:
                       $desctiption .= _('Create method').' '
                                    .$operation->description.'() '
                                    .$this->view->translate('in file').' '
                                    .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_CSS:
                        $desctiption .= $operation->description.' '
                                     .$this->view->translate('in file').' '
                                     .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_JS:
                        $desctiption .= $operation->description.' '
                                     .$this->view->translate('in file').' '
                                     .$this->view->relativePath($operation->target);
                        break;
                    case Zfmyadmin_Models_Operation::CATEGORY_ROUTER:
                        $desctiption .= $this->view->translate('Create route').' '
                                     .$this->view->translate('in file').' '
                                     .$this->view->relativePath($operation->target);
                        break;
                }
                break;            
        }
        return $desctiption;
    }
}

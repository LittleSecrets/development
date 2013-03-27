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

class Zfmyadmin_View_Helper_RelativePath  extends Zend_View_Helper_Abstract
{
    public function relativePath($path)
    {
        $path = Zfmyadmin_Models_Project::getInstance()->getRelativePath($path);
        return $path;
    }
    
}
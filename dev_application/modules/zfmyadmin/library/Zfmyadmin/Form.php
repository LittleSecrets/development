<?php
/**
* %Description%*
*
* @package    zfmyadmin
* @subpackage library
* @copyright  http://zfmyadmin.com
* @license    GNU General Public License   http://www.gnu.org/copyleft/gpl.html
* @version    1.0
* @link       http://zfmyadmin.com
* @since      File available since Release 1.1.0
* @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
*/


class Zfmyadmin_Form extends Zend_Form 
{
    public function init() {
        
    }

    /**
     * Returns translated text
     * @param string $text 
     * @return string
     */
    
    public function translate($text)
    {
        $translate = Zend_Registry::get('Zend_Translate');        
        $text = $translate->_($text);
        return $text;
    }
    
    public function setSubmit(){
        $element = new Zend_Form_Element_Submit('Submit', array(
            'label' => $this->translate('Submit'),
            'id'    => 'form-submit',
        ));
        $this->addElement($element); 
        $this->Submit->setDecorators(array(
            'ViewHelper',
        ));
    }
}
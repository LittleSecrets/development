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
 * @subpackage forms
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

/**
 * Install module zfmyadmin
 *
 * @package    zfmyadmin
 * @subpackage forms
 */
class Zfmyadmin_Forms_Install extends Zend_Form
{
    /**
     * Init form parametrs and create form elements
     *
     * @return void
     */
    public function init()
    {

        $this->setAction('');
        $this->setMethod('POST');

        $element = new Zend_Form_Element_Text('root',
            array(
                'id'       => 'form-install-root',
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => _('Project root'),
                'size'     => '64',
                'value'    => realpath($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'..')

            )
        );
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('publicDir',
            array(
                'id'       => 'form-install-public',
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => _('Path to public folder'),
                'size'     => '64',
                'value'    => $_SERVER['DOCUMENT_ROOT']
            )
        );

        $this->addElement($element);

        $element = new Zend_Form_Element_Checkbox('separate_db', array(
            'filters' => array('Int'),
            'value'   => '0',
            'label'   => _('Use separate database'),
            'onchange' => 'separate_db_switch()',
        ));

        $this->addElement($element);

        $element = new Zend_Form_Element_Text('host',
            array(
                'id'      => 'form-install-host',
                'filters' => array('StringTrim'),
                'label'   => _('Host'),
                'size'    => '64',
            )
        );
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('username',
            array(
                'id'      => 'form-install-username',
                'filters' => array('StringTrim'),
                'label'   => _('Username'),
                'size'    => '64',
            )
        );
        $this->addElement($element);
        $element = new Zend_Form_Element_Text('password',
            array(
                'id'      => 'form-install-password',
                'filters' => array('StringTrim'),
                'label'   => _('Password'),
                'size'    => '64',
            )
        );
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('dbname',
            array(
                'id'      => 'form-install-dbname',
                'filters' => array('StringTrim'),
                'label'   => _('Database'),
                'size'    => '64',
            )
        );
        $this->addElement($element);


        $element = new Zend_Form_Element_Text('adapter',
            array(
                'id'      => 'form-install-adapter',
                'filters' => array('StringTrim'),
                'label'   => _('Adapter'),
                'size'    => '64',
                'value'   => 'Pdo_Mysql'
            )
        );
        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('submit', array(
            'id'    => 'form-install-submit',
            'value' => 'submit'
        ));
        $element->setDecorators(array(
            'ViewHelper'
        ));
        $this->addElement($element);
    }
}

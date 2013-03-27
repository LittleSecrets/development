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
 * Manages user rules for admin
 * @package    zfmyadmin
 * @subpackage forms
 */
class Zfmyadmin_Forms_UsersEdit extends Zfmyadmin_Form 
{
    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */        
    public function init()
    {
        $this->setAction('/zfmyadmin/user/');
        $this->setMethod('POST');
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));  
         
    }     
    
    /**
     * Add a user and his settings to the form
     *
     * @param object $user
     * @return void
     */
    public function setData($user)
    {
        $this->setName('edit-user-id-'.$user->id);
        $this->setAttrib('id', 'edit-user-id-'.$user->id);
        $this->setAttrib('class', 'edit-user-form');
        
        $element = new Zend_Form_Element_Hidden('userId', array(
            'id'    => 'user-id-'.$user->id,
            'value' => $user->id
        ));
        $element->setFilters(array('Int'));
        $this->addElement($element);         
        
        $element = new Zend_Form_Element_Radio('role', array(
            'id'      => 'role-user-id-'.$user->id,
            'class'   => 'role-user',
            'data-id' => $user->id,
        ));
        $element->setMultiOptions(Zfmyadmin_Models_User::$_roles);
        $element->setValue($user->role);
        $element->setSeparator('');
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class'  => 'form-field-error' )),
            'Label',
        ));
        $element->setRequired();
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Submit('save', array(
            'id'    => 'save-user-button-id-'.$user->id,
            'class' => 'save-user-button',
            'label' => $this->translate('Save'),
        ));
        $element->setFilters(array('Int'));
        $element->setDecorators(array(
            'ViewHelper',
        ));        
        $this->addElement($element); 

        
        $element = new Zend_Form_Element_Submit('delete', array(
            'id'      => 'delete-user-button-id-'.$user->id,
            'class'   => 'delete-user-button',
            'data-id' => $user->id,
            'label'   => $this->translate('Delete'),
        ));
        $element->setDecorators(array(
            'ViewHelper',
        ));        
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Hidden('formTarget',
            array(
                'value' => 'edit'            
            )
        );
        $this->addElement($element); 
        
        foreach ($this as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
           ));
        }       
        
    }

}
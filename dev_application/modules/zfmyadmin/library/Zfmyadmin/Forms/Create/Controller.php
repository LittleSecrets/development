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
 * Form for created new controller and index action in existing module.
 *
 * @package    zfmyadmin
 * @subpackage forms
 */


class Zfmyadmin_Forms_Create_Controller extends Zfmyadmin_Forms_Create 
{
    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */
    public function init()
    {     
        parent::init();
        $this->setAction('/zfmyadmin/create/controller/');
        $this->setAttrib('id', 'create-controller-form'); 
        
        $element = new Zend_Form_Element_Select('moduleName', array(
            'id'    => 'module-name',
            'class' => 'module-name'
        ));
        $element->addValidator(new Zfmyadmin_Validate_ModuleExist);        
        $element->setSeparator('');
        $element->setDecorators(array(            
            'ViewHelper',          
            'Errors',
        )); 
        $this->addElement($element);        
        
        $element = new Zend_Form_Element_Text('controllerName', array(
            'id'           => 'controller-name',
            'autocomplete' => 'off',
            'size'         => 30,
            'filters'      => array('StringTrim'),
            'required'     => true,
        ));
        $element->addFilter(new Zfmyadmin_Filter_WhitespaseToCamelCase);
        $element->addFilter(new Zend_Filter_Word_DashToCamelCase);
        $element->addFilter(new Zend_Filter_Word_UnderscoreToCamelCase);
        $element->addValidator(new Zfmyadmin_Validate_ControllerName);
        
        $this->addElement($element);                 
        
        $element = new Zend_Form_Element_Text('controllerClassName', array(
            'id'      => 'controller-class-name',
            'size'    => 40,
            'value'   => "Zend_Controller_Action",
            'filters' => array('StringTrim'),
        ));
        $element->addValidator(new Zfmyadmin_Validate_ExtendsControllerClass);
        $this->addElement($element); 
        
       
        $element = new Zend_Form_Element_Checkbox('controllerCreateInit', array(
            'id'      => 'controller-create-init',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('controllerPreDispatch', array(
            'id'      => 'controller-create-pre-dispatch',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('controllerCreatePostDispatch', array(
            'id'      => 'controller-create-post-dispatch',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('controllerCreateJs', array(
            'id'      => 'controller-create-js',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('controllerCreateCss', array(
            'id'      => 'controller-create-css',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element);
        $element = new Zend_Form_Element_Checkbox('includePublicFiles', array(
            'id'      => 'action-include-public',
            'filters' => array('Int'),
            'value'   => '1'
        ));
        $this->addElement($element); 
       
    
        $element = new Zend_Form_Element_Hidden('intentionSignature', array(
            'id'   => 'intention-signature',
            'value'=> ''
        ));
       
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Hidden('creatorCategory', array(
            'id'      => 'creator-category',
            'value'   => Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER,
            'filters' => array('Int'),
        ));
       
        $this->addElement($element);  
        
        foreach ($this as $element) {        
            $element->setDecorators(array(
                'ViewHelper',
                array('Errors', array('class'  => 'form-field-error' )),
                'Label',
            ));
        }         
        
        $this->setSubmit();
       
        
    }

}

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
 * Form for created new action in existing controller.
 *
 * @package    zfmyadmin
 * @subpackage forms
 */

class Zfmyadmin_Forms_Create_Router extends Zfmyadmin_Forms_Create
{

    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */
    public function init()
    {     
        parent::init();
        $this->setAction('/zfmyadmin/create/router/');
        $this->setAttrib('id', 'create-router-form');  
        
        $element = new Zend_Form_Element_Select('moduleName', array(
            'id'           => 'module-name',
            'class'        => 'module-name',
            'required'     => true,
        ));
        $element->addValidator(new Zfmyadmin_Validate_ModuleExist);
        $element->setSeparator('');
        $element->setDecorators(array(            
                'ViewHelper',          
                'Errors',
        )); 
        $this->addElement($element);  
        
        $element = new Zend_Form_Element_Select('controllerName', array(
            'id'           => 'controller-name',
            'class'        => 'controller-name',
            'required'     => true,
        ));
        $element->setSeparator('');
        $element->setDecorators(array(            
                'ViewHelper',          
                'Errors',
        )); 
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Select('actionName', array(
            'id'           => 'action-name',
            'class'        => 'action-name',
            'required'     => true,
        ));
        $element->setSeparator('');
        $element->setDecorators(array(            
                'ViewHelper',          
                'Errors',
        )); 
        $this->addElement($element);         
                       
        
        $element = new Zend_Form_Element_Text('routerTitle', array(
            'id'      => 'create-router-name',
            'size'    => 30,
            'filters' => array('StringTrim'),
        ));
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('routerUrlName', array(
            'id'      => 'create-router-name',
            'size'    => 30,
            'filters' => array('StringTrim'),
        ));
        $this->addElement($element); 
             
        $element = new Zend_Form_Element_Hidden('intentionSignature', array(
            'id'=>'intention-signature',
            'value'=>''
        ));
       
        $this->addElement($element); 
        
         $element = new Zend_Form_Element_Hidden('creatorCategory', array(
            'id'      => 'creator-category',
            'value'   => Zfmyadmin_Models_Operation::CATEGORY_ROUTER,
            'filters' => array('Int'),
        ));
       
        $this->addElement($element); 
        
        foreach ($this as $element) {        
            $element->setDecorators(array(
                'ViewHelper',
                array('Errors', array('class' => 'form-field-error' )),
                'Label',
            ));
        } 
        
        $this->setSubmit();
        
    }
    
}

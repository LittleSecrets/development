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
 * Form for created new form.
 *
 * @package    zfmyadmin
 * @subpackage forms
 */

class Zfmyadmin_Forms_Create_Form extends Zfmyadmin_Forms_Create
{
    public static $_form_methods = array(
        '0' => '',
        'GET'  => 'GET',
        'POST' => 'POST',
        'HEAD' => 'HEAD',
    );
    public static $_form_targets = array(
        '0'       => '',        
        '_blank'  => '_blank',
        '_self'   => '_self',
        '_parent' => '_parent',
        '_top'    => '_top',
    );

    public static $_form_enctype = array(
        '0' => '',
        'application/x-www-form-urlencoded'  => 'application/x-www-form-urlencoded',
        'multipart/form-data'                => 'multipart/form-data',
        'text/plain'                         => 'text/plain',
    );

    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */
    public function init()
    {     
        parent::init();
        $this->setAction('/zfmyadmin/create/form/');
        $this->setAttrib('id', 'create-form-form');  
        
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
 //====================================================================                      
        
        $element = new Zend_Form_Element_Text('formClassName', array(
            'id'      => 'create-form-class-name',
            'size'    => 50,
            'filters' => array('StringTrim'),
        ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('formExtendsClassName', array(
            'id'      => 'create-form-extends-class-name',
            'size'    => 50,
            'filters' => array('StringTrim'),
        ));
        $this->addElement($element);

        
        
//        =================================================================
        
//        ===============================================================        
        
        $element = new Zend_Form_Element_Hidden('intentionSignature', array(
            'id'=>'intention-signature',
            'value'=>''
        ));
       
        $this->addElement($element);        
        
        
         $element = new Zend_Form_Element_Hidden('creatorCategory', array(
            'id'      => 'creator-category',
            'value'   => Zfmyadmin_Models_Operation::CATEGORY_FORM,
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
        $this->addSubFormAttr();
        $this->setSubmit();
        
    }
    
    public function addSubFormAttr()
    {
        $subform = new Zend_Form_SubForm();
        $element = new Zend_Form_Element_Text('name', array(
            'id'      => 'create-form-name',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('name'),
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Text('action', array(
            'id'      => 'create-form-formAction',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('action'),
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Select('method', array(
            'id'      => 'create-form-formMethod',
            'filters' => array('StringTrim'),
            'label'   => $this->translate('method'),
        ));
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form::$_form_methods);
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Select('enctype', array(
            'id'      => 'create-form-formEnctype',
            'filters' => array('StringTrim'),
            'label'   => $this->translate('enctype'),
        ));
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form::$_form_enctype);
        $subform->addElement($element);        
        
        $element = new Zend_Form_Element_Text('id', array(
            'id'      => 'create-form-formId',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('id'),
        )); 
        $subform->addElement($element);  
        
        $element = new Zend_Form_Element_Text('class', array(
            'id'      => 'create-form-formClass',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('class'),
        )); 
        $subform->addElement($element);         
        
        $element = new Zend_Form_Element_Select('target', array(
            'id'      => 'create-form-formTarget',
            'multiple' => false,
            'label'   => $this->translate('target'),
        )); 
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form::$_form_targets);
        $subform->addElement($element);  
        
        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                 array('Label', array('tag' => 'h4','class'  => 'element-label' )),
                 array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'fieldset')),
           ));
        } 
        
        $subform->setDecorators(array(
            'FormElements',
        )); 
        
        $this->addSubForm($subform, 'attr');
    }
    
    public function addSubFormDesign($list)
    {
    
        $subform = new Zend_Form_SubForm();
        
        $options = array();
        foreach ($list as $key => $value) {
            $options[$key] = $value['name'];            
        }
//        var_dump($options);
//        die;
        $element = new Zend_Form_Element_Radio('type', array(
            'multiOptions' => $options,
            'class' => 'design-type'
        ));
        
        $element->setDecorators(array(
            'ViewHelper',
            'Errors',
//            array('Label', array('tag' => 'span', 'class' => 'desine-type-label')),
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fieldset fieldset-left-40')),
        ));


        $subform->addElement($element);      


        $subform->setDecorators(array(
            'FormElements',
        ));
        
        
        $this->addSubForm($subform, 'design');        
        
    }
}

<?php

class Zfmyadmin_Forms_Create_Form_Field extends Zfmyadmin_Forms_Create
{
    public static $_field_tags = array(
        'input' => 'input',
        'select'  => 'select',
        'textarea' => 'textarea',
    );
    
    public static $_field_input_types = array(
        'text' => 'text',
        'checkbox' => 'checkbox',
        'radio' => 'radio',
        'password' => 'password',
        'file' => 'file',
        'hidden' => 'hidden',
        'submit' => 'submit',
        'button' => 'button',
        'image' => 'image',        
        'reset' => 'reset',
    );  
    
    public function init()
    {     
        parent::init();
        $this->setAction('/zfmyadmin/create/form/');
        $this->setAttrib('id', 'create-form-formfield'); 
        

        
        $this->addSubFormGeneral();
        $this->addSubFormInput();
        $this->addSubFormSelect();
        $this->addSubFormTextarea();
        $this->setSubmit();
        
        $element = new Zend_Form_Element_Hidden('formType', array(
            'id'=>'intention-signature',
            'value'=>'addField'
        ));
       
        $this->addElement($element); 
        

    }

    public function addSubFormGeneral()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Select('tag', array(
            'id'      => 'create-form-formTag',
            'multiple' => false,
            'label'   => $this->translate('tag'),
        ));
        
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form_Field::$_field_tags);
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Text('name', array(
            'id'      => 'create-formfield-name',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('name'),
        ));
        $subform->addElement($element); 
        
       
        $element = new Zend_Form_Element_Text('value', array(
            'id'      => 'create-formfield-name',
            'size'    => 20,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('value'),
        ));
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
        
        foreach ($subform as $element) {        
            $element->setDecorators(array(
                'ViewHelper',
                array('Errors', array('class' => 'form-field-error' )),
                'Label',
            ));
        } 
        
        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                 array('Label', array('tag' => 'h4','class'  => 'element-label' )),
                 array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'fieldset')),
           ));
        }
        
        $this->addSubForm($subform, 'general');
        
    }    
    public function addSubFormInput()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Select('type', array(
            'id'      => 'create-form-formType',
            'multiple' => false,
            'label'   => $this->translate('type'),
        )); 
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form_Field::$_field_input_types);
        $subform->addElement($element);         
        
        
        $subform->setDecorators(array(
            'FormElements',
        )); 
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
        $this->addSubForm($subform, 'input');
    }
    
    public function addSubFormSelect()
    {
        $subform = new Zend_Form_SubForm();  
        
        $element = new Zend_Form_Element_Checkbox('multiple', array(
            'id'      => 'create-form-formMultiple',
            'label'   => $this->translate('multiple'),
        )); 
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Text('size', array(
            'id'      => 'create-form-formSize',
            'size'    => 50,
            'filters' => array('Int'),
            'label'   => $this->translate('size'),
        )); 
        $subform->addElement($element);         

        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                 array('Label', array('tag' => 'h4','class'  => 'element-label' )),
                 array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'fieldset')),
           ));
        }
        
        $this->addSubForm($subform, 'select');
    }
    
    public function addSubFormTextarea()
    {
        $subform = new Zend_Form_SubForm();
        
        
        
        $element = new Zend_Form_Element_Text('cols', array(
            'id'      => 'create-form-formCols',
            'size'    => 50,
            'filters' => array('Int'),
            'label'   => $this->translate('cols'),
        )); 
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Text('rows', array(
            'id'      => 'create-form-formRows',
            'size'    => 50,
            'filters' => array('Int'),
            'label'   => $this->translate('rows'),
        )); 
        $subform->addElement($element);

        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                 array('Label', array('tag' => 'h4','class'  => 'element-label' )),
                 array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'fieldset')),
           ));
        } 
        
        $this->addSubForm($subform, 'textarea');
    }
}

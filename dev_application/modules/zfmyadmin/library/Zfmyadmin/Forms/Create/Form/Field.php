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
        
        $element = new Zend_Form_Element_Text('name', array(
            'id'      => 'create-formfield-name',
            'size'    => 50,
            'filters' => array('StringTrim'),
            'label'   => $this->translate('name'),
        ));
        $this->addElement($element); 
        
        $element = new Zend_Form_Element_Select('tag', array(
            'id'      => 'create-form-formTarget',
            'multiple' => false,
            'label'   => $this->translate('tag'),
        )); 
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form_Field::$_field_tags);
        $this->addElement($element);
        
        foreach ($this as $element) {        
            $element->setDecorators(array(
                'ViewHelper',
                array('Errors', array('class' => 'form-field-error' )),
                'Label',
            ));
        } 
        $this->addSubFormInput();
        $this->addSubFormSelect();
        $this->addSubFormTextarea();
        $this->setSubmit();
        

    }
    
    public function addSubFormInput()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Select('type', array(
            'id'      => 'create-form-formType',
            'multiple' => false,
            'label'   => $this->translate('tag'),
        )); 
        $element->setMultiOptions(Zfmyadmin_Forms_Create_Form_Field::$_field_input_types);
        $subform->addElement($element);         
        
        
        $subform->setDecorators(array(
            'FormElements',
        )); 
        
        $this->addSubForm($subform, 'input');
    }
    
    public function addSubFormSelect()
    {
        $subform = new Zend_Form_SubForm();
        
        
        
        $subform->setDecorators(array(
            'FormElements',
        )); 
        
        $this->addSubForm($subform, 'select');
    }
    
    public function addSubFormTextarea()
    {
        $subform = new Zend_Form_SubForm();
        
        
        
        $subform->setDecorators(array(
            'FormElements',
        )); 
        
        $this->addSubForm($subform, 'textarea');
    }
}

<?php
class Zfmyadmin_Forms_Create_Form_Show extends Zend_Form
{
    public function init()
    {     
        parent::init();
        $this->setAction('');
        
        
        $element = new Zend_Form_Element_Text('testElement', array(
            'id'           => 'test-element',
            'class'        => 'test-element',
            'value'     => 'test',
        ));
        $this->addElement($element);
    }
}

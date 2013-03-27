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
 * Manages personal user settings for commit, documentation and other options
 * @package    zfmyadmin
 * @subpackage forms
 */
class Zfmyadmin_Forms_UserSettings extends Zfmyadmin_Form 
{
    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */         
    public function init()
    {
        $this->setAction('/zfmyadmin/user/settings');
        $this->setMethod('POST');
        $this->setAttrib('id', 'user-settings-form');
        
        $this->addSubFormPersonal();
        $this->addSubFormCommit();
        $this->addSubFormGenerator();
        
        $subform = new Zend_Form_SubForm();
        $this->addSubForm($subform, 'doc');
        
        $this->addSubFormDocDefault();
        $this->addSubFormDoc('phpFile');
        $this->addSubFormDoc('class');
        $this->addSubFormDoc('method');
        $this->addSubFormDoc('viewFile');
        $this->addSubFormDoc('cssFile');
        $this->addSubFormDoc('jsFile'); 
        foreach ($this as $subform) {        
            $subform->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'div','class'  => 'subform' )),
            ));  
        } 
        
        $element = new Zend_Form_Element_Submit('submit', array(
            'id' => 'form-submit',
            'label' => $this->translate('Submit'),
        ));
        $element->setDecorators(array(
            'ViewHelper',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
        ));
        
        $this->addElement($element); 
        
    }

    /**
     * Fields for personal data
     *
     * @return void
     */
    public function addSubFormPersonal()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Text('name', 
            array(
                'id'      => 'form-user-name',            
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your name')
            )
        );
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('email', 
            array(
                'id'      => 'form-user-email',            
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Email')
            )
        );

        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('company', 
            array(
                'id'      => 'form-user-company',            
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Company')
            )
        );
        
        $subform->addElement($element);

        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
                array('Label', array('tag' => 'div','class'  => 'element-label' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
           ));
        } 
        
        $this->addSubForm($subform, 'personal');       
    }

    /**
     * Fields for commit options
     *
     * @return void
     */
    public function addSubFormCommit()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Checkbox('isSaveToLog', 
            array(
                'id'      => 'form-user-save-to-project',            
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Save my changes to log'),
                'value'   => '1',
            )
        );

        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('isSaveToProject', 
            array(
                'id'      => 'form-user-save-to-log',            
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Save my changes to project'),
                'value'   => '1',
            )
        );

        $subform->addElement($element);
        
        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
                array('Label', array('tag' => 'div','class'  => 'element-label' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
           ));
        } 
        
        $this->addSubForm($subform, 'commit');      
    }
    
    /**
     * Fields for options of code generator
     *
     * @return void
     */

    public function addSubFormGenerator()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Checkbox('include_router', 
            array(
                'id'      => 'form-user-include-router',            
                'filters' => array('Int'),
                'label'   => $this->translate('Include route to project'),
                'value'   => '1',
            )
        );

        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('include_router_ini', 
            array(
                'id'      => 'form-user-include-router',            
                'filters' => array('Int'),
                'label'   => $this->translate('Include route to project in .ini format'),
                'value'   => '1',
            )
        );

        $subform->addElement($element);
        
       
        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
                array('Label', array('tag' => 'div','class'  => 'element-label' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
           ));
        } 
       
        $this->addSubForm($subform, 'generator');      
    }
    /**
     * Fields for default options of docblocs
     *
     * @return void
     */
    public function addSubFormDocDefault()
    {
        $tags = Zfmyadmin_Models_Project::$_phpDocTags;        
        $subform = new Zend_Form_SubForm();
        $element = new Zend_Form_Element_Checkbox('packageDefault', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Use module name as package name '),
                'value'   => '1',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('package', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your package name '),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('subpackageDefault', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Use file type as package name (controllers, views, js, css ...)'),
                'value'   => '1',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('subpackage', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your subpackage name '),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('copyrightDefault', 
            array(          
                'filters'    => array('StringTrim'),
                'label'      => $this->translate('Use company name as copyright '),
                'value'      => '1',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('copyright', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your copyright'),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('license', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your license'),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
        $subform->addElement($element);
        $element = new Zend_Form_Element_Text('version', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your version'),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('authorDefault', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Use my name and e-mail'),
                'value'   => '1',
            )
        );  
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Text('author', 
            array(          
                'filters' => array('StringTrim'),
                'label'   => $this->translate('Your author name '),
                'value'   => '',
            )
        );  
        $subform->addElement($element);
        
 
        
        foreach ($subform as $element) {        
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
                array('Label', array('tag' => 'div','class'  => 'element-label' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
           ));
        }      
        $subform->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div','class'  => 'doc-settings-default-block' )),
        ));         
        $this->doc->addSubForm($subform, 'default'); 
        
    }

    /**
     * Fields for options of docblocs
     *
     * @return void
     */
    public function addSubFormDoc($type)
    {
        if($type == 'method') {
            $tags = Zfmyadmin_Models_Project::$_phpDocMethodTags;
        } else {
            $tags = Zfmyadmin_Models_Project::$_phpDocTags; 
        }
               
        $subform = new Zend_Form_SubForm();
        foreach ($tags as $key => $value) {
           $element = new Zend_Form_Element_Checkbox($key, 
                array(          
                    'filters' => array('StringTrim'),
                    'label'   => $value,
                    'value'   => '1',
                )
           );  
           $element->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),
                array('Label', array('tag' => 'div','class'  => 'element-label' )),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
           ));
            $subform->addElement($element);
        }  
        $subform->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div','class'  => 'doc-settings-type-block' )),
        )); 
        $this->doc->addSubForm($subform, $type); 
    }
    
}

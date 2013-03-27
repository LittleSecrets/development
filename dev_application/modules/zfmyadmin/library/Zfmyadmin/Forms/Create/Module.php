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
 * Form for created new module with all folders, bootstrap and .ini files 
 *
 * @package    zfmyadmin
 * @subpackage forms
 */



class Zfmyadmin_Forms_Create_Module extends Zfmyadmin_Forms_Create 
{
    /**
     * Init form parametrs and create form elements
     * 
     * @return void
     */    
    public function init()
    {     
        parent::init();
        $this->setAction('/zfmyadmin/create/module/');
        $this->setAttrib('id', 'create-module-form'); 
        
        $element = new Zend_Form_Element_Text('moduleName', array(
            'id'           => 'module-name',
            'autocomplete' => 'off',
            'size'         => 30,
            'filters'      => array('StringTrim','StringToLower'),
            'required'     => true,
            'label'        => $this->translate('Module name')
        ));
        
      
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class'  => 'form-field-error' )),
            'Label',
        ));

        $element->addValidator(new Zfmyadmin_Validate_ModuleNoExist);
        $element->addValidator(new Zfmyadmin_Validate_ModuleName);
        
        $this->addElement($element); 
        
        $this->addFolders();
        $this->addBootstrap();        

    
        $element = new Zend_Form_Element_Hidden('intentionSignature', array(
            'id'    => 'intention-signature',
            'value' => ''
        ));
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'form-field-error' )),
            'Label',
        ));       
        $this->addElement($element); 
        

        
        $element = new Zend_Form_Element_Hidden('creatorCategory', array(
            'id'      => 'creator-category',
            'value'   => Zfmyadmin_Models_Operation::CATEGORY_MODULE,
            'filters' => array('Int'),
        ));
        $this->addElement($element); 
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'form-field-error' )),
            'Label',
        ));        
        $this->setSubmit();
    }
    
    /**
     * Add elements for created foldes
     * 
     * @return void
     */
    public function addFolders()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Checkbox('configs', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => 'configs'
        ));
        $subform->addElement($element);   
        
        $element = new Zend_Form_Element_Checkbox('controllers', array(
            'filters'  => array('Int'),
            'value'    => '1',
            'required' => true,
            'checked'  => true,
            'label'    => 'controllers'            
        ));
        $subform->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox('forms', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => 'forms'           
        ));
        $subform->addElement($element);         
        
        $element = new Zend_Form_Element_Checkbox('controllers_helpers', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',
            'value'   => '1',
            'label'   => 'controllers/helpers'         
        ));
        $subform->addElement($element);   

        
        $element = new Zend_Form_Element_Checkbox('layouts', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => 'layouts'           
        ));
        $subform->addElement($element); 
        
                $element = new Zend_Form_Element_Checkbox('views', array(
            'filters'  => array('Int'),
            'value'    => '1',
            'required' => true,
            'checked'  => true,
            'label'    => 'views'       
        ));
        $subform->addElement($element); 
        
        
        
        
        
        $element = new Zend_Form_Element_Checkbox('layouts_filters', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',
            'value'   => '1',
            'label'   => 'layouts/filters'      
        ));
        
        $subform->addElement($element); 
                $element = new Zend_Form_Element_Checkbox('views_filters', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',            
            'value'   => '1',
            'label'   => 'views/filters'          
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('layouts_helpers', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',
            'value'   => '1',
            'label'   => 'layouts/helpers'         
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('views_helpers', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',            
            'value'   => '1',
            'label'   => 'views/helpers'        
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('layouts_scripts', array(
            'filters' => array('Int'),
            'class'   => 'indent-1',
            'value'   => '1',
            'label'   => 'layouts/scripts'          
        ));
        
        $subform->addElement($element); 
                $element = new Zend_Form_Element_Checkbox('views_scripts', array(
            'filters'  => array('Int'),
            'class'    => 'indent-1',            
            'value'    => '1',
            'required' => true,
            'checked'  => true,
            'label'    => 'views/scripts'           
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('models', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => 'models'         
        ));
        $subform->addElement($element); 
        
        $element = new Zend_Form_Element_Checkbox('services', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => 'services'           
        ));
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
            array('HtmlTag', array('tag' => 'div','class'  => 'subform' )),
        )); 
        
        $this->addSubForm($subform, 'folders');  
    }
    
    /**
     * Add elements for created bootstrap and ini
     * 
     * @return void
     */    
    public function addBootstrap()
    {
        $subform = new Zend_Form_SubForm();
        
        $element = new Zend_Form_Element_Checkbox('createBootstrap', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => $this->translate('Create module Bootstrap.php')
        ));
        $subform->addElement($element);   
        
        $element = new Zend_Form_Element_Checkbox('createAplicationIni', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => $this->translate('Create module application.ini')            
        ));
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
            array('HtmlTag', array('tag' => 'div','class'  => 'subform' )),
        )); 
        
        $this->addSubForm($subform, 'bootstrap');
    }
   
}

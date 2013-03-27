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
 * Setup main project settings
 * 
 * @package    zfmyadmin
 * @subpackage forms
 */
class Zfmyadmin_Forms_ProjectSettings extends Zfmyadmin_Form 
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
                'id'       => 'form-project-root',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('Project root'),
                'size'     => '100',
            )
        );   
        $element->addValidator(new Zfmyadmin_Validate_DirExists);
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('publicDir', 
            array(
                'id'       => 'form-project-public',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('Public directory (the one for your CSS and JS files)'),
                'size'     => '100',
            )
        );   
        $element->addValidator(new Zfmyadmin_Validate_DirExists('root'));
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Text('viewScriptsDir', 
            array(
                'id'       => 'form-project-view-scripts',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('Directory for views of controllers in modules'),
                'size'     => '100',
            )
        );        
        $this->addElement($element);


        
        $element = new Zend_Form_Element_Checkbox('separate_modules_public_dirs', array(
            'filters' => array('Int'),
            'value'   => '1',
            'label'   => $this->translate('Put styles and JS to separate directories for each module')
        ));
        $this->addElement($element);  
        
        
        
        $element = new Zend_Form_Element_Text('cssDir', 
            array(
                'id'       => 'form-project-css',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('Directory for .css files (inside Public folder)'),
                'size'     => '100',
            )
        );        
        $this->addElement($element);
        $element = new Zend_Form_Element_Text('jsDir', 
            array(
                'id'       => 'form-project-js',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('Directory for .js files (inside Public folder)'),
                'size'     => '100',
            )
        );        
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('routerFile', 
            array(
                'id'       => 'form-project-rourer-file',            
                'filters'  => array('StringTrim'),
                'required' => true,
                'label'    => $this->translate('File to store your routers (must be .php or .ini)'),
                'size'     => '100',
            )
        );        
        $this->addElement($element);
        
       
        $element = new Zend_Form_Element_Text('routerVar', 
            array(
                'id'          => 'form-project-rourer-var',            
                'filters'     => array('StringTrim'),
                'placeholder' => '$router',
                'label'       => $this->translate('Variable containing router object of class Zend_Controller_Router_Rewrite (for .php only)'),
                'size'        => '100',
            )
        );        
        $this->addElement($element);
        
        $this->setSubmit();
        
    }
}    

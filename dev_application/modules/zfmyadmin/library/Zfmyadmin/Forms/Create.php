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
 * Parent form for all creator forms
 *
 * @package    zfmyadmin
 * @subpackage forms
 */


class Zfmyadmin_Forms_Create extends Zfmyadmin_Form 
{
    /**
     * Init form parametrs and decorators
     * 
     * @return void
     */  
    public function init()
    {     
        parent::init();
        $this->setMethod('POST');
        $this->setAttrib('class', 'creator-form');
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div')),
            'Form'
        )); 
    }
    
    public function setSubmit(){
        $element = new Zend_Form_Element_Submit('createSubmit', array(
            'label'                  => $this->translate('Get TODO'),
            'id'                     => 'create-submit',
            'data-alternative-label' => $this->translate('Get TODO')
        ));
        $this->addElement($element); 
        $this->createSubmit->setDecorators(array(
            'ViewHelper',
        ));
    }

    /**
     * Sets modules list as mulpioptions of form element moduleName
     * 
     * @param array $modules
     * @return void
     */   
    public function setModulesList($modules = array())
    {
        foreach ($modules as $module) {
            if($module->isActive) {
               $list[$module->name]=$module->name; 
            }                        
        }
        $this->moduleName->setMultiOptions($list);
        $this->moduleName->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class'  => 'form-field-error' )),
        ));
    }
    
    /**
     * Sets controllers list as mulpioptions of form element  controllerName
     * 
     * @param array $controllers
     * @return void
     */
    public function setControllersList($controllers = array())
    {
        $list = array();
        foreach ($controllers as $controller) {
               $list[$controller->name] = $controller->name.'Controller.php';                
        }
        $this->controllerName->setMultiOptions($list);
        
        foreach ($controllers as $controller) {
             $this->controllerName->setValue($controller->name);
             break;
        }
        if(empty($controllers)) {
            $this->controllerName->setValue('');
        }
        $this->controllerName->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'form-field-error' )),
        ));
    }
    
    /**
     * Sets actions list as mulpioptions of form element  actionName
     * 
     * @param array $actions
     * @return void
     */
    public function setActionsList($actions = array())
    {
        $list = array();
        foreach ($actions as $action) {
               $list[$action] = $action.'Action()';                
        }
        $this->actionName->setMultiOptions($list);
        
        foreach ($actions as $action) {
             $this->actionName->setValue($action);
             break;
        }
        $this->actionName->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'form-field-error' )),
        ));
    } 
    /**
     * Sets elements of router
     *
     * @return void
     */      
    public function addRouter(){
        $element = new Zend_Form_Element_Text('routerTitle', array(
            'id'      => 'create-router-name',
            'size'    => 30,
            'filters' => array('StringTrim'),
        ));
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class'  => 'form-field-error' )),
            'Label',
        ));   
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('routerUrlName', array(
            'id'      => 'create-router-url-name',
            'size'    => 30,
            'filters' => array('StringTrim'),
        ));
        $element->setDecorators(array(
            'ViewHelper',
            array('Errors', array('class'  => 'form-field-error' )),
            'Label',
        ));        
        $this->addElement($element); 
        
    }
    
    /**
     * Sets elements of router vars for output and validate
     * 
     * @param array $routerData
     * @return void
     */      
    public function addRouterVars($routerData){
        
        $router = new Zend_Form_SubForm();
        
        $vars = array();
        if (!is_array($routerData)) {
            for ($index = 0; $index < (int)$routerData; $index++) {
                $vars[$index] = $index;
            }
        } else {
            foreach ($routerData as $key => $value) { 
                $index = intval(str_replace('routerVars_', '', $key));
                $vars[$index] = $index;
            }            
        }

        foreach ($vars as $index => $value) { 
            
            $routerVars = new Zend_Form_SubForm();
            $routerVars->setDecorators(array(
                'FormElements',
            ));        
            $routerVars->addElementPrefixPath('Zfmyadmin_Forms_Decorators',
                'Zfmyadmin/Forms/Decorators/', 'decorator'
            );
            
            
            $element = new Zend_Form_Element_Text('Name', array(
                'size'        => 26,
                'filters'     => array('StringTrim'),
                'data-type'   => 'var-name',
                'data-number' => $index,
                
            ));
            $element->addValidator(new Zfmyadmin_Validate_VarName);
            $element->setDecorators(array(                                    
                    'ViewHelper',
                    array('Errors', array('class'  => 'form-field-error' )),
                    'RouterVars'
                )
            );
            $routerVars->addElement($element);

            $element = new Zend_Form_Element_Text('Value', array(
                'size'        => 26,
                'filters'     => array('StringTrim'),
                'data-type'   => 'var-value',
                'data-number' => $index,
            ));
            $element->setDecorators(array(
                    'ViewHelper',
                    array('Errors', array('class'  => 'form-field-error' )),
                    'RouterVars'
                )
            );
            $routerVars->addElement($element); 
            $router->addSubForm($routerVars, 'routerVars_'.$index); 
        }       
   
        $router->setDecorators(array(            
            'FormElements',
        )); 
                
        $this->addSubForm($router, 'router');
    }

}

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
 * @subpackage models
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

/**
 * Is responsible for creating routers
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Router extends Zfmyadmin_Models_Transaction
{
    /**
     * Creates atomic opearations for creating single router
     * @param array $data Prepared form data
     * @return array Elementary atomic operations
     */
    public function create($data)
    {
       
        
        if((empty($data['routerUrlName']))||(empty($data['routerTitle']))){
            return array();
        }
        
        $vars = new Zfmyadmin_Models_Vars;
        $userSettings = $vars->getUserSettings(Zfmyadmin_Models_User::getCurrentUser());
        if(empty($userSettings['generator']['include_router'])) {
            return array();
        }
        
        $vars = array();
        foreach ($data['router'] as $key => $value) {
            if(!empty($value['Name'])){
                $vars[$value['Name']] = $value['Value'];
            }           
        }
       
        if(count($vars)>0) {
            $patternVars ='';
            $paramVars = '';
            $paramVarsIni = '';
            foreach ($vars as $key => $value) {
               $patternVars .= "/:$key";               
               $paramVars .= ", '$key' => '$value'";
               $paramVarsIni .= "routes.%routeName%.defaults.$key = $value\n";
            }            
        }
        $module = $data['moduleName'];
        $controller = $data['controllerName'];
        $action = $data['actionName'];
        $settings = $this->project->getProjectSettings();      
        
        $pattern = $data['routerUrlName'].$patternVars;
        
        if(!empty($userSettings['generator']['include_router_ini'])) {
            $code = file_get_contents($this->project->getCodebasePath('router-ini'));
            
            $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
            $stringToLower = new Zend_Filter_StringToLower;             
            
            $code  = str_replace('%moduleName%', $data['moduleName'], $code );
            $code  = str_replace('%controllerName%', $data['controllerName'], $code );
            $actionNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['actionName']));
            $code  = str_replace('%actionName%', $actionNamePrepared, $code );
            $code  = str_replace('%vars%', $paramVarsIni, $code ); 
            
            $replasePattern = "|^ *;zfmyadmin routers|m";
            
        } else {
            $code = file_get_contents($this->project->getCodebasePath('router'));            
            $code  = str_replace('%projectRouterName%', $settings['routerVar'], $code );
            $params = "array('module' => '$module', 'controller' => '$controller', 'action' => '$action' $paramVars)";
            $replasePattern = "|^ */\*zfmyadmin routers\*/|m";
            
        }
        
        
        $code  = str_replace('%pattern%', $pattern, $code );
        $code  = str_replace('%params%', $params, $code );        
        $code  = str_replace('%routeName%', $data['routerTitle'], $code );
        
        $path = $this->project->getRoot().DIRECTORY_SEPARATOR.$settings['routerFile']; 
        
        $operations = array();
        if(file_exists($path)) {
            $target = $path;
            if(!empty($userSettings['generator']['include_router_ini'])) {
                $sourse = file_get_contents($path);

            } else {
                $generator = Zend_CodeGenerator_Php_File::fromReflectedFileName($path);
                $sourse = $generator->generate();
                $code  = $this->setIndent($code, 2);
            }
            $content = preg_replace($replasePattern, $code, $sourse);
        } else {
            $target = $path.' '.$this->translate('<span class="error"> Does not exist </span>');
            $content = '';
        }     
            


        

        $operation = $this->getOperation(); 
        $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_CODE;
        $operation->category = Zfmyadmin_Models_Operation::CATEGORY_ROUTER; 
        $operation->target = $target;
        $operation->description = $this->translate('Insert router');
        $operation->content = $content;
        $operation->code = $code;            
        $operations[] = $operation;
            

        return $operations;         
    }
    
}

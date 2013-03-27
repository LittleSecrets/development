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
 * Creates view
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_View extends Zfmyadmin_Models_Transaction 
{
    /**
     * Generates list of elementary atomic operations for creating View
     * @param array $data Prepared form data
     * @return array Elementary atomic operations
     */
    public function create($data)
    {
        if((empty($data['actionCreateView']))&&(empty($data['controllerCreateView']))){
            return array();
        } 
       // $operations = array();
        $module = $this->project->getModule($data['moduleName']);
        $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
        $stringToLower = new Zend_Filter_StringToLower;

        $operations = array(); 
        $result = $this->createDir(
            $module->viewScriptsPath,
            Zfmyadmin_Models_Operation::CATEGORY_VIEW
        );        
        $operations = array_merge($operations, $result);
        
        
        
        $controllerViewScriptsPath = $module->viewScriptsPath
            .DIRECTORY_SEPARATOR
            .$stringToLower->filter($camelCaseToDash->filter($data['controllerName']));
    
        
        $result = $this->createDir(
            $controllerViewScriptsPath,
            Zfmyadmin_Models_Operation::CATEGORY_VIEW
        );        
        $operations = array_merge($operations, $result);
        
        $controllerViewScriptsFile = $controllerViewScriptsPath
            .DIRECTORY_SEPARATOR
            .$stringToLower->filter($camelCaseToDash->filter($data['actionName']))
            .'.phtml';
            
        $content = file_get_contents($this->project->getCodebasePath('action-view'));
        $content = str_replace('%actionName%', 
            $data['actionName'].'Action()', 
            $content
        );
        $content = str_replace('%controllerName%', 
            $data['controllerName'], 
            $content
        );
        $content = str_replace('%moduleName%', 
            $module->name, 
            $content
        );
        $content = str_replace('%viewFileName%', 
            $this->project->getRelativePath($controllerViewScriptsFile), 
            $content
        );


        $docBlockData = array(
            'description' => 'View file of action '.$data['actionName'].' of controller '.$data['controllerName'].' in module '.$data['moduleName'],
            'package' => $data['moduleName'],
            'subpackage' => 'controllers'
        );

        $docblock = $this->getDocBlock( 'viewFile', $docBlockData);
        $docblock = $docblock->generate();
        $content = str_replace('%docblock%', 
            $docblock, 
            $content
        );
        $description = $this->translate('Add documentation code '); 
        $result = $this->createFile(
            $controllerViewScriptsFile,
            $content,
            Zfmyadmin_Models_Operation::CATEGORY_VIEW,
            $description
        );        
        $operations = array_merge($operations, $result);            
        
        return $operations;
    }

    
}

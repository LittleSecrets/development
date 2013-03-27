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
 * Is responsible for creating JavaScript files and directories
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Js extends Zfmyadmin_Models_Transaction
{
    protected $_jsPath;

    /**
     * Creates list of atomic operations for creating JavaScript files and directories
     * @param array $data Prepared form data
     * @return array Elementary atomic operations
     */
    public function create($data)
    {
        
        if((empty($data['controllerCreateJs']))&&(empty($data['actionCreateJs']))){
            return array();
        }     
        
        $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
        $stringToLower = new Zend_Filter_StringToLower;

        $module = $this->project->getModule($data['moduleName']);
        $moduleNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['moduleName']));
        
        $operations = array();        
        $settings = $this->project->getProjectSettings();
        
        if(!empty($settings['separate_modules_public_dirs'])) {  
            $operations = array_merge($operations, $this->createJsDir(
                    $this->project->getPublicPath()
                    .DIRECTORY_SEPARATOR
                    .$moduleNamePrepared
                )
            );            
            $jsBasePath = $this->project->getPublicPath().DIRECTORY_SEPARATOR
                .$moduleNamePrepared.DIRECTORY_SEPARATOR
                .$this->project->getJsDir();
            $operations = array_merge($operations, $this->createJsDir($jsBasePath));
            
        } else {
           $operations = array_merge($operations, $this->createJsDir(
                    $this->project->getPublicPath()
                    .DIRECTORY_SEPARATOR
                    .$this->project->getJsDir()
                )
            ); 
            $jsBasePath = $this->project->getPublicPath().DIRECTORY_SEPARATOR
                 .$this->project->getJsDir().DIRECTORY_SEPARATOR
                 .$moduleNamePrepared;
            $operations = array_merge($operations, $this->createJsDir($jsBasePath));
        }
        
         
        $controllerNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['controllerName']));
        
        
        if(!empty($data['controllerCreateJs'])){
            $jsFile = $jsBasePath.DIRECTORY_SEPARATOR.$controllerNamePrepared.'.js';
            $content = file_get_contents($this->project->getCodebasePath('controller-js'));

            $content = str_replace('%controllerName%', 
                $data['controllerName'], 
                $content
            );
            $content = str_replace('%moduleName%', 
                $module->name, 
                $content
            ); 
            $docBlockData = array(
                'description' => 'Js for url '.$moduleNamePrepared.'/'.$controllerNamePrepared,
            );
            
            $docblock = $this->getDocBlock( 'jsFile', $docBlockData);
            $docblock = $docblock->generate();
            $content = str_replace('%docblock%', 
                $docblock, 
                $content
            );
            $operations = array_merge($operations, $this->createJsFile($jsFile, $content)); 
            
            if(($data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_JS)
                &&(!empty($data['includePublicFiles']))
            ){
                $data['methodName']='preDispatch';
                $code = $this->getIncludePublicFilesCode($data, false);
                $code = $this->setIndent($code, 2);
                $description = _('Include controller JS file to project');                
                $operations = array_merge($operations, $this->includeToProject($code, $data, false, $description)); 
                unset($data['methodName']);
            }            
            
        }
        
        
        $actionNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['actionName']));
        if(!empty($data['actionCreateJs'])&& !empty($data['actionName'])) {
            $createDirPath = $jsBasePath.DIRECTORY_SEPARATOR
                .$controllerNamePrepared;
            $operations = array_merge($operations, $this->createJsDir($createDirPath));           
            $jsPath = $jsBasePath.DIRECTORY_SEPARATOR
            .$controllerNamePrepared;
            $jsFile = $jsPath.DIRECTORY_SEPARATOR.$actionNamePrepared.'.js';
            $content = file_get_contents($this->project->getCodebasePath('action-js'));

            $content = str_replace('%controllerName%', 
                $data['controllerName'], 
                $content
            );
            $content = str_replace('%moduleName%', 
                $module->name, 
                $content
            );
            $content = str_replace('%actionName%', 
                $data['actionName'], 
                $content
            ); 
            
            $docBlockData = array(
                'description' => 'Style for url '.$moduleNamePrepared.'/'.$controllerNamePrepared.'/'.$actionNamePrepared,
            );
            
            $docblock = $this->getDocBlock( 'jsFile', $docBlockData);
            $docblock = $docblock->generate();
            $content = str_replace('%docblock%', 
                $docblock, 
                $content
            );
            
            $operations = array_merge($operations, $this->createJsFile($jsFile, $content));         
            if(($data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_JS)
                &&(!empty($data['includePublicFiles']))
            ){
                
                $code = $this->getIncludePublicFilesCode($data, true);
                $code = $this->setIndent($code, 2);
                $description = _('Include action JS file to project');                
                $operations = array_merge($operations, $this->includeToProject($code, $data, true, $description)); 
                
            }          
            
        }
        return $operations;
    }

    /**
     * Creates atomic opearations for creating single JavaScript file
     * @param string $jsFile Path to file
     * @param string $content JavaScript file content
     * @return array Elementary atomic operations
     */
    public function createJsFile($jsFile, $content)
    {
        $description = $this->translate('Add documentation code '); 
        $operations = $this->createFile($jsFile, $content, Zfmyadmin_Models_Operation::CATEGORY_JS, $description);
        return $operations;
    }

    /**
     * Creates atomic opearations for creating JavaScript file directory
     * @param string $createDirPath Path to directory
     * @return array Elementary atomic operations
     */
    public function createJsDir($createDirPath)
    {
        $operations = $this->createDir($createDirPath, Zfmyadmin_Models_Operation::CATEGORY_JS);
        return $operations;
    }    
}

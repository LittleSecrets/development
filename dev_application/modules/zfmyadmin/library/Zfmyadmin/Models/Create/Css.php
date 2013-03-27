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
 * Is responsible for creating style files and directories
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Css extends Zfmyadmin_Models_Transaction
{
    protected $_cssPath;

    /**
     * Creates list of atomic operations for creating css files and directories
     * @param array $data Prepared form data
     * @return array Elementary atomic operations
     */
    public function create($data)
    {
        
        if((empty($data['controllerCreateCss']))&&(empty($data['actionCreateCss']))){
            return array();
        }     
        
        $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
        $stringToLower = new Zend_Filter_StringToLower;

        $module = $this->project->getModule($data['moduleName']);
        $moduleNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['moduleName']));
        
        $operations = array();        
        $settings = $this->project->getProjectSettings();
        
        if(!empty($settings['separate_modules_public_dirs'])) {  
            $operations = array_merge($operations, $this->createCssDir(
                    $this->project->getPublicPath()
                    .DIRECTORY_SEPARATOR
                    .$moduleNamePrepared
                )
            );            
            $cssBasePath = $this->project->getPublicPath().DIRECTORY_SEPARATOR
                .$moduleNamePrepared.DIRECTORY_SEPARATOR
                .$this->project->getCssDir();
            $operations = array_merge($operations, $this->createCssDir($cssBasePath));
            
        } else {
           $operations = array_merge($operations, $this->createCssDir(
                    $this->project->getPublicPath()
                    .DIRECTORY_SEPARATOR
                    .$this->project->getCssDir()
                )
            ); 
            $cssBasePath = $this->project->getPublicPath().DIRECTORY_SEPARATOR
                 .$this->project->getCssDir().DIRECTORY_SEPARATOR
                 .$moduleNamePrepared;
            $operations = array_merge($operations, $this->createCssDir($cssBasePath));
        }
        
         
        $controllerNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['controllerName']));
        
        
        if(!empty($data['controllerCreateCss'])){
            $cssFile = $cssBasePath.DIRECTORY_SEPARATOR.$controllerNamePrepared.'.css';
            $content = file_get_contents($this->project->getCodebasePath('controller-css'));

            $content = str_replace('%controllerName%', 
                $data['controllerName'], 
                $content
            );
            $content = str_replace('%moduleName%', 
                $module->name, 
                $content
            ); 
            $docBlockData = array(
                'description' => 'Style for url '.$moduleNamePrepared.'/'.$controllerNamePrepared,
            );
            
            $docblock = $this->getDocBlock( 'cssFile', $docBlockData);
            $docblock = $docblock->generate();
            $content = str_replace('%docblock%', 
                $docblock, 
                $content
            );
            $operations = array_merge($operations, $this->createCssFile($cssFile, $content));
            
            if(($data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_CSS)
                &&(!empty($data['includePublicFiles']))
            ){
                $data['methodName']='preDispatch';
                $code = $this->getIncludePublicFilesCode($data, false);
                $code = $this->setIndent($code, 2);
                $description = _('Include controller CSS file to project ');                
                $operations = array_merge($operations, $this->includeToProject($code, $data, false, $description)); 
                unset($data['methodName']);
            }           
          
        }
        
        
        $actionNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['actionName']));
        if(!empty($data['actionCreateCss']) && !empty($data['actionName'])) {
            $createDirPath = $cssBasePath.DIRECTORY_SEPARATOR
                .$controllerNamePrepared;
            $operations = array_merge($operations, $this->createCssDir($createDirPath));           
            $cssPath = $cssBasePath.DIRECTORY_SEPARATOR
            .$controllerNamePrepared;
            $cssFile = $cssPath.DIRECTORY_SEPARATOR.$actionNamePrepared.'.css';
            $content = file_get_contents($this->project->getCodebasePath('action-css'));

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
            
            $docblock = $this->getDocBlock( 'cssFile', $docBlockData);
            $docblock = $docblock->generate();
            $content = str_replace('%docblock%', 
                $docblock, 
                $content
            );
            
            $operations = array_merge($operations, $this->createCssFile($cssFile, $content)); 
            
            if(($data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_CSS)
                &&(!empty($data['includePublicFiles']))
            ){                
                $code = $this->getIncludePublicFilesCode($data, true);
                $code = $this->setIndent($code, 2);
                $description = _('Include action CSS file to project');                
                $operations = array_merge($operations, $this->includeToProject($code, $data, true, $description)); 
                
            }   
            
        }
        return $operations;
    }

    /**
     * Creates atomic opearations for creating single css file
     * @param string $cssFile Path to file
     * @param string $cssContent CSS file content
     * @return array Elementary atomic operations
     */
    public function createCssFile($cssFile, $cssContent)
    {
        $description = $this->translate('Add documentation code ');        
        $operations = $this->createFile($cssFile, $cssContent, Zfmyadmin_Models_Operation::CATEGORY_CSS, $description);
        return $operations;
    }

    /**
     * Creates atomic opearations for creating css file directory
     * @param string $createDirPath Path to directory
     * @return array Elementary atomic operations
     */
    public function createCssDir($createDirPath)
    {
        $operations = $this->createDir($createDirPath, Zfmyadmin_Models_Operation::CATEGORY_CSS);
        return $operations;
    }

}

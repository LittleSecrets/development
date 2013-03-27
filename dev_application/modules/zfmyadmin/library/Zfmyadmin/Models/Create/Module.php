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
 * Is responsible for creation of new modules
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Module extends Zfmyadmin_Models_Transaction 
{

    /**
     * Creates list of atomic operation for creation of a new controller
     * @param array $data Prepared array of form data to create a controller
     * @return array Array of elementary atomic operations for controller creation
     */
    public function create($data) 
    {
        $dirFilter = new Zend_Filter_Dir();
        $realPathFilter = new Zend_Filter_RealPath();
        $moduleName = $data['moduleName'];
        $modulesPath = $realPathFilter->filter(
            $dirFilter->filter(
                $this->project->getFrontController()->getModuleDirectory()
            )                
        );
       
        $modulePath = $modulesPath.DIRECTORY_SEPARATOR.$moduleName;        
        $operations = array(); 
        $result = $this->createDir(
            $modulePath,
            Zfmyadmin_Models_Operation::CATEGORY_MODULE
        );      
        $operations = array_merge($operations, $result);
        
        $parentDirectory = array();
        foreach ($data['folders'] as $folder => $value) {
            if (!empty($value)) {
                
                if (preg_match('%_%', $folder)) {
                    $dirs = explode('_', $folder);
                    $tempPath = $modulePath;
                    foreach ($dirs as $subdir){                        
                        $tempPath .= DIRECTORY_SEPARATOR.$subdir; 
                        if (empty($data['folders'][$subdir])
                            && empty($parentDirectory[$subdir])
                        ) {
                            $result = $this->createDir(
                                $tempPath,
                                Zfmyadmin_Models_Operation::CATEGORY_MODULE
                            );        
                            $operations = array_merge($operations, $result);
                            $parentDirectory[$subdir] = 1;
                        }
                    }
                    
                } else {
                    $result = $this->createDir(
                        $modulePath.DIRECTORY_SEPARATOR.$folder,
                        Zfmyadmin_Models_Operation::CATEGORY_MODULE
                    );        
                    $operations = array_merge($operations, $result);
                }
            }
        }  
        
        if ((!empty($data['bootstrap']['createAplicationIni'])) 
            &&(!empty( $data['folders']['configs']))
        ) {
            $content = '[production]';
            $description = $this->translate('Create [production] section');
            $result = $this->createFile(
                $modulePath.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'application.ini',
                $content,
                Zfmyadmin_Models_Operation::CATEGORY_MODULE,
                $description
            );        
            $operations = array_merge($operations, $result);
        }
        
        if (!empty($data['bootstrap']['createBootstrap'])) {
            $docBlockData = array(
                'description' => 'Bootstrap file of module '.$data['moduleName'],
                'package'     => $data['moduleName'],
            );        
            $docblock = $this->getDocBlock( 'phpFile', $docBlockData);  
            
            $fileGenerator = new Zend_CodeGenerator_Php_File;
            $fileGenerator->setDocblock($docblock);
            
            $classGenerator = new Zend_CodeGenerator_Php_Class();
            $docBlockData['description'] = 'Class Bootstrap of module '.$data['moduleName'];
            $docblock = $this->getDocBlock('class', $docBlockData);
            
            if($this->project->isPrefixDefaultModule||(!$module->isDefault)){
                $prefix = ucfirst($data['moduleName']).Zfmyadmin_Models_Project::CLASS_SEPARATOR;
            }
            $className = $prefix
                       .'Bootstrap';
            
            $classGenerator 
                ->setName($className) 
                ->setExtendedClass('Zend_Application_Module_Bootstrap');
          
            $classGenerator->setDocblock($docblock);
            $fileGenerator->setClass($classGenerator);
            $content = $fileGenerator->generate(); 
            $description = $this->translate('Class Bootstrap of module').' '.$data['moduleName'];
            
            $result = $this->createFile(
                $modulePath.DIRECTORY_SEPARATOR.'Bootstrap.php',
                $content,
                Zfmyadmin_Models_Operation::CATEGORY_MODULE,
                $description
            );        
            $operations = array_merge($operations, $result);
        }
        
        return $operations;
    }
}    
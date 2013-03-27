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
 * Is responsible for creation of new controllers
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Controller extends Zfmyadmin_Models_Transaction 
{

    /**
     * Creates list of atomic operation for creation of a new controller
     * @param array $data Prepared array of form data to create a controller
     * @return array Array of elementary atomic operations for controller creation
     */
    public function create($data) 
    {  
        $module = $this->project->getModule($data['moduleName']);
        $fileName = $data['controllerName'].'Controller.php';
        $pathToController = $module->controllersDir
                           .DIRECTORY_SEPARATOR.$fileName;        

        $docBlockData = array(
            'description' => 'File of controller '.$data['controllerName'].' in module '.$data['moduleName'],
            'package' => $data['moduleName'],
            'subpackage' => 'controllers'
        );        
        $docblock = $this->getDocBlock( 'phpFile', $docBlockData);        
        $fileGenerator = new Zend_CodeGenerator_Php_File;
        $fileGenerator->setDocblock($docblock);
        $controllerClassGenerator = new Zend_CodeGenerator_Php_Class();
        if($this->project->isPrefixDefaultModule()||(!$module->isDefault)){
            $prefix = ucfirst($module->name).Zfmyadmin_Models_Project::CLASS_SEPARATOR;
        }
        $controllerClassName = $prefix
            .$data['controllerName']
            .'Controller';
        
        $docBlockData['description'] = 'Class '.$controllerClassName.' of controller in module '.$data['moduleName']; 
        $docblock = $this->getDocBlock('class', $docBlockData); 
        $controllerClassGenerator 
            ->setName($controllerClassName) 
            ->setExtendedClass($data['controllerClassName'])
            ->setDocblock($docblock);
        $fileGenerator->setClass($controllerClassGenerator);        
       
        $content = $fileGenerator->generate(); 
        $operations = array(); 
        $result = $this->createFile(
            $pathToController,
            $content,
            Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER
        );        
        $operations = array_merge($operations, $result);
        
        $dataCreateMethod = array(
            'moduleName' =>$data['moduleName'],
            'controllerName' => $data['controllerName'],
            'includePublicFiles' => $data['includePublicFiles']
        );
        
        if (!empty($data['controllerCreateInit'])) {
            $dataCreateMethod['methodName'] = 'init'; 
            $result = $this->addMethod($fileGenerator, $dataCreateMethod);
            $operations = array_merge($operations, $result);
        }
        
        if (!empty($data['controllerPreDispatch'])) {
            $dataCreateMethod['methodName'] = 'preDispatch';   
            $result = $this->addMethod($fileGenerator, $dataCreateMethod);
            $operations = array_merge($operations, $result);
        }
        
        if (!empty($data['controllerCreatePostDispatch'])) {
            $dataCreateMethod['methodName'] = 'postDispatch'; 
            $result = $this->addMethod($fileGenerator, $dataCreateMethod);
            $operations = array_merge($operations, $result);
        }
        
        $result = $this->createCss($data);
        $operations = array_merge($operations, $result);
        
        $result = $this->createJs($data);
        $operations = array_merge($operations, $result);

        
        return $operations;
    }

    /**
     * Creates list of atomic operations for adding method to controller class
     * @param object $fileGenerator Zend_CodeGenerator_Php_Class Generates PHP
     * files that creates controller
     * @param array $data Prepared form data for creating method
     * @param bool $isAction States that method is action
     * @return array Array of elementary atomic operations for adding method to controller class
     */
    public function addMethod($fileGenerator, $data) 
    {
        $classGenerator = $fileGenerator->getClass();
        $module = $this->project->getModule($data['moduleName']);
        $fileName = $data['controllerName'].'Controller.php';
        $pathToController = $module->controllersDir
            .DIRECTORY_SEPARATOR.$fileName;
        
        $operations = array();
        $operation = $this->getOperation();
        $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_CODE;
        $body = '';
        
        $description = 'Service method of controller '.$data['controllerName'].' in module '.$data['moduleName'];
        $operation->category = Zfmyadmin_Models_Operation::CATEGORY_METHOD; 
        $controllerMethodName = $data['methodName'];
        $body = "parent::$controllerMethodName();\n";
  
        
        $docBlockData = array(
            'description' => $description,
        );
        $docblock = $this->getDocBlock( 'method', $docBlockData);
        
        if (!empty($data['includePublicFiles'])) {
            $body .= $this->getIncludePublicFilesCode($data, false);
        }
        
        $method = new Zend_CodeGenerator_Php_Method(array(
            'name' => $controllerMethodName,
            'body'       => $body,
            'docblock'   => $docblock,
        ));
        
        $controllerMethod = $method->generate();        
        $classGenerator->setMethod($method);
        $fileGenerator->setClass($classGenerator); 
        $controllerClass = $fileGenerator->generate(); 
        
        $operation->code = $controllerMethod;
        $operation->content = $controllerClass;
        $operation->target = $pathToController; 
        $operation->description = $data['methodName'];
        $operations[] = $operation;
        return $operations; 
    }

}
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
 * Is responsible for creation of new actions
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Create_Action extends Zfmyadmin_Models_Transaction 
{

    /**
     * Creates list of atomic operations for adding action to controller
     * 
     * @param array $data Prepared form data for creating method
     * @return array Array of elementary atomic operations for adding method to controller class
     */
    public function create($data) 
    {
        $module = $this->project->getModule($data['moduleName']);
        $fileName = $data['controllerName'].'Controller.php';
        $pathToController = $module->controllersDir
                           .DIRECTORY_SEPARATOR.$fileName;   
        
        
        $operation = $this->getOperation();
        $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_CODE;        
        $operation->category = Zfmyadmin_Models_Operation::CATEGORY_ACTION;
        
        $description = 'Action of controller';        
        $docBlockData = array(
            'description' => $description,
        );
        $docblock = $this->getDocBlock( 'method', $docBlockData);
        
        $body = ''; 
        if (!empty($data['includePublicFiles'])) {
            $body .= $this->getIncludePublicFilesCode($data, true);
        }        
        
        
        
        $method = new Zend_CodeGenerator_Php_Method(array(
            'name' => $data['actionName'].'Action',
            'body'       => $body,
            'docblock'   => $docblock,
        ));
        
        $methodSourse = $method->generate();
        $fileSourse = trim(file_get_contents($pathToController));
        $pattern = '%\}\z%';        
        $replacement = $methodSourse."\n}";
        
        $controllerClass = preg_replace($pattern, $replacement, $fileSourse);
        
        $operation->code = $methodSourse;
        $operation->content = $controllerClass;
        $operation->target = $pathToController; 
        $operation->description = $data['actionName'].'Action';
        $operations[] = $operation;

        if (!empty($data['actionCreateView'])) {

        } 
        
        $result = $this->createView($data);
        $operations = array_merge($operations, $result);
        
        $result = $this->createCss($data);
        $operations = array_merge($operations, $result);        

        
        $result = $this->createJs($data);
        $operations = array_merge($operations, $result);
        
        $result = $this->createRouter($data);
        $operations = array_merge($operations, $result);        

        
        return  $operations;        
    }

}
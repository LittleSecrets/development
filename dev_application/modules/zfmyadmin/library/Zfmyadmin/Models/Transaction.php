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
 * Manages transactions (i.e. group of atomic operations) required to create project entities
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Transaction extends Zend_Db_Table_Abstract 
{
    /** Table name */
    protected $_name = 'zfmyadmin_transactions';

    /** Primary Key */
    protected $_primary = 'id';
    protected $_dependentTables = array('zfmyadmin_operations');
    
    public static $_saveToLog = false;
    protected static $_saveToProject = false;
    
    public $id;
    public $user_id;   
    public $time;
    
    public static $_creatorsName = array(
        Zfmyadmin_Models_Operation::CATEGORY_MODULE => 'module_creator',
        Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER => 'controller_creator',
        Zfmyadmin_Models_Operation::CATEGORY_ACTION => 'action_creator',
        Zfmyadmin_Models_Operation::CATEGORY_METHOD => 'method_creator',
        Zfmyadmin_Models_Operation::CATEGORY_VIEW => 'view_creator',
        Zfmyadmin_Models_Operation::CATEGORY_CSS => 'css_creator',
        Zfmyadmin_Models_Operation::CATEGORY_JS => 'js_creator',
        Zfmyadmin_Models_Operation::CATEGORY_ROUTER => 'router_creator',
    );

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct() 
    {
        parent::__construct();
        $this->project = Zfmyadmin_Models_Project::getInstance();
    }

    /**
     * Returns translated text
     * @param string $text 
     * @return string
     */
    
    public function translate($text)
    {
        $translate = Zend_Registry::get('Zend_Translate');        
        $text = $translate->_($text);
        return $text;
    }
    public function getOperation($id = false)
    {
        $model = new Zfmyadmin_Models_Operation();
        if($id) {
           $operation = $model->find($id)->current();
        } else {
            $operation = $model->createRow();
        }  
        return $operation;
    }
    
    /**
     * Sets permission to save transaction and operations to database
     * @param bool $permit
     * @return void
     */
    public function setSaveToLog ($permit)
    {     
        self::$_saveToLog = $permit;        
    }
    
    /**
     * Returns permission to save transaction and operations to database
     *
     * @return bool
     */
    public function isSaveToLog ()
    {     
        return self::$_saveToLog;
    }
    
    /**
     * Sets permission to create direcoty files and pasting code to working project
     * @param bool $permit
     * @return void
     */
    public function setSaveToProject ($permit)
    {     
        self::$_saveToProject = $permit;        
    }
    
    /**
     * Returns permission to create direcoty files and pasting code to working project
     *
     * @return bool
     */
    public function isSaveToProject()
    {
        return self::$_saveToProject;
    }
    
    /**
     * Returns list of transactions
     * @param array $where Condition for transactions search
     * @param array $order Condition for sorting transactions
     * @param int $count Quantity of returned transactions
     * @param int $offset From where input starts
     * @return array
     */
    public function getTransactions($where = null, $order = null, $count = null, $offset = null)
    {
        $select = $this->select();
        foreach ($where as $cond => $value) {
            $select->where($cond, $value); 
        }
        $select->order($order); 
     
        if($count && $offset) {
            $select->limit($count, $offset);
        }
        $transactions = $this->fetchAll($select);
        return $transactions;
    }

    /**
     * Creates new transaction
     * @param int $userId User ID of transaction's owner
     * @return object Transaction
     */
    public function createTransaction($userId)
    {
        $transaction = $this->createRow();
        if($userId) {
            $transaction->user_id = $userId;
        }
        $transaction -> save();           
        return $transaction;
    }  

   /**
     * Creates list of elementary atomic operations for creating single view
     * @param array $data Prepared form data
     * @return array List of atomic operations
     */
    public function createView($data)
    {
        $model = new Zfmyadmin_Models_Create_View;
        $operations = $model ->create($data);
        return $operations;
    }

    /**
     * Creates list of elementary atomic operations for creating single css file
     * @param array $data Prepared form data
     * @return array List of atomic operations
     */
    public function createCss($data)
    {
        $model = new Zfmyadmin_Models_Create_Css;
        $operations = $model ->create($data);
        return $operations;
    }

    /**
     * Creates list of elementary atomic operations for creating single JavaScript file
     * @param array $data Prepared form data
     * @return array List of atomic operations
     */
    public function createJs($data)
    {
        $model = new Zfmyadmin_Models_Create_Js;
        $operations = $model ->create($data);
        return $operations;

    }
    
    /**
     * Creates list of elementary atomic operations for creating single router
     * @param array $data Prepared form data
     * @return array List of atomic operations
     */
    public function createRouter($data)
    {
        $model = new Zfmyadmin_Models_Create_Router;
        $operations = $model ->create($data);
        return $operations;
    }
     
   
    /**
     * Creates list of elementary atomic operations for creation of  file
     * @param string $file Path to a file
     * @param string $content File content
     * @param int $category  file category 
     * @return array Atomic operations list
     */
    public function createFile($file, $content, $category, $description = '')
    {
        $operations = array();
        if(!file_exists($file)) {
            $operation = $this->getOperation();        
            $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_FILE;
            $operation->category = $category;
            $operation->target = $file;
            $operations[] = $operation;

            $operation = $this->getOperation();        
            $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_CODE;
            $operation->category = $category; 
            $operation->content = $content;
            $operation->code = $content;
            $operation->target = $file;
            $operation->description = $description; 
            $operations[] = $operation;
        }          
        return $operations;        
        
    }
    
    /**
     * Creates atomic opearations for creating single directory
     * @param string $createDirPath Path to directory
     * @param int $category directory category 
     * @return array Elementary atomic operations
     */
    public function createDir($createDirPath, $category)
    {
        $operations = array();
        if(!is_dir($createDirPath)) {                        
            $operation = $this->getOperation();        
            $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_DIR;
            $operation->category = $category; 
            $operation->target = $createDirPath;
            $operations[] = $operation;
        };
        return $operations;
    }
    
    
    public function renderData($operations, $data)
    {
        $transactionData = array();
        foreach ($operations as $operation) {            
            if($operation->type == Zfmyadmin_Models_Operation::TYPE_CREATE_CODE) {
                $transactionData['fullCode'][$operation->target] = $operation->content;
            }
        }
        $transactionData['module'] =  $data['moduleName']; 
        $transactionData['controller'] = $data['controllerName'];  
        if(!empty($data['actionName'])) {
            $transactionData['action'] =  $data['actionName'];
        } else {
            $transactionData['action'] = 'index';
        }
        $transactionData['creatorCategory'] =  $data['creatorCategory'];
        
        $vars = new Zfmyadmin_Models_Vars;
        $userSettings = $vars->getUserSettings(Zfmyadmin_Models_User::getCurrentUser());
        if(!empty($userSettings['generator']['include_router'])) {
            $transactionData['route'] =  $data['routerUrlName'];
            $transactionData['routerName'] =  $data['routerTitle']; 
        }
        return $transactionData;
    }

    /**
     * Performs actual saving to database - creates directories and files, inserts code to the working project
     * @param array $operations List of operations
     * @param int $userId User ID
     * @param array $data Prepared form data
     * @return void
     */
    public function commit($operations, $userId = null ,$data = array())
    {
        if($this->isSaveToLog()) {
            $transaction = $this->createTransaction($userId);
            $transactionData = array();

            foreach ($operations as $operation) {
                $operation->status = Zfmyadmin_Models_Operation::STATUS_ADD_TO_LOG;                
                if($this->isSaveToProject()) {
                    switch ($operation->type) {
                        case Zfmyadmin_Models_Operation::TYPE_CREATE_DIR:
                            if(mkdir($operation->target, 0777)) {
                                $operation->status = Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE;    
                            } else {
                               $operation->status = Zfmyadmin_Models_Operation::STATUS_ERROR_ADD_TO_FILE; 
                            }  
                            break;

                        case Zfmyadmin_Models_Operation::TYPE_CREATE_FILE:
                                $fileDescriptor = fopen($operation->target, 'w+', 0777);
                                if($fileDescriptor) {
                                    $operation->status = Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE;    
                                } else {
                                    $operation->status = Zfmyadmin_Models_Operation::STATUS_ERROR_ADD_TO_FILE; 
                                }
                                fclose($fileDescriptor);
                            break;

                        case Zfmyadmin_Models_Operation::TYPE_CREATE_CODE:                       
                            if(file_put_contents($operation->target, $operation->content)) {
                                $operation->status = Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE;    
                            } else {
                                $operation->status = Zfmyadmin_Models_Operation::STATUS_ERROR_ADD_TO_FILE; 
                            }
                            
                            break;                
                        default:
                            break;
                    }
                }

                $operation->transaction_id = $transaction->id;
                $operation->save();
            }            
            
            $transactionData = $this->renderData($operations, $data);
            $transaction->data = serialize($transactionData);
            $transaction->save();            
        }

    }

        /**
         * Generates documentation block
         * @param string $type Type of documentation block (PHP file, class, method, ... )
         * @param array $data Prepared data
         * @return object 
         */
        public function getDocBlock($type, $data = array()){
        
        $vars = new Zfmyadmin_Models_Vars;
        $userSettings = $vars->getUserSettings(Zfmyadmin_Models_User::getCurrentUser());
        $docUserSettings = $userSettings["doc"][$type];
        $docUserSettingsValue = $userSettings['doc']['default'];
        $prefix = "* @";
        $data['author'] = $userSettings['personal']['name'].' <'.$userSettings['personal']['email'].'>';
        $data['copyright'] = $userSettings['personal']['company'];
        $tags = array();
        foreach($docUserSettings as $tag => $tagPermit){
            if ((int)$tagPermit > 0) {
                if (!empty($docUserSettingsValue[$tag.'Default'])) {
                    $value =             array(
                        'name'        => $tag,
                        'description' => !empty($data[$tag])?$data[$tag]:'',
                    );
                } else {
                    $value =             array(
                        'name'        => $tag,
                        'description' => !empty($docUserSettingsValue[$tag])?$docUserSettingsValue[$tag]:'',
                    );                    
 
                }                        

                $tags[] = $value;
            }    
        } 
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => $data['description'],
//          'longDescription'  => 'This is a class generated with Zend_CodeGenerator.',
            'tags'             => $tags
        ));
        return $docblock;
    }
    
    /**
     * Generates block for including css and js files
     * @param array $data Prepared data
     * @param bool $isAction
     * @return object 
     */
    public function getIncludePublicFilesCode($data, $isAction){
        $vars = new Zfmyadmin_Models_Vars;
        $userSettings = $vars->getUserSettings(Zfmyadmin_Models_User::getCurrentUser());                
        $settings = $this->project->getProjectSettings();
        
        $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
        $stringToLower = new Zend_Filter_StringToLower;
        $moduleNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['moduleName']));
        $controllerNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['controllerName']));


        if(!empty($settings['separate_modules_public_dirs'])) {  
             $publicCssPath = $this->project->getPublicUrl().'/'
                         .$moduleNamePrepared.'/'
                         .$this->project->getCssDir();
             $publicJsPath = $this->project->getPublicUrl().'/'
                         .$moduleNamePrepared.'/'
                         .$this->project->getJsDir();

        } else {
             $publicCssPath = $this->project->getPublicUrl().'/'
                         .$this->project->getCssDir().'/'
                         .$moduleNamePrepared;
             $publicJsPath = $this->project->getPublicUrl().'/'
                         .$this->project->getJsDir().'/'
                         .$moduleNamePrepared;             
             
        }     
        
        
        if($isAction){
            $actionNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['actionName']));
            $stylePath = $publicCssPath.'/'
                       .$controllerNamePrepared.'/'
                       .$actionNamePrepared.'.css';
            $scriptPath = $publicJsPath.'/'
                       .$controllerNamePrepared.'/'
                       .$actionNamePrepared.'.js'; 
        }     

        if (!empty($data['methodName'])&&($data['methodName']=='preDispatch')) {
            $stylePath = $publicCssPath.'/'
                       .$controllerNamePrepared.'.css';
            $scriptPath = $publicJsPath.'/'
                       .$controllerNamePrepared.'.js'; 
        }    
        
        $body = '';
        
        
        if (!empty($stylePath) && !empty($scriptPath) ) {
            
            if(!empty($data['controllerCreateCss'])||!empty($data['actionCreateCss'])){
                $includeCssFile = file_get_contents($this->project->getCodebasePath('include-style-path'));   
                $includeCssFile = str_replace('%stylePath%', 
                    $stylePath, 
                    $includeCssFile
                );
                $body .= $includeCssFile;                
            }


            if(!empty($data['controllerCreateJs'])||!empty($data['actionCreateJs'])){
               
                $includeJsFile = file_get_contents($this->project->getCodebasePath('include-javascript-path'));   
                $includeJsFile = str_replace('%scriptPath%', 
                    $scriptPath,  
                    $includeJsFile
                );  
                $body .= "\n".$includeJsFile;
                
            }
            
            
        }            
        return $body;

    }

    /**
     * Sets indent for content aliquot to 4 spaces
     * @param string $content Content that requires input
     * @param int $indent Number of indents
     * @return void
     */
    public function setIndent($content, $indent = 0)
    {      
        if($indent>0) {
            $tabReplacement = str_repeat(' ', $indent*4);
            $content = preg_replace("/(^ *)/m", "$0".$tabReplacement, $content);
        }
        return $content;
    }
    

    
    /**
     * Creates atomic opearations for creating css file directory
     * @param string $createDirPath Path to directory
     * @return array Elementary atomic operations
     */
    public function includeToProject($code, $data, $isAction, $desctiption)
    {
        $operations = array();        
        $module = $this->project->getModule($data['moduleName']);
        $fileName = $data['controllerName'].'Controller.php';
        $pathToController = $module->controllersDir
                           .DIRECTORY_SEPARATOR.$fileName;  
        
        if(Zend_Registry::isRegistered('tmpFiles')) {
            $tmpFiles = Zend_Registry::get('tmpFiles');
        } else {
            $tmpFiles = array(); 
        }
        
        if(!empty($tmpFiles[$pathToController])) {
            $fileSourse = $tmpFiles[$pathToController];
        } else {
            $fileSourse = trim(file_get_contents($pathToController));
        }
                
        if ($isAction) {
           $name = $data['actionName'];
           $pattern = "|public *function *$name"."Action *\(\)\s*\{|";

        } else {
            $name = $data['methodName'];
            $pattern = "|public *function *preDispatch *\(\)\s*\{|"; 
            
        }
        $content = preg_replace($pattern, "$0\n".$code, $fileSourse);
        $tmpFiles[$pathToController] = $content;
        Zend_Registry::set('tmpFiles', $tmpFiles);
        
        $operation = $this->getOperation();
        $operation->type = Zfmyadmin_Models_Operation::TYPE_CREATE_CODE;        
        $operation->category = Zfmyadmin_Models_Operation::CATEGORY_CSS;
        $operation->code = $code;
        $operation->content = $content;
        $operation->target = $pathToController; 
        $operation->description = $desctiption;
        $operations[] = $operation;

        return $operations;
    }        
    
}

<?php
/**
 * File of controller Install in module zfmyadmin
 *
 * @package My project
 * @subpackage controllers
 * @copyright zfmyadmin
 * @license
 * @version
 * @author los312 <los312@mail.ru>
 */
include_once dirname(__FILE__).'/../library/Zfmyadmin/Controller/Action.php';
/**
 * Class Zfmyadmin_InstallController of controller in module zfmyadmin
 *
 * @package My project
 * @subpackage controllers
 * @author los312 <los312@mail.ru>
 */

class Zfmyadmin_InstallController extends Zfmyadmin_Controller_Action 
{
    public function preDispatch() {
        
        $this->view->headLink()->appendStylesheet('/zfmyadmin_public/css/install.css');
        $this->view->headScript()->appendFile('/zfmyadmin_public/js/install.js');
        if (is_dir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'zfmyadmin_public')
            &&($this->getRequest()->getActionName()!='index')
        ) {             
            parent::preDispatch();
            $realPath = new Zend_Filter_RealPath();
            /*Path to models and forms*/
            $models = $realPath->filter(dirname($this->getFrontController()->getModuleDirectory()));
            /*Path to configs*/
            $configsPath = $realPath->filter(
                $this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'configs'
            );
            /*Path to Zfmyadmin library */
            $library = $realPath->filter(
                $this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'library'
            );
            /*include new paths*/
            $paths = $library.PATH_SEPARATOR.$models.PATH_SEPARATOR.get_include_path();
            set_include_path($paths);
            /*if used Zend_Application*/
            $bootstrap = $this->getFrontController()->getParam('bootstrap');
            if (!empty ($bootstrap)) {
                $application = $bootstrap->getApplication();            
                $optionsZfmyadmin = array(
                    'autoloaderNamespaces'=>array('Zfmyadmin'=>'Zfmyadmin_'),
                );
                $options = $application->mergeOptions($application->getOptions(), $optionsZfmyadmin);
                $application->setOptions($options);
            }
            if (file_exists($configsPath.DIRECTORY_SEPARATOR.'zfmyadmin.ini')) {
                $zfmyadminConfig = new Zend_Config_Ini($configsPath.DIRECTORY_SEPARATOR.'zfmyadmin.ini',
                    'zfmyadmin'
                );
                if (!empty($zfmyadminConfig->resources->db->adapter)
                    && (!empty($zfmyadminConfig->resources->db->params))
                ) {            
                    $db = Zend_Db::factory($zfmyadminConfig->resources->db->adapter,
                        $zfmyadminConfig->resources->db->params
                    );        
                    Zend_Db_Table_Abstract::setDefaultAdapter($db);            
                }           
            }
            $this->session = new Zend_Session_Namespace('zfmyadmin');         
            $this->user = $this->session->user;
            $this->acl = $this->getAcl();
            $this->view->acl = $this->acl;              
        } else {
            
            $this->setTranslate('en');
        }
    }
   
    public function indexAction()
    {
        if (is_dir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'zfmyadmin_public')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'install', 'action'=>'uninstall')
            ));
        }        
        $this->_helper->layout->disableLayout();
        include_once dirname(__FILE__).'/../library/Zfmyadmin/Forms/Install.php';
        $form = new Zfmyadmin_Forms_Install();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getParams();
            if (!empty($data['separate_db'])) {
                $form->getElement('host')->setRequired();
                $form->getElement('username')->setRequired();
                $form->getElement('dbname')->setRequired();
                $form->getElement('adapter')->setRequired();
            }
            if ($form->isValid($data)) {
                $data = $form->getValues();
                /*Check requrements*/   
                $errors = array();
                $modulesPath = dirname(__FILE__).'/../../';
                if (!is_writable($modulesPath)) {
                    $errors[] = $this->translate('Modules directory is non writable' );
                }
                
                $configPath = dirname(__FILE__).'/../configs';
                if (!is_writable($configPath)) {
                    $errors[] = $this->translate('Directory configs are non writable' );
                }
                
                if (!is_writable($data['publicDir'])) {
                    $errors[] = $data['publicDir'].' '. $this->translate('is non writable') ;
                }
                
                if (!empty($data['separate_db'])) {
                    $configDbParamsData = array(
                        'host'     => $form->getElement('host')->getValue(),
                        'username' => $form->getElement('username')->getValue(),
                        'password' => $form->getElement('password')->getValue(),
                        'dbname'   => $form->getElement('dbname')->getValue(),
                    );

                    $db = Zend_Db::factory($form->getElement('adapter')->getValue(), $configDbParamsData);
                } else {
                    $db = Zend_Db_Table_Abstract::getDefaultAdapter();  
                }
                
                try {
                $db->getConnection();
                } catch (Zend_Db_Adapter_Exception $e) {
                    $errors[] = $this->translate('Invalid connection parameters or database disabled');
                } catch (Zend_Exception $e) {
                    $errors[] = $this->translate('Data base adapter does not exist');
                }
                
                if (empty($errors)) {                    
                    $installErrors = array();                
                    $dataInstallPath = $this->getFrontController()->getModuleDirectory()
                                    .DIRECTORY_SEPARATOR
                                    .'data'
                                    .DIRECTORY_SEPARATOR
                                    .'install';                
                    $zip = new ZipArchive;
                    $zip->open($dataInstallPath.DIRECTORY_SEPARATOR.'zfmyadmin_public.zip');
                    if (!$zip->extractTo($data['publicDir'])) {
                        $installErrors[] = $this->translate('Public files not extracted');
                    }    
                    $zip->close();                   
                    
                    try {
                        $realPath = new Zend_Filter_RealPath();
                        $configsPath = $realPath->filter(
                            $this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'configs'
                        );
                        $configDbDataIni = array(
                            'zfmyadmin' => array(
                                'resources' => array(
                                    'db'     => array(
                                        'adapter' => $form->getElement('adapter')->getValue(),
                                        'params'  => $configDbParamsData
                                     )
                                )
                            )
                        );

                        $zfmyadminConfig = new Zend_Config($configDbDataIni, true);
                        $writer = new Zend_Config_Writer_Ini(array('config' => $zfmyadminConfig,
                            'filename' => $configsPath.DIRECTORY_SEPARATOR.'zfmyadmin.ini')
                        );
                        $writer->write();
                    } catch (Zend_Exception $e) {
                        $installErrors[] = $this->translate('zfmyadmin.ini file not created');
                    }
                    
                    try {
                        include_once $dataInstallPath.DIRECTORY_SEPARATOR.'zfmyadmindDumpDb.php'; 
                        $db->beginTransaction();
                        foreach ($zfmyadmindDumpDb as $value) {
                           $db->getConnection()->exec($value); 
                        }
                        $users = new Zend_Config_Ini($configsPath.DIRECTORY_SEPARATOR.'users.ini', 'instal');        
                        $users = $users->toArray();
                        foreach ($users as $key => $value) {
                            $pass = md5($value);
                            $sql = "INSERT INTO `zfmyadmin_users` (`login`, `password`, `role`) VALUES
                                            ('$key', '$pass', '$key')";
                            $db->getConnection()->exec($sql);
                        }
                        $db->commit(); 
                    }  catch (Zend_Exception $e) {
                        $installErrors[] = $this->translate('zfmyadmin database tables not created');
                        $db->rollBack();
                    }
                    if (empty($installErrors)) {
                        $this->_redirect($this->_redirect($this->view->url(
                            array('module'=>'zfmyadmin', 'controller'=>'user', 'action'=>'login')
                        )));
                    } else {
                        $this->view->errors = $installErrors;
                    }
                    
                } else {
                    $this->view->errors = $errors;
                }  
            }
        }        
        
        $this->view->form = $form;
    }
    
    public function uninstallAction()
    {
        if (empty($this->user)||($this->user->role != 'admin')) {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }
        $this->_helper->layout->setLayout('layout');
        $this->_helper->layout->setLayoutPath($this->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'layouts');
        $form = new Zend_Form;
        $form ->setMethod('POST');
        $form ->setAction('');
        $form->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div')),
            'Form'
        ));  

        $element = new Zend_Form_Element_Checkbox('uninstal',
            array(
                'id'      => 'form-install-confirm',
                'filters' => array('StringTrim'),
                'label'   => $this->translate('I want to uninstall ZfMyAdmin and permanently remove all its files and folders'),
                'required' => true,
                'value'   => '1',
                'checked' => false
            )
        );
        $element->setDecorators(array(
            'Label',    
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element')),

            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'  => 'element-container')),
        ));
        $form->addElement($element);

        $element = new Zend_Form_Element_Submit('uninstall', array(
            'id'    => 'form-install-submit',
            'value' => 'uninstall'
        ));
        $element->setDecorators(array(
            'ViewHelper'
        ));
        $form->addElement($element);
        $this->view->form = $form;
        
        if($this->getRequest()->isPost()&& $form->isValid($this->getRequest()->getParams())) {
            $publicDir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'zfmyadmin_public';
            $moduleDir = $this->getFrontController()->getModuleDirectory();
            
            $errors = array();
            
            try {
                $dataInstallPath = $this->getFrontController()->getModuleDirectory()
                                .DIRECTORY_SEPARATOR.'data'
                                .DIRECTORY_SEPARATOR.'install'; 
                include_once $dataInstallPath.DIRECTORY_SEPARATOR.'uninstallDb.php';
                $db = Zend_Db_Table_Abstract::getDefaultAdapter(); 
                
                $db->beginTransaction();
                foreach ($uninstallDb as $value) {
                   $db->getConnection()->exec($value); 
                }
                $db->commit(); 
            }  catch (Zend_Exception $e) {
                $errors[] = $this->translate('zfmyadmin database tables not removed. Please remove manually');
                $db->rollBack();
            }            
            

            if (!$this->recursiveDirectoryRemoval($publicDir)) {
                $errors[] = $publicDir . $this->translate(' not removed. Please remove manually');
            }
            if (!$this->recursiveDirectoryRemoval($moduleDir)) {
                $errors[] = $moduleDir . $this->translate(' not removed. Please remove folder of zfmyadmin module manually');
            }

            
            if (empty($errors)) {
               $html = '<h1>'.$this->translate('Uninstall complete').'</h1>';
               $html .= '<p>'
                     .$this->translate('If zfmyadmin folder still remains in the project, please remove it manually')
                     .' ('.$moduleDir.')'
                     .'</p>';
               $this->getResponse()
                    ->setBody($html)
                    ->sendResponse();
               die;              
            } else {
               $html = '<h1>'.$this->translate('Uninstall errors').'</h1>';
               $html .= '<ul>';
               foreach ($errors as $error) {
                  $html .= '<li>'.$error.'</li>'; 
               }
               $html .= '</ul>';
               $this->getResponse()
                    ->setBody($html)
                    ->sendResponse();
               die; 
            }

        }
        
    }
    
    protected function recursiveDirectoryRemoval($path) {
        if (is_dir($path)) {
            $handler = opendir($path);
            while ($filename = readdir($handler)) {
                if ($filename == '.' || $filename == '..') {
                    continue;
                }
                if (is_file($filename)) {
                    @unlink($path.DIRECTORY_SEPARATOR.$filename);
                } else {
                    $this->recursiveDirectoryRemoval($path.DIRECTORY_SEPARATOR.$filename);
                }
            }
            @closedir($handler);
            if (@rmdir($path)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (@unlink($path)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
   
}


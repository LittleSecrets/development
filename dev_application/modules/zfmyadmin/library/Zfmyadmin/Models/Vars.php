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
 * Stores variables regarding project and users
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Vars  extends Zend_Db_Table_Abstract
{

    /** Table name */
    protected $_name = 'zfmyadmin_vars';
    /** Primary Key */
    protected $_primary = 'id';
    
    
    /**
     * Returns user settings
     * @param object $user
     * @return array User settings
     */
    public function getUserSettings($user){
        $select = $this->select();
        $select->where('user_id = ?', $user->id);
        $select->where('type = ?', 'user');
        $select->where('name = ?', 'settings');
        $row = $this->fetchRow($select);
        if(!empty($row)){
            return unserialize($row->value);
        } else {
            $this->project = Zfmyadmin_Models_Project::getInstance();
            $zfmyadmin = $this->project->getModule('zfmyadmin');
            
            $config = new Zend_Config_Ini($zfmyadmin->pathToModule.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'default.ini', 'user');
            $settings = $config->toArray();
            return $settings;
            return Zfmyadmin_Models_User::$_defaultSettings;
        }
    }

    /**
     * Sets user settings
     * @param object $user
     * @param array $data Prepared form data
     * @return void
     */
    public function setUserSettings($user, $data = array()){
        $select = $this->select();
        $select->where('user_id = ?', $user->id);
        $select->where('type = ?', 'user');
        $select->where('name = ?', 'settings');
        $row = $this->fetchRow($select);
        if($row->id > 0){
            $row->value = serialize($data);
            $row->save();
        } else {
            $row = $this->createRow();
            $row->user_id = $user->id;
            $row->type = 'user';
            $row->name = 'settings';
            $row->value = serialize($data);
            $row->save();
        }

//        return array();
    }

    /**
     * Returns settings of the working project from database
     *
     * @return array
     */
    public function getProjectSettings(){
        $select = $this->select();
        $select->where('type = ?', 'project');
        $select->where('name = ?', 'configs');
        $settings = $this->fetchRow($select);
        if(empty($settings)){
            return array();
        } else {
            return unserialize($settings->value);
        }
        return array();
    }

    /**
     * Sets settings of the working project
     * @param array $data Prepared form data
     * @param object $user
     * @return void
     */
    public function setProjectSettings( $data = array(), $user = null){
        $select = $this->select();
        $select->where('type = ?', 'project');
        $select->where('name = ?', 'configs');
        $info = $this->fetchRow($select);
        if($info->id > 0){
            $info->value = serialize($data);
            $info->save();
        } else {
            $row = $this->createRow();
            if($user) {
                $row->user_id = $user->id;
            }            
            $row->type = 'project';
            $row->name = 'configs';
            $row->value = serialize($data);
            $row->save();
        }
//        return array();
    }

    /**
     * Returns user settings for creating project entities
     * @param int $userId User ID
     * @param string $type Settings type (zfmyadmin, project, user, ... )
     * @param string $name Settings name
     * @return array
     */
    public function getSettingsTemplate($userId, $type, $name)
    {
        $select = $this->select();
        $select->where('type = ?', $type);
        $select->where('name = ?', $name);
        if($userId) {
            $select->where('user_id = ?', $userId);  
        }        
        $row = $this->fetchRow($select);
        if(!empty($row)){
            $data = unserialize($row->value);
            $data = $this->validateSettingsTemplate($data);  
            return $data;
        }
        return array();
    }
    
    
        /**
     * Returns all types user settings for creating project entities
     * @param int $userId User ID
     * @param string $type Settings type (zfmyadmin, project, user, ... )
     * @param string $name Settings name
     * @return array
     */
    public function getAllSettingsTemplates($name, $userId)
    {
        $select = $this->select();
        $select->where('name = ?', $name);
        if($userId) {
            $select->where('user_id = ?', $userId);  
        }        
        $rows = $this->fetchAll();       
        $settings = array();
        foreach ($rows as $row) {
            $data = unserialize($row->value);
            $data = $this->validateSettingsTemplate($data);             
            $settings[$row->type] = $data;
        }  
        $this->project = Zfmyadmin_Models_Project::getInstance();
        $realPath = new Zend_Filter_RealPath();
        $configsPath = $realPath->filter($this->project->getFrontController()->getModuleDirectory().DIRECTORY_SEPARATOR.'configs');        
        $creatorConfigs = new Zend_Config_Ini($configsPath.DIRECTORY_SEPARATOR.'creator.ini', $name);        
        $creatorConfigs = $creatorConfigs->toArray();
        $settings[Zfmyadmin_Models_User::SETTINGS_TEMPLATE_ZFMYADMIN] = $creatorConfigs;        
        
        return $settings;
    }

    /**
     * Sets user settings for creating project entities
     * @param array $data Prepared form data
     * @param int $userId User ID
     * @param string $type Settings type (zfmyadmin, project, user, ... )
     * @param string $name Settings name
     * @return bool
     */
    public function setSettingsTemplate($data, $userId, $type, $name )
    {
        $select = $this->select();
        $select->where('type = ?', $type);
        $select->where('name = ?', $name);
        $select->where('user_id = ?', $userId);
        $row = $this->fetchRow($select);
        if($row->id > 0){
            $row->value = serialize($data);
            $result = $row->save();
        } else {
            $row = $this->createRow();
            if($userId) {
                $row->user_id = $userId;
            }            
            $row->type = $type;
            $row->name = $name;
            $row->value = serialize($data);
            $result = $row->save();
            
        }
        return $result;
    }
    
    public function validateSettingsTemplate($data){
      
        $project = Zfmyadmin_Models_Project::getInstance();
        $module = $project->getModule($data['moduleName']);
        if (empty($module)) {
            unset($data['moduleName']);
            unset($data['controllerName']);
            unset($data['actionName']);
            return $data;
        }  
               
        $controller = $project->getController($data['moduleName'], $data['controllerName']);
        if (empty($controller)) {
            unset($data['controllerName']);
            unset($data['actionName']);
            return $data;
        }
        
        $action = $project->getAction($data['moduleName'], $data['controllerName'], $data['actionName']);
        if (empty($action)) {
            unset($data['actionName']);
            return $data;
        }   
        return $data;
    }
    public function getAvailableLanguages()
    {
        $this->project = Zfmyadmin_Models_Project::getInstance();
        $path = $this->project->getModule('zfmyadmin')->pathToModule.DIRECTORY_SEPARATOR
              .'data'.DIRECTORY_SEPARATOR
              .'locales';
        $result = scandir($path);
        $availableLanguages = array();
        include_once $path.DIRECTORY_SEPARATOR.'LanguageNames.php';
        foreach ( $result as $value) {
            if(preg_match('%\.mo$%', $value)) {                
                $key = preg_replace('%\.mo$%', '', $value);                
                if (array_key_exists($key, $languageNames)) {
                    $availableLanguages[$key] = $languageNames[$key];
                } else {
                    $availableLanguages[$key] = $key;
                }
            }            
        }
        return $availableLanguages;
    }
    
}

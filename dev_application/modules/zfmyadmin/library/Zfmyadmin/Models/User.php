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
 * Manages users of Zfmyadmin module (separate from working site users)
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_User  extends Zend_Db_Table_Abstract
{

    /** Table name */
    protected $_name = 'zfmyadmin_users';
    /** Primary Key */
    protected $_primary = 'id';
    
    protected $_user;


    public $id;
    public $login;   
    public $password;
    public $role;
    
    const ROLE_ADMIN = 'admin';
    const ROLE_DEVELOPER = 'developer';
    const ROLE_RESTRICTED = 'restricted';
    const ROLE_DEMO = 'demo';    
    const ROLE_GUEST = 'guest';
    
    
    const SETTINGS_TEMPLATE_ZFMYADMIN = 'zfmyadmin_settings_type';
    const SETTINGS_TEMPLATE_PROJECT = 'project_settings_type';
    const SETTINGS_TEMPLATE_USER = 'user_settings_type';
    const SETTINGS_TEMPLATE_LAST = 'last_settings_type';

    public static $_roles = array(
        'admin' => self::ROLE_ADMIN,
        'developer' => self::ROLE_DEVELOPER,
        'restricted' => self::ROLE_RESTRICTED,
        'demo' => self::ROLE_DEMO,   
    );

    /**
     * Returns corrent user of Zfmyadmin
     *
     * @return object
     */
    public static function getCurrentUser()
    {
        $session = new Zend_Session_Namespace('zfmyadmin');
        return $session->user;
    }
    
    /**
     * Performs user login
     * @param array $data Prepared data of login form
     * @return object User object
     */
    public function login($data)
    {
        $select = $this->select();
        $select->where('login = ?', $data['login']);
        $select->where('password = ?', md5($data['password']));
        $user = $this->fetchRow($select);
        if(!empty($user->id)){              
            $session = new Zend_Session_Namespace('zfmyadmin');
            $session->user = $user; 
            $this->_user = $user;
        }
        return  $user;
    }

    /**
     * Performs user logout
     *
     * @return void
     */
    public function logout()
    {
         $this->_user = null;
         $session = new Zend_Session_Namespace('zfmyadmin');
         unset($session->user);         
    }

    /**
     * Returns list of users
     * @param array $where Condition for users search
     * @param array $order Condition for sorting users
     * @param int $count Quantity of returned users
     * @param int $offset From where input starts
     * @return array
     */
    public function getList($where = array(), $order = null, $count = null, $offset = null)
    {
        $select = $this->select();
        foreach ($where as $cond => $value) {
            $select->where($cond, $value); 
        }
        $select->order($order); 
     
        if($count && $offset) {
            $select->limit($count, $offset);
        }
        $list = $this->fetchAll($select);
        return $list;
    }

    /**
     * Gets a single user
     * @param object $userId User ID
     * @return object
     */
    public function getUser($userId)
    {
        $result = $this->find($userId);
        $user = $result[0];
        return $user;
    }
    
    /**
     * Saves user settings for creator form
     * @param array $data Prepared form data for creating method
     * @param int $userId User ID
     * @param string $type Type of the selected settings
     * @param string $name Name of the saved settings
     * @return bool Success of the operation
     */
    public function saveSettingsTemplate($data, $userId, $name, $type = self::SETTINGS_TEMPLATE_LAST){
        $model = new Zfmyadmin_Models_Vars;
        unset ($data['createSubmit']);
        unset ($data['intentionSignature']);
        unset ($data['router']);
        unset ($data['routerUrlName']);
        unset ($data['routerTitle']);
        $result = $model->setSettingsTemplate($data, $userId, $type, $name);  
        return $result;
    }   
    
    
    
    
    
    
    
    
}

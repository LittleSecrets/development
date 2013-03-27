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
 * Manages atomic operations that create project's entities
 * @package    zfmyadmin
 * @subpackage models
 */
class Zfmyadmin_Models_Operation  extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name = 'zfmyadmin_operations';

    /** Primary Key */
    protected $_primary = 'id';
    
    protected $_referenceMap    = array(
        'transaction' => array(
            'columns'           => array('transaction_id'),
            'refTableClass'     => 'Zfmyadmin_Models_Transaction',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        ),
    );  
    
    
    public $id;
    public $transaction_id;
    public $type;
    public $category;
    public $content;
    public $target;
    public $description;
    public $status;

    const TYPE_CREATE_DIR = 1;
    const TYPE_CREATE_FILE = 2;
    const TYPE_CREATE_CODE = 3;
    
    
    
    
    const CATEGORY_MODULE = 1;
    const CATEGORY_CONTROLLER = 2;
    const CATEGORY_ACTION = 3;
    const CATEGORY_METHOD = 4;
    const CATEGORY_VIEW = 5;
    const CATEGORY_CSS = 6;
    const CATEGORY_JS = 7;
    const CATEGORY_ROUTER = 8;
    
//    const CATEGORY_HELPER = 0;
//    const CATEGORY_FORM = 0;    
//    const CATEGORY_PARTIAL = 0;
    
    const STATUS_ADD_TO_LOG = 1;
    const STATUS_ADD_TO_FILE = 2;
    const STATUS_ERROR_ADD_TO_FILE = 3; 
    
    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        Zend_Db_Table_Abstract::setDefaultMetadataCache();
        parent::__construct();
    }    
}



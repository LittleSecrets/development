<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValueAvailable
 *
 * @author los312
 */
class App_Validate_ValueAvailable extends Zend_Validate_Db_Abstract
{
    protected $_model;
    protected $_field;
    protected $_id;
    public function __construct($options) {
        //parent::__construct($options);
        $this->_model = $options['model'];
        $this->_field = $options['field'];
        $this->_id = $options['id'];
    }
    public function isValid($value)
    {
        $valid = true;
        $model = new $this->_model;
        $select = $model->select();        
        $select->where('id != ?', $this->_id)
               ->where('alias = ?', $value);
        $rowset = $model->fetchAll($select);
        $rowCount = count($rowset);
 
        if ($rowCount > 0) {
            $this->_error(self::ERROR_RECORD_FOUND, $value);
            $valid = false;
        }
        return $valid;        
    }     
}
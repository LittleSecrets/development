<?php

class App_Db_Table extends Zend_Db_Table
{
    public function getList($where = null, $order = null, $count = null, $offset = null)
    {
        
        $select = $this->select();
        if(!empty ($where)){
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    $select->where($key, $value);
                }                
            } else {
                $select->where($where, '');
            }
            
        }
        
        if(!empty ($order)){
            if (is_array($order)) {
                foreach ($order as $key => $value) {
                    $select->order($order);
                }                
            } else {
                $select->order($order);
            }
            
        }
        
        if (!empty($count)&&!empty($offset)) {
            $select->limit($count, $offset);
        }    
       
        $list = $this->fetchAll($select);

        return $list;
    }
}

<?php

class App_Model_Content_Entity  extends App_Model_Content_Abstract
{
    protected $_translation;
    public function __construct($config = array(), $definition = null) {
        parent::__construct($config, $definition);
        $this->_translation = new $this->_dependentTables[0];
    }
    /*
     * 
     * @return object row
     */
    public function findByAlias ($alias = '', $where = null){
        if(!empty($where)){
            $where['alias = ?'] = $alias;
        } else {
            $where = array('alias = ?' => $alias);
        }
        $row = $this->getList($where)->current();
        return $row;        
    }  
    
    /*
     * 
     * @return object row
     */
    public function findTranslationByAlias ($alias , $lang , $where = null){
        $row = $this->findByAlias($alias, $where);
        if(!empty($row)){
            $translations = $row->findDependentRowset($this->_translation);
            foreach ($translations as $translation) {
                if ($translation->lang == $lang){
                    return $translation;
                }   
            }

        } 
        return new stdClass();       
    }
   
    public function getListTranslationSets ($where = null, $order = null, $count = null, $offset = null)
    {
        
        $entities = $this->getList($where = null, $order = null, $count = null, $offset = null);
        $sets = array();
        foreach ($entities as $key => $value) {
           $item = new stdClass();
           $item->entity = $value;
           $item->translations =array();
           $sets[$value->id] = $item;
        }        
        $translations = array();
        if(!empty($sets)) {
            $entityIndexes = array_keys($sets);
            $entityIndexes = implode(',', $entityIndexes);
            $where = "entity_id IN ($entityIndexes)";
            $translations = $this->_translation->getList($where);    
        }

        foreach ($translations as $translation) {
            $item = $sets[$translation->entity_id];
            $item->translations[$translation->lang] = $translation;
        }
        return $sets;
    }


    public function getTree($id){
        
    }
}

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tree
 *
 * @author los312
 */
class App_Model_Content_Tree extends App_Model_Content_Abstract
{
    private $_model;
    private $_options;
    
    
    private $_children = array();
    private $_childrenContent = array();
    private $_node;
    private $_level;
    
   
    public function __construct($node, $options) {
        parent::__construct();        

        $this->_options = $options;
        if(empty($node)){
            $node = new stdClass();
            $node->id = 0;
        }
        $this->_node = $node;        
        $this->_model = $options['model'];
        if(empty($options['level'])) {
            $options['level'] = 1;
        }        
        $this->_level = $options['level'];
        $options['level']++;
        $where = array('parent_id = ?' => $node->id);
        if (!empty($this->_options['where'])) {
            if (is_array($this->_options['where'])) {
                $where = array_merge($where, $this->_options['where']);
            } else {
                $where[$this->_options['where']] = '';  
            }
        }        
        $rowset = $this->_model->getList($where);
        
        if(!empty($rowset)){
            foreach ($rowset as $row) {
                $this->_children[$row->id]= new App_Model_Content_Tree($row, $options);
            }            
        }
        
        if (!empty($this->_options['contentModel'])) {
            if($node->id != 0){
                $content = $node->findDependentRowset($this->_options['contentModel']);
                foreach($content as $row) {
                    $this->_childrenContent[$row->id]= $row;
                   
                }
            
            }
           
        }
    }

    public function getChildren(){
        return $this->_children;
    }
    
    public function getChildrenContent(){
        return $this->_childrenContent;
    }
    
    public function getNode(){
        return $this->_node;
    }
    
    public function getLevel(){
        return $this->_level;
    }
    
    public function getTranslation($lang){
        if(empty($this->_options['translation'])) {
            return false;
        }
        $node = $this->getNode();
        if(!empty($node)) {
            $result = $node->findDependentRowset($this->_options['translation']); 
            foreach ($result as $value) {
                if ($value->lang == $lang) {
                    return $value;
                }
            } 
        }
        return false;
    }
    
    public function getTranslationContent($lang, $item){
        if(empty($this->_options['contentTranslation'])) {
            return false;
        }
        if(!empty($item)) {
            $result = $item->findDependentRowset($this->_options['contentTranslation']);
            foreach ($result as $value) {
                if ($value->lang == $lang) {
                    return $value;
                }
            } 
        }
        return false;
    }
    
    public function hasChildren(){
        if(!empty($this->_children)){
            return true;
        }         
        return false;
    }
    
    public function hasChildrenContent(){
        if(!empty($this->_childrenContent)){
            return true;
        }         
        return false;
    }
    
    public function getPages($options) {
        $pages = array();
        if($this->hasChildren()){
            foreach ($this->_children as $key => $value) {
                $label = $value->getNode()->header;
                $title = 'Not translate';
                $params = array();
                if(!empty($options['lang'])){
                    $params['lang'] = $options['lang'];
                    $translate = $value->getTranslation($options['lang']);
                    if (!empty($translate)) {                        
                        $label = $translate->header;
                        $title = '';    
                    }
                }
                
                if(!empty($options['alias'])){
                    $params['category'] = $value->getNode()->alias;
                } else {
                    $params['id'] = $value->getNode()->id;
                }
                
                $page = array(
                    'label'      => $label,
                    'title'      => $title,
                    'route' => $options['route'],
                    'params' => $params,
                    'class' => $options['class'],
                );
                if ($value->hasChildren()){
                    $page['pages'] = $value->getPages($options);
                }
                /*Output articles in empty category*/
                
                if ($value->hasChildrenContent() && !$value->hasChildren()){
                    $contentPages = array();
                    foreach ($value->_childrenContent as $item) {
                        $label = $item->header;
                        $title = 'Not translate';
                        $params = array();
                        if(!empty($options['lang'])){
                            $params['lang'] = $options['lang'];
                            $translate = $this->getTranslationContent($options['lang'], $item);
                            if (!empty($translate)) {                        
                                $label = $translate->header;
                                $title = '';    
                            }
                        }                        
                        if(!empty($options['aliasContent'])){
                            $params['category'] = $value->getNode()->alias;
                            $params['article'] = $item->alias;
                        } else {
                            $params['id'] = $item->id;
                        }
                        
                        $itemPage = array(
                            'label'      => $label,
                            'title'      => $title,
                            'route' => $options['routeContent'],
                            'params' => $params,
                            'class' => $options['classContent'],
                        );

                        $contentPages[] = $itemPage;
                    }                    
                    if(!empty($page['pages'])){
                        $page['pages'] = array_merge($page['pages'], $contentPages);
                    } else {
                        $page['pages'] = $contentPages;
                    }                   
                    
                }                
                $pages[] = $page;
            }
        }
        /*Output articles in to not first level category*/
        if ($this->hasChildrenContent() && $this->getNode()->id != 0){
            $contentPages = array();
            foreach ($this->_childrenContent as $item) {
                $label = $item->header;
                $title = 'Not translate';
                $params = array();
                if(!empty($options['lang'])){
                    $params['lang'] = $options['lang'];
                    $translate = $this->getTranslationContent($options['lang'], $item);
                    if (!empty($translate)) {                        
                        $label = $translate->header;
                        $title = '';    
                    }
                }                        
               
                if(!empty($options['aliasContent'])){
                    $params['category'] = $this->getNode()->alias;
                    $params['article'] = $item->alias;
                } else {
                    $params['id'] = $item->id;
                }                

                $itemPage = array(
                    'label'      => $label,
                    'title'      => $title,
                    'route' => $options['routeContent'],
                    'params' => $params,
                    'class' => $options['classContent'],
                );

                $contentPages[] = $itemPage;
            }                    
            if(!empty($pages)){
                $pages = array_merge($pages, $contentPages);
            } else {
                $pages = $contentPages;
            }                   

        }                        
       
        return $pages;
    }
}
<?php
/**
 * CyEngine
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @package    CyEngine
 * @copyright  Copyright (c) 2007-2008 Constantine Yurevich (http://php.webconsulting.by)
 * @license    http://framework.zend.com/license/new-bsd       New BSD License
 */
 
/** Zend_Controller_Router_Route */
require_once 'Zend/Controller/Router/Route.php';
 
 
class App_Controller_Router_Route_Multilingual extends Zend_Controller_Router_Route
{
    /**
     * Language prefixes that should be detected by router
     *
     * @var array
     */
    protected static $_languagePrefixes = array('en', 'ru');
 
 
    /**
     * Static function
     * Sets prefixes that should be detected by router
     *
     * @param array $prefixes
     */
    public static function setLanguagePrefixes(array $prefixes) 
    {
        self::$_languagePrefixes = $prefixes;
    }
 
    /**
     * Matches a user submitted path with parts defined by a map. Assigns and
     * returns an array of variables on a successful match.
     *
     * @param string $path Path used to match against this routing map
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path)
    {
        $path = trim($path, $this->_urlDelimiter);
 
        $pathParts = explode($this->_urlDelimiter, $path, 2);
 
        if(in_array($pathParts[0], self::$_languagePrefixes)) {
            $path = (sizeof($pathParts) > 1) ? $pathParts[1] : '';
            $currentLanguage = $pathParts[0];
        } else {
            $currentLanguage = $this->_defaults['lang'];
        }
 
        $params = parent::match($path);
        if($params) {
            $params = array_merge($params, array('lang' => $currentLanguage));
        }
 
        return $params;
    }
 
 
    /**
     * Assembles user submitted parameters forming a URL path defined by this route
     *
     * @param  array $data An array of variable and value pairs used as parameters
     * @param  boolean $reset Whether or not to set route defaults with those provided in $data
     * @return string Route path with user submitted parameters
     */
    //public function assemble($data = array(), $reset = false)
    public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
    {
        if(isset($data['lang'])) {
            $lang = $data['lang'];
            unset($data['lang']);
            $assemble = parent::assemble($data, $reset);
            if(in_array($lang, self::$_languagePrefixes)) {
                if($lang != $this->_defaults['lang']) {
                    $assemble = implode($this->_urlDelimiter, array($lang, $assemble));
                }
            }
            return $assemble;
        } else {
            return parent::assemble($data, $reset);
        }
    }
}
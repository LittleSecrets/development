<?php
/**
* Menus of zfmyadmin module
*
* @package    zfmyadmin
* @subpackage helpers
* @copyright  http://zfmyadmin.com
* @license    GNU General Public License   http://www.gnu.org/copyleft/gpl.html
* @version    1.0
* @link       http://zfmyadmin.com
* @since      File available since Release 1.1.0
* @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
*/

class Zfmyadmin_View_Helper_ZfmyadminMenu  extends Zend_View_Helper_Abstract
{
    public function translate($text)
    {
        $translate = Zend_Registry::get('Zend_Translate');        
        $text = $translate->_($text);
        return $text;
    }
    
    public function zfmyadminMenu($type)
    {
        switch ($type) {
            case 'main-menu':
                $pages = $this->getMainMenuPages();
                break;
            case 'login-menu':
                $pages = $this->getLoginMenuPages();

                break;
            case 'create-menu':
                $pages = $this->getCreateMenuPages();
                break;

            default:
                break;
        }        
        $container = new Zend_Navigation($pages);
        return $container;
    }
    
    public function getLoginMenuPages()
    {
        $pages = array(
            array(
                'label'      => $this->translate('Login'),
                'title'      => $this->translate('Login'),
                'module'     => 'zfmyadmin',
                'controller' => 'user',
                'action'     => 'login',
                'class'      => 'main-menu-link',
            ),
       );
       return $pages;
    }
    public function getMainMenuPages()
    {
        $pages = array(
            array(
                'label'      => $this->translate('Password'),
                'title'      => $this->translate('Password'),
                'module'     => 'zfmyadmin',
                'controller' => 'user',
                'action'     => 'change-password',
                'class'      => 'main-menu-link',
                'resource'   => 'settings',
            ),            
            array(
                'label'      => $this->translate('Manage users'),
                'title'      => $this->translate('Manage users'),
                'module'     => 'zfmyadmin',
                'controller' => 'user',
                'action'     => 'index',
                'class'      => 'main-menu-link',
                'resource'   => 'users',
            ),            
            array(
                'label'      => $this->translate('My settings'),
                'title'      => $this->translate('My settings'),
                'module'     => 'zfmyadmin',
                'controller' => 'user',
                'action'     => 'settings',
                'class'      => 'main-menu-link',
                'resource'   => 'settings',
            ),

            array(
                'label'      => $this->translate('Project settings'),
                'title'      => $this->translate('Project settings'),
                'module'     => 'zfmyadmin',
                'controller' => 'index',
                'action'     => 'project-settings',
                'class'      => 'main-menu-link',
                'resource'   => 'project_settings',
            ), 
            array(
                'label'      => $this->translate('Uninstall'),
                'title'      => $this->translate('Uninstall zfmyadmin'),
                'module'     => 'zfmyadmin',
                'controller' => 'install',
                'action'     => 'uninstall',
                'class'      => 'main-menu-link',
                'resource'   => 'project_settings',
            ), 
            array(
                'label'      => $this->translate('Logout'),
                'title'      => $this->translate('Logout'),
                'module'     => 'zfmyadmin',
                'controller' => 'user',
                'action'     => 'logout',
                'class'      => 'main-menu-link',
                'id'         => 'exit-button'
            ),
         );
        return $pages;
    }
    
        public function getCreateMenuPages(){
            $pages = array(
                array(
                    'label'      => $this->translate('Module'),
                    'title'      => $this->translate('Create module'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'module',
                    'resource'   => 'creater',
                ),
                 array(
                    'label'      => $this->translate('Controller'),
                    'title'      => $this->translate('Create controller'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'controller',
                    'resource'   => 'creater',
                ),
                array(
                    'label'      => $this->translate('Action'),
                    'title'      => $this->translate('Create action'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'action',
                    'resource'   => 'creater',
                ),
                array(
                    'label'      => $this->translate('Css'),
                    'title'      => $this->translate('Create css'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'css',
                    'resource'   => 'creater',
                ),   
                array(
                    'label'      => $this->translate('Js'),
                    'title'      => $this->translate('Create js'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'js',
                    'resource'   => 'creater',
                ),  
                array(
                    'label'      => $this->translate('Route'),
                    'title'      => $this->translate('Create router'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'router',
                    'resource'   => 'creater',
                ),         
                array(
                    'label'      => $this->translate('Form'),
                    'title'      => $this->translate('Create form'),
                    'module'     => 'zfmyadmin',
                    'controller' => 'create',
                    'action'     => 'form',
                    'resource'   => 'creater',
                ),                
            );
            return $pages;
        }
    
}

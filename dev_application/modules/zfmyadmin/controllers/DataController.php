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
 * @subpackage controllers
 * @copyright  Copyright (c) 2012 Oleksii Chkhalo http://zfmyadmin.com
 * @license    http://zfmyadmin.com/license     New BSD License
 * @version    1.0
 * @author     Oleksii Chkhalo <oleksii.chkhalo@zfmyadmin.com>
 */

include_once dirname(__FILE__).'/../library/Zfmyadmin/Controller/Action.php';
/**
 * Is resposible for the output of data to partials and AJAX queries
 * @package    zfmyadmin
 * @subpackage controllers
 */
class Zfmyadmin_DataController extends Zfmyadmin_Controller_Action  
{

    public function preDispatch()
    {
        parent::preDispatch();
        if (empty($this->user->id)) {            
            $this->view->errorMessage = $this->translate->_("No logged in user to access this data");       
            $jsonData = Zend_Json::encode($this->view);            
            if ($this->getRequest() -> isXmlHttpRequest()) {
               $this->getResponse()
                    ->setHeader('Content-Type', 'application/json')
                    ->setBody($jsonData)
                    ->sendResponse();
                die; 
            } 
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }        

        $this->_helper->layout->setLayout('layout');
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext -> addActionContext('controllers-list', 'json')
                     -> addActionContext('get-log-transaction-operations', 'json')
                     -> addActionContext('get-operation-detail', 'json')
                     -> addActionContext('actions-list', 'json')                
                     ->initContext('json'); 
    }
   
    /**
     * Forms AJAX response with module's controller list
     *
     * @return void
     */
    public function controllersListAction() {
        $modulName = $this->getRequest()->getParam('moduleName');
        $type = $this->getRequest()->getParam('type');
        
        
        $controllers = $this->project->getControllers($modulName);
        $this->view->controllers = $controllers;
        $this->view->moduleName = $modulName;
        if ($type == 'form') {
            $form = new Zfmyadmin_Forms_Create_Action;
            $form->setControllersList($this->project->getControllers($modulName));
            $this->view->form = $form;            
            $htmlBody = $this->view->render('data/controllers-list-form.phtml');
            $this->view->htmlBodyHiddenForm = $form->controllerName->render();
            unset($this->view->form);
        } 
        
        if ($type == 'tooltip') {
            $htmlBody = $this->view->render('data/controllers-list-tooltip.phtml');
        }
        
        $this->view->success = 1;
        $this->view->number = count($controllers);
        $this->view->htmlBody=$htmlBody;
        $this->view->errorMessage = 0;       

    }
    
    /**
     * Forms AJAX response with module's controller list
     *
     * @return void
     */
    public function actionsListAction() {
        $modulName = $this->getRequest()->getParam('moduleName');
        $controllerName = $this->getRequest()->getParam('controllerName');
        $type = $this->getRequest()->getParam('type');        
        
        $actions = $this->project->getActions($modulName, $controllerName);
        
        $this->view->controllerName = $controllerName;
        $this->view->moduleName = $modulName;
        $this->view->actions  = $actions;
        if ($type == 'tooltip') {
            $htmlBody = $this->view->render('data/actions-list-tooltip.phtml');
        }  
        
        if ($type == 'form-css') {
            $form = new Zfmyadmin_Forms_Create_Css;
            $form->setActionsList($actions);
            $this->view->form = $form;            
            $htmlBody = $this->view->render('data/actions-list-form.phtml');
            $this->view->htmlBodyHiddenForm = $form->actionName->render();
            unset($this->view->form);
        } 
        
        $this->view->success = 1;
        $this->view->number = count($actions);
        $this->view->htmlBody = $htmlBody;
        $this->view->errorMessage = 0;       

    }

    /**
     * Forms AJAX response with the list of transaction's operations
     *
     * @return void
     */
    public function getLogTransactionOperationsAction()
    {
        $model = new Zfmyadmin_Models_Transaction;
        $transaction = $model->find((int)$this->getRequest()->getParam('transaction_id'));
        $operations = $transaction->current()->findDependentRowset('Zfmyadmin_Models_Operation');
        $this->view->operations = $operations;
        $htmlBody = $this->view->render('data/get-log-transaction-operations.phtml');
        $this->view->success = 1;
        $this->view->html = $htmlBody;
        $this->view->errorMessage = 0;       
        $jsonData = Zend_Json::encode($this->view);
    }

   
    /**
     * Forms AJAX response with details of an operation
     *
     * @return void
     */
    public function getOperationDetailAction()
    {
        $operationId = $this->getRequest()->getParam('operationId');
        $intention = $this->getRequest()->getParam('intention');
        if (empty($intention)) {
            $model = new Zfmyadmin_Models_Operation;
            $operation = $model->find((int)$operationId);
            $operation = $operation[0];   
            $transaction = $operation->findParentRow('Zfmyadmin_Models_Transaction');
            if ($transaction->user_id != $this->user->id) {
                $this->view->errorMessage = $this->translate->_("This operation has another owner");
                $jsonData = Zend_Json::encode($this->view);
                $this->getResponse()
                    ->setHeader('Content-Type', 'application/json')
                    ->setBody($jsonData)
                    ->sendResponse();
                die;                  
            } 
            
            $data = unserialize($transaction->data);              
        } else {
            $session = new Zend_Session_Namespace('zfmyadmin');
            $operations = $session->operations;
            $operation = $operations[$operationId];
            
            $intentionData = $session->intentionData;
            $transactionsModel = new Zfmyadmin_Models_Transaction;
            $data = $transactionsModel->renderData($operations, $intentionData);
            $this->view->isLinkWorking = false;
            if ($session->intentionSignature != $intention) {
                $this->view->errorMessage = $this->translate->_('intention of operaition expired');
                $this->view->description = $this->translate->_('intention of operaition expired');
                $jsonData = Zend_Json::encode($this->view);
                $this->getResponse()
                    ->setHeader('Content-Type', 'application/json')
                    ->setBody($jsonData)
                    ->sendResponse();
                die;
            }
        }
        if (!empty($data['fullCode'][$operation->target])) {
            $targetCode = $data['fullCode'][$operation->target];
        } else {
            $targetCode = '';
        }
        $this->view->operation = $operation;
        
        $camelCaseToDash = new Zend_Filter_Word_CamelCaseToDash;
        $stringToLower = new Zend_Filter_StringToLower;
        
        $moduleNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['module']));
        $controllerNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['controller']));
        $actionNamePrepared = $stringToLower->filter($camelCaseToDash->filter($data['action']));
        if(!empty($actionNamePrepared)&&empty($controllerNamePrepared)) {
            $controllerNamePrepared = 'index';
        }
        if(!empty($controllerNamePrepared)&&empty($moduleNamePrepared)) {
            $moduleNamePrepared = 'index';
        }
        
        
        
        $this->view->linkToPageDirect = $this->getFrontController()->getBaseUrl()
                                      .'/'.$moduleNamePrepared
                                      .'/'.$controllerNamePrepared
                                      .'/'.$actionNamePrepared;
        
        $linkToPageData = array(
            'module'     => $data['module'],
            'controller' => $data['controller'],
            'action'     => $data['action']
        );   
        
        if ($data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_MODULE
            || $data['creatorCategory'] == Zfmyadmin_Models_Operation::CATEGORY_CONTROLLER
        ) {
             $this->view->isLinkExists = false;
        } else {
            $this->view->isLinkExists = true;           
        }
        
        if ((int)$operation->status == Zfmyadmin_Models_Operation::STATUS_ADD_TO_FILE
        ) {
             $this->view->isLinkWorking = true;
        } else {
            $this->view->isLinkWorking = false;           
        }
            
        if (!empty($data['routerName'])
        ) {   
            try {
                $this->view->linkToPageRouter = $this->view->url(array(), $data['routerName']); 
            } catch (Exception $exc) {
                $this->view->isLinkRouterWorking = false;
                $this->view->linkToPageRouter = $this->getFrontController()->getBaseUrl()
                                              .'/'.$data['route'];
            }

                       
        } else {
            
            $this->view->linkToPageRouter = false;
        }
                
        $htmlDescription = $this->view->render('data/get-operation-detail-description.phtml');
        $this->view->description = $htmlDescription;
        unset($this->view->operation);
        unset($this->view->linkToPage);
        
        if ($operation->type == Zfmyadmin_Models_Operation::TYPE_CREATE_CODE) {
            $parts = explode($operation->code, $targetCode);
            if(count($parts) == 2) {
                $this->view->showFullCode = '<pre>'.htmlspecialchars($parts[0]).'</pre>'
                                          .'<pre class="highlight-code">'
                                          .htmlspecialchars($operation->code)
                                          .'</pre>'
                                          .'<pre>'.htmlspecialchars($parts[1])
                                          .'</pre>';
            }
            
            if (!empty($operation->code)) {
                $this->view->showCode = '<pre>'.htmlspecialchars($operation->code) .'</pre>';            
                $this->view->code = $operation->content;
            }            
            
            if ((!empty($operation->code))&&($operation->content == $operation->code)) {
                $this->view->showFullCode = '<pre class="highlight-code">'
                                          .htmlspecialchars($targetCode)
                                          .'</pre>'; 
                unset($this->view->showCode);
            } 
        }
        $this->view->errorMessage = 0;      
    }

}
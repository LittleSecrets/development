<?php
/**
 * File of controller Partials in module zfmyadmin
 *
 * @package zfmyadmin
 * @subpackage controllers
 * @copyright zfmyadmin
 * @license
 * @version
 * @author los312 <los312@mail.ru>
 */

include_once dirname(__FILE__).'/../library/Zfmyadmin/Controller/Action.php';
/**
 * Class Zfmyadmin_PartialsController of controller in module zfmyadmin
 *
 * @package zfmyadmin
 * @subpackage controllers
 * @author los312 <los312@mail.ru>
 */
class Zfmyadmin_PartialsController extends Zfmyadmin_Controller_Action
{

    
    /**
     * Ban direct call
     *
     * @version
     * @author los312 <los312@mail.ru>
     */
    public function preDispatch() {
        parent::preDispatch();
        /*Ban direct call*/
        if ($this->getFrontController()->getRequest()->getControllerName()== 'partials') {
            $this->_redirect($this->view->url(
                array('module'=>'zfmyadmin', 'controller'=>'index', 'action'=>'index')
            ));
        }
    }
    
    /**
     * Action of controller Partials in module zfmyadmin
     *
     * @version
     * @author los312 <los312@mail.ru>
     */
    public function indexAction()
    {
        die();
    }


    /**
     * Action of controller
     *
     * @version
     * @author los312 <los312@mail.ru>
     */
    public function getLanguagesAction()
    {
        $model = new Zfmyadmin_Models_Vars; 
        $this->view->languages = $model->getAvailableLanguages();
    }
    
    /**
     * Gets list of intentions and outputs to partial
     *
     * @return void
     */
    public function getLogIntentionAction()
    {
               
    }    
    
    /**
     * Gets list of transactions and outputs to partial
     *
     * @return void
     */
    public function getLogTransactionsAction()
    {
       
       $model = new Zfmyadmin_Models_Transaction;
       $count = 50;
       $offset = 0;
       $openedTransactions = 1;
       $transactions = $model->getTransactions(
           array(
               'user_id = ?' => $this->user->id
           ),
           array('time DESC'),
           $count,
           $offset
       );   
       $this->view->transactions = array();
       $i = 0;
       foreach ($transactions as $transaction) {
           $item = $transaction ->toArray();
           $item = (object)$item;
           if ($i < $openedTransactions) {
              $item ->operations = $transaction->findDependentRowset('Zfmyadmin_Models_Operation'); 
           } else {
              $item ->operations = array(); 
           }
           
           $this->view->transactions[] = $item;
           $i++;
       }       
    }

}
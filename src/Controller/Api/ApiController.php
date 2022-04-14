<?php
declare(strict_types=1);
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class ApiController extends Controller {

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
        $this->loadComponent('Authentication.Authentication');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }
     /**
     * @inerhitDoc
     */
    public function beforeRender(EventInterface $event) {
        parent::beforeRender($event);
        $this->viewBuilder()->setVar('_serialize', array_keys($this->viewBuilder()->getVars()));
    }

    protected function _getAuthorizeUserData() {
        if($this->request->getAttribute('authentication')) {
            $result = $this->Authentication->getResult();
            if(!$result->isValid()) {
                return false;
            }
            return $result->getData();

        }
        
    }
}
?>
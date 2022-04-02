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
        $status = true;
        $error = '';
        $this->viewBuilder()->setVars(compact('status', 'error'));
    }
     /**
     * @inerhitDoc
     */
    public function beforeRender(EventInterface $event) {
        parent::beforeRender($event);
        $this->viewBuilder()->setVar('_serialize', array_keys($this->viewBuilder()->getVars()));
    }
}
?>
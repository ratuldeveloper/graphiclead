<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Http\ServerRequest;
use App\Error\Exception\ValidationErrorException;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use SwaggerBake\Lib\Annotation as Swag;
use stdClass;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]
 */
class UsersController extends ApiController {
    
    public function initialize(): void {
        parent::initialize();
        //$this->viewBuilder()->setClassName('Json');
        
    }
    public function beforeFilter(\Cake\Event\EventInterface $event){
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions([
            'login',
            'register'
        ]);
    }
    public function index() {
        $this->request->allowMethod(['get']);
        $users = [];
        $userData = new stdClass();
        $userData->name = 'Ratul Samanta';
        $userData->age = 30;
        $users[] = $userData;
        $this->set('users',$users);
    }
    /**
     * User login
     *
     * Your Swagger forms are built automatically straight from your Cake Models and Routes, without the need for
     * additional annotations! All batteries were included in this example.
     * @property email
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function login() {
        $this->request->allowMethod(['post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $privateKey = file_get_contents(CONFIG . '/ssl/jwt.key');
            $user = $result->getData();
            $token = JWT::encode([
                'id' => $user['id'],
                'sub' => $user['id'],
                'iat' => time(),
                'exp' =>  time() + 86400,
            ],$privateKey,'RS256');
            $json = [
                'token' => 'Bearer '.$token,
            ];
        } else {
            $this->response = $this->response->withStatus(401);
            $json = [];
        }
        $this->set(compact('json'));
    }

    public function register() {
        $this->request->allowMethod(['post']);
        $userEntity = $this->Users->newEntity($this->request->getData());
        
        if(!$this->Users->save($userEntity)) {
            throw new ValidationErrorException($userEntity);
            
        }
        $response['status'] = 200;
        $response['message'] = 'succesfully created';
        $this->set(compact('response'));

    }

}


?>
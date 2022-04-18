<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Http\ServerRequest;
use App\Error\Exception\ValidationErrorException;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use SwaggerBake\Lib\Annotation as Swag;
use SwaggerBake\Lib\Attribute\OpenApiRequestBody;
use SwaggerBake\Lib\Attribute\OpenApiResponse;
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
    /**
     * User login
     *
     * Your Swagger forms are built automatically straight from your Cake Models and Routes, without the need for
     * additional annotations! All batteries were included in this example.
     * @property email
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    #[OpenApiRequestBody(ref: "#/components/schemas/Login")]
    public function login() {
        $this->request->allowMethod(['post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $privateKey = file_get_contents(CONFIG . '/ssl/jwt.key');
            $user = $result->getData();
            $iat = time();
            $expiry = time()+8600;
            $token = JWT::encode([
                'id' => $user['id'],
                'sub' => $user['id'],
                'iat' => $iat,
                'exp' =>  $expiry,
            ],$privateKey,'RS256');
            $response = [
                'user_name' => $user->user_name,
                'token' => 'Bearer '.$token,
                'expires' => $expiry,
                'email' => $user->email
            ];
        } else {
            $this->response = $this->response->withStatus(422);
            $response = [
                'details' => [
                    'message' => 'Invalid user credential' 
                ],
                'status' => 422
            ];
        }
        $this->set($response);
    }

    #[OpenApiRequestBody(ref: "#/components/schemas/Registration")]
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
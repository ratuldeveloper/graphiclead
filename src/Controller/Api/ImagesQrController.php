<?php
declare(strict_types=1);
namespace App\Controller\Api;
use App\Controller\Api\ApiController;
use App\Utility\Utility;
use Cake\Core\Configure;
use App\Error\Exception\ValidationErrorException;
use App\Error\Exception\ApiException;
use Cake\Routing\Router;
use Exception;
use SwaggerBake\Lib\Attribute\OpenApiHeader;
use SwaggerBake\Lib\Attribute\OpenApiSecurity;
use SwaggerBake\Lib\Attribute\OpenApiRequestBody;
use SwaggerBake\Lib\Attribute\OpenApiResponse;

/**
 * ImagesQueue Controller
 *
 * @property \App\Model\Table\ImageQr $ImagesQueue
 * @method \App\Model\Entity\ImagesQr[]
 */
class ImagesQrController extends ApiController {
   
    private $_userData;
    private $_attributeConfiguration = [
        'error_correction' => ['L','M','Q','H'],
        'quiet_zone' => [0,1,2,3,4],
        'version' => [1,2,3,4,5],
        'rotate' => [0,90,180,270],
        'eye_shape' => ['rounded','square'],
    ];
    public function initialize(): void {
        parent::initialize();
        $this->_userData = $this->_getAuthorizeUserData();
        //$this->viewBuilder()->setClassName('Json');
    }
    public function beforeFilter(\Cake\Event\EventInterface $event){
        parent::beforeFilter($event);
    }
    #[OpenApiSecurity(name: 'BearerAuth', scopes: ['read'])]
    #[OpenApiHeader(name: "BearerAuth", description: "Put your Authorization token", isRequired: true)]
    public function getImageQrById() {
        $responseData = [];
        try {
            $imageQruuId = $this->request->getParam('uuid');
            $imageQrData = $this->ImagesQr->find()
                            ->where(['uuid = ' => $imageQruuId])
                            ->where(['user_id' => $this->_userData->id])
                            ->first();
            if(empty($imageQrData)) {
                throw new ApiException("Invalid image qr id",422);
            }
            if(!$imageQrData->submitted) {
                throw new ApiException("Qr image is not submitted yet",400);
            }
            $this->response = $this->getResponse()->withHeader('Content-Type',$imageQrData->content_type);
            $this->response = $this->getResponse()->withFile(Configure::read('App.qrimagepath').$imageQrData->image_url);
            return $this->response;
        } catch(Exception $e) {
            $this->response = $this->getResponse()->withStatus($e->getCode());
            $responseData['details'] = [
                'message' => $e->getMessage(),
            ];

         }
         $this->set($responseData);
        
    }
    #[OpenApiSecurity(name: 'BearerAuth', scopes: ['read'])]
    #[OpenApiHeader(name: "BearerAuth", description: "Put your Authorization token", isRequired: true)]
    
    public function getAllImageQueue() {
        $imageQrData = $this->ImagesQr->find('all')
                            ->where(['user_id' => $this->_userData->id]);
        $imageQrDataArray = $imageQrData->all()->toArray();
                            
        $this->set($imageQrDataArray);

    }
    #[OpenApiSecurity(name: 'BearerAuth', scopes: ['read'])]
    #[OpenApiRequestBody(ref: "#/components/schemas/PostImageQr")]
    #[OpenApiHeader(name: "BearerAuth", description: "Put your Authorization token", isRequired: true)]
    public function postImageQr() {
        try {
            $imageQrEntityData = [];
            $requestData = $this->request->getData();
            $validationResult = $this->_valdateImageQrRequestData($requestData);
            if($validationResult['error']) {
                $this->set($validationResult);
                //$this->setResponse($this->getResponse())
                $this->response = $this->getResponse()->withStatus($validationResult['status']);
            } else {
                $uuid = Utility::generateRanmdomUniqueId();
                $this->_setAttributeData($requestData,$uuid);
                // check if request method is url
                if($requestData['image']['method'] === 'url') {
                    $imageData = Utility::createImageFromUrl($requestData['image']['url'],$uuid);
                    if(!$imageData) {
                        throw new ApiException("Something went wrong dumring image upload",422);
                    }
                    $imageQrEntityData['content_type'] = $imageData['content_type'];
                    $imageQrEntityData['image_url'] = $imageData['file_name'];
                    $imageQrEntityData['submitted'] = date('Y-m-d H:i:s');
                    $imageQrEntityData['status'] = Configure::read('Status')['STATUS_SUBMITTED'];

                }
                $imageQrEntityData['uuid'] = $uuid;
                $imageQrEntityData['user_id'] = $this->Authentication->getResult()->getData()->id;
                if(!$this->_saveImageQrEntity($imageQrEntityData)) {
                    throw new ApiException('something went wrong during saving information',422);
                }
                $this->set([
                    'uuid' => $uuid,
                    'url' => Router::fullBaseUrl().'/api/imageqr/'.$uuid,
                ]);

            }
        } catch(ApiException $e) {
            $errorData['details'] = [
                $e->getMessage(),
            ];
            $errorData['status'] = $e->getCode();
            $this->set($errorData);
            $this->response = $this->getResponse()->withStatus($e->getCode());
        } 
        
    }
    #[OpenApiSecurity(name: 'BearerAuth', scopes: ['read'])]
    #[OpenApiHeader(name: "BearerAuth", description: "Put your Authorization token", isRequired: true)]
    public function updateImageQrById() {
        try {
            $imageQruuId = $this->request->getParam('uuid');
            $filesData = $this->request->input();
            $uploadedFileData =  Utility::uploadImageFromBinaryData($filesData,$imageQruuId);
            $imageQrData = $this->ImagesQr->find()
                            ->where(['uuid = ' => $imageQruuId])
                            ->where(['user_id' => $this->_userData->id])
                            ->first();
            if(!$imageQrData) {
                throw new ApiException("Invalid uuid",400);
            }
            $imageQrData->image_url = $uploadedFileData['file_name'];
            $imageQrData->content_type = $uploadedFileData['content_type'];
            $imageQrData->submitted = date('Y-m-d H:i:s');
            $imageQrData->status = Configure::read('Status')['STATUS_SUBMITTED'];
            $this->ImagesQr->save($imageQrData);
            $this->set($uploadedFileData);

        } catch(Exception $e) {
            $errorData['details'] = [
                $e->getMessage(),
            ];
            $errorData['status'] = $e->getCode();
            $this->set($errorData);
            $this->response = $this->getResponse()->withStatus($e->getCode());
        }
    }

    private function _saveImageQrEntity($requestData) {
        $imageQrEntity = $this->ImagesQr->newEntity($requestData);
        return $this->ImagesQr->save($imageQrEntity);

    }



    private function _setAttributeData($requestData,$uuid) {
        $fileName = $uuid.'.json';
        $qrDataPath = WWW_ROOT. 'qrdata'. DS . $fileName;
        $configuredAttributeData = [
            'error_correction' => 'L',
            'quiet_zone' => 4,
            'version' => 5,
            'rotate' => 0,
            'eye_shape' => 'square'
        ];
        if(isset($requestData['attributes']) && !empty($requestData['attributes'])) {
            foreach($requestData['attributes'] as $attributeKey => $attributesData) {
               if(!empty($attributesData)) {
                   $configuredAttributeData[$attributeKey] = $attributesData;
               }
            }
        }
        return file_put_contents($qrDataPath,json_encode($configuredAttributeData));

    }

    private function _valdateImageQrRequestData($data) {
        $validatedData = ['error' => false,'message' => []];
        $validationErrorMessgae = [];
        empty($data['data']) ? array_push($validationErrorMessgae,"Missing data property") : '';
        empty($data['image']) ? array_push($validationErrorMessgae,"Missing image property") : '';
        if(!empty($data['image'])) {
            empty($data['image']['method']) ? array_push($validationErrorMessgae,"Missing method property") : '';
            (!empty($data['image']['method']) 
            && $data['image']['method'] === 'url' 
            && empty($data['image']['url'])) ? array_push($validationErrorMessgae,"Missing image url data") : '';
        }
        if(!empty($data['attributes'])) {
            (isset($data['attributes']['error_correction']) && !in_array($data['attributes']['error_correction'],$this->_attributeConfiguration['error_correction'])) ? 
            array_push($validationErrorMessgae,"Error correction string must be any of the following ".implode(',',$this->_attributeConfiguration['error_correction'])) : '';
            
            (isset($data['attributes']['quiet_zone']) && !in_array($data['attributes']['quiet_zone'],$this->_attributeConfiguration['quiet_zone'])) ? 
            array_push($validationErrorMessgae,"Quiet Zone string must be any of the following ".implode(',',$this->_attributeConfiguration['quiet_zone'])) : '';
           
            (isset($data['attributes']['version']) && !in_array($data['attributes']['version'],$this->_attributeConfiguration['version'])) ? 
            array_push($validationErrorMessgae,"Version must be any of the following ".implode(',',$this->_attributeConfiguration['version'])) : '';
            
            (isset($data['attributes']['rotate']) && !in_array($data['attributes']['rotate'],$this->_attributeConfiguration['rotate'])) ? 
            array_push($validationErrorMessgae,"Rotate must be any of the following ".implode(',',$this->_attributeConfiguration['rotate'])) : '';
            
            (isset($data['attributes']['eye_shape']) && !in_array($data['attributes']['eye_shape'],$this->_attributeConfiguration['eye_shape'])) ? 
            array_push($validationErrorMessgae,"Eye Shape must be any of the following ".implode(',',$this->_attributeConfiguration['eye_shape'])) : '';

        }
        if(count($validationErrorMessgae) > 0) {
            $validatedData = ['status' => 400,'error' => true,'message' => $validationErrorMessgae];
        }
        return $validatedData;
    }



}


?>
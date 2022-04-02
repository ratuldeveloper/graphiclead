<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Response;
use App\Error\Exception\ValidationErrorException;
use Exception;

/**
 * Exception renderer for ApiListener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class AppExceptionRenderer extends \Cake\Error\ExceptionRenderer
{
    /**
     * Renders validation errors and sends a 422 error code
     *
     * @param \Crud\Error\Exception\ValidationException $error Exception instance
     * @return \Cake\Http\Response
     */
    public function validationError(ValidationErrorException $error): Response
    {
      $parsedError = [];
      foreach($error->getValidationErrors() as $errorField => $validationError) {
          $parsedError[$errorField] = [];
          $parsedError[$errorField]['message'] = [];
          foreach($validationError as $message) {
            $parsedError[$errorField] ['message'][] = $message;
          }
      }
      $errorData['error'] = $parsedError;
      $errorData['error']['status'] = $error->getCode();
      return $this->controller->getResponse()
               ->withStatus($error->getCode())
               ->withType('application/json')
               ->withStringBody(json_encode($errorData));
    }
    
    public function notfound($error) {
        return $this->_parseErrorData($error);
    }
    public function missingController($error) {
        return $this->_parseErrorData($error);
    }
    public function recordNotFound($error) {
        return $this->_parseErrorData($error);

    }
    public function unAuthenticated($error) {
        return $this->_parseErrorData($error);
    }

    private function _parseErrorData($error) {
        $errorData = [
            'status' => $error->getCode(),
            'message' => $error->getMessage(),
        ];
        $parsedErrorData['error'] = $errorData;
        return $this->controller->getResponse()
               ->withStatus($error->getCode())
               ->withType('application/json')
               ->withStringBody(json_encode($parsedErrorData));
    }

}
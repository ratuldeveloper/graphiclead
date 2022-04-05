<?php
  declare(strict_types=1);
  namespace App\Error\Exception;
  use Exception;
  class ApiException extends Exception {
   
    public function __construct($message = 'Something went wrong',$errorCode = 422){
        parent::__construct($message,$errorCode);
        
    }
  }
?>
<?php 
require_once("./database/server.php");
require_once("./functions/api.php");
require_once("./functions/jwt.php");
require_once("./constants/constants.php");

class Rest
{
      protected $request;
      protected $servicename;
      protected $param;

      public function __construct()
      {
             //receive request
             $this->validateRequest();

      }

      public function validateRequest()
      {

         if($_SERVER['REQUEST_METHOD'] != "POST")
         {
             //throw an error
             $this->throwError(INVALID_REQUEST_METHOD,  'Invalid request method.');

         }

         if($_SERVER['CONTENT_TYPE'] != "application/json")
         {
             //throw error
            $this->throwError(INVALID_CONTENT_TYPE,  'Invalid content type. It should be in JSON');
         }

         $incoming = fopen("php://input", "r");
         $this->request = stream_get_contents($incoming);

         $data = json_decode($this->request, true);
         

         //further validation
         if(strlen($data['apiname'] == "" || empty($data['apiname'])))
         {
             $this->throwError(APINAME_REQUIRED,    'API NAME is required.');
         }

         //assign servicename
         $this->servicename = $data["apiname"];

        

         if(!is_array($data['param']))
         { 
           $this->throwError(PARAM_REQUIRED,  'Provide parameters.'); 
         }

         

         //assign param 
         $this->param = $data['param'];
         

      }

      public function processApi()
      {

        $api = new Api;
        try {
          $rMethod = new ReflectionMethod('API', $this->servicename);
         
          $rMethod->invoke($api);

        } catch (\Exception $e) {
          $this->throwError(INVALID_API,    'Invalid API. ' . $e->getMessage());
        }

      }

      public function validateParameters($fieldname, $value, $datatype, $required = true)
      {
        if(empty($this->param))
        {
          $this->throwError(PROVIDE_PARAM,  'Provide parameters in the required order.');
        }


        if( $required == true && empty($value) )
        {
            $this->throwError(EMPTY_FIELD,  $fieldname .' value is required.');
        }

        switch($datatype)
        {
            case 'STRING':
                # code...
            if(!is_string($value))
            {
                $this->throwError(INVALID_DATATYPE,   'Invalid datatype for '.$fieldname. '. It should be of type string.');
            }
            break;

            case 'INTEGER':
            if(!is_numeric($value))
            {
                $this->throwError(INVALID_DATATYPE,   'Invalid datatype for '.$fieldname. '. It should numeric.');
            }
            break;

            case 'BOOLEAN':
            if(!is_bool($value))
            {
                $this->throwError(INVALID_DATATYPE,   'Invalid datatype for '.$fieldname. '. It should boolean.');
            }
            break;

            Default:

            break;

        }

        return $value;

      }

      public function throwError($code, $msg)
      {
          header("content-type: application/json");
          echo json_encode(["code"=>$code, "msg"=>$msg]);
          exit;
      }

      


}
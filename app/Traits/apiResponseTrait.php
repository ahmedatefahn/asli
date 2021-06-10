<?php

namespace App\Traits;
use Validator;

trait apiResponseTrait {
    public function apiResponse($status=true, $message = null, $data = null )
    {

        $array = [
            'status' => $status,
            'message' =>$message,
            'data' => $data,
        
        ];

        return response($array);

    }

    public function setSuccess($msg, $data)
    {
        return $this->apiResponse(true, $msg, $data);
    }
       
    public function notFoundResponse () 
    {

        return $this->apiResponse(false, 'Sorry Not Found!!', null);

    }
    public function setError($msg ) 
    {
        return $this->apiResponse(false, $msg, null);
    }




}
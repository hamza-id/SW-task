<?php

namespace App\Helpers;

class ResponderHelper
{
    /**
    * @param $error HTTP Status Code
    * @param $data Data array or errors array
    * @param $message Optional error message
    */

    public function respond($error = 500, $data = array(), $message = null)
    {
        $responseData = array();
        $code       = $error ? 500 : 200;

        $responseData = [
            'code'    => $code,
            'message' => null,
            'data'    => $data
        ];

        if(!$error){
            $responseData['data'] = $data;
        }

        if($error)
            $responseData['code'] = $code;

        if($message)
            $responseData['message'] = $message;

        if(!$data || $error)
            $responseData['data']['error'] = $message;

        return response()->json($responseData, $code);
    }
}


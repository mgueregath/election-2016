<?php

use Phalcon\Http\Response;

class PhalconResponse implements ResponseAdapter
{
    private static $codes  = array(
        0 => 'Phalcon error',
        5 => 'Phalcon error',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        406 => 'Not Acceptable',
        409 => 'Conflict',
        500 => 'Internal Server Error'
    );
    
    static function send($result, $code, $data = "")
    {
        $response = new Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode($code, self::$codes[$code]);
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');      
        
        
        $answer = [];
        $answer["result"] = $result;
        $answer["code"] = $code;
        $answer["status"] =  self::$codes[$code];
        if($data != "") {
            
            $keys = array_keys($data);
            
            if (array_keys($keys) !== $keys) {
                $data = array($data);
            }
            $answer["data"] = $data;
        }
        
        $response->setContent(json_encode($answer, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
        $response->sendHeaders();
        $response->send();
    }
}

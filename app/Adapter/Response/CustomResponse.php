<?php

class CustomResponse implements ResponseAdapter
{
    private static $codes  = array(
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

    public static function send($result, $code, $data = "")
    {
        header('Cache-Control: no cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json; charset=UTF-8');
        http_response_code($code);

        $answer = [];
        $answer["result"] = $result;
        $answer["code"] = $code;
        $answer["status"] =  self::$codes[$code];
        if($data != "") {
            if (!is_array($data)) {
                $data = array($data);
            }
            $answer["data"] = self::utf8ize($data);
            //$answer["data"] = $data;
        }

        echo str_replace('\u0000*\u0000', '', json_encode($answer, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
    }
    
    public static function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                if (is_object($v)) {
                    $d[$k] = (array) self::utf8ize($v);
                } else {
                    $d[$k] = self::utf8ize($v);
                }
                
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
}

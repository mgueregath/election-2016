<?php

interface ResponseAdapter
{
    static function send($result, $code, $data = "");
}

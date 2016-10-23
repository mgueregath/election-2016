<?php

use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{
    function notFoundAction()
    {
        throw new \Exception("The requested resource was not found", 404);
    }
}

<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller;


class SecureController extends Controller
{
    
    private $secureRoutes = [
        ['controller'=> 'table_votes', 'action' => 'register'],
        ['controller'=> 'table', 'action' => 'jointables'],
        ['controller'=> 'table', 'action' => 'getall'],
        ['controller'=> 'table_votes', 'action' => 'deleteone'],
        ['controller'=> 'location', 'action' => 'getmylocation'],
    ];
    
    public function beforeExecuteRoute($dispatcher)
    {
        if (!$this->isProtectedRoute($dispatcher)) {
            return true;
        }
        
        $token = $this->request->getHeader("Authorization");
        
        $jwt = new JwtLcobucci();
        
        if (!$token) {
            throw new \Exception("A valid token must be provided", 403);
        }
        
        if (!$jwt->validate($token)) {
            throw new \Exception("A valid token must be provided", 403);
        }
        
        $dispatcher->setParam('userId', $jwt->getUserIdFromToken($token));
        
        return true;
    }
    
    private function isProtectedRoute(Dispatcher $dispatcher)
    {
        foreach ($this->secureRoutes as $route) {

            if ($route['controller'] == $dispatcher->getControllerName()
                && $route['action'] == $dispatcher->getActionName()
               ) {
                return true;
            }
        }
        return false;
    }
}
    
<?php

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

require realpath ("..") . "/vendor/autoload.php";


try {
    $loader = new Loader();
    
    
    $loader->registerDirs(
        [
            "../app/Controller/",
            "../app/Model/",
            "../app/Adapter/Response",
            "../app/Adapter/Jwt",
            "../app/Adapter/Password",
        ]
    );
    
    
    $loader->register();

    $di = new FactoryDefault();

    $di->set('db', function () {
        return new PdoMysql(
            array(
                "host"     => "localhost",
                "username" => "root",
                "password" => "",
                "dbname"   => "counter",
                "charset"  => "utf8",
            )
        );
    });

    $di['router'] = function () {
        $router = new RouterAnnotations(false);
        //$router->setDefaultController('Table');
        //$router->setDefaultAction('getAll');
        
        //Request Not Found
        $router->notFound(array(
            'controller' => 'Error',
            'action' => 'notFound'
        ));
        
        $router->addResource('TableVotes', '/api/votes');
        $router->addResource('Table', '/api/tables');
        $router->addResource('Location', '/api/locations');
        $router->addResource('Auth', '/auth');
        $router->addResource('Testing', '/test');
        $router->addResource('Stats', '/api/stats');

        return $router;
    };

    $di->set('url', function () {
        $url = new Phalcon\Mvc\Url();
        $url->setBaseUri('/');
        return $url;
    });

    $di->set('view',function(){
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('/views/');
        $view->registerEngines(array(
            ".phtml" => 'Phalcon\Mvc\View\Engine\Volt'
        ));
        return $view;
    });

    $app = new \Phalcon\Mvc\Application($di);

    echo $app->handle()->getContent();

} catch (Exception $e) {
    if(strpos($e->getFile(),"Decoder.php") != false) {
        PhalconResponse::send(false,401);
    } else {
        PhalconResponse::send(false, $e->getCode(), array(
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ));
    }
}

<?php


/**
* @RoutePrefix("/test")
*/
class TestingController extends SecureController
{
    /**
    *@Get("/password")
    */
    public function createPasswordAction()
    {
        for ($i = 1; $i <= 26; $i++) {
            echo '</br>local' . $i . ' clave: ' .PhpPwdHasher::hash('local' . $i, 1);
        }
        
    }
    
    /**
    *@Get("/password/verify")
    */
    public function verifyPasswordAction()
    {
        echo PhpPwdHasher::verify('local1', '$2y$10$o0XfwaBJOsJHksaFkC6mqu1JXX4riiqHvnjeCMVTfdttBCUG3FuUm');
    }

}

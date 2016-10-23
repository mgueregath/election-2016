<?php


/**
* @RoutePrefix("/auth")
*/
class AuthController extends SecureController
{
    /**
    *@Post("/login")
    */
    public function loginAction()
    {
        $params = $this->request->getJsonRawBody();
        
        if (
            empty($params) 
            || !array_key_exists('id', $params)
            || !array_key_exists('password', $params)
        ) {
            throw new \Exception('User and password must be provided', 400);
        }
        
        $locationId = $params->id;
        
        if (!is_int($locationId)) {
            throw new \Exception('The user must be integer', 400);
        }
        
        $locationPassword = $params->password;
        
        $location = Location::findFirst($locationId);
        if (!$location) {
            throw new \Exception('Incorrect user or password', 403);
        }
        
        if (!PhpPwdHasher::verify($locationPassword, $location->getHash())) {
            throw new \Exception('Incorrect user or password 2', 403);
        }
        
        $token = (new JwtLcobucci())->generate($locationId);
        
        PhalconResponse::send(true, 200, array('Authorization' => $token, 'name' => $location->getName()));
    }
}

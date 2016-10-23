<?php

class PhpPwdHasher implements PasswordAdapter
{
    public function hash($password, $defaultPassword)
    {
        return password_hash($password, $defaultPassword);
    }
    
    public function verify($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
    
}
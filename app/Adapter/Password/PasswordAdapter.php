<?php

interface PasswordAdapter
{
    function hash($password, $defaultPassword);
    function verify($password, $hashedPassword);
}

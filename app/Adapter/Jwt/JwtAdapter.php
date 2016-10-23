<?php

interface JwtAdapter
{
    function generate($userId);
    function validate($token);
    function getUserIdFromToken($token);
}
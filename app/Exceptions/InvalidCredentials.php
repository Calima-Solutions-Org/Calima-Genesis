<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentials extends Exception
{
    public function __construct()
    {
        parent::__construct('Your username or password is incorrect. Please try again.');
    }
}

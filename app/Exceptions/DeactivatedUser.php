<?php

namespace App\Exceptions;

use Exception;

class DeactivatedUser extends Exception
{
    public function __construct()
    {
        parent::__construct('Your account has been deactivated. If you think this is an error, please reach out to the tech team.');
    }
}

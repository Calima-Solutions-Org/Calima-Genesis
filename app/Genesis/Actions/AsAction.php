<?php

namespace App\Genesis\Actions;

trait AsAction
{
    public static function run(...$args)
    {
        $action = new static();

        return $action(...$args);
    }
}

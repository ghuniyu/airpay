<?php

namespace App\Traits;

trait StringIndex
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return "string";
    }
}

<?php

namespace Oshitsd\PhpSocket\Facades;

use Illuminate\Support\Facades\Facade;

class PhpSocket extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'phpsocket';
    }
}

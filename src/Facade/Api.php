<?php
namespace MasterZero\Epay\Facade;

use Illuminate\Support\Facades\Facade;


class Api extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EpayApi';
    }
}

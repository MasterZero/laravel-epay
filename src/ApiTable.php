<?php
namespace MasterZero\Epay;

use Exception;

/**
 * class MasterZero\Epay\ApiTable
 */
abstract class ApiTable
{

    /**
     * table with method and url in format:
     * 'methodName' => 'https://here.method.can/be/called.asmx?WSDL'
     *
     * @return array
     */
    public static function list() : array
    {
        return [
            'gettransaction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL',
        ];
    }

    /**
     * get url by methodName
     *
     * @param string    $methodName
     * @return string
     */
    public static function url(string $methodName) : string
    {
        $table = static::list();

        if(!isset($table[$methodName])) {
            throw new Exception("Epay\ApiTable doesn't contains method '$methodName'", 1);

        }

        return $table[$methodName];
    }

}


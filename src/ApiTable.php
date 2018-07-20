<?php
namespace MasterZero\Epay;

use Exception;

/**
 * class MasterZero\Epay\ApiTable
 */
abstract class ApiTable
{

    /**
     * table with method and urls in format:
     * 'methodName' => [
     *      'location' => 'https://here.method.can/be/called.asmx',
     *      'uri' => 'https://here.method.can/be/called,'
     *      'SOAPAction' => 'https://here.placed/the/method',
     * ]
     *
     * @return array
     */
    public static function list() : array
    {
        return [

            // payment
            'capture' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment/capture',
            ],

            'credit' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment/credit',
            ],

            'delete' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment/delete',
            ],

            // subscription
            'authorize' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription/authorize',
            ],


            'deletesubscription' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription/deletesubscription',
            ],


            'getPbsError' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription/getPbsError',
            ],

            'getEpayError' => [
                'location' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx',
                'uri' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription',
                'SOAPAction' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription/getEpayError',
            ],

        ];
    }

    /**
     * get url by methodName
     *
     * @param string    $methodName
     * @return array
     */
    public static function url(string $methodName) : array
    {
        $table = static::list();

        if(!isset($table[$methodName])) {
            throw new Exception("Epay\ApiTable doesn't contains method '$methodName'", 1);

        }

        return $table[$methodName];
    }

}


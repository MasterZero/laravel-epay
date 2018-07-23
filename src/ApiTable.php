<?php
namespace MasterZero\Epay;

use Exception;

/**
 * class MasterZero\Epay\ApiTable
 */
abstract class ApiTable
{

    /**
     * get url by methodName
     *
     * @param string    $methodName
     * @return array [
     *      'location',
     *      'uri',
     *      'SOAPAction',
      * ]
     */
    public static function url(string $methodName) : array
    {

        $endpointName = static::getEndpoint($methodName);
        $endpointTable = static::endpoints();
        $uri = $endpointTable[$endpointName];

        $ret = [
            'location' => $uri . '.asmx',
            'uri' => $uri,
            'SOAPAction' => $uri . '/' . $methodName,
        ];

        return $ret;
    }


    /**
     * @return endpoint list in format array [
     * 'methodName' => 'uri',
     * ]
     */
    protected static function endpoints() : array
    {
        return [
            'payment' => 'https://ssl.ditonlinebetalingssystem.dk/remote/payment',
            'subscription' => 'https://ssl.ditonlinebetalingssystem.dk/remote/subscription',
        ];
    }


    /**
     * table with method and urls in format:
     * 'endpointName' => '[
          *      'method1',
          *      'method2',
          *      'method3',
          * ]'
     *
     * @return array
     */
    protected static function list() : array
    {
        return [

            'payment' => [
                'capture',
                'credit',
                'delete',
            ],

            'subscription' => [
                'authorize',
                'deletesubscription',
                'getPbsError',
                'getEpayError',
            ],
        ];
    }

    /**
     * get endpoint name by method name
     *
     * @param $methodName | string
     * @return string
     * ]
     */
    protected static function getEndpoint(string $methodName) : string
    {
        $table = static::list();

        foreach ($table as $endpoint => $methodList) {
            if (in_array($methodName, $methodList)) {
                return $endpoint;
            }
        }

        throw new Exception("Method '$methodName' doesn't exists in ApiTable", 1);
    }

    

}


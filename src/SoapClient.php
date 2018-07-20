<?php
namespace MasterZero\Epay;


use Exception;


/**
 * class MasterZero\Epay\SoapClient
 */
class SoapClient
{


    protected $location;
    protected $sslVerify = true;



    /**
     * http methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';




    /**
     * @param array $params = [
     *      'location'      => string *required
     *      'sslVerify'     => bool
     * ]
     */
    public function __construct(array $params)
    {


        if (!array_key_exists('location', $params)) {
            throw new Exception("'location' parameter missing.", 1);
        }

        $this->location = $params['location'];


        if (array_key_exists('sslVerify', $params)) {
            $this->sslVerify = $params['sslVerify'];
        }
        
    }

    /**
     * make a soap call
     *
     * @param $action       | string
     * @param $SOAPAction   | string
     * @param $uri          | string
     * @param $params       | array
     * @return array
     */
    public function call(string $action, string $SOAPAction, string $uri, array $params = []) : array
    {

        $content = $this->generateXml($action, $uri, $params);
        $headers = $this->headers($SOAPAction);

        $ret = $this->request($this->location, static::METHOD_POST, $content, $headers);

        $body = $ret['soapBody'];

        $successKey = $action . 'Response';
        
        if (array_key_exists($successKey, $body)) {
            return $body[$action . 'Response'];
        } elseif (array_key_exists('soapFault', $body)) {
            return $body;
        } else {
            throw new Exception("Unknown answer", 1);
        }

    }

    public function headers(string $SOAPAction) : array
    {
        return [
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "'. $SOAPAction .'"',
        ];
    }

    /**
     * do request
     *
     * @param $url      | string
     * @param $method   | string
     * @param $data     | string
     * @param $headers  | array of strings
     * @return array
     */
    protected function request(string $url, string $method = 'GET', string $data = '', array $headers = []) : array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($method === static::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);// this should be set to true in production

        $responseData = curl_exec($ch);

        if(curl_errno($ch)) {
            throw new CurlException('[SoapClient] ' . curl_error($ch), 1);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $this->stringToXmlArray($responseData);
    }


    /**
     * generate soap body
     *
     * @param $action   | string
     * @param $uri      | string
     * @param $params   | array
     * @return array
     */
    protected function generateXml(string $action, string $uri, array $params) : string
    {
        $strParams = $this->arrayToXmlstring($params);

        return 
        '<?xml version="1.0" encoding="utf-8"?>'.
        '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'.
            '<soap:Body>'.
                '<' . $action . ' xmlns="' . $uri . '">'.
                    $strParams.
                '</'. $action .'>'.
            '</soap:Body>'.
        '</soap:Envelope>';
    }


    /**
     * xml => array convertation method
     *
     * @param $str | string
     * @return array
     */
    protected function stringToXmlArray(string $str) : array
    {
        $str = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $str);
        return json_decode(json_encode(simplexml_load_string($str)),1);
    }

    /**
     * array => xml string convertation method
     *
     * @param $str | string
     * @return array
     */
    protected function arrayToXmlstring(array $arr) : string
    {
        $ret = '';

        foreach ($arr as $key => $value) {
            $value = htmlentities($value);
            $ret .= "<$key>$value</$key>";
        }

        return $ret;
    }


}


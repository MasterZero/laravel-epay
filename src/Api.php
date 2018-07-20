<?php
namespace MasterZero\Epay;


use SoapClient;

/**
 * class MasterZero\Epay\Api
 */
class Api
{


    /**
     * merchantnumber from your account
     */
    protected $merchantnumber;

    /**
     * pwd from your account
     */
    protected $password;


    /**
     * http methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';


    /**
     * @param $params | array
     * contain custom parameters to create Api instance.
     * all  defined in $params parameters will be overwrited by them.
     */
    public function __construct(array $params = [])
    {
        $this->merchantnumber = $params['merchantnumber'] ?? config("epay.merchantnumber");
        $this->password = $params['password'] ?? config("epay.password");
    }


    /**
     * get transaction info
     *
     * @param string    $transactionid
     * @return array
     */
    public function gettransaction(string $transactionid) : array
    {
        $params = [
            'transactionid' => $transactionid,
        ];

        return $this->request('gettransaction', $params);
    }


    /**
     * pay for subscription
     *
     * @param array     $params     contains: [
     *     subscriptionid   | *required long int
     *     orderid          | *required string (max 20)
     *     amount           | *required integer ( 123.45 => 12345 )
     *     currency         | *required string
     *     instantcapture   | *required Integer (1 or 0)
     *     group            | String (max 100)
     *     description      | String (max 1024)
     *     email            | String (max 100)
     *     sms              | String (max 8)
     *     ipaddress        | String (max 15)
     * ]
     * @return array
     */
    public function authorize(array $params) : array
    {
        return $this->request('authorize', $params);
    }

    /**
     * delete subscription
     *
     * @param string    $subscriptionid
     * @return array
     */
    public function deletesubscription(string $subscriptionid) : array
    {
        $params = [
            'subscriptionid' => $subscriptionid,
        ];

        return $this->request('deletesubscription', $params);
    }


    /**
     * get epay error at $language by $epayresponsecode code
     *
     * @param int    $language
     * @param int    $epayresponsecode
     *
     * laguages:
     * 1 - Danish
     * 2 - English
     * 3 - Swedish
     *
     * @return array
     */
    public function getEpayError(int $language, int $epayresponsecode) : array
    {
        $params = [
            'language' => $language,
            'epayresponsecode' => $epayresponsecode,
        ];

        return $this->request('getEpayError', $params);
    }


    /**
     * send request to epay and get answer
     *
     * @param string    $method    soap method on specific url
     * @param  array    $params    parameters for request, except for default parameters. see defaultParams().
     * @return array
     */
    protected function request(string $method, array $params) : array
    {
        $params = array_merge($this->defaultParams(), $params);
        $urls = ApiTable::url($method);

        $clientParams = [
            'location' => $urls['location'],
        ];

        $client = new SoapClient($clientParams);

        $ret = $client->call($method, $urls['SOAPAction'], $urls['uri'], $params);

        return $ret;
    }


    /**
     * default parameters for all requests
     * @return array
     */
    protected function defaultParams() : array
    {
        return [
            'merchantnumber' => $this->merchantnumber,
            'pwd' => $this->password,
        ];
    }

}


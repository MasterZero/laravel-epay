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
     * send request to epay and get answer
     *
     * @param string    $method    soap method on specific url
     * @param  array    $params    parameters for request, except for default parameters. see defaultParams().
     * @return array
     */
    protected function request(string $method, array $params) : array
    {
        $params = array_merge($this->defaultParams(), $params);
        $url = ApiTable::url($method);

        $client = new SoapClient($url);
        $answer = $client->$method($params);

        // stdClass -> array
        $ret = json_decode(json_encode($answer),1);

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
            'epayresponse' => '-1',
        ];
    }

}


<?php

namespace App\Libraries;
use SoapClient;

class SOAP{

    public $client = NULL;

    public function __construct(){
        $params = [
            'location'=> env("SOAP_SERVER"),
            'uri' =>  env("SOAP_URN"),
            'trace'=>1,
            'cache_wsdl'=>WSDL_CACHE_NONE
        ];
        $this->client =  new SoapClient(NULL, $params);       
    }

    public function call($method,$data = []){
        return $this->client->__soapCall($method,$data);
    }

    public function validateToken($token){
        return $this->client->__soapCall('validateToken',[$token]);
    }

}
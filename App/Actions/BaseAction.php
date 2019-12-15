<?php

namespace App\Actions;

use Curl\Curl;

abstract class BaseAction{

    private $curl;

    protected $api_url;

    protected $serverUrl;

    public function __construct($serverUrl)
    {

        $this->serverUrl = $serverUrl;

        $this->curl = new Curl();

        $this->curl->setDefaultJsonDecoder(true);

    }

    /**
     * Send curl query to portal
     *
     * @return array
     */
    protected function curlExecute($queryUrl, $queryData){

        return $this->curl->get($queryUrl, $queryData);

    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->curl->close();
    }

}
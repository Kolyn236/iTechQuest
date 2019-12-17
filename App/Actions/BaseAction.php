<?php

namespace App\Actions;

use Curl\Curl;

/**
 * Class BaseAction
 * @package App\Actions
 */
abstract class BaseAction
{

    protected $api_url;

    protected $serverUrl;

    protected $curl;

    public function __construct($serverUrl)
    {

        $this->serverUrl = $serverUrl;

        $this->curl = new Curl();

        $this->curl->setDefaultJsonDecoder(true);
    }

    public function __destruct()
    {

        $this->curl->close();
    }

    /**
     * Send curl query to portal
     *
     * @return array
     */
    protected function curlExecute($queryUrl, $queryData)
    {

        return $this->curl->get($queryUrl, $queryData);
    }
}

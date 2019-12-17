<?php

/**
 *
 * PHP version 7.2.0
 *
 * @category Bitrix
 * @author Itech-Group LLC <nikola@itech-group.ru>
 * @see ____file_see____
 * @since Битрикс 24
 */

namespace App\Actions;

use Curl\Curl;

/**
 * Class BaseAction
 * Curl init and execute user query
 *
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

<?php

/**
 *
 * PHP version 7.2.0
 *
 * @category Bitrix 24
 * @author Itech-Group LLC <nikola@itech-group.ru>
 * @see ____file_see____
 * @since Битрикс 24
 */

namespace App\Actions;

/**
 * Class LeadAction
 * Create and delete Lead from service
 *
 * @package App\Actions
 */
class LeadAction extends BaseAction
{

    const API_ENDPOINT = '/crm.lead.';

    /**
     * @param $fields
     * @return array
     */
    public function createLead($fields)
    {
        $queryUrl = $this->serverUrl . self::API_ENDPOINT .'add';
        $queryData = http_build_query([
            'fields' => array(
                "TITLE" => 'Заявка от ' . $fields['firstname']. ' '. $fields['lastname'],
                "NAME" => $fields['firstname'],
                "LAST_NAME" => $fields['lastname'],
                "STATUS_ID" => "NEW",
                "OPENED" => "Y",
                "ASSIGNED_BY_ID" => 1,
                "CURRENCY_ID"=> "USD",
                "COMMENTS" => $fields['message'],
                "PHONE" => array(array("VALUE" => $fields['phone'], "VALUE_TYPE" => "WORK" )),
                "EMAIL" => array(array("VALUE" => $fields['email'], "VALUE_TYPE" => "WORK" )),
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        ]);

        return $this->curlExecute($queryUrl, $queryData);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteLead($id)
    {
        $queryUrl = $this->serverUrl . self::API_ENDPOINT .'delete';

        $queryData = http_build_query([
            'id' => $id
        ]);

        return $this->curlExecute($queryUrl, $queryData);
    }

    /**
     * @return array
     */
    public function listLead()
    {

        $queryUrl = $this->serverUrl . self::API_ENDPOINT .'list';

        $queryData = http_build_query([
            "filter" => [
                "STATUS_ID" => "NEW"
            ],
            "select" => [
                "ID",
                "TITLE",
                "STATUS_ID",
                "NAME",
                "LAST_NAME"
            ]
        ]);

        return $this->curlExecute($queryUrl, $queryData);
    }
}

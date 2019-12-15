<?php

namespace App\Actions;

class LeadAction extends BaseAction {

    const API_ENDPOINT = '/crm.lead.';

    public function createLead($fields){

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

    public function deleteLead($id){

        $queryUrl = $this->serverUrl . self::API_ENDPOINT .'delete';

        $queryData = http_build_query([
            'id' => $id
        ]);

        return $this->curlExecute($queryUrl, $queryData);

    }

    public function listLead(){

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